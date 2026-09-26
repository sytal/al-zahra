{{-- Props: icon (heroicon), title, message, illustration (file stem in public/images/illustrations, e.g. empty|articles|courses|research|resources|consultation|success), compact bool. Optional named slot "action". --}}
@props(['icon' => 'inbox', 'title', 'message' => null, 'illustration' => null, 'compact' => false])

<div {{ $attributes->merge(['class' => 'relative isolate mx-auto flex w-full max-w-xl flex-col items-center gap-3 overflow-hidden rounded-panel border border-dashed border-strong bg-surface-raised text-center ' . ($compact ? 'px-5 py-8' : 'px-5 py-12 sm:px-10 sm:py-16')]) }} role="status">
    <span class="bg-pattern-dots absolute inset-0 -z-10" aria-hidden="true"></span>
    @if ($illustration && !$compact)
        <img src="{{ asset('images/illustrations/illus-' . $illustration . '.svg') }}" alt="" width="400" height="300" loading="lazy" decoding="async" class="h-auto w-full max-w-[16rem]">
    @else
        <span class="relative flex size-16 items-center justify-center rounded-2xl bg-tint text-brand-primary">
            <x-icon :name="$icon" class="size-8" aria-hidden="true" />
            <span class="star-mark absolute -end-2 -top-2 text-lg animate-pulse-soft" aria-hidden="true"></span>
        </span>
    @endif
    <p class="heading-4 max-w-md break-words">{{ $title }}</p>
    @if ($message)<p class="max-w-md text-balance text-sm text-body">{{ $message }}</p>@endif
    @isset($action)<div class="mt-2 flex flex-wrap items-center justify-center gap-3">{{ $action }}</div>@endisset
</div>
