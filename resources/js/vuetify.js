import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
Vue.use(Vuetify)

export default new Vuetify({
    theme: {
        options: { customProperties: true },
        themes: {
            light: {
                primary: '#8672C0',
                //secondary: '#e78856',
                secondary: '#E9B049', // sunray yellow
                tertiary: '#C69963', // gold
                //secondary: '#533f8d', // donker paars
                accent: '#15102c', //'#533f8d',

                info: '#4856ad',
                //warning: '#D06A4C', // Copper Red
                warning: '#e78856', // Orthobegeleiding orange
                //warning: '#e9b049', // sunray yellow
                error: '#b6244f',
                success: '#c6c86a',

                light1: '#f1eee4',
                light2: '#E4D8C6',

                grey1: '#999999',
                grey2: '#333333',
              }
        },
    },
})
