/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.html',
    './parts/**/*.html',
    './inc/**/*.php',
    './*.php',
  ],
  theme: {
    extend: {
      colors: {
        navy: '#3B2350',
        navydeep: '#2A1638',
        action: '#643486',
        actiondark: '#4A2364',
        charcoal: '#453E4C',
        mist: '#F7F3FA',
      },
      fontFamily: {
        display: ['Archivo', 'sans-serif'],
        body: ['"Source Sans 3"', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
