/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./templates/**/*.{html.twig, html}"],
  theme: {
    colors: {
      'wcblue' : '#212738',
      'wcwhite' : '#E8E8E8',
      "white" : "#fff",
      "black": "#000"
    },
    extend: {},
    fontFamily: {
      "logo" : "Leckerli One",
      "default" : "Lexend"
    }
  },
  plugins: [],
}

