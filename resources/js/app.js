/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import vuetify from './vuetify';
import store from './store/index';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret, faEye, faUserCircle, faEyeSlash, faKey, faCheck, faArrowRightFromBracket, faCircleCheck, faCircleExclamation, faXmark, faHeading, faInfoCircle } from '@fortawesome/free-solid-svg-icons'
import Vue from 'vue';

/* add icons to the library */
library.add(faUserSecret, faEye, faUserCircle, faKey, faEyeSlash, faCheck, faArrowRightFromBracket, faCircleCheck, faCircleExclamation, faXmark, faHeading, faInfoCircle)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('card-top', require('./components/cards/CardTop.vue').default);
Vue.component('icon-input', require('./components/form/IconInput.vue').default);
Vue.component('btn-pressed', require('./components/buttons/BtnPressed.vue').default);
Vue.component('CrudTable', require('./components/table/CrudTable.vue').default);
Vue.component('chic', require('./components/sections/Chic.vue').default);
Vue.component('playful', require('./components/sections/Playful.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store,
    vuetify,

});
