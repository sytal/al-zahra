<?php

namespace App\Modules\User\Livewire;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.minimal')]
#[Title('Forgot Password')]
class ForgotPassword extends Component
{
    public string $email = '';

    public ?string $status = null;

    public function sendResetLink(): void
    {
        $this->validate(['email' => ['required', 'email']]);

        $result = Password::sendResetLink(['email' => $this->email]);

        if ($result === Password::RESET_LINK_SENT) {
            $this->status = __($result);
            $this->email = '';
        } else {
            $this->addError('email', __($result));
        }
    }

    public function render()
    {
        $seo = [
            'title' => __('auth-pages.forgot_password_title'),
            'description' => null,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.user.forgot-password', compact('seo'));
    }
}
