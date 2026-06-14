/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Penting: menggunakan class strategy
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Http/Livewire/**/*.php",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    DEFAULT: '#0EA5E9',
                    dark: '#0284C7',
                }
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'sans-serif'],
                grotesk: ['Space Grotesk', 'sans-serif'],
            }
        },
    },
    plugins: [],
}