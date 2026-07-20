<?php

namespace App\Http\Requests\Posts;

use App\DTO\PostFilterData;
use App\Enums\PostVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tags' => ['nullable', 'string'],
            'author' => ['nullable', 'integer', 'exists:users,id'],
            'search' => ['nullable', 'string', 'max:100'],
            'visibility' => ['nullable', Rule::enum(PostVisibility::class)],
            'sort' => ['nullable', Rule::in(['newest', 'oldest', 'title_asc', 'title_desc'])],
        ];
    }

    public function toDto(): PostFilterData
    {
        $tags = collect(explode(',', $this->string('tags')->toString()))
            ->map(fn (string $tag): string => trim($tag))
            ->filter()
            ->values()
            ->all();

        return new PostFilterData(
            tags: $tags,
            authorId: $this->integer('author') ?: null,
            search: $this->filled('search') ? $this->string('search')->toString() : null,
            visibility: $this->filled('visibility') ? PostVisibility::from($this->string('visibility')->toString()) : null,
            sort: $this->string('sort', 'newest')->toString(),
        );
    }
}
