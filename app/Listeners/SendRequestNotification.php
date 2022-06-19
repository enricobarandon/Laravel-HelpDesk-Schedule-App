<?php

namespace App\Listeners;

use App\Events\RequestReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendRequestNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RequestReceived  $event
     * @return void
     */
    public function handle(RequestReceived $event)
    {
        //
    }
}
