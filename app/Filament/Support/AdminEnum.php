<?php

namespace App\Filament\Support;

use BackedEnum;

class AdminEnum
{
    public static function label(mixed $state): string
    {
        $value = $state instanceof BackedEnum ? (string) $state->value : (string) $state;

        return __('admin_ui.enum.'.$value);
    }

    public static function color(mixed $state): string
    {
        $value = $state instanceof BackedEnum ? (string) $state->value : (string) $state;

        return match ($value) {
            'answered', 'replied', 'free_question', 'beginner', 'pdf' => 'success',
            'pending', 'new', 'paid_booking', 'intermediate' => 'warning',
            'scheduled', 'read', 'template', 'students' => 'info',
            'completed', 'guide', 'advanced' => 'primary',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
