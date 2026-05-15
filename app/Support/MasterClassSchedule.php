<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class MasterClassSchedule
{
    public const TIME_SLOTS = [
        '09:00' => '09:00 - 11:00',
        '11:00' => '11:00 - 13:00',
        '13:00' => '13:00 - 15:00',
        '15:00' => '15:00 - 17:00',
    ];

    public static function all(): array
    {
        return self::TIME_SLOTS;
    }

    public static function isValid(?string $timeSlot): bool
    {
        return $timeSlot !== null && array_key_exists(self::normalize($timeSlot), self::TIME_SLOTS);
    }

    public static function label(?string $timeSlot): string
    {
        $normalized = self::normalize($timeSlot);

        return self::TIME_SLOTS[$normalized] ?? (string) $timeSlot;
    }

    public static function normalize(?string $timeSlot): ?string
    {
        if ($timeSlot === null) {
            return null;
        }

        return substr($timeSlot, 0, 5);
    }

    public static function dateOptions(int $days = 21): array
    {
        $today = CarbonImmutable::today();

        return collect(range(0, $days - 1))
            ->map(fn (int $offset) => $today->addDays($offset))
            ->all();
    }
}
