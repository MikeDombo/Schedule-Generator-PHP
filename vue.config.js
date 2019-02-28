if(process.env.NODE_ENV === 'production') {
    module.exports = {
        outputDir: "src/public/vue",
        publicPath: "./public/vue/",
        integrity: true
    };
}
else {
    module.exports = {};
}
