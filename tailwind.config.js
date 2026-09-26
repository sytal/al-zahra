import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import rtl from 'tailwindcss-rtl';

const token = (name) => `color-mix(in srgb, var(--color-${name}) calc(<alpha-value> * 100%), transparent)`;

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'brand-primary': token('brand-primary'),
                'brand-secondary': token('brand-secondary'),
                ink: token('ink'),
                surface: token('surface'),
                success: token('success'),
                danger: token('danger'),
                'on-brand': token('on-brand'),
            },
        },
    },

    plugins: [forms, rtl],
};
