{{-- Wrapper around the Livewire newsletter form. Pass class/attributes to place it; the form itself lives in livewire/newsletter/newsletter-form.blade.php. --}}
<div {{ $attributes->merge(['class' => 'min-w-0']) }}>
    <livewire:newsletter.newsletter-form />
</div>
