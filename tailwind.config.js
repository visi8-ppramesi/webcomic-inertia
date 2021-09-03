const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            height: theme => ({
              "screen-navbar": "calc(100vh - 64px)",
              "screen/2": "50vh",
              "screen/3": "calc(100vh / 3)",
              "screen/4": "calc(100vh / 4)",
              "screen/5": "calc(100vh / 5)",
              "fit-content": "fit-content"
            }),
            width: theme => ({
              "screen/2": "50vw",
              "screen/3": "calc(100vw / 3)",
              "screen/4": "calc(100vw / 4)",
              "screen/5": "calc(100vw / 5)",
              "screen/6": "calc(100vw / 6)",
              "screen/8": "calc(100vw / 8)",
              "screen/10": "calc(100vw / 10)",
              "fit-content": "fit-content"
            }),
            maxHeight: theme => ({
              '0': '0',
              '1/4': '25vh',
              '1/2': '50vh',
              '3/4': '75vh',
              'full': '100vh',
            }),
            maxWidth: theme => ({
              '0': '0',
              '1/4': '25vw',
              '1/2': '50vw',
              '3/4': '75vw',
              'full': '100vw',
            })
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
