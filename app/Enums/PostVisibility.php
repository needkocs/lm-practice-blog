<?php

namespace App\Enums;

enum PostVisibility: string
{
    case Public = 'public';
    case RequestOnly = 'request_only';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Публичный',
            self::RequestOnly => 'По запросу',
        };
    }
}
