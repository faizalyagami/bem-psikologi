/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#F1F5F2',
                    100: '#E2EBE5',
                    200: '#C4D7CB',
                    300: '#9CBBA8',
                    400: '#6E9981',
                    500: '#47775F',
                    600: '#315F49',
                    700: '#234D3B',
                    800: '#173F35',
                    900: '#102F28',
                },
                accent: {
                    DEFAULT: '#D6B66D',
                    light: '#E9D7A8',
                    dark: '#B99546',
                },
                surface: {
                    DEFAULT: '#F7F8F7',
                    card: '#FFFFFF',
                },
                // Tema gelap CMS
                dark: {
                    bg: '#0a0a0a',
                    surface: '#111318',
                    card: '#1a1d24',
                    border: '#252a33',
                    hover: '#1e222a',
                },
            },

            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                heading: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
            },

            boxShadow: {
                soft: '0 2px 12px rgba(0,0,0,0.04)',
            },

            borderRadius: {
                card: '14px',
            },
        },
    },

    plugins: [],
};