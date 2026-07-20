<?php

namespace App\Models;

use App\Enums\PostVisibility;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property PostVisibility $visibility
 * @property Carbon|null $published_at
 * @property Collection<int, Tag> $tags
 * @property User $author
 */
#[UseFactory(PostFactory::class)]
#[Fillable(['user_id', 'title', 'slug', 'content', 'visibility', 'published_at'])]
class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'visibility' => PostVisibility::class,
            'published_at' => 'immutable_datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function accessRequests(): HasMany
    {
        return $this->hasMany(PostAccessRequest::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at');
    }

    public function isPublic(): bool
    {
        return $this->visibility === PostVisibility::Public;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
