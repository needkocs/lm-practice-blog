<?php

namespace App\DTO;

readonly class CreateAccessRequestData
{
    public function __construct(
        public ?string $message,
    ) {}
}
