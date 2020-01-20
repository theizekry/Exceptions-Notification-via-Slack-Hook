<?php

namespace App\Events\Exceptions;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Exception;

class ExceptionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $exception;
    public $requestData;

    /**
     * Create a new event instance.
     *
     * THIS EVENT JUST TO PUSH NOTIFICATION VIA SLACK WHEN ANY EXCEPTION OCCURRED.
     *
     * @param Exception $exception
     * @param $requestData
     */
    public function __construct(Exception $exception, array $requestData)
    {
        $this->exception   = $exception;
        $this->requestData = $requestData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
