<?php
namespace App\Modules\Jwt\Interfaces;

use App\Modules\User\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;

interface InterfaceJwt
{
    public function parse(string $token, Request $request): Request|HttpResponseException;
    public function getRedisToken(string $key): ?string;
}