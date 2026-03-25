export default {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
        ...(process.env.NODE_ENV === 'production' ? {
            '@fullhuman/postcss-purgecss': {
                content: [
                    './resources/views/**/*.blade.php',
                    './app/Livewire/**/*.php',
                    './resources/js/**/*.js',
                ],
                defaultExtractor: (content) => content.match(/[\w-/:]+(?<!:)/g) || [],
                safelist: [
                    /^select2-/,
                    /^dataTables_/
                ]
            }
        } : {})
    },
};
