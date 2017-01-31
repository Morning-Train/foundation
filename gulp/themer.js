module.exports = function( basepath ) {

    /*
    Dependencies
     */

    var fs = require("fs"),
        elixir = require("laravel-elixir");

    // Read all themes from the resources folder
    var themes = fs.readdirSync(basepath + '/resources/themes');

    //Loop over all themes to compile their assets
    if(themes.length > 0){
        themes.forEach(function(theme){

            if(fs.lstatSync(basepath + '/resources/themes/' + theme).isDirectory()) {

                //If the current theme has a sass file, compile it
                var sassPath = basepath + '/resources/themes/' + theme + '/assets/sass/app.scss';
                try {
                    fs.accessSync(sassPath, fs.F_OK);
                    elixir(mix => {
                        mix.sass(sassPath, basepath + '/public/themes/' + theme.toLowerCase() + '/app.css');
                    });
                } catch (e) {
                    console.error(e);
                }

                //If the current theme has a js file, compile it
                var jsPath = basepath + '/resources/themes/' + theme + '/assets/js/app.js';
                try {
                    fs.accessSync(jsPath, fs.F_OK);
                    elixir(mix => {
                        mix.browserify(jsPath, basepath + '/public/themes/' + theme.toLowerCase() + '/app.js');
                    });
                } catch (e) {
                    console.error(e);
                }

            }
        });
    }
};