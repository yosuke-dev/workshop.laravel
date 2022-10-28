<?php

namespace App\Listeners;

use App\Events\Posted;
use Illuminate\Support\Facades\Log;

class PostNotificationListener
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
     * @param  Posted  $event
     * @return void
     */
    public function handle(Posted $event)
    {
        Log::Debug("{$event->post()->user->name}({$event->post()->user->email})に本当は投稿が完了したことを通知する");
    }
}
