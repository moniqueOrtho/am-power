
import Vue from 'vue'
import Vuex from 'vuex';
import translateModule from './labels/index.js';

Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        labels: translateModule
    }
})

export default store;
