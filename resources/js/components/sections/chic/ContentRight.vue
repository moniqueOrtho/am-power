<template>
    <div :class="contentManager">
        <editor-menu
        :container="false"
        direction="left"
        :actions="actions"
        :position="{top: true, right: true, bottom: false, left: false}"
        @big-activator="bigActivatorClicked"
        @action-btn="actionBtnClicked"
        :labels="labels"
        >
        </editor-menu>
        <input class="chic__heading-3 mb-5 editor__box editor__box--title" v-model="section.title" :placeholder="text.title"  v-if="title && maker">
        <textarea class="chic__subheading-2 chic__subheading-2--dark mb-8 editor__box editor__box--subtitle" v-model="section.subtitle" :placeholder="text.subtitle" rows="2" v-if="subtitle && maker"></textarea>
        <p
            class="story__text mb-10 mb-8 editor__box"
            contenteditable
            v-if="maker"
            ref="editable"
            @blur="textIsChanged()"
        >{{ section.text }}</p>
        <div class="story__button-container" v-if="maker">
            <div class="story__button"
                v-for="button in this.section.body"
                :key="button.id"
                :id="button.id"
            >
                <div class="curl-btn__container">
                    <curl-btn :obj="button" @curl-action="curlClicked"></curl-btn>
                </div>

                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <button
                            class="chic__btn"
                            :class="button.class"
                            v-bind="attrs"
                            v-on="on"
                        >{{ button.text }}</button>
                    </template>
                    <div class="section-maker__v-menu">
                        <v-text-field
                            v-model="button.text"
                            class="pa-2"
                            :label="text.text"
                            outlined
                            hide-details
                        ></v-text-field>
                        <v-text-field
                            v-model="button.url"
                            class="pa-2"
                            label="link"
                            outlined
                            hide-details
                        ></v-text-field>
                        <v-select
                            v-model="button.class"
                            :items="classItems"
                            class="pa-2"
                            :label="`${text.button} ${text.layout.toLowerCase()}`"
                            outlined
                            hide-details
                        ></v-select>

                    </div>
                </v-menu>
            </div>

        </div>
        <content-right :data="section" v-if="!maker"></content-right>
        <div class="message__container message__container--right">
            <close-alert
                :alertColor="getAlertColor"
                :alert-message="getAlertMessage"
                :type="getAlertType"
                spacing="my-3"
                :closeAction="true"
            ></close-alert>
        </div>
        <v-tooltip left v-if="changed && maker">
            <template v-slot:activator="{ on, attrs }">
                <div class="save-btn__container save-btn__container--right">
                    <div class="pulsate" >
                        <v-fab-transition>
                        <v-btn color="primary" fab dark v-bind="attrs" v-on="on" @click="save">
                            <v-icon> mdi-content-save </v-icon>
                        </v-btn>
                        </v-fab-transition>
                    </div>
                </div>
            </template>
            <span>{{ this.labels.save }}</span>
        </v-tooltip>
    </div>
</template>

