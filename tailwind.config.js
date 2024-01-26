/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["dist/*.{html,js,php}"],
  darkMode: 'class',
  theme: {
    extend: {
      colors:{
        instalines: "#dbdbdb",
        instablue: "#4cb5f9",
        instabuttondark: "#373736",
        instabuttondarkhover: "#272627",
        instabuttonlight: "#eeeeef",
        instabuttonlighthover: "#dadadb",
        instabuttonblue: "#0195f7",
        instabuttonbluehover: "#1976f2"
      },
    },
  },
  plugins: [],
}

