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
                primary: {
                    DEFAULT: '#B01116',
                    hover: '#950e12',
                },
                maroon: {
                    DEFAULT: '#713134',
                },
                activePink: {
                    DEFAULT: '#FCE8E8',
                },
                darkText: {
                    DEFAULT: '#252525',
                },
                subText: {
                    DEFAULT: '#6B7280',
                },
                borderGray: {
                    DEFAULT: '#E5E7EB',
                },
                checkGreen: {
                    DEFAULT: '#16A34A',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
