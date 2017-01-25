// Vendors
import {Module} from "wrapper6";
import $ from "jquery";
import alert from "alert.js";

export default class ModalModule extends Module {

    alert( contents, options ) {
        return alert(contents, options);
    }

    // How to use confirm ?
    // e.g. ..
    //
    // app.modal.confirm("Are you sure?").then(function() { // pressed Yes }, function() { // pressed No })

    confirm( contents, options ) {
        return new Promise(( resolve, reject ) => {
            alert(contents, Object.assign({}, options, {
                buttons: {
                    "Yes": (dialog) => {
                        dialog.close();
                        resolve();
                    },

                    "No": (dialog) => {
                        dialog.close();
                        reject();
                    }
                }
            }));
        });
    }

    ready() {
        var _ = this;

        /*
        Auto bind events which have to be confirmed
         */

        $("a[data-confirm]").each(function () {
            var target = $(this),
                event = target.attr("data-confirm-event") || "click",
                message = target.attr("data-confirm"),
                location = target.attr("href"),
                confirmed = false;

            if ((typeof message === "string") && (message.length > 0)) {
                target.on(event, function (e) {
                    if (confirmed === false) {
                        e.preventDefault();
                        e.stopPropagation();

                        return _.confirm(message)
                            .then(() => {
                                confirmed = true;
                                target.trigger(event);
                            })
                            .catch(() => {
                                confirmed = false;
                            });
                    }

                    // reset flag
                    confirmed = false;

                    // Go to location
                    window.location = location;
                });
            }
        });
    }

}