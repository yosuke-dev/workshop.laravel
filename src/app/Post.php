<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $title
 * @property string $content_text
 * @property-read User|null $user
 * @property-read Collection|Comment[] $comments
 * @property-read Collection|Tag[] $tags
 * @property-read Collection|Image[] $images
 */
class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content_text'
    ];

    use SoftDeletes;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeUpdateLastFiveMinutes($query): void
    {
        $now = new Carbon();
        $query->where('updated_at', '>', $now->subMinutes(5));
    }

    public function getTitleAndContentAttribute(): string
    {
        return "$this->title : $this->content_text";
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'attach_tags');
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getShortenTitleAttribute(): string
    {
        return substr($this->title, 0, 10);
    }

    private function unUsedFunction(): void
    {
        // This function is not used anywhere
    }
}
