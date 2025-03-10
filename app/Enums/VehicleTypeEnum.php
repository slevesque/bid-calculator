<?php

namespace App\Enums;

enum VehicleTypeEnum: string
{
    case COMMON = 'common';
    case LUXURY = 'luxury';

    /**
     * Get the label for HTML display.
     */
    public function label()
    {
        return match ($this) {
            self::COMMON => 'Common',
            self::LUXURY => 'Luxury',
        };
    }

    /**
     * Get options for a select dropdown
     */
    public static function options(): array
    {
        return array_map(fn ($status) => [
            'value' => $status->value,
            'label' => $status->label(),
        ], self::cases());
    }
}
