// Vendors
import {Module} from "wrapper6";
import $ from "jquery";

// Helpers
function createNotification( message, type = null, icon = null ) {
    var element = $("<div>").addClass("alert");

    // Type
    if (typeof type === "string") {
        element.addClass(type);
    }

    // Icon
    if (typeof icon === "string") {
        $("<i>").addClass("material-icons").html(icon).appendTo(element);
    }

    // Message
    $("<span>").html(message).appendTo(element);

    return element;
}

function showNotification( module, notification ) {
    // Show new message
    setTimeout(() => {
        notification.addClass("show");

        // Hide after a while
        setTimeout(() => {
            notification.removeClass("show");
            notification.on("transitionend", () => {
                notification.remove();
            });

        }, module.app.options.get("notifications.showDuration", 5000));

    }, 100);
}

// Class
export default class NotificationModule extends Module {

    boot( app ) {
        // Get the container
        this.container = $(".notifications");

        if (this.container.length === 0) {
            this.container = $("<div>").addClass("notifications").appendTo($("body"));
        }
    }

    ready( app ) {
        // Show blade-queued notifications
        showNotification(this, this.container.find(".alert:first"));
    }

    hide() {
        var hidden = this.container.find(".alert.show").removeClass("show");

        // Remove after transition is done
        hidden.on("transitionend", () => {
            hidden.remove();
        });

        return this;
    }

    show( message, type = null, icon = null ) {
        var module = this;

        // Hide old messages
        this.hide();

        // Add new message
        showNotification(this, createNotification(message, type, icon).appendTo(this.container));

        return this;
    }

}