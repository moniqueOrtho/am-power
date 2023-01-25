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
        <v-tooltip
            :bottom="position.top && (direction === 'right' || direction === 'left')"
            :top="position.bottom && (direction === 'right' || direction === 'left')"
            :left="position.right && (direction === 'top' || direction === 'bottom')"
            :right="position.left && (direction === 'top' || direction === 'bottom')"
            v-for="(editor, index) in editors"
            :key="index"
        >
            <template v-slot:activator="{ on, attrs }">
                <v-btn

                    fab
                    :dark="defaultFab.dark"
                    :light="defaultFab.light"
                    small
                    :color="editor.color"
                    @click="actionsBtnClicked(editor)"
                    v-bind="attrs"
                    v-on="on"
                >
                    <font-awesome-icon class="fa__icon" :icon="editor.icon" v-if="editor.icon.search('fa-') > -1" />

                    <v-icon v-if="editor.icon.search('mdi-')  > -1">{{ editor.icon }}</v-icon>
                    <span class="editor-menu__opp-icon" v-if="editor.opp"></span>
                </v-btn>
            </template>
            <span>{{ editor.tip }}</span>
        </v-tooltip>


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
        labels: {
            type: Object,
            default: () => {
                return {
                    feature: 'Feature',
                    add: 'Add',
                    title: 'Title',
                    save: 'Save',
                    noTitle: 'No title'
                }
            }
        }
    },
    emits: ['big-activator', 'action-btn'],
    data() {
        return {
            defaultFab : { color: 'primary', open: 'mdi-close', closed: 'mdi-pencil-box-multiple', dark: false, light: true },
            fab: false,
            defaultEditors: [
                {name: 'add', color: 'accent', icon: 'mdi-plus', opp: false, tip: this.labels.add},
                {name: 'delete', color: 'error', icon: 'mdi-delete', opp: false},
                {name: 'title', color: 'primary', icon: 'fas fa-heading', opp: false, tip: this.labels.title},
                {name: 'no-title', color: 'primary', icon: 'fas fa-heading', opp: true, tip: this.labels.noTitle},
                {name: 'subtitle', color: 'secondary', icon: 'mdi-subtitles', opp: false},
                {name: 'no-subtitle', color: 'secondary', icon: 'mdi-subtitles', opp: true},
                {name: 'view', color: 'success', icon: 'mdi-eye', opp: false},
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
    watch: {
        actions() {
            this.editors = [];
            this.setActionbuttons();
        }
    }
}
</script>

<style lang="scss" scoped>
    .fa__icon {
            font-size: 1.25rem;
    }

    .editor-menu {
        &__opp-icon {
            position: absolute;
            top: -9px;
            left: 0px;
            height: 40px;
            width: 40px;
            border-radius: 50%;
            &::after {
                content: "";
                position: absolute;
                top: 6px;
                left: 35px;
                border-top: 3px solid var(--v-light1-base);
                width: 40px;
                transform: rotate(135deg);
                transform-origin: 0% 0%;
            }
        }
    }

</style>
