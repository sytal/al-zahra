<?php

// Mail design tokens derived from config('theme.*'); shared by the mail layout, header, button, footer and theme css.
    $L = config('theme.roles.light');
    $D = config('theme.roles.dark');
    $P = config('theme.palettes');
    $mailLocale = app()->getLocale();
    $mailRtl = in_array($mailLocale, (array) config('app.rtl_locales', []), true);
    $mailDir = $mailRtl ? 'rtl' : 'ltr';
    $mailAlign = $mailRtl ? 'right' : 'left';
    $mailOpp = $mailRtl ? 'left' : 'right';
    $sans = match ($mailLocale) {
        'ur' => "'Noto Naskh Arabic','Segoe UI',Tahoma,Arial,sans-serif",
        'fa' => "Vazirmatn,'Segoe UI',Tahoma,Arial,sans-serif",
        'hi' => "'Noto Sans Devanagari','Nirmala UI','Mangal',Arial,sans-serif",
        default => "Figtree,-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif",
    };
    $serif = $mailRtl || $mailLocale === 'hi' ? $sans : "'Playfair Display',Georgia,'Times New Roman',serif";
    $c = [
        'page' => $L['page'], 'card' => $L['surface-raised'], 'tint' => $L['tint'],
        'strong' => $L['text-strong'], 'body' => $L['text-body'], 'muted' => $L['text-muted'],
        'border' => $L['border'], 'link' => $L['link'], 'brand' => $L['brand'], 'onbrand' => $L['on-brand'],
        'gold' => $L['brand-secondary'],
        'band' => $P['deep']['900'], 'bandtext' => $P['deep']['100'],
        'dpage' => $D['page'], 'dcard' => $D['surface-raised'], 'dtint' => $D['tint'],
        'dstrong' => $D['text-strong'], 'dbody' => $D['text-body'], 'dmuted' => $D['text-muted'],
        'dborder' => $D['border'], 'dlink' => $D['link'], 'dbrand' => $D['brand'], 'donbrand' => $D['on-brand'],
        'dgold' => $D['brand-secondary'], 'dband' => $P['deep']['950'],
    ];
    $mailBase = rtrim((string) config('app.url'), '/');

return compact('L','D','P','mailLocale','mailRtl','mailDir','mailAlign','mailOpp','sans','serif','c','mailBase');
