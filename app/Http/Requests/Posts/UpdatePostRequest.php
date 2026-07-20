<?php

namespace App\Http\Requests\Posts;

use App\DTO\UpdatePostData;
use App\Enums\PostVisibility;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('post')) ?? false;
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

    public function toDto(): UpdatePostData
    {
        return new UpdatePostData(
            title: $this->string('title')->toString(),
            content: $this->string('content')->toString(),
            visibility: PostVisibility::from($this->string('visibility')->toString()),
            tags: $this->array('tags'),
        );
    }
}
