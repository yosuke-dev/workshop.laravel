<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content_text'
    ];

    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeUpdateLastFiveMinutes($query)
    {
        $now = new Carbon();
        $query->where('updated_at', '>', $now->subMinutes(5));
    }

    public function getTitleAndContentAttribute()
    {
        return "{$this->title} : {$this->content_text}";
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'attach_tags');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
