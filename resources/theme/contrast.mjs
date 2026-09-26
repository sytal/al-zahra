import { readFileSync } from 'node:fs';
const raw = JSON.parse(readFileSync(new URL('./theme.json', import.meta.url), 'utf8'));
const merge = (a, b) => { const o = { ...a }; for (const [k, v] of Object.entries(b || {})) o[k] = v && typeof v === 'object' && !Array.isArray(v) && a?.[k] ? merge(a[k], v) : v; return o; };
const lum = (h) => { const c = [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16) / 255).map((v) => (v <= 0.03928 ? v / 12.92 : ((v + 0.055) / 1.055) ** 2.4)); return 0.2126 * c[0] + 0.7152 * c[1] + 0.0722 * c[2]; };
const cr = (a, b) => { const [x, y] = [lum(a), lum(b)].sort((p, q) => q - p); return (x + 0.05) / (y + 0.05); };
let fails = 0;
for (const name of Object.keys(raw.presets)) {
    const t = merge(raw.base, raw.presets[name]);
    const hex = (ref) => (ref.startsWith('#') ? ref : t.palettes[ref.split('.')[0]][ref.split('.')[1]]);
    for (const mode of ['light', 'dark', 'band']) {
        const r = Object.fromEntries(Object.entries({ ...t.roles.light, ...(mode === 'band' ? t.roles.dark : {}), ...t.roles[mode] }).map(([k, v]) => [k, hex(v)]));
        const bgs = mode === 'band' ? ['page', 'surface-raised'] : ['page', 'surface-raised', 'surface-sunken'];
        const pairs = [];
        for (const bg of bgs) for (const fg of ['text-strong', 'text-body', 'text-muted', 'brand', 'link', 'secondary-text', 'success', 'danger', 'warning', 'info', 'accent']) pairs.push([fg, bg, 4.5]);
        pairs.push(['text-subtle', 'page', 4.5], ['on-brand', 'brand', 4.5], ['on-secondary', 'brand-secondary', 4.5]);
        for (const [fg, bg, min] of pairs) {
            if (!r[fg] || !r[bg]) continue;
            const v = cr(r[fg], r[bg]);
            if (v < min) { fails++; console.log(`FAIL ${name} ${mode} ${fg} on ${bg} ${v.toFixed(2)}`); }
        }
    }
}
console.log('fails', fails);
