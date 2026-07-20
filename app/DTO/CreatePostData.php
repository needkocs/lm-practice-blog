<?php

namespace App\DTO;

use App\Enums\PostVisibility;

readonly class CreatePostData
{
    /**
     * @param  list<string>  $tags
     */
    public function __construct(
        public string $title,
        public string $content,
        public PostVisibility $visibility,
        public array $tags,
    ) {}
}
