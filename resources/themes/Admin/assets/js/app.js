/* Dependencies
 ----------------------------------------------------------*/
import {Application} from "wrapper6";

/* Services
 ----------------------------------------------------------*/
import CookieService from "./services/cookie";

/* Modules
 ----------------------------------------------------------*/
import MenuModule from "./modules/menu";
import NotificationModule from "./modules/notifications";
import ModalModule from "./modules/modal";
import SortableModule from "./modules/sortable";


/* Setup
 ----------------------------------------------------------*/
var app = window.app = new Application(window.options || {});

/* Register services
 ----------------------------------------------------------*/
app.use("cookie", CookieService);

/* Register modules
 ----------------------------------------------------------*/
app.use(MenuModule);
app.use("notifications", NotificationModule);
app.use("modals", ModalModule);
app.use(SortableModule);