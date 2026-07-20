<?php

namespace App\DTO;

use App\Enums\PostVisibility;

readonly class PostFilterData
{
    /**
     * @param  list<string>  $tags
     */
    public function __construct(
        public array $tags = [],
        public ?int $authorId = null,
        public ?string $search = null,
        public ?PostVisibility $visibility = null,
        public string $sort = 'newest',
    ) {}
}
