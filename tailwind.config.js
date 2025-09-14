/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  safelist: [
    // Calendar activity colors - needed for dynamic color generation
    'bg-red-500', 'bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500',
    'bg-pink-500', 'bg-indigo-500', 'bg-orange-500', 'bg-teal-500', 'bg-cyan-500',
    'bg-amber-500', 'bg-lime-500', 'bg-emerald-500', 'bg-sky-500', 'bg-violet-500',
    'bg-fuchsia-500', 'bg-rose-500', 'bg-slate-500', 'bg-gray-500', 'bg-stone-500',
    'bg-red-600', 'bg-blue-600', 'bg-green-600', 'bg-yellow-600', 'bg-purple-600',
    'bg-pink-600', 'bg-indigo-600', 'bg-orange-600', 'bg-teal-600', 'bg-cyan-600',
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