const path = require("path");
const glob = require("glob");

module.exports = {
    entry: {
        "bundle.js": glob.sync("build/static/js/*.js").map(f => path.resolve(__dirname, f)),
    },
    output:{
        filename: "build/static/js/bundle.js",
    }
}