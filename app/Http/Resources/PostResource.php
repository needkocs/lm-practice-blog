<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Support\DateFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/**
 * @mixin Post
 */
class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $canViewFullContent = (bool) ($this->additional['canViewFullContent'] ?? $this->isPublic());

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $canViewFullContent ? $this->getAttribute('content') : null,
            'excerpt' => Str::limit(strip_tags((string) ($this->getAttribute('excerpt') ?? $this->getAttribute('content'))), 180),
            'visibility' => $this->visibility->value,
            'visibility_label' => $this->visibility->label(),
            'published_at' => DateFormatter::date($this->published_at),
            'created_at' => DateFormatter::date($this->created_at),
            'author' => [
                'id' => $this->author->id,
                'name' => $this->author->name,
            ],
            'tags' => $this->tags->map(fn ($tag): array => [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
            ])->values(),
            'can' => [
                'view_full' => $canViewFullContent,
                'update' => $request->user()?->can('update', $this->resource) ?? false,
                'delete' => $request->user()?->can('delete', $this->resource) ?? false,
            ],
        ];
    }
}
