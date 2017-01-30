module.exports = function (basepath) {

    var elixir = require("laravel-elixir"),
        fs = require("fs"),
        sass = [],
        js = [];

    var fieldsPath = basepath + "/resources/fields";

    if (!fs.lstatSync(fieldsPath).isDirectory()) {
        return;
    }

    var fields = fs.readdirSync(fieldsPath);

    fields.forEach(function (field) {
        var sassPath = basepath + "/resources/fields/" + field + "/field.scss",
            jsPath = basepath + "/resources/fields/" + field + "/field.js";


        // Validate sass file
        if (fs.existsSync(sassPath)) {
            try {
                fs.accessSync(sassPath, fs.F_OK);
                sass.push(sassPath);
            }
            catch (e) {
                console.error(e);
            }
        }

        // Validate js file
        if (fs.existsSync(jsPath)) {
            try {
                fs.accessSync(jsPath, fs.F_OK);
                js.push(jsPath);
            }
            catch (e) {
                console.error(e);
            }
        }
    });

    // Add files to elixir
    elixir(mix = > {
        if (sass.length > 0
    )
    {
        mix.sass(sass, "public/assets/css/fields.css");
    }

    if (js.length > 0) {
        mis.browserify(js, "public/assets/js/fields.js");
    }
})
    ;

};