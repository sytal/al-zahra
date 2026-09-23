<?php

namespace App\Modules\User\Livewire;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.minimal')]
#[Title('Reset Password')]
class ResetPassword extends Component
{
    public string $token = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
        $this->email = (string) request()->query('email', '');
    }

    public function resetPassword(): void
    {
        $data = $this->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $status = Password::reset(
            $data,
            function (User $user) use ($data) {
                $user->forceFill([
                    'password' => Hash::make($data['password']),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            $this->redirectRoute('login', ['locale' => app()->getLocale()], navigate: true);

            return;
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        $seo = [
            'title' => __('auth-pages.reset_password_title'),
            'description' => null,
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.user.reset-password', compact('seo'));
    }
}
