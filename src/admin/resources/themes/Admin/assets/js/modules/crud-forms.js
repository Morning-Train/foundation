/*
 Dependencies
 */

import {Module} from "wrapper6";
import $ from "jquery";

/*
 Helpers
 */

function getUrlVars(href) {
    var vars = {};
    var parts = href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
        function (m, key, value) {
            vars[key] = value;
        });
    return vars;
}

/*
 Class
 */

export default class CrudForms extends Module {

    ready() {
        var _ = this;

        $("form.crud-index").each(function () {
            _.setupIndexForm($(this));
        });
    }

    /*
     Index forms
     */

    setupIndexForm(form) {
        var _ = this;

        // Click on pagination
        form.on("click", ".pagination a", function (e) {
            e.preventDefault();

            var anchor = $(this),
                vars = getUrlVars(anchor.attr("href"));

            form.append(`<input type="hidden" name="page" value="${vars.page}" />`);
            form.submit();
        });

        // Form submission
        form.on("submit", function (e) {
            e.preventDefault();

            var action = form.attr("action"),
                data = form.serialize();

            $.get(action, data).then((response) => {
                form.empty().append($(response).find("form.crud-index").children());

            }).catch(() => {
                // ...
            });
        });

    }

}