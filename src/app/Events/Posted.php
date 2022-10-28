<?php

namespace App\Events;

use App\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Posted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $post;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function post(): Post
    {
        return $this->post;
    }
}
