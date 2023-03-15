<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @noinspection PhpUnused */
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'attach_tags');
    }
}
