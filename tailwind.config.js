const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/**/*.php",
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
                // Tambahkan definisi warna green yang lebih lengkap
                green: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    200: "#bbf7d0",
                    300: "#86efac",
                    400: "#4ade80",
                    500: "#22c55e",
                    600: "#16a34a",
                    700: "#15803d",
                    800: "#166534",
                    900: "#14532d",
                },
            },
        },
    },

    // Safelist untuk memastikan kelas tidak ter-purge
    safelist: [
        "bg-green-600",
        "bg-green-700",
        "bg-opacity-90",
        "text-green-300",
        "text-green-600",
        "text-green-700",
        "from-green-600",
        "to-green-700",
        "from-green-50",
        "from-green-100",
        "to-green-50",
        "to-green-100",
        "via-green-50",
        "via-white",
        "hover:bg-green-600",
        "bg-gradient-to-b",
        "bg-gradient-to-br",
        "transform",
        "hover:scale-105",
        "transition",
        "shadow-lg",
        "rounded-lg",
        "overflow-hidden",
        {
            pattern: /bg-green-(50|100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /text-green-(50|100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /from-green-(50|100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /to-green-(50|100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /via-green-(50|100|200|300|400|500|600|700|800|900)/,
        },
    ],

    plugins: [require("@tailwindcss/forms")],
};
