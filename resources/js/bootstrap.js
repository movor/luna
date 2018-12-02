// Lodash
//window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    // Popper needs to be included only when we are using bootstrap popover
    // (see customize_bootstrap.js file)
    //window.Popper = require('popper.js').default;

    require('bootstrap');
    // TODO.WHEN:bootstrap solves bug with "Util is not defined"
    // Bootstrap (separate js components)
    //require('../../../node_modules/bootstrap/js/dist/alert');
    //require('../../../node_modules/bootstrap/js/dist/button');
    //require('../../../node_modules/bootstrap/js/dist/carousel');
    //require('../../../node_modules/bootstrap/js/dist/collapse');
    //require('../../../node_modules/bootstrap/js/dist/dropdown');
    //require('../../../node_modules/bootstrap/js/dist/modal');
    //require('../../../node_modules/bootstrap/js/dist/popover');
    //require('../../../node_modules/bootstrap/js/dist/scrollspy');
    //require('../../../node_modules/bootstrap/js/dist/tab');
    //require('../../../node_modules/bootstrap/js/dist/tooltip');
    //require('../../../node_modules/bootstrap/js/dist/util');

} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

//
// Axios
//

//window.axios = require('axios');
//window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

// let token = document.head.querySelector('meta[name="csrf-token"]');

// if (token) {
//     window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
// } else {
//     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
// }

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
