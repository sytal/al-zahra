<div>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
        <meta name="robots" content="noindex, nofollow">
    </x-slot>

    @php
        $user = auth()->user();
        $avatarUrl = $user->getFirstMediaUrl('avatar');
        $preview = ($avatar && $avatar->isPreviewable()) ? $avatar->temporaryUrl() : null;
        $names = ['en' => 'English', 'ur' => 'اردو', 'hi' => 'हिन्दी', 'fa' => 'فارسی', 'ur-roman' => 'Roman Urdu'];
        $sections = [
            'photo' => ['label' => __('dash_learn_ui.pf_nav_photo'), 'icon' => 'camera'],
            'basic' => ['label' => __('dash_learn_ui.pf_nav_basic'), 'icon' => 'identification'],
            'prefs' => ['label' => __('dash_learn_ui.pf_nav_prefs'), 'icon' => 'language'],
            'password' => ['label' => __('dash_learn_ui.pf_nav_password'), 'icon' => 'lock-closed'],
        ];
        $cardHead = 'flex items-start gap-3 border-b border-subtle p-5 sm:p-6';
        $iconBox = 'flex size-11 shrink-0 items-center justify-center rounded-xl bg-tint text-brand-primary';
    @endphp

    <x-toast />

    <div
        class="mx-auto w-full max-w-6xl space-y-6 px-4 py-6 md:px-6 md:py-8"
        x-data="{
            active: 'photo',
            init() {
                if (!('IntersectionObserver' in window)) return;
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((e) => { if (e.isIntersecting) this.active = e.target.id.replace('sec-', ''); });
                }, { rootMargin: '-30% 0px -60% 0px' });
                this.$root.querySelectorAll('[data-section]').forEach((el) => io.observe(el));
            },
        }"
    >
        <section class="relative isolate overflow-hidden rounded-panel border border-subtle bg-hero-gradient p-5 sm:p-8">
            <span class="glow-teal -z-10" style="--glow-size: 20rem; inset-inline-end: -5rem; top: -8rem" aria-hidden="true"></span>
            <span class="star-mark pointer-events-none absolute -end-6 -top-6 -z-10 text-[9rem] opacity-15 sm:text-[12rem]" aria-hidden="true"></span>
            <x-app.page-header :title="__('dashboard.profile_page_title')" :eyebrow="__('dash_learn_ui.pf_eyebrow')" :description="__('dash_learn_ui.pf_subtitle')" />
            <p class="mt-4 flex min-w-0 items-center gap-3 text-sm text-body">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="" width="40" height="40" class="size-10 shrink-0 rounded-full object-cover ring-2 ring-surface-raised">
                @else
                    <x-avatar :name="$user->name" size="md" decorative />
                @endif
                <span class="min-w-0"><span class="block text-xs text-muted">{{ __('dash_learn_ui.pf_member') }}</span><span class="block truncate font-semibold text-strong" dir="auto">{{ $user->email }}</span></span>
            </p>
        </section>

        <div class="grid items-start gap-6 lg:grid-cols-[13rem_minmax(0,1fr)] xl:gap-8">
            <nav aria-label="{{ __('dash_learn_ui.pf_nav') }}" class="sticky top-14 z-sticky -mx-4 border-b border-subtle bg-page/90 px-4 backdrop-blur md:top-16 md:-mx-6 md:px-6 lg:top-24 lg:mx-0 lg:border-0 lg:bg-transparent lg:p-0 lg:backdrop-blur-none">
                <ul class="no-scrollbar flex gap-1 overflow-x-auto py-2 lg:flex-col lg:overflow-visible lg:py-0">
                    @foreach ($sections as $id => $s)
                        <li class="shrink-0">
                            <a
                                href="#sec-{{ $id }}"
                                x-on:click="active = '{{ $id }}'"
                                x-bind:aria-current="active === '{{ $id }}' ? 'true' : null"
                                class="focus-ring flex min-h-11 items-center gap-2.5 whitespace-nowrap rounded-xl px-3.5 text-sm font-semibold transition duration-fast lg:w-full"
                                x-bind:class="active === '{{ $id }}' ? 'bg-brand text-on-brand shadow-soft' : 'text-body [@media(hover:hover)]:hover:bg-tint [@media(hover:hover)]:hover:text-strong'"
                            >
                                <x-icon :name="$s['icon']" class="size-5 shrink-0" aria-hidden="true" />{{ $s['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="min-w-0 space-y-6">
                {{-- Photo --}}
                <section id="sec-photo" data-section class="card-surface scroll-mt-32 overflow-hidden">
                    <header class="{{ $cardHead }}">
                        <span class="{{ $iconBox }}"><x-icon name="camera" class="size-6" aria-hidden="true" /></span>
                        <div class="min-w-0">
                            <h2 class="heading-4 text-strong">{{ __('dashboard.profile_avatar_heading') }}</h2>
                            <p class="mt-0.5 text-sm text-body">{{ __('dash_learn_ui.pf_photo_hint') }}</p>
                        </div>
                    </header>

                    <div class="p-5 sm:p-6">
                        <form wire:submit="updateAvatar" class="flex flex-col gap-5 sm:flex-row sm:items-center">
                            <div class="relative mx-auto shrink-0 sm:mx-0">
                                @if ($preview || $avatarUrl)
                                    <img src="{{ $preview ?? $avatarUrl }}" alt="{{ $preview ? __('dash_learn_ui.pf_preview') : $user->name }}" width="112" height="112" class="size-28 rounded-full object-cover ring-4 ring-surface-raised shadow-lift {{ $preview ? 'outline outline-2 outline-offset-2 outline-brand-secondary' : '' }}">
                                @else
                                    <x-avatar :name="$user->name" size="xl" decorative class="!size-28 !text-4xl" />
                                @endif
                                <span wire:loading.flex wire:target="avatar" class="absolute inset-0 items-center justify-center rounded-full bg-page/70 backdrop-blur-sm" role="status">
                                    <x-spinner size="md" />
                                    <span class="sr-only">{{ __('dash_learn_ui.pf_uploading') }}</span>
                                </span>
                            </div>

                            <div class="min-w-0 flex-1 space-y-3">
                                <div class="relative">
                                    <input id="avatar-file" type="file" wire:model="avatar" accept="image/jpeg,image/png,image/webp" class="peer absolute inset-0 z-[1] size-full cursor-pointer opacity-0">
                                    <div class="flex min-h-20 items-center gap-3 rounded-2xl border-2 border-dashed border-strong bg-surface-sunken px-4 py-3 text-sm text-body transition duration-fast peer-hover:border-brand peer-hover:bg-tint peer-focus-visible:ring-4 peer-focus-visible:ring-brand/30">
                                        <x-icon name="arrow-up-tray" class="size-6 shrink-0 text-brand-primary" aria-hidden="true" />
                                        <span class="min-w-0 break-words font-semibold text-strong">{{ $avatar ? $avatar->getClientOriginalName() : __('dash_learn_ui.pf_choose_photo') }}</span>
                                    </div>
                                </div>
                                @error('avatar')<p class="text-sm font-medium text-danger" role="alert">{{ $message }}</p>@enderror

                                <div class="flex flex-wrap items-center gap-2">
                                    @if ($avatar)
                                        <x-button type="submit" variant="primary" icon="check">{{ __('dashboard.profile_avatar_upload') }}</x-button>
                                        <x-button type="button" wire:click="$set('avatar', null)" variant="ghost">{{ __('dash_learn_ui.pf_cancel') }}</x-button>
                                    @endif
                                    @if ($avatarUrl && ! $avatar)
                                        <x-button type="button" wire:click="removeAvatar" variant="ghost" icon="trash">{{ __('dashboard.profile_avatar_remove') }}</x-button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </section>

                {{-- Basic info + preferences (one form, one save) --}}
                <form wire:submit="updateProfile" class="space-y-6" novalidate>
                    <section id="sec-basic" data-section class="card-surface scroll-mt-32 overflow-hidden">
                        <header class="{{ $cardHead }}">
                            <span class="{{ $iconBox }}"><x-icon name="identification" class="size-6" aria-hidden="true" /></span>
                            <div class="min-w-0">
                                <h2 class="heading-4 text-strong">{{ __('dashboard.profile_basic_info_heading') }}</h2>
                                <p class="mt-0.5 text-sm text-body">{{ __('dash_learn_ui.pf_basic_hint') }}</p>
                            </div>
                        </header>
                        <div class="grid gap-5 p-5 sm:p-6 md:grid-cols-2">
                            <x-input name="name" icon="user" :label="__('dashboard.profile_name')" autocomplete="name" required wire:model="name" class="md:col-span-2" />
                            <x-input name="email" type="email" icon="envelope" :label="__('dashboard.profile_email')" required wire:model="email" dir="ltr" />
                            <x-input name="phone" type="tel" icon="phone" :label="__('dashboard.profile_phone')" wire:model="phone" dir="ltr" />
                        </div>
                    </section>

                    <section id="sec-prefs" data-section class="card-surface scroll-mt-32 overflow-hidden">
                        <header class="{{ $cardHead }}">
                            <span class="{{ $iconBox }}"><x-icon name="language" class="size-6" aria-hidden="true" /></span>
                            <div class="min-w-0">
                                <h2 class="heading-4 text-strong">{{ __('dash_learn_ui.pf_prefs_heading') }}</h2>
                                <p class="mt-0.5 text-sm text-body">{{ __('dash_learn_ui.pf_prefs_hint') }}</p>
                            </div>
                        </header>
                        <div class="p-5 sm:p-6">
                            <x-select name="preferred_locale" icon="globe-alt" :label="__('dashboard.profile_preferred_locale')" wire:model="preferred_locale" :options="collect($locales)->mapWithKeys(fn ($l) => [$l => $names[$l] ?? strtoupper($l)])->all()" class="max-w-sm" />
                        </div>
                    </section>

                    <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-subtle bg-surface-raised p-4">
                        <p class="flex min-w-0 items-center gap-2 text-sm">
                            <span wire:dirty wire:target="name,email,phone,preferred_locale" class="inline-flex items-center gap-2 font-semibold text-strong">
                                <span class="size-2.5 shrink-0 rounded-full bg-brand-secondary animate-pulse-soft" aria-hidden="true"></span>{{ __('dash_learn_ui.pf_unsaved') }}
                            </span>
                            <span wire:dirty.remove wire:target="name,email,phone,preferred_locale" class="inline-flex items-center gap-2 text-muted">
                                <x-icon name="check-circle" class="size-5 shrink-0 text-success" aria-hidden="true" />{{ __('dash_learn_ui.pf_saved') }}
                            </span>
                        </p>
                        <x-button type="submit" variant="primary" icon="check" wire:target="updateProfile">{{ __('dashboard.profile_save_changes') }}</x-button>
                    </div>
                </form>

                {{-- Password --}}
                <section id="sec-password" data-section class="card-surface scroll-mt-32 overflow-hidden">
                    <header class="{{ $cardHead }}">
                        <span class="{{ $iconBox }}"><x-icon name="lock-closed" class="size-6" aria-hidden="true" /></span>
                        <div class="min-w-0">
                            <h2 class="heading-4 text-strong">{{ __('dashboard.profile_password_heading') }}</h2>
                            <p class="mt-0.5 text-sm text-body">{{ __('dash_learn_ui.pf_password_hint') }}</p>
                        </div>
                    </header>

                    <form
                        wire:submit="updatePassword"
                        class="space-y-5 p-5 sm:p-6"
                        novalidate
                        x-data="{
                            get score() {
                                const p = $wire.password || '';
                                if (!p) return 0;
                                let s = 0;
                                if (p.length >= 8) s++;
                                if (p.length >= 12) s++;
                                if (/[a-z]/.test(p) && /[A-Z]/.test(p)) s++;
                                if (/\d/.test(p) && /[^A-Za-z0-9]/.test(p)) s++;
                                return Math.max(1, s);
                            },
                            labels: @js([__('dash_learn_ui.pf_strength_weak'), __('dash_learn_ui.pf_strength_fair'), __('dash_learn_ui.pf_strength_good'), __('dash_learn_ui.pf_strength_strong')]),
                        }"
                    >
                        <div class="max-w-md">
                            <x-input name="current_password" type="password" icon="key" :label="__('dashboard.profile_current_password')" wire:model="current_password" />
                        </div>
                        <div class="grid gap-5 md:grid-cols-2">
                            <div class="space-y-3">
                                <x-input name="password" type="password" icon="lock-closed" :label="__('dashboard.profile_new_password')" wire:model="password" />
                                <div x-show="score > 0" x-cloak x-transition.opacity class="space-y-1.5" aria-live="polite">
                                    <div class="flex gap-1.5" aria-hidden="true">
                                        @foreach ([1, 2, 3, 4] as $n)
                                            <span class="h-1.5 flex-1 rounded-full bg-surface-sunken transition-colors duration-base" x-bind:class="score >= {{ $n }} ? (score <= 1 ? 'bg-danger' : (score === 2 ? 'bg-warning' : (score === 3 ? 'bg-info' : 'bg-success'))) : ''"></span>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-body">{{ __('dash_learn_ui.pf_strength') }}: <span class="font-semibold text-strong" x-text="labels[Math.max(score, 1) - 1]"></span></p>
                                </div>
                            </div>
                            <x-input name="password_confirmation" type="password" icon="lock-closed" :label="__('dashboard.profile_confirm_password')" wire:model="password_confirmation" />
                        </div>
                        <x-button type="submit" variant="primary" icon="shield-check" wire:target="updatePassword">{{ __('dashboard.profile_update_password_cta') }}</x-button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
