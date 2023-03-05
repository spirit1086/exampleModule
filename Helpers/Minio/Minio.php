<?php

namespace App\Helpers\Minio;

use App\Helpers\Setting\Setting;
use Aws;

class Minio {

    private $access_key;
    private $secret_key;
    private $bucket;
    private $s3;

    public function __construct()
    {
        require base_path().'/vendor/autoload.php';
        $this->access_key = config('app.minio.access_key');
        $this->secret_key = config('app.minio.secret_key');
        $this->bucket = config('app.minio.bucket');
        $credentials = new Aws\Credentials\Credentials($this->access_key, $this->secret_key);
        $this->s3 = new Aws\S3\S3Client([
            'version' => 'latest',
            'region'=>'us-east-1',
            'endpoint' => config('app.minio.host'),
            'use_path_style_endpoint' => true,
            'credentials' => $credentials,
        ]);
    }

    public function put($key,string $originalName, string $path = ''){
        $this->s3->putObject([
            'Bucket' => $this->bucket,
            'Key'    => $originalName,
            'SourceFile' =>  $path . $key,
        ]);
    }

    public function getUrl(string $key,bool $expire =false){
        if($expire){
            $cmd = $this->s3->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key'    => $key
            ]);

            $request = $this->s3->createPresignedRequest($cmd, '+24 hour');
            return $request->getUri();
        }
        return $this->s3->getObjectUrl($this->bucket, $key);
    }

    public function setFile(string $key,string $originalName, string $path = ''){
        $this->put($key, $originalName, $path);
        return $this->getUrl($originalName, true);
    }

}
