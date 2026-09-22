@props(['compact' => true])

@php
$director = \App\Modules\Director\Models\Director::where('is_published', true)->first();
@endphp

@if ($director)
    <div class="flex {{ $compact ? 'items-center gap-4' : 'flex-col gap-6' }}" {{ $attributes }}>
        <img
            src="{{ $director->getFirstMediaUrl('profile_photo') ?: asset('images/avatar-placeholder.png') }}"
            alt="{{ $director->full_name }}"
            class="{{ $compact ? 'size-16' : 'size-40' }} rounded-full object-cover"
        >
        <div>
            <h3 class="{{ $compact ? 'text-lg' : 'text-2xl' }} font-semibold text-ink">{{ $director->full_name }}</h3>
            <p class="text-brand-primary">{{ $director->professional_title }}</p>

            @if ($compact)
                <p class="mt-1 text-sm text-ink/70">{{ $director->tagline }}</p>
            @else
                <p class="mt-3 text-ink/70">{{ $director->bio_full }}</p>

                @if (!empty($director->credentials))
                    <ul class="mt-4 space-y-1 text-sm text-ink/70">
                        @foreach ($director->credentials as $credential)
                            <li class="flex items-center gap-2">
                                <x-icon name="academic-cap" class="size-4 text-brand-primary" />
                                {{ $credential }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if (!empty($director->research_interests))
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($director->research_interests as $interest)
                            <x-badge color="brand" :text="$interest" />
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>
@endif
