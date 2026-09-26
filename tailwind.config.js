import forms from '@tailwindcss/forms';
import rtl from 'tailwindcss-rtl';

import plugin from 'tailwindcss/plugin';
import { themeBase, themeColors } from './resources/theme/build.js';

const tc = themeColors();

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    darkMode: 'class',

    theme: {
        screens: { xs: '420px', sm: '640px', md: '768px', lg: '1024px', xl: '1280px', '2xl': '1536px' },
        extend: {
            fontFamily: {
                sans: 'var(--font-sans)',
                display: 'var(--font-display)',
            },
            colors: tc.colors,
            borderColor: tc.borderColor,
            ringColor: tc.ringColor,
            boxShadow: tc.boxShadow,
            borderRadius: {
                card: 'var(--radius-card)',
                panel: 'var(--radius-panel)',
            },
            maxWidth: {
                page: 'var(--container-page)',
                narrow: 'var(--container-narrow)',
                prose: 'var(--container-prose)',
            },
            zIndex: {
                base: '0',
                raised: '10',
                sticky: '30',
                header: '40',
                dropdown: '50',
                overlay: '60',
                modal: '70',
                toast: '80',
                top: '90',
            },
            transitionDuration: {
                fast: '150ms',
                base: '250ms',
                slow: '400ms',
            },
            transitionTimingFunction: {
                enter: 'cubic-bezier(0.22, 1, 0.36, 1)',
                exit: 'cubic-bezier(0.4, 0, 1, 1)',
                spring: 'cubic-bezier(0.34, 1.4, 0.64, 1)',
            },
            backgroundImage: { ...tc.backgroundImage, 'brand-gradient': 'var(--gradient-brand)', 'hero-gradient': 'var(--gradient-hero)', 'gold-gradient': 'var(--gradient-gold)' },
            keyframes: {
                float: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-10px)' } },
                'fade-up': { from: { opacity: '0', transform: 'translateY(16px)' }, to: { opacity: '1', transform: 'none' } },
                'fade-in': { from: { opacity: '0' }, to: { opacity: '1' } },
                'scale-in': { from: { opacity: '0', transform: 'scale(0.95)' }, to: { opacity: '1', transform: 'none' } },
                'pulse-soft': { '0%,100%': { opacity: '1' }, '50%': { opacity: '0.55' } },
            },
            animation: {
                float: 'float 7s ease-in-out infinite',
                'fade-up': 'fade-up 0.5s cubic-bezier(0.22,1,0.36,1) both',
                'fade-in': 'fade-in 0.4s ease-out both',
                'scale-in': 'scale-in 0.3s cubic-bezier(0.22,1,0.36,1) both',
                'pulse-soft': 'pulse-soft 2.4s ease-in-out infinite',
            },
        },
    },

    plugins: [forms, rtl, plugin(({ addBase }) => addBase(themeBase()))],
};
