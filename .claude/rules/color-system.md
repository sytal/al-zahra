# Color system (docs/CLAUDE.md Section 8.1)

Single source: `resources/css/app.css` `@theme` block (Tailwind v4) or
`tailwind.config.js` `theme.extend.colors` (Tailwind v3) — whichever the
installed version uses. NEVER hardcode a hex code inside a Blade file.

```css
--color-brand-primary: #0F766E;   /* deep teal */
--color-brand-secondary: #D4A24C; /* warm gold */
--color-ink: #1E293B;             /* headings/text */
--color-surface: #F8FAFC;         /* light bg */
--color-success: #22C55E;
--color-danger: #F87171;
```

Dark mode overrides declared under `.dark` class variant, same variable
names, different values. Every component (button, card, badge) references
`bg-brand-primary`, `text-ink`, etc. — never raw hex.

RTL: never hardcode `ml-`/`pl-`/`text-left` directional utilities — always
use logical properties (`ps-4`, `pe-4`, etc. via `tailwindcss-rtl`).
