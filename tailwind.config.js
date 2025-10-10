import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // enable manual dark mode toggle
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Poppins', ...defaultTheme.fontFamily.sans],
      },

      colors: {
        // --- Core brand ---
        copper: {
          light: '#FFD7BA',
          DEFAULT: '#FF8C42',
          dark: '#D76B00',
          900: '#B55300',
        },

        // --- Neutral surfaces ---
        sand: {
          DEFAULT: '#FFF9F3',   // base light
          100: '#FFF5EC',
          200: '#FFEEDD',
          900: '#1C1C1C',       // dark background base
          800: '#262626',       // dark card
        },

        ink: {
          900: '#1A1A1A',  // strong body text
          700: '#404040',
          500: '#6B6B6B',
          200: '#E5E5E5',  // light text for dark mode
          100: '#F5F5F5',
        },

        // --- Utility colors ---
        sky: {
          DEFAULT: '#89CFF0',
          100: '#E6F7FF',
          900: '#075985', // deeper accent for dark mode
        },
        forest: {
          DEFAULT: '#15803D',
          100: '#DCFCE7',
          900: '#052E16',
        },

        // --- Legacy (keep for compatibility) ---
        brand: {
          DEFAULT: '#2563EB',
          50: '#EFF6FF',
          100: '#DBEAFE',
          600: '#2563EB',
          700: '#1D4ED8',
        },
      },

      boxShadow: {
        card: '0 8px 24px rgba(0, 0, 0, 0.06)',
        soft: '0 4px 16px rgba(0, 0, 0, 0.04)',
        glow: '0 0 10px rgba(255, 140, 66, 0.25)',
        'glow-dark': '0 0 10px rgba(255, 140, 66, 0.4)',
      },

      borderRadius: {
        xl: '1rem',
        '2xl': '1.5rem',
        '3xl': '2rem',
      },

      backgroundImage: {
        'gradient-copper':
          'linear-gradient(90deg, #FF8C42 0%, #D76B00 100%)',
        'gradient-copper-dark':
          'linear-gradient(90deg, #D76B00 0%, #B55300 100%)',
        'gradient-sky': 'linear-gradient(90deg, #89CFF0 0%, #3BAFDA 100%)',
      },
    },
  },

  plugins: [forms],
}
