@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block min-h-11 w-full rounded-xl border-strong bg-surface-raised px-3.5 py-2.5 text-base text-strong shadow-soft transition-[border-color,box-shadow] duration-fast placeholder:text-subtle focus:border-brand focus:ring-4 focus:ring-brand/20 disabled:cursor-not-allowed disabled:opacity-60']) }}>
