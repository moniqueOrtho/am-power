<template>
    <div class="editing-bar">
        <editor-menu
            direction="right"
            :actions="buttons"
            :position="{top: false, right: false, bottom: false, left: false}"
            @big-activator="bigActivatorClicked"
            @action-btn="actionBtnClicked"
            :labels="labels"
            >
        </editor-menu>
        <v-tooltip left v-if="changed">
            <template v-slot:activator="{ on, attrs }">
                <div class="pulsate" >
                    <v-fab-transition>
                    <v-btn color="primary" fab dark v-bind="attrs" v-on="on" @click="save">
                        <v-icon>mdi-content-save</v-icon>
                    </v-btn>
                    </v-fab-transition>
                </div>
            </template>
            <span>{{ this.labels.save }}</span>
        </v-tooltip>
    </div>
</template>

<script>
import EditorMenu from './EditorMenu.vue';
export default {
    name: 'editing-bar',
    components: {EditorMenu},
    props: {
        changed: {
            type: Boolean,
            default: false
        },
        actions: {
            type: Array,
            required: true
        }
    },
    emits: ['title-actions', 'editor-active', 'save-section'],
    created() {
        this.initialize();
    },
    data() {
        return {
            buttons: []
        }
    },
    methods: {
        initialize() {
            this.buttons = [...this.actions];
        },
        bigActivatorClicked(closed) {
            this.$emit('editor-active')
        },
        actionBtnClicked(action) {
            switch (action.name) {
                case 'title':
                    this.addTitle(action.name);
                    break;
                case 'no-title':
                    this.deleteTitle(action.name);
                break;
                case 'subtitle':
                    this.addSubtitle(action.name);
                    break;
                case 'no-subtitle':
                    this.deleteSubtitle(action.name);
                break;
                default:
                    break;
            }
        },
        setRightTitleBtn(title, newbtn) {
            let index = this.buttons.indexOf(title);
            if(index > -1) {
                this.$set(this.buttons, index, newbtn);
            }
        },
        addTitle(title) {
            this.$emit('title-actions', {title: true})
            this.setRightTitleBtn(title, 'no-title');
        },
        deleteTitle(title) {
            this.$emit('title-actions', {title: false})
            this.setRightTitleBtn(title, 'title');
        },
        addSubtitle(title) {
            this.$emit('title-actions', {subtitle: true})
            this.setRightTitleBtn(title, 'no-subtitle');
        },
        deleteSubtitle(title) {
            this.$emit('title-actions', {subtitle: false})
            this.setRightTitleBtn(title, 'subtitle');

        },
        save() {

        }
    },
    computed: {
        labels() {
            return this.$store.getters['labels/getTranslations'];
        },
    }
}
</script>

<style lang="scss" scoped>
    .editing-bar {
        position: absolute;
        left: 0;
        top: 0;
        margin: .5rem 0;
        width: 100%;
        z-index: 10;
        display: flex;
        justify-content: space-between;
    }
</style>
