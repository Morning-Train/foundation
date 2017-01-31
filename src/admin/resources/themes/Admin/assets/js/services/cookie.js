/*
Dependencies
 */

import {Module} from "wrapper6";

/*
Service
 */

export default class CookieService extends Module {

    set( name, value, daysToExpire = 1, path = '/' ) {
        var expirationDate = new Date();
        expirationDate.setDate(expirationDate.getDate() + daysToExpire);

        document.cookie = name + "=" + escape(value) + "; expires=" + expirationDate.toUTCString() + "; path=" + path;
    }

    get( name ) {
        var key, value, cookies = document.cookie.split(";");

        for(var i = 0, length = cookies.length; i < length; i++) {
            key = cookies[i].substr(0, cookies[i].indexOf("=")).replace(/^\s+|\s+$/g, "");
            value = cookies[i].substr(cookies[i].indexOf("=") + 1);

            if (key === name) {
                return unescape(value);
            }
        }
    }

}