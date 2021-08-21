require('datatables.net-dt');
require('datatables.net-rowgroup-dt');



// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);


window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

//window.$ = window.jQuery = require('jquery');
window.bootstrap = require('bootstrap');

window.capitalize = function(value) {
    if (!value) {
        return '';
    } else {
        value = value.toString();
        return value.charAt(0).toUpperCase() + value.slice(1);
    }
}
