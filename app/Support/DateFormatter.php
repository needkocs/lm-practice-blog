<?php

namespace App\Support;

use Carbon\CarbonInterface;

class DateFormatter
{
    public static function date(?CarbonInterface $date): ?string
    {
        return $date?->locale('ru')->translatedFormat('j F Y');
    }
}
