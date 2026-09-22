@props(['headers' => []])

<div class="overflow-x-auto rounded-xl border border-ink/10">
    <table class="min-w-full divide-y divide-ink/10">
        <thead class="bg-surface">
            <tr>
                @foreach ($headers as $header)
                    <th class="px-4 py-3 text-start text-xs font-semibold uppercase tracking-wide text-ink/60">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="divide-y divide-ink/10 bg-white dark:bg-surface">
            {{ $slot }}
        </tbody>
    </table>
</div>
