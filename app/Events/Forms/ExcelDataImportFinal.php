<?php

namespace App\Events\Forms;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Redis;

class ExcelDataImportFinal implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(private $message, private $key, protected string $sessionId)
    {
        // Чистим идентификаторы из памяти
        Redis::del(config('app.redis_keys.rows_names_prefix').$key);
        Redis::del(config('app.redis_keys.rows_count_prefix').$key);
        Redis::del(config('app.redis_keys.rows_uploaded_prefix').$sessionId);
    }

    public function broadcastOn()
    {
        return new Channel('excel-data-import-final.'.$this->key);
    }
}
