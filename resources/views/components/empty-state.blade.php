@props(['icon' => 'inbox', 'title', 'message' => null])

<div class="flex flex-col items-center gap-3 py-16 text-center">
    <x-icon :name="$icon" class="size-10 text-ink/30" />
    <p class="font-medium text-ink">{{ $title }}</p>
    @if ($message)<p class="text-sm text-ink/60">{{ $message }}</p>@endif
    @isset($action){{ $action }}@endisset
</div>
