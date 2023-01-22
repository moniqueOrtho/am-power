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
            @click="bigActivatorClicked"
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
            @click="actionsBtnClicked(editor)"
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
            default: 'right'// Posibilities: 'left', 'bottom' and 'top'
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
        },
    },
    emits: ['big-activator', 'action-btn'],
    data() {
        return {
            defaultFab : { color: 'primary', open: 'mdi-close', closed: 'mdi-pencil-box-multiple', dark: false, light: true },
            fab: false,
            defaultEditors: [
                {name: 'add', color: 'accent', icon: 'mdi-plus'},
                {name: 'delete', color: 'error', icon: 'mdi-delete'},
                {name: 'title', color: 'primary', icon: 'mdi-format-annotation-plus'},
                {name: 'subtitle', color: 'secondary', icon: 'mdi-subtitles'},
                {name: 'view', color: 'success', icon: 'mdi-eye'},
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
        bigActivatorClicked() {
            this.$emit('big-activator', this.fab);
        },
        actionsBtnClicked(data) {
            this.$emit('action-btn', data);
        }
    },
}
</script>

<style ang="scss" scoped>
    .editor-menu {

    }

</style>
