<?php

namespace App\Modules\Jwt\Services;

use App\Modules\Jwt\Interfaces\InterfaceJwt;
use App\Modules\User\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Redis\Connections\PredisConnection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use MiladRahimi\Jwt\Cryptography\Algorithms\Hmac\HS256;
use MiladRahimi\Jwt\Parser;
use MiladRahimi\Jwt\Generator;

class ServiceJwt implements InterfaceJwt
{
    private HS256 $signer;
    private string $refreshKey = '_refresh';
    private int $expPerSec = 900;
    private PredisConnection $redisConnection;

    public function __construct(PredisConnection $redisConnection)
    {
        $this->redisConnection = $redisConnection;
        $this->signer = new HS256(config('app.secret'));
    }

    public function parse(string $token, Request $request): Request|HttpResponseException
    {
        $parser = new Parser($this->signer);
        $claims = $parser->parse($token);
        $username = explode('@', $claims['username']);
        $username = mb_strtolower($username[0]);
        $redisUserKey = !isset($claims['token_type'])
            ? $username . $claims['id']
            : $username . $claims['id'] . $this->refreshKey;
        $user_token = $this->getRedisToken($redisUserKey);
        if ($user_token != $token) {
            throw new AuthenticationException(__('Authentication::main.token_invalid'));
        }
        $request['token'] = $user_token;
        $request['username'] = $username;
        $request['auth_user_id'] = $claims['id'];
        $request['rbac'] = $this->rbac($request['auth_user_id'],$user_token);
        if(!Auth::check()) {
            $userClass = config('auth.providers.users.model');
            Auth::setUser(new $userClass());
        }
        return $request;
    }

    public function getRedisToken(string $key): ?string
    {
        return $this->redisConnection->client()->get($key);
    }

    public function generateTokens(User $user, bool $isRefreshToken = false): array
    {
        $time_created = date('Y-m-d H:i:s');
        $username = explode('@', $user->email);
        $username = mb_strtolower($username[0]);
        $userKey = $username . $user->id;
        $generator = new Generator($this->signer);
        $jwt = $generator->generate(
            ['id' => $user->id,
                'username' => $username,
                'time_created' => $time_created]);
        $this->redisConnection->client()->set($userKey, $jwt);
        $this->redisConnection->client()->expire($userKey,$this->expPerSec);
        if ($isRefreshToken) {
            $refreshJwt = $generator->generate(
                ['id' => $user->id,
                    'username' => $username,
                    'token_type' => $this->refreshKey,
                    'time_created' => $time_created]);
            $this->redisConnection->client()->set($userKey . $this->refreshKey, $refreshJwt);
        }
        return isset($refreshJwt) ?
            ['access_token' => $jwt, 'user_token_exp' => $this->expPerSec, $this->refreshKey => $refreshJwt, 'auth_user_id' => $user->id]
            :
            ['access_token' => $jwt, 'user_token_exp' => $this->expPerSec, 'auth_user_id' => $user->id];
    }

    private function rbac(int $user_id, string $access_token): array
    {
        $rbac = Http::withToken($access_token)->get(config('app.services.rbac') . '/rbac/roles/access/' . $user_id);
        return json_decode($rbac->body(),true);
    }
}
