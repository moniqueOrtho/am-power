<template>
    <div class="editor-menu">
        <v-speed-dial
        v-model="fab"
        absolute
        :top="position.top"
        :bottom="position.bottom"
        :right="position.right"
        :left="position.left"
        :direction="direction"
        :open-on-hover="hover"
        :transition="transition"
        >
        <template v-slot:activator>
            <v-btn
            v-model="fab"
            :color="defaultFab.color"
            :dark="defaultFab.dark"
            :light="defaultFab.light"
            fab

            >
            <v-icon v-if="fab">{{ defaultFab.open }}</v-icon>
            <v-icon v-else>{{ defaultFab.closed }}</v-icon>
            </v-btn>
        </template>
        <v-btn
            v-for="(editor, index) in editors"
            :key="index"
            fab
            :dark="defaultFab.dark"
            :light="defaultFab.light"
            small
            :color="editor.color"
        >
            <v-icon>{{ editor.icon }}</v-icon>
        </v-btn>

        </v-speed-dial>
    </div>
</template>

<script>
export default {
    props: {
        activator : {
            type: [Object, Boolean],
            default: false
        },
        position: {
            type: Object,
            default: () => {
                return {top: false, right: false, bottom: false, left: false}
            }
        },
        direction: {
            type: String,
            default: 'left'// Posibilities: 'right', 'bottom' and 'top'
        },
        transition: {
            type: String,
            default: 'slide-x-reverse-transistion' // Posibilities: 'slide-y-transistion', 'slide-y-reverse-transistion', 'slide-x-transistion', 'scale-transistion'
        },
        actions: {
            type: Array,
            required: true
        },
        hover: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            defaultFab : { color: 'primary', open: 'mdi-close', closed: 'mdi-account-circle', dark: false, light: true },
            fab: false,
            defaultEditors: [
                {name: 'add', color: 'accent', icon: 'mdi-plus'},
                {name: 'delete', color: 'error', icon: 'mdi-delete'}
            ],
            editors: [],
            right: false,
            left: false,
            bottom: false,
            top: false,
        }
    },
    created() {
        if(this.activator) this.setDefaults(this.activator, this.defaultFab);
        this.setActionbuttons();

    },
    methods: {
        setDefaults( prop, def) {
            const keys = Object.keys(prop);
            keys.forEach(key => {
                def[key] = prop[key];
            });
        },
        setActionbuttons() {
            this.actions.forEach(action => {
                let find = this.defaultEditors.find(x => x.name === action);
                if(find) this.editors.push(find);
            })
        },
    }

}
</script>

<style ang="scss" scoped>
    .editor-menu {

    }

</style>
