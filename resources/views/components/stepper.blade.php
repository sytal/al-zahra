@props(['steps' => [], 'current' => 1, 'vertical' => false])

@php
$total = count($steps);
$labels = array_values($steps);
@endphp

<nav aria-label="{{ __('kit_forms.steps') }}" {{ $attributes->merge(['class' => 'min-w-0']) }}>
    <p class="mb-3 text-sm font-semibold text-muted sm:sr-only">{{ __('kit_forms.step_of', ['current' => $current, 'total' => $total]) }}</p>
    <ol class="flex {{ $vertical ? 'flex-col' : 'items-start' }}">
        @foreach ($labels as $i => $stepLabel)
            @php
            $n = $i + 1;
            $state = $n < $current ? 'done' : ($n === $current ? 'current' : 'todo');
            $last = $n === $total;
            @endphp
            <li
                class="relative flex min-w-0 {{ $vertical ? 'gap-4 pb-8 last:pb-0' : ($last ? 'flex-none' : ($state === 'current' ? 'flex-[3] sm:flex-1' : 'flex-1')) . ' items-start' }}"
                @if ($state === 'current') aria-current="step" @endif
            >
                <div class="{{ $vertical ? 'flex flex-col items-center' : 'flex min-w-0 flex-col items-center gap-2 sm:items-start' }}">
                    <span
                        class="relative grid size-9 shrink-0 place-items-center rounded-full border-2 text-sm font-bold tabular-nums transition-[background-color,border-color,box-shadow,color] duration-base ease-enter
                            {{ $state === 'done' ? 'border-brand bg-brand text-on-brand' : '' }}
                            {{ $state === 'current' ? 'border-brand-secondary bg-surface-raised text-strong shadow-gold' : '' }}
                            {{ $state === 'todo' ? 'border-strong bg-surface-raised text-muted' : '' }}"
                    >
                        @if ($state === 'done')
                            <x-icon name="check" size="size-5" aria-hidden="true" />
                        @else
                            {{ $n }}
                        @endif
                        @if ($state === 'current')
                            <span aria-hidden="true" class="star-mark absolute -end-1.5 -top-1.5 text-xs"></span>
                        @endif
                    </span>
                    @if ($vertical && ! $last)
                        <span aria-hidden="true" class="mt-1 w-0.5 flex-1 rounded-full {{ $state === 'done' ? 'bg-brand' : 'bg-subtle/40' }}"></span>
                    @endif
                </div>

                <div class="{{ $vertical ? 'min-w-0 flex-1 pt-1.5' : ($state === 'current' ? 'ms-3 min-w-0 pt-1.5' : 'min-w-0 max-sm:sr-only sm:ms-3 sm:pt-1.5') }}">
                    <span class="block break-words text-sm font-semibold leading-snug {{ $state === 'todo' ? 'text-muted' : 'text-strong' }}">{{ $stepLabel }}</span>
                    @if ($state !== 'todo')
                        <span class="sr-only">, {{ $state === 'done' ? __('kit_forms.step_completed') : __('kit_forms.step_current') }}</span>
                    @endif
                </div>

                @if (! $vertical && ! $last)
                    <span aria-hidden="true" class="mx-2 mt-[1.0625rem] hidden h-0.5 min-w-4 flex-1 rounded-full sm:block {{ $state === 'done' ? 'bg-brand' : 'bg-subtle/40' }}"></span>
                    <span aria-hidden="true" class="mx-2 mt-[1.0625rem] h-0.5 min-w-3 flex-1 rounded-full sm:hidden {{ $state === 'done' ? 'bg-brand' : 'bg-subtle/40' }}"></span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
