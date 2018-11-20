<?php

namespace App\Listeners;

use App\Events\EventTest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendHelp
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
     * @param  EventTest  $event
     * @return void
     */
    public function handle(EventTest $event)
    {
        //
         return json_encode("SEND HELP");
    }
}
