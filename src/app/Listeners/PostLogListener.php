<?php

namespace App\Listeners;

use App\Events\Posted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class PostLogListener
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
        Log::info("{$event->post()->userId}: {$event->post()->title}が投稿されました");
    }
}
