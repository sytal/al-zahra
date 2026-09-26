# Color system (docs/CLAUDE.md Section 8.1)

Single source: `resources/theme/theme.json` (palettes, light/dark/band roles,
gradients, shadows, presets). Tailwind (`tailwind.config.js` via
`resources/theme/build.js`) and PHP (`config/theme.php`) both read it.
NEVER hardcode a hex code in Blade, CSS or PHP (`tests/Feature/ThemeTest.php`
enforces this; whitelist entries need a documented reason).

Use roles and palettes: `bg-primary-700`, `text-strong`, `bg-surface-raised`,
`text-on-brand` on brand fills, `text-on-secondary` on gold, `text-secondary-text`
for gold text. Legacy `brand-primary`, `brand-secondary`, `ink`, `surface`
keep working as aliases. Dark values come from `roles.dark`; components do
not need `dark:` for role colors.

Re-theme: edit theme.json, `npm run build`. Details: docs/DESIGN-SYSTEM.md
("Theming: one file").

RTL: never hardcode `ml-`/`pl-`/`text-left` directional utilities; use
logical properties (`ps-4`, `pe-4`, `text-start`, via `tailwindcss-rtl`).
