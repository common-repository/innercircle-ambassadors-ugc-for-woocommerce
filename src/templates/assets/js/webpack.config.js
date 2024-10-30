const path = require("path");
const TerserPlugin = require("terser-webpack-plugin");

module.exports = {
    entry: [
        './src/user.js'],
    output:
        {
            path: path.resolve(__dirname, 'dist'),
            filename: 'ic-woo-user.min.js',
            publicPath: '/dist'
        },
    optimization: {
        minimize: true,
        minimizer: [
            new TerserPlugin({
                minify: (file, sourceMap) => {
                    const uglifyJsOptions = {
                    };
                    if (sourceMap) {
                        uglifyJsOptions.sourceMap = {
                            content: sourceMap,
                        };
                    }
                    return require("uglify-js").minify(file, uglifyJsOptions);
                },
            }),
        ],
    },
}
