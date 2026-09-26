{{-- Props: links = ['linkedin' => url, ...] or [['network' => 'linkedin', 'url' => '...', 'label' => ?], ...], size sm|md, tone soft|solid, label (aria name of the group). Networks with labels: enums.social.* plus kit_sections.social.*. --}}
@props(['links' => [], 'size' => 'md', 'tone' => 'soft', 'label' => null])

@php
$icons = ['linkedin' => 'briefcase', 'researchgate' => 'beaker', 'twitter' => 'x-mark', 'x' => 'x-mark', 'email' => 'envelope', 'facebook' => 'user-group', 'instagram' => 'camera', 'youtube' => 'play-circle', 'whatsapp' => 'chat-bubble-left-ellipsis', 'website' => 'globe-alt', 'orcid' => 'identification', 'scholar' => 'academic-cap'];
$rows = collect($links)->map(function ($v, $k) {
    if (is_array($v)) { return ['network' => $v['network'] ?? (is_string($k) ? $k : 'website'), 'url' => $v['url'] ?? '', 'label' => $v['label'] ?? null]; }
    return ['network' => is_string($k) ? $k : 'website', 'url' => (string) $v, 'label' => null];
})->filter(fn ($r) => $r['url'] !== '')->values();
$box = $size === 'sm' ? 'size-9' : 'size-11';
$skin = $tone === 'solid' ? 'bg-brand-primary text-on-brand' : 'border bg-surface-raised text-body';
@endphp

@if ($rows->isNotEmpty())
    <ul {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2']) }} aria-label="{{ $label ?? __('kit_sections.follow') }}">
        @foreach ($rows as $r)
            @php
                $net = strtolower($r['network']);
                $name = $r['label'] ?? (\Illuminate\Support\Facades\Lang::has('enums.social.' . $net) ? __('enums.social.' . $net) : (\Illuminate\Support\Facades\Lang::has('kit_sections.social.' . $net) ? __('kit_sections.social.' . $net) : $r['network']));
                $href = $net === 'email' && !str_starts_with($r['url'], 'mailto:') ? 'mailto:' . $r['url'] : $r['url'];
            @endphp
            <li>
                <a href="{{ $href }}" @unless ($net === 'email') target="_blank" rel="noopener noreferrer me" @endunless title="{{ $name }}" aria-label="{{ $name }}"
                    class="focus-ring tap-target {{ $box }} rounded-full {{ $skin }} transition duration-fast ease-enter active:scale-95 [@media(hover:hover)]:hover:-translate-y-0.5 [@media(hover:hover)]:hover:border-brand-primary [@media(hover:hover)]:hover:text-brand-primary">
                    <x-icon :name="$icons[$net] ?? 'globe-alt'" class="{{ $size === 'sm' ? 'size-4' : 'size-5' }}" aria-hidden="true" />
                </a>
            </li>
        @endforeach
    </ul>
@endif
