<?php

namespace App\Events\Forms;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class ExcelDataImportProcessed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(private $message, protected $key)
    {

    }

    public function broadcastOn()
    {
        return new Channel('excel-data-import-process.'.$this->key);
    }
}
