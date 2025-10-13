/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'royal-blue': {
          '50': '#f1f4fd',
          '100': '#dfe6fa',
          '200': '#c7d3f6',
          '300': '#a0b8f0',
          '400': '#7392e7',
          '500': '#4b68dd',
          '600': '#3e51d2',
          '700': '#3540c0',
          '800': '#30369d',
          '900': '#2c337c',
          '950': '#1f214c',
        },
        'primary': '#4b68dd',
      },
    },
  },
  plugins: [],
}