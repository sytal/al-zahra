<div class="mx-auto max-w-3xl px-4 py-8 space-y-6" data-aos="fade-up">
    <h1 class="text-2xl font-bold text-ink">{{ __('dashboard.profile_page_title') }}</h1>

    {{-- Avatar card --}}
    <x-card>
        <h2 class="font-semibold text-ink">{{ __('dashboard.profile_avatar_heading') }}</h2>

        @if ($avatarStatus)
            <p class="mt-2 rounded-lg bg-success/10 px-3 py-2 text-sm text-success">{{ $avatarStatus }}</p>
        @endif

        <div class="mt-4 flex items-center gap-4">
            @if (auth()->user()->getFirstMediaUrl('avatar'))
                <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" alt="{{ auth()->user()->name }}" class="size-16 rounded-full object-cover" />
            @else
                <div class="flex size-16 items-center justify-center rounded-full bg-surface text-ink/40">
                    <x-icon name="user-circle" class="size-10" />
                </div>
            @endif

            <div class="flex-1">
                <form wire:submit="updateAvatar" class="flex flex-wrap items-center gap-3">
                    <input type="file" wire:model="avatar" accept="image/*" class="block text-sm text-ink/70" />
                    <x-button type="submit" variant="primary" size="sm">{{ __('dashboard.profile_avatar_upload') }}</x-button>
                    @if (auth()->user()->getFirstMediaUrl('avatar'))
                        <x-button type="button" wire:click="removeAvatar" variant="ghost" size="sm">{{ __('dashboard.profile_avatar_remove') }}</x-button>
                    @endif
                </form>
                @error('avatar')<p class="mt-1 text-sm text-danger">{{ $message }}</p>@enderror
            </div>
        </div>
    </x-card>

    {{-- Basic info card --}}
    <x-card>
        <h2 class="font-semibold text-ink">{{ __('dashboard.profile_basic_info_heading') }}</h2>

        @if ($profileStatus)
            <p class="mt-2 rounded-lg bg-success/10 px-3 py-2 text-sm text-success">{{ $profileStatus }}</p>
        @endif

        <form wire:submit="updateProfile" class="mt-4 space-y-4">
            <x-input name="name" :label="__('dashboard.profile_name')" wire:model="name" />
            <x-input name="email" type="email" :label="__('dashboard.profile_email')" wire:model="email" />
            <x-input name="phone" :label="__('dashboard.profile_phone')" wire:model="phone" />

            <x-select name="preferred_locale" :label="__('dashboard.profile_preferred_locale')" wire:model="preferred_locale" :options="collect($locales)->mapWithKeys(fn ($l) => [$l => strtoupper($l)])->all()" />

            <x-button type="submit" variant="primary">{{ __('dashboard.profile_save_changes') }}</x-button>
        </form>
    </x-card>

    {{-- Password card --}}
    <x-card>
        <h2 class="font-semibold text-ink">{{ __('dashboard.profile_password_heading') }}</h2>

        @if ($passwordStatus)
            <p class="mt-2 rounded-lg bg-success/10 px-3 py-2 text-sm text-success">{{ $passwordStatus }}</p>
        @endif

        <form wire:submit="updatePassword" class="mt-4 space-y-4">
            <x-input name="current_password" type="password" :label="__('dashboard.profile_current_password')" wire:model="current_password" />
            <x-input name="password" type="password" :label="__('dashboard.profile_new_password')" wire:model="password" />
            <x-input name="password_confirmation" type="password" :label="__('dashboard.profile_confirm_password')" wire:model="password_confirmation" />

            <x-button type="submit" variant="primary">{{ __('dashboard.profile_update_password_cta') }}</x-button>
        </form>
    </x-card>
</div>
