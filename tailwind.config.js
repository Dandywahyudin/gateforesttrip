import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'primary': '#d35400',
                'primary-dark': '#b83f00',
                'forest-green': '#1b4332',
                'background-light': '#f8f7f5',
                'background-dark': '#0d1109',
                'surface-light': '#ffffff',
                'surface-dark': '#1a1f16',
            },
            fontFamily: {
                'display': ['Barlow Semi Condensed', 'sans-serif'],
                'subheading': ['Plus Jakarta Sans', 'sans-serif'],
                'body': ['Inter', 'sans-serif'],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'DEFAULT': '0.5rem',
                'lg': '0.75rem',
                'xl': '1.5rem',
                '2xl': '2.5rem',
            },
        },
    },

    plugins: [forms],
};
