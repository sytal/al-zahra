<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Contracts\Support\Htmlable;

class Login extends BaseLogin
{
    protected static string $layout = 'filament.layouts.auth-split';

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string|Htmlable|null
    {
        return __('admin_ui.login_heading');
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __('admin_ui.login_subheading');
    }
}
