/*
 Dependencies
 */

import $ from "jquery";
import {Module} from "wrapper6";

export default class CsrfService extends Module {

    boot() {
        var token = $("meta[name=_token]").attr("content");

        if ((typeof token === "string") && (token.length > 0)) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": token
                }
            });
        }
    }

}