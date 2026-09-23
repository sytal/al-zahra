<x-layouts.public>
    <x-slot name="seo">
        <x-seo :title="$seo['title']" :description="$seo['description']" :image="$seo['image']" :type="$seo['type']" :schema="$seo['schema']" />
    </x-slot>

    @if ($director)
        <!-- 1. Hero: cover photo + name/title overlay -->
        <section class="relative flex h-72 items-end justify-center overflow-hidden bg-ink text-white" data-aos="fade-up">
            @if ($director->hasMedia('cover_photo'))
                <img src="{{ $director->getFirstMediaUrl('cover_photo') }}" alt="" class="absolute inset-0 size-full object-cover opacity-50">
            @endif
            <div class="relative z-10 pb-8 text-center">
                <h1 class="text-3xl font-bold md:text-4xl">{{ $director->full_name }}</h1>
                <p class="mt-1 text-white/80">{{ $director->professional_title }}</p>
            </div>
        </section>

        <!-- 2. Full director profile -->
        <section class="mx-auto max-w-4xl px-4 py-16" data-aos="fade-up">
            <x-director-profile :compact="false" />
        </section>

        <!-- 3. Mission & Vision -->
        <section class="bg-surface px-4 py-16" data-aos="fade-up">
            <div class="mx-auto grid max-w-4xl gap-8 sm:grid-cols-2">
                @if ($mission)
                    <div>
                        <h2 class="text-xl font-semibold text-ink">{{ __('about.mission_title') }}</h2>
                        <p class="mt-2 text-ink/70">{{ $mission }}</p>
                    </div>
                @endif
                @if ($vision)
                    <div>
                        <h2 class="text-xl font-semibold text-ink">{{ __('about.vision_title') }}</h2>
                        <p class="mt-2 text-ink/70">{{ $vision }}</p>
                    </div>
                @endif
            </div>
        </section>

        <!-- 4. Social/contact links -->
        @if (!empty($director->social_links))
            <section class="mx-auto max-w-4xl px-4 py-16 text-center" data-aos="fade-up">
                <h2 class="mb-4 text-xl font-semibold text-ink">{{ __('about.connect_title') }}</h2>
                <div class="flex flex-wrap justify-center gap-3">
                    @foreach ($director->social_links as $platform => $url)
                        @if ($url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="rounded-full border border-ink/10 px-4 py-2 text-sm text-ink/70 transition duration-200 ease-in-out hover:border-brand-primary hover:text-brand-primary">
                                {{ ucfirst($platform) }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </section>
        @endif
    @else
        <section class="mx-auto max-w-4xl px-4 py-16 text-center" data-aos="fade-up">
            <x-empty-state icon="user" :title="__('about.page_title')" />
        </section>
    @endif
</x-layouts.public>
