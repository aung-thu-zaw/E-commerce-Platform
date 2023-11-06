const defaultTheme = require("tailwindcss/defaultTheme");
const plugin = require("tailwindcss/plugin");
const colors = require("tailwindcss/colors");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./node_modules/flowbite/**/*.js",
        "node_modules/preline/dist/*.js",
    ],

    theme: {
        // @keyframes bounce {
        //     0%, 20%, 50%, 80%, 100% {
        //       transform: translateY(0);
        //     }
        //     40% {
        //       transform: translateY(-15px);
        //     }
        //     60% {
        //       transform: translateY(-10px);
        //     }
        //   }

        colors: {
            ...colors,
        },
        extend: {
            keyframes: {
                press: {
                    "0%": {
                        transform: "scale(1)",
                    },
                    "50%": {
                        transform: "scale(0.9)",
                    },
                    "100%": {
                        transform: "scale(1)",
                    },
                },
            },
            animation: {
                press: "press 0.2s ease-in-out",
            },

            colors: {
                brand: "#0EA5E9",
                dark: "#0F172A",
                mid: "#CBD5E1",
                light: "#F8FAFC",
            },
            fontFamily: {
                roboto: ["Roboto", "sans-serif"],
                merriewather: ["Merriweather", "serif"],
            },
            minHeight: {
                "screen-75": "75vh",
            },
            fontSize: {
                55: "55rem",
            },
            opacity: {
                80: ".8",
            },
            zIndex: {
                2: 2,
                3: 3,
            },
            inset: {
                "-100": "-100%",
                "-225-px": "-225px",
                "-160-px": "-160px",
                "-150-px": "-150px",
                "-94-px": "-94px",
                "-50-px": "-50px",
                "-29-px": "-29px",
                "-20-px": "-20px",
                "25-px": "25px",
                "40-px": "40px",
                "95-px": "95px",
                "145-px": "145px",
                "195-px": "195px",
                "210-px": "210px",
                "260-px": "260px",
            },
            height: {
                "95-px": "95px",
                "70-px": "70px",
                "350-px": "350px",
                "500-px": "500px",
                "600-px": "600px",
            },
            maxHeight: {
                "860-px": "860px",
            },
            maxWidth: {
                "100-px": "100px",
                "120-px": "120px",
                "150-px": "150px",
                "180-px": "180px",
                "200-px": "200px",
                "210-px": "210px",
                "580-px": "580px",
            },
            minWidth: {
                "140-px": "140px",
                48: "12rem",
            },
            backgroundSize: {
                full: "100%",
            },
        },
    },

    // variants: [
    //     "responsive",
    //     "group-hover",
    //     "focus-within",
    //     "first",
    //     "last",
    //     "odd",
    //     "even",
    //     "hover",
    //     "focus",
    //     "active",
    //     "visited",
    //     "disabled",
    // ],

    plugins: [
        require("flowbite/plugin"),
        require("@tailwindcss/line-clamp"),
        // require("tw-elements/dist/plugin"),
        require("@tailwindcss/forms"),
        require("preline/plugin"),
        plugin(function ({ addComponents, theme }) {
            const screens = theme("screens", {});
            addComponents([
                {
                    ".container": { width: "100%" },
                },
                {
                    [`@media (min-width: ${screens.sm})`]: {
                        ".container": {
                            "max-width": "640px",
                        },
                    },
                },
                {
                    [`@media (min-width: ${screens.md})`]: {
                        ".container": {
                            "max-width": "768px",
                        },
                    },
                },
                {
                    [`@media (min-width: ${screens.lg})`]: {
                        ".container": {
                            "max-width": "1024px",
                        },
                    },
                },
                {
                    [`@media (min-width: ${screens.xl})`]: {
                        ".container": {
                            "max-width": "1280px",
                        },
                    },
                },
                {
                    [`@media (min-width: ${screens["2xl"]})`]: {
                        ".container": {
                            "max-width": "1280px",
                        },
                    },
                },
            ]);
        }),
    ],
};
