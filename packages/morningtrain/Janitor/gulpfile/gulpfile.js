/*
Dependencies
 */

var fs = require("fs"),
    elixir = require("laravel-elixir");

/*
Elixir settings
 */

process.env.DISABLE_NOTIFIER = true;

elixir.config.css.autoprefix = {
    enabled: true, //default, this is only here so you know how to disable
    options: {
        cascade: true,
        browsers: ['last 50 versions', '> 1%']
    }
};

elixir.config.watch.usePolling = true;
elixir.config.watch.interval = true;

/*
Fetch all gulp tasks and run them
 */

fs.readdirSync("./gulp/").forEach(function( filename ) {
    if (filename.match(/\.js$/) !== null) {
        var task = require("./gulp/" + filename);

        if (typeof task === "function") {
            task(".");
        }
    }
});