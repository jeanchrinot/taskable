const TailwindExtractor = class TailwindExtractor {
    static extract(content) {
        return content.match(/[A-Za-z0-9-_:/]+/g) || []
    }
};

module.exports = {
    plugins: {
        'tailwindcss': {},
        '@fullhuman/postcss-purgecss': {
            content: [
                './resources/js/*/*.vue',
            ],
            whitelist: ['html', 'body'],
            extractors: [
                {
                    extractor: TailwindExtractor,
                    extensions: ['vue']
                }
            ]
        },
        'cssnano': {}
    }
};