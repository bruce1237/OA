<?php

namespace App\Listeners;

use App\Events\EventTest;

class sendSms
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
         return json_encode("SEND SMS");
         
         $a="d";
         
         
    }

}
