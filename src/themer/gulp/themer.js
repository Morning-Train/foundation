module.exports = function (basepath) {

    /*
     Dependencies
     */

    var fs = require("fs"),
        elixir = require("laravel-elixir");

    // Read all themes from the resources folder
    var themesPath = basepath + '/resources/themes';

    if (!fs.lstatSync(themesPath).isDirectory()) {
        return;
    }

    var themes = fs.readdirSync(themesPath);

    //Loop over all themes to compile their assets
    if (themes.length > 0) {
        themes.forEach(function (theme) {

            if (fs.lstatSync(basepath + '/resources/themes/' + theme).isDirectory()) {

                //If the current theme has a sass file, compile it
                var sassPath = basepath + '/resources/themes/' + theme + '/assets/sass/app.scss';

                if (fs.existsSync(sassPath)) {
                    try {
                        fs.accessSync(sassPath, fs.F_OK);
                        elixir(mix => {
                            mix.sass(sassPath, basepath + '/public/themes/' + theme.toLowerCase() + '/app.css');
                        });
                    } catch (e) {
                        console.error(e);
                    }
                }

                //If the current theme has a js file, compile it
                var jsPath = basepath + '/resources/themes/' + theme + '/assets/js/app.js';

                if (fs.existsSync(jsPath)) {
                    try {
                        fs.accessSync(jsPath, fs.F_OK);
                        elixir(mix => {
                            mix.browserify(jsPath, basepath + '/public/themes/' + theme.toLowerCase() + '/app.js');
                        });
                    } catch (e) {
                        console.error(e);
                    }
                }

            }
        });
    }
};