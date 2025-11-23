module.exports = {
  content: [
    "./*.php",
    "./parts/*.html",
    "./templates/**/*.{html,php}",
    "./**/*.php",
    "./**/*.html",
    "./src/**/*.{js,css.scss,php,html}"
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          green: "#0b6e4f",
          dark: "#064e37"
        }
      }
    }
  },
  plugins: []
}
