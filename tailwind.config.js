import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                yellow: {
                    400: "#FFEB00",
                    500: "#FFEB00",
                },
                "badminton-blue": "#0057A8",
                "badminton-green": "#00A651",
                "badminton-court": "#17223B",
                "badminton-lines": "#FFFFFF",
                "badminton-net": "#263238",
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
