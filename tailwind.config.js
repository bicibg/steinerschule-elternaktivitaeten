/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'steiner-blue': '#4a90a4',
        'steiner-dark': '#2c5aa0',
      },
    },
  },
  plugins: [],
}