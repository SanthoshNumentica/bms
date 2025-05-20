module.exports = {
  content: [
    './vendor/filament/**/*.blade.php',
    './resources/views/**/*.blade.php',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#6CA0DC',  // mild blue
          light: '#A3C1E1',
          dark: '#4B7DAA',
        },
        secondary: {
          DEFAULT: '#4CAF50',  // green
          light: '#80C880',
          dark: '#357A38',
        },
      },
    },
  },
  plugins: [],
}
