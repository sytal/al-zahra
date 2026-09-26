import { readFileSync } from 'node:fs';

const raw = JSON.parse(readFileSync(new URL('./theme.json', import.meta.url), 'utf8'));

const isObj = (v) => v && typeof v === 'object' && !Array.isArray(v);
const merge = (a, b) => {
    const out = { ...a };
    for (const [k, v] of Object.entries(b || {})) out[k] = isObj(v) && isObj(a?.[k]) ? merge(a[k], v) : v;
    return out;
};

export const presetNames = Object.keys(raw.presets);
export const resolvePreset = (name) => merge(raw.base, raw.presets[name] || {});
export const theme = resolvePreset(raw.activePreset);

const hexToTriplet = (h) => [1, 3, 5].map((i) => parseInt(h.slice(i, i + 2), 16)).join(' ');
const isHex = (v) => /^#[0-9a-f]{6}$/i.test(v);
const steps = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];

const alpha = (a) => (a === undefined ? '' : ` / ${a.includes('var(') ? a : Number(a) / 100}`);

/** Replace {palette.step/alpha}, {role:name/alpha}, {#HEX/alpha} with rgb(var(--c-x) / a). */
export const resolveTemplate = (str) =>
    str.replace(/\{([^}/]+)(?:\/([^}]+))?\}/g, (_, ref, a) => {
        if (isHex(ref)) return `rgb(${hexToTriplet(ref)}${alpha(a)})`;
        if (ref.startsWith('role:')) return `rgb(var(--c-${ref.slice(5)})${alpha(a)})`;
        return `rgb(var(--c-${ref.replace('.', '-')})${alpha(a)})`;
    });

const refToTriplet = (ref) => (isHex(ref) ? hexToTriplet(ref) : `var(--c-${ref.replace('.', '-')})`);

const paletteVars = (palettes) => {
    const v = {};
    for (const [name, scale] of Object.entries(palettes || {})) {
        for (const s of steps) {
            if (!scale[s]) continue;
            v[`--c-${name}-${s}`] = hexToTriplet(scale[s]);
            v[`--color-${name}-${s}`] = `rgb(var(--c-${name}-${s}))`;
        }
    }
    return v;
};

const roleVars = (roles, aliases) => {
    const v = {};
    for (const [name, ref] of Object.entries(roles || {})) {
        v[`--c-${name}`] = refToTriplet(ref);
        v[`--color-${name}`] = `rgb(var(--c-${name}))`;
    }
    for (const [alias, target] of Object.entries(aliases || {})) {
        if (!roles || !(target in roles)) continue;
        v[`--c-${alias}`] = `var(--c-${target})`;
        v[`--color-${alias}`] = `rgb(var(--c-${alias}))`;
    }
    if (roles?.border) {
        v['--border-color'] = 'rgb(var(--c-border))';
        v['--border-strong'] = 'rgb(var(--c-border-strong))';
        v['--ring-color'] = 'rgb(var(--c-ring))';
    }
    return v;
};

const gradientVars = (g) => Object.fromEntries(Object.entries(g || {}).map(([k, val]) => [`--gradient-${k}`, resolveTemplate(val)]));
const shadowVars = (s) => Object.fromEntries(Object.entries(s || {}).map(([k, val]) => [`--shadow-${k}`, resolveTemplate(val)]));
const statusVars = (st) => {
    const v = {};
    for (const [name, { bg, text }] of Object.entries(st || {})) {
        v[`--c-status-${name}-bg`] = refToTriplet(bg);
        v[`--c-status-${name}-text`] = refToTriplet(text);
    }
    return v;
};
const chartVars = (c) => Object.fromEntries((c || []).map((ref, i) => [`--c-chart-${i + 1}`, refToTriplet(ref)]));
const modeVars = (t, mode) => ({
    ...roleVars(t.roles[mode], t.aliases),
    ...gradientVars(t.gradients[mode]),
    ...shadowVars(t.shadows[mode]),
    ...statusVars(t.status[mode]),
    ...chartVars(t.chart[mode]),
});

/** Base rules for Tailwind addBase. */
export const themeBase = () => {
    const t = theme;
    const css = {
        ':root': { ...paletteVars(t.palettes), ...modeVars(t, 'light') },
        '.dark': modeVars(t, 'dark'),
        '.bg-section-dark, [data-band]': roleVars(t.roles.band, t.aliases),
    };
    for (const name of presetNames) {
        if (name === raw.activePreset) continue;
        const p = resolvePreset(name);
        css[`html[data-theme="${name}"]`] = { ...paletteVars(raw.presets[name].palettes), ...(raw.presets[name].roles?.light ? modeVars(p, 'light') : {}) };
        if (raw.presets[name].roles?.dark) css[`html[data-theme="${name}"].dark`] = modeVars(p, 'dark');
    }
    return css;
};

const rgbVar = (name) => `rgb(var(--c-${name}) / <alpha-value>)`;

/** Tailwind theme.extend.colors / borderColor / backgroundImage / boxShadow derived from the JSON. */
export const themeColors = () => {
    const t = theme;
    const colors = {};
    for (const name of Object.keys(t.palettes)) {
        colors[name] = Object.fromEntries(steps.map((s) => [s, rgbVar(`${name}-${s}`)]));
        const def = t.paletteDefaults?.[name];
        if (def) colors[name].DEFAULT = rgbVar(def);
    }
    const roleNames = Object.keys(t.roles.light);
    const textRename = { 'text-strong': 'strong', 'text-body': 'body', 'text-muted': 'muted', 'text-subtle': 'subtle', 'text-inverse': 'inverse' };
    for (const r of roleNames) {
        if (r.startsWith('border') || r === 'ring' || t.palettes[r]) continue;
        colors[textRename[r] || r] = rgbVar(r);
    }
    colors['brand-primary'] = { ...colors.primary, DEFAULT: rgbVar('brand') };
    colors['brand-secondary'] = { ...colors.secondary, DEFAULT: rgbVar('brand-secondary') };
    colors.ink = rgbVar('ink');
    for (const n of Object.keys(t.chart.light)) colors[`chart-${Number(n) + 1}`] = rgbVar(`chart-${Number(n) + 1}`);
    colors.status = {};
    for (const [name] of Object.entries(t.status.light)) {
        colors.status[`${name}-bg`] = rgbVar(`status-${name}-bg`);
        colors.status[`${name}-text`] = rgbVar(`status-${name}-text`);
    }
    const borderColor = { DEFAULT: rgbVar('border'), strong: rgbVar('border-strong'), subtle: rgbVar('border-subtle') };
    const ringColor = { DEFAULT: rgbVar('ring') };
    const backgroundImage = Object.fromEntries(Object.keys(t.gradients.light).map((k) => [`gradient-${k}`, `var(--gradient-${k})`]));
    const boxShadow = Object.fromEntries(Object.keys(t.shadows.light).map((k) => [k, `var(--shadow-${k})`]));
    return { colors, borderColor, ringColor, backgroundImage, boxShadow };
};

/** Flat palette map for PHP (config/theme.php mirrors this logic). */
export const paletteHex = () => theme.palettes;
export { steps };
