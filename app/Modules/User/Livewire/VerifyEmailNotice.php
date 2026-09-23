<?php

namespace App\Modules\User\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.minimal')]
#[Title('Verify Email')]
class VerifyEmailNotice extends Component
{
    public bool $resent = false;

    public function resend(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectRoute('dashboard', ['locale' => app()->getLocale()], navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->resent = true;
    }

    public function logout(): void
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        $this->redirectRoute('home', ['locale' => app()->getLocale()], navigate: true);
    }

    public function render()
    {
        $seo = [
            'title' => __('auth-pages.verify_email_title'),
            'description' => null,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.user.verify-email-notice', compact('seo'));
    }
}
