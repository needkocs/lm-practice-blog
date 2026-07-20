<?php

namespace App\Http\Requests\Posts;

use App\DTO\CreatePostData;
use App\Enums\PostVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:180'],
            'content' => ['required', 'string', 'max:20000'],
            'visibility' => ['required', Rule::enum(PostVisibility::class)],
            'tags' => ['nullable', 'array', 'max:10'],
            'tags.*' => ['required', 'string', 'max:40'],
        ];
    }

    public function toDto(): CreatePostData
    {
        return new CreatePostData(
            title: $this->string('title')->toString(),
            content: $this->string('content')->toString(),
            visibility: PostVisibility::from($this->string('visibility')->toString()),
            tags: $this->array('tags'),
        );
    }
}
