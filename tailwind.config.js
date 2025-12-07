/** @type {import('tailwindcss').Config} */
import defaultTheme from 'tailwindcss/defaultTheme'
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./Modules/**/*.{js,blade.php,vue}",
    "./app/Core/**/Resources/views/**/*.{js,blade.php}",
    "./node_modules/@fortawesome/fontawesome-free/**/*.js",
  ],
  safelist: [
    { pattern: /text-(blue|sky|cyan|teal|green|emerald|lime|yellow|amber|orange|red|rose|pink|fuchsia|purple|violet|indigo|slate|gray|zinc|stone)-(500|600|700|800|900)/ },
    { pattern: /bg-(blue|green|purple|teal|orange|amber|indigo|stone|gray)-50/ },
    { pattern: /border-(blue|green|purple|teal|orange|amber|indigo|stone|gray)-300/, variants: ['hover'] },
    { pattern: /from-(blue|green|purple|teal|orange|amber|indigo|stone|gray)-(300|400)\/(50|60)/ },
    { pattern: /bg-(blue|sky|cyan|teal|green|emerald|lime|yellow|amber|orange|red|rose|pink|fuchsia|purple|violet|indigo|slate|gray|zinc|stone)-(500|600|700|800|900)/ },
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: [
          'Sahel',
          ...defaultTheme.fontFamily.sans,
        ],
      },
      colors: {
        primary: 'var(--color-primary)',
        secondary: 'var(--color-secondary)',
        'text-primary': 'var(--color-text-primary)',
        'text-secondary': 'var(--color-text-secondary)',
        'text-muted': 'var(--color-text-muted)',
        'bg-primary': 'var(--color-bg-primary)',
        'bg-secondary': 'var(--color-bg-secondary)',
        'bg-tertiary': 'var(--color-bg-tertiary)',
        'bg-label': 'var(--color-bg-label)',
        'border-light': 'var(--color-border-light)',
        'border-medium': 'var(--color-border-medium)',
        'border-dark': 'var(--color-border-dark)',
      },
      spacing: {
        'xs': '4px',
        'sm': '8px',
        'md': '12px',
        'lg': '16px',
        'xl': '20px',
        '2xl': '24px',
        '3xl': '32px',
        '4xl': '40px',
        '5xl': '80px',
      },
      borderRadius: {
        'sm': '6px',
        'md': '8px',
        'lg': '10px',
        'xl': '12px',
        '2xl': '16px',
        '3xl': '20px',
      },
      fontSize: {
        'xs': '13px',
        'sm': '14px',
        'base': '15px',
        'md': '16px',
        'lg': '18px',
        'xl': '20px',
        '2xl': '24px',
        '3xl': '30px',
        '4xl': '36px',
      },
      lineHeight: {
        'tight': '1.25',
        'snug': '1.375',
        'normal': '1.5',
        'relaxed': '1.625',
        'loose': '1.75',
      },
      boxShadow: {
        'sm': '0 1px 3px rgba(0,0,0,0.04)',
        'md': '0 4px 16px rgba(0,0,0,0.06)',
        'lg': '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
        'focus': '0 0 0 3px rgba(15, 23, 42, 0.05)',
        'button': '0 4px 12px rgba(15, 23, 42, 0.15)',
      }
    }
  },
  plugins: [],
}
