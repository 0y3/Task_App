import type { Config } from 'tailwindcss'

export default {
    darkMode: 'class', // enable dark mode via class
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.vue',
        './resources/**/*.ts',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
} satisfies Config
