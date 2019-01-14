
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

const output = $('#cmdout');
const input = $('#cmdin');

$('#cmdform')
    .on('submit', (e) => {
        e.preventDefault();

        let in_str = input.val();

        if (in_str === 'clear') {
            output.empty();
            return;
        }

        axios
            .post('cmd', {
                input: in_str,
            })
            .then((response) => {
                if (response.data.success = false) {
                    throw response.data.message;
                }

                if (response.data.message) {
                    response.data.message.replace(/\n/g, '<br>');
                } else {
                    response.data.message = 'No response.';
                }

                output.append('<p>' + response.data.message + '</p>');
                input.val('');
            })
            .catch((error) => {
                output.append(error + '<br>');
            });
    })

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Vue.component('dungeon-interface', require('./components/DungeonInterface.vue'));

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app'
});
