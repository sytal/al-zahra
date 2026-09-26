<div class="print:hidden" aria-hidden="true">
    <div wire:loading.class.remove="hidden" class="pointer-events-none fixed inset-x-0 top-0 z-top hidden h-[3px] animate-pulse bg-gradient-to-r from-brand-primary via-brand-secondary to-brand-primary"></div>
</div>
<style>
    #nprogress { pointer-events: none; }
    #nprogress .bar { background: linear-gradient(90deg, var(--color-brand-primary), var(--color-brand-secondary)) !important; height: 3px !important; z-index: 90 !important; }
    #nprogress .peg { box-shadow: 0 0 10px var(--color-brand-secondary), 0 0 5px var(--color-brand-secondary) !important; }
    @media print { #nprogress { display: none !important; } }
</style>
