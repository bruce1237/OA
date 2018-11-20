<?php

namespace App\Listeners;

use App\Events\EventTest;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class sendMail 
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->sendMail();
        
    }
    
    public function sendMail(){
//        dd("ABC");
        return json_encode("SEND EMAIL");
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
         return json_encode("SEND EMAIL");
    }
}
