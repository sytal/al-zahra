<?php

namespace App\Modules\User\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.dashboard')]
#[Title('Profile')]
class ProfileEdit extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public string $preferred_locale = 'en';

    public $avatar = null;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public ?string $profileStatus = null;

    public ?string $passwordStatus = null;

    public ?string $avatarStatus = null;

    public function mount(): void
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->preferred_locale = $user->preferred_locale ?? app()->getLocale();
    }

    public function updateProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'lowercase', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'preferred_locale' => ['required', 'in:'.implode(',', config('app.locales'))],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email') && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->profileStatus = __('dashboard.profile_updated');
    }

    public function updateAvatar(): void
    {
        $this->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        Auth::user()->addMedia($this->avatar->getRealPath())
            ->usingFileName($this->avatar->getClientOriginalName())
            ->toMediaCollection('avatar');

        $this->reset('avatar');
        $this->avatarStatus = __('dashboard.profile_avatar_updated');
    }

    public function removeAvatar(): void
    {
        Auth::user()->clearMediaCollection('avatar');

        $this->avatarStatus = __('dashboard.profile_avatar_removed');
    }

    public function updatePassword(): void
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ]);

        Auth::user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        $this->reset('current_password', 'password', 'password_confirmation');
        $this->passwordStatus = __('dashboard.profile_password_updated');
    }

    public function render()
    {
        $seo = [
            'title' => __('dashboard.profile_page_title'),
            'description' => __('dashboard.profile_page_title'),
            'image' => null,
            'type' => 'website',
            'schema' => null,
        ];

        return view('livewire.dashboard.profile-edit', [
            'seo' => $seo,
            'locales' => config('app.locales'),
        ]);
    }
}
