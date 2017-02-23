/* Dependencies
 ----------------------------------------------------------*/
import {Application} from "wrapper6";

/* Setup
 ----------------------------------------------------------*/
var app = window.app = new Application(window.options || {});

/* Services
 ----------------------------------------------------------*/
import CookieService from "./../../../../shared/js/services/cookie";
import CsrfService from "./../../../../shared/js/services/csrf";

/* Register services
 ----------------------------------------------------------*/
app.use("cookie", CookieService);
app.use(CsrfService);

/* Modules
 ----------------------------------------------------------*/
import MenuModule from "./modules/menu";
import NotificationModule from "./modules/notifications";
import ModalModule from "./modules/modal";
import SortableModule from "./modules/sortable";
import CrudForms from "./modules/crud-forms";

/* Register modules
 ----------------------------------------------------------*/
app.use(MenuModule);
app.use("notifications", NotificationModule);
app.use("modals", ModalModule);
app.use(SortableModule);
app.use(CrudForms);