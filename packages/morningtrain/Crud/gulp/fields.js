module.exports = function( basepath ) {

    var elixir = require("laravel-elixir"),
        fs = require("fs"),
        fields = fs.readdirSync(basepath + "/resources/fields"),
        sass = [],
        js = [];

    fields.forEach(function( field ) {
        var sass_path = basepath + "/resources/fields/" + field + "/field.scss",
            js_path = basepath + "/resources/fields/" + field + "/field.js";

        // Validate sass file
        try {
            fs.accessSync(sass_path, fs.F_OK);
            sass.push(sass_path);
        }
        catch(e) {
            console.error(e);
        }

        // Validate js file
        try {
            fs.accessSync(js_path, fs.F_OK);
            js.push(js_path);
        }
        catch(e) {
            console.error(e);
        }
    });

    // Add files to elixir
    elixir(mix => {
        if (sass.length > 0) {
            mix.sass(sass, "public/assets/css/fields.css");
        }

        if (js.length > 0) {
            mis.browserify(js, "public/assets/js/fields.js");
        }
    });

};