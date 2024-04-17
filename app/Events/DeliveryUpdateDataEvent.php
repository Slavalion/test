<?php

namespace App\Events;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryUpdateDataEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $user,
        public Purchase $purchase,
    ) {
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'dilivery.update';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->user->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->purchase->id,
            'delivery_status' => $this->purchase->delivery_status,
            'delivery_qrcode' => $this->purchase->delivery_qrcode.'?v='.microtime(),
            'delivery_pin' => $this->purchase->delivery_pin,
        ];
    }
}
