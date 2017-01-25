/*
Dependencies
 */

import {Module} from "wrapper6";
import $ from "jquery";

/*
Module
 */

export default class SortableModule extends Module {

    ready() {

        $(".content").on("click", "[data-sortable=on]", function() {

            var trigger = $(this),
                name = trigger.attr("data-name"),
                direction = trigger.attr("data-order") || "none",
                path = `${location.protocol}//${location.host}${location.pathname}`;

            // Determine direction to toggle
            direction = direction === "asc" ? "desc" : "asc";

            window.location = `${path}?order=${name}&direction=${direction}`;
        });

    }

}