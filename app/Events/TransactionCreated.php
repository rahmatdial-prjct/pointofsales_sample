<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('branch.' . $this->transaction->branch_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->transaction->id,
            'invoice_number' => $this->transaction->invoice_number,
            'total' => $this->transaction->total,
            'status' => $this->transaction->status,
            'created_at' => $this->transaction->created_at,
        ];
    }
} 