<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RequestModel;
use DB;

class RequestReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $pendingRequestCount;
    public $approvedGroupRequestCount;

    public function __construct()
    {
        $this->pendingRequestCount = DB::table('requests')->where('status','pending')->get()->count();
        $this->approvedGroupRequestCount = DB::table('requests')
                                                ->where('status','approved')
                                                ->whereIn('operation', ['groups.create','groups.update'])
                                                ->where('is_processed',0)
                                                ->get()
                                                ->count();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['requests'];
    }
}
