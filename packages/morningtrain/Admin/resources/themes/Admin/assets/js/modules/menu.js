/*
 Dependencies
 */

import {Module} from "wrapper6";
import $ from "jquery";

/*
 Module
 */

export default class MenuModule extends Module {

    boot(app) {
        return app.require(["cookie"]).then(({cookie}) = > {
            this.cookie = cookie;
    })
        ;
    }

    ready() {
        // Module
        var _ = this;

        $(".sidebar").each(function () {
            var menu = $(this);

            _.loadState(menu);

            $('[data-toggle=' + _.getUniqueId(menu) + ']').on('click', () = > {
                _.toggleMenu(menu);
        })
            ;
        });
    }

    /*
     Helpers
     */

    getTargetWidth(menu) {
        var clone = menu.clone(true).css({
                left: '-10000px',
                position: 'absolute',
                transistion: 'none'
            }).appendTo('body'),

            width = clone.width();

        clone.remove();
        return width;
    }

    getCssForSiblings(menu) {
        var key = menu.attr("data-align") === "right" ? "padding-right" : "padding-left";

        return {
            [key]: this.getTargetWidth(menu) + "px"
        };
    }

    openMenu(menu) {
        menu.removeClass("closed");
        menu.find('li span').addClass('menu-open');
        menu.siblings().css(this.getCssForSiblings(menu));
    }

    closeMenu(menu) {
        menu.addClass("closed");
        menu.find('li span').addClass('menu-close');
        menu.siblings().css(this.getCssForSiblings(menu));
    }

    getUniqueId(menu) {
        var id = menu.attr('data-slug');
        return typeof id === "undefined" ? "default" : id;
    }

    saveState(menu) {
        this.cookie.set(this.getUniqueId(menu) + '_menu_status', menu.hasClass('closed') ? 'closed' : 'open', 365);
    }

    loadState(menu) {
        var status = this.cookie.get(this.getUniqueId(menu) + '_menu_status');
        status === "closed" ? this.closeMenu(menu) : this.openMenu(menu);
    }

    toggleMenu(menu) {
        menu.hasClass('closed') ? this.openMenu(menu) : this.closeMenu(menu);
        this.saveState(menu);
    }
}