<?php

use Symfony\Component\Finder\Finder;

/*
 * Colors live only in resources/theme/theme.json (docs/DESIGN-SYSTEM.md, "Theming: one file").
 * Whitelist: paths that cannot read the theme at runtime. Each needs a reason.
 * TODO owners: convert mail css to a Blade theme and the PDF template to config('theme.*').
 */
const THEME_HEX_WHITELIST = [
    'resources/views/vendor/mail/html/themes/default.css' => 'Laravel mail CSS is a static file inlined by CssToInlineStyles',
    'resources/views/certificates/pdf.blade.php' => 'mPDF template, pending migration to config(theme.*)',
];

it('exposes theme config from theme.json', function () {
    expect(config('theme.palettes.primary.700'))->toBe('#0F766E')
        ->and(config('theme.roles.light.brand'))->toBe('#0F766E')
        ->and(config('theme.filament.primary.700'))->toBe('15, 118, 110');
});

it('has 11 steps for every palette', function () {
    foreach (config('theme.palettes') as $scale) {
        expect(array_keys($scale))->toBe([50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950]);
    }
});

it('contains no raw hex colors outside theme.json', function () {
    $finder = (new Finder)->files()->in([base_path('resources/views'), base_path('resources/css'), base_path('app')])
        ->name(['*.php', '*.css']);
    $hits = [];
    foreach ($finder as $file) {
        $rel = str_replace('\\', '/', substr($file->getPathname(), strlen(base_path()) + 1));
        if (isset(THEME_HEX_WHITELIST[$rel]) || str_contains($rel, 'resources/views/vendor/')) {
            continue;
        }
        if (preg_match('/#[0-9a-fA-F]{6}\b/', $file->getContents())) {
            $hits[] = $rel;
        }
    }
    expect($hits)->toBe([]);
});