<script>
import EditorMenu from "../../tools/EditorMenu.vue";
import CurlBtn from "../../buttons/CurlBtn.vue";
import CloseAlert from "../../alerts/CloseAlert.vue";
import ContentRight from "../../themes/chic/ContentRight.vue";
export default {
    components: {EditorMenu, CurlBtn, CloseAlert, ContentRight},
    props: {
        sequence: {
            type: Number,
            required: true
        },
        data: {
            type: Object,
            required: true
        },
        success: {
            type: String,
            default: ''
        },
        error: {
            type: String,
            default: ''
        },
        labels: {
            type: Object,
            required: false
        },
        emits: ['save-section', 'delete-message'],
    },
    created() {
        this.setLabels();
        this.initialize();
    },
    data() {
        return {
            section: {
                title: '',
                subtitle: '',
                text: '',
                body: []
            },
            text: {
                add: 'Add',
                title: 'Title',
                subtitle: 'Subtitle',
                save: 'Save',
                noTitle: 'No title',
                happyCustomers: 'Happy customers',
                bestDecision: 'The best decision for our company!',
                moreReviews: 'More reviews',
                text: 'Text',
                button: 'Button',
                layout: 'Layout',
                new2: 'New',
                closed: 'Closed'
            },
            changed: false,
            actions: ['button', 'no-title', 'no-subtitle'],
            title: false,
            subtitle: false,
            focusedId: null,
            maker: false
        }
    },
    computed: {
        contentManager() {
            return this.maker ? 'story' : 'story__viewer';
        },
        getAlertColor() {
            return this.error ? 'red-me' : 'green-me';
        },
        getAlertMessage() {
            let message = '';
            if(this.error) message = this.error;
            if(this.success) message = this.success;
            return message;
        },
        getAlertType() {
            return this.error ? 'error' : 'success';
        },
        defaultSubtitle() {
            return `“${this.text.bestDecision}”`
        },
        classItems() {
            return [ {text : this.text.closed, value : 'chic__btn--filled'}, {text : 'Open', value : 'chic__btn--open'}]
        },
        defaultButton() {
            return { id: `btn-${this.section.body.length}`, text: `${this.text.new2} ${this.text.button.toLowerCase()}`, url: '', class:"chic__btn--filled" }
        }
    },
    methods: {
        initialize() {
            this.section.title = this.data.title ? this.data.title : this.text.happyCustomers;
            this.title = true;

            this.section.subtitle = this.data.subtitle ? this.data.subtitle : this.defaultSubtitle;
            this.subtitle = true;

            if(this.data.body) {
                this.section.body = JSON.parse(this.data.body);
            } else {
                this.section.body.push(this.defaultButton);
            }

            this.section.text = this.data.text ? this.data.text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus. Quidem consequatur harum volupta!';

        },
        setLabels() {
            if(this.labels) {
                let keys = Object.keys(this.text);
                keys.forEach(k => this.text[k] = this.labels[k]);
            }
        },
        bigActivatorClicked(closed) {
            this.maker = ! closed;
            if(this.error || this.success) {
                this.$emit('delete-message');
            }
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
                case 'button':
                    this.addButton();
                break;
                default:
                    break;
            }
        },
        setRightTitleBtn(title, newbtn) {
            let index = this.actions.indexOf(title);
            if(index > -1) {
                this.$set(this.actions, index, newbtn);
            }
        },
        addTitle(title) {
            this.title = true;
            this.section.title = this.text.happyCustomers;
            this.setRightTitleBtn(title, 'no-title');
        },
        deleteTitle(title) {
            this.title = false;
            this.setRightTitleBtn(title, 'title');
            if(this.section.title) {
                this.section.title = null;
                this.changed = true;
            }
        },
        addSubtitle(title) {
            this.subtitle = true;
            this.section.subtitle = this.defaultSubtitle
            this.setRightTitleBtn(title, 'no-subtitle');
            this.changed = true;

        },
        deleteSubtitle(title) {
            this.subtitle = false;
            this.setRightTitleBtn(title, 'subtitle');
            if(this.section.subtitle) {
                this.section.subtitle = null;
                this.changed = true;
            }
        },
        addButton() {
            this.section.body.push(this.defaultButton);
        },
        textIsChanged() {
            let text = this.$refs.editable.innerText;
            this.section.text = text;
            this.changed = true;
        },
        curlClicked(data) {
            const index = this.section.body.findIndex(x => x.id === data.id);
            if(index > -1) {
                this.section.body.splice(index, 1);
                this.changed = true;
            }
        },
        save() {
            this.changed = false;
            this.maker = false;
            let text = this.section.body.map((s) => {
                return `<h6 class="text-h6">${s.title}</h6><p>${s.text}</p>`
            });
            this.$emit('save-section', {
                id : this.data === null ? null : this.data.id,
                sequence: this.sequence,
                component: 'TheFeatures',
                title: this.section.title,
                subtitle: this.section.subtitle,
                body: JSON.stringify(this.section.body),
                text: text.toString()

            });
        }

    }

}
</script>

<style lang="scss" scoped>
    .story {
        padding: 3.75rem 8vw;
        display: grid;
        align-content: center;
        justify-items: start;
        position: relative;
        &__viewer {
            position: relative;
            display: flex;
        }

        &__text {
            font-size: 1rem;
            font-style: italic;
        }

        &__button-container {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fill, 11rem);
            grid-row-gap: 1rem;
            align-items: center;
            align-content: start;
            justify-content: space-between;
        }

        &__button {
            position: relative;
        }
    }



</style>
