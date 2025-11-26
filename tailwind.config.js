/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./Modules/**/*.{js,blade.php,vue}",
    "./app/Core/**/Resources/views/**/*.{js,blade.php}",
    "./node_modules/@fortawesome/fontawesome-free/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#0f172a',
        secondary: '#64748b',
        'text-primary': '#0f172a',
        'text-secondary': '#475569',
        'text-muted': '#64748b',
        'bg-primary': '#ffffff',
        'bg-secondary': '#f8fafc',
        'bg-tertiary': '#fafbfc',
        'bg-label': '#f8fafc',
        'border-light': '#f1f5f9',
        'border-medium': '#e2e8f0',
        'border-dark': '#cbd5e1',
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
