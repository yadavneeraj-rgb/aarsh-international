<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Orders;

class OrderUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $action;

    public function __construct(Orders $order, $action = 'created')
    {
        $this->order = $order;
        $this->action = $action;
    }

    public function broadcastOn()
    {
        return new Channel('order-updates');
    }

    public function broadcastAs()
    {
        return 'order.updated';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->order->id,
            'first_name' => $this->order->first_name,
            'last_name' => $this->order->last_name,
            'email' => $this->order->email,
            'amount' => $this->order->amount,
            'payment_status' => $this->order->payment_status,
            'payment_method' => $this->order->payment_method,
            'created_at' => $this->order->created_at->toISOString(),
            'action' => $this->action
        ];
    }
}