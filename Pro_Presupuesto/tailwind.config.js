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
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-green': '#378fc2ff',
                'primary-orange': '#FF9933',
                'dark-text': '#333333',
                'light-text': '#666666',
                'bg-light-blue': '#F0F4F8',
                'card-bg': '#FFFFFF',
                'nav-bg-light': '#FBFBFB',
                'illustration-bg': '#EAF7F5',
            },
            borderRadius: {
                'app-xl': '20px',
            },
        },
    },

    plugins: [forms],
};
