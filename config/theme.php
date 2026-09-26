<?php

/*
 * PHP view of resources/theme/theme.json (the single source of truth for colors).
 * Usage: config('theme.palettes.primary.600') => '#0D9488'
 *        config('theme.roles.light.brand')     => resolved hex
 *        config('theme.filament.primary')      => [50 => '240, 253, 250', ...] for Filament Color arrays
 */

$path = resource_path('theme/theme.json');
$raw = is_file($path) ? json_decode((string) file_get_contents($path), true) : null;

if (! is_array($raw)) {
    return ['active' => 'default', 'palettes' => [], 'roles' => [], 'filament' => []];
}

$merge = function (array $a, array $b) use (&$merge): array {
    foreach ($b as $k => $v) {
        $a[$k] = is_array($v) && isset($a[$k]) && is_array($a[$k]) ? $merge($a[$k], $v) : $v;
    }

    return $a;
};

$active = $raw['activePreset'] ?? 'default';
$theme = $merge($raw['base'], $raw['presets'][$active] ?? []);

$resolve = function (string $ref) use ($theme): string {
    if (str_starts_with($ref, '#')) {
        return strtoupper($ref);
    }
    $parts = explode('.', $ref);
    $name = $parts[0];
    $step = $parts[1] ?? null;

    return strtoupper($theme['palettes'][$name][$step] ?? '#000000');
};

$roles = [];
foreach (['light', 'dark', 'band'] as $mode) {
    foreach ($theme['roles'][$mode] ?? [] as $role => $ref) {
        $roles[$mode][$role] = $resolve($ref);
    }
}

$triplet = fn (string $hex): string => implode(', ', array_map('hexdec', str_split(ltrim($hex, '#'), 2)));

$filament = [];
foreach ($theme['palettes'] as $name => $scale) {
    foreach ($scale as $step => $hex) {
        $filament[$name][$step] = $triplet($hex);
    }
}

return [
    'active' => $active,
    'palettes' => $theme['palettes'],
    'roles' => $roles,
    'gradients' => $theme['gradients'],
    'shadows' => $theme['shadows'],
    'status' => $theme['status'],
    'chart' => $theme['chart'],
    'filament' => $filament,
    'presets' => array_keys($raw['presets'] ?? []),
];
