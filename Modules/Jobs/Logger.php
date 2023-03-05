<?php

namespace App\Modules\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class Logger implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $redis;
    /**
     * @var array $data
     */
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->redis = Redis::connection(config('database.redis_connection'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $json_data = json_encode($this->data);
        $this->redis->client()->rPush('logger-history',$json_data);
    }
}
