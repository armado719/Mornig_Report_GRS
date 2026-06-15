import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                grs: {
                    fondo:    '#0D1F17',
                    primario: '#1B4D35',
                    acento:   '#2D7A4F',
                    verde:    '#6DBE6D',
                    borde:    '#2D4A3E',
                    texto:    '#D1D5DB',
                },
            },
        },
    },

    plugins: [forms],
};
