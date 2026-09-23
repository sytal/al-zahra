<?php

namespace App\Modules\User\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.minimal')]
#[Title('Confirm Password')]
class ConfirmPassword extends Component
{
    public string $password = '';

    public function confirm(): void
    {
        $this->validate(['password' => ['required', 'string']]);

        if (! Auth::guard('web')->validate([
            'email' => auth()->user()->email,
            'password' => $this->password,
        ])) {
            $this->addError('password', __('auth.password'));

            return;
        }

        request()->session()->put('auth.password_confirmed_at', time());

        $this->redirectRoute('dashboard', ['locale' => app()->getLocale()], navigate: true);
    }

    public function render()
    {
        $seo = [
            'title' => __('auth-pages.confirm_password_title'),
            'description' => null,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.user.confirm-password', compact('seo'));
    }
}
