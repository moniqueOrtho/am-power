<template>
    <div class="features-maker">
        <editor-menu
        :actions="actions"
        :position="{top: true, right: false, bottom: false, left: true}"
        @big-activator="bigActivatorClicked"
        @action-btn="actionBtnClicked"
        :labels="labels"
        >
        </editor-menu>

        <div class="d-flex justify-center" v-if="maker && title">
            <input @focus="deleteFocus" type="text" :placeholder="labels.title" v-model="section.title" class="features-maker__heading" :style="styleHeading">
        </div>
        <div class="features-maker__container" v-if="maker" :style="styleContainer">
            <div

                class="feature-maker"
                v-for="(feature, index) in section.body"
                :key="index"
                :id="feature.id"
                @click="makerClicked(feature.id)"
            >
                <curl-btn :obj="feature" @curl-action="curlClicked"></curl-btn>

                <v-menu
                    offset-y
                    :close-on-content-click="false"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                        class="feature-maker__icon"
                        color="primary"
                        light
                        icon
                        v-bind="attrs"
                        v-on="on"
                        @click="makerClicked(feature.id)"
                        >
                            <v-icon size="2.8rem">{{ feature.icon ? feature.icon : 'mdi-shape-plus' }}</v-icon>
                        </v-btn>
                    </template>
                    <div class="feature-maker__menu">
                        <v-text-field
                            v-model="feature.icon"
                            class="pa-3"
                            label="Icoon"
                            placeholder="mdi-"
                            outlined
                            hide-details
                        ></v-text-field>
                    </div>
                </v-menu>
                <h4 class="chic__heading-4 chic__heading-4--dark" contenteditable @blur="featureTitleIsChanged">{{ feature.title }}</h4>
                <div class="feature-maker__text" contenteditable @blur="featureTextIsChanged">{{ feature.text }}</div>
            </div>



        </div>
        <TheFeatures :section="section" v-else/>
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
import TheFeatures from "../../themes/chic/TheFeatures.vue";
import EditorMenu from "../../tools/EditorMenu.vue";
import CurlBtn from "../../buttons/CurlBtn.vue";
import CloseAlert from "../../alerts/CloseAlert.vue";
export default {
    components: {TheFeatures, EditorMenu, CurlBtn, CloseAlert},
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
    emits: ['save-section', 'delete-message'],
    created() {
        this.initialize();
    },
    data() {
        return {
            maker: false,
            features: [
                {id:"feature-0", title: `${this.labels.feature} 1`, icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'},
                {id:"feature-1", title: `${this.labels.feature} 2`, icon: '', text: 'Voluptatum mollitia quae. Vero ipsum sapiente molestias accusamus rerum sed a eligendi aut quia.'},
                {id:"feature-2", title: `${this.labels.feature} 3`, icon: '', text: 'Tenetur distinctio necessitatibus pariatur voluptatibus quidem consequatur harum.'},
                {id:"feature-3", title: `${this.labels.feature} 4`, icon: '', text: 'Vero ipsum sapiente molestias accusamus rerum. Lorem, ipsum dolor sit amet consectetur adipisicing elit.'},
                {id:"feature-4", title: `${this.labels.feature} 5`, icon: '', text: 'Quidem consequatur harum, voluptatum mollitia quae. Tenetur distinctio necessitatibus pariatur voluptatibus.'},
            ],
            section: {
                title: null,
                subtitle: null,
                body: []
            },
            changed: false,
            actions: ['add', 'title'],
            title: false,
            subtitle: false,
            focusedId: null
        }
    },
    computed: {
        styleContainer() {
            return this.title ? {margin: '0 0 9rem 0'} : {margin: '9rem 0'};
        },
        styleHeading() {
            let obj = this.title ? { margin: '4.5rem 0'} : {margin: '0 0 4.5rem 0'}
            if(!this.data.title && this.section.title === 'Title') obj['color'] = '#A9A9AC'

            return obj;
        },
        titleChanged() {
            return this.section.title;
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
    },
    watch: {
        titleChanged() {
            this.changed = this.section.title.length > 0 && !this.data.title
        }
    },
    methods: {
        initialize() {
            if(this.data.title) {
                this.section.title = this.data.title;
                this.title = true;
                this.setRightTitleBtn('title', 'no-title');
            }
            if(this.data.subtitle) {
                this.section.subtitle = this.data.subtitle;
                this.subtitle = true;
            }
            if(this.data.body) {
                this.section.body = JSON.parse(this.data.body);
            } else {
                this.section.body = [...this.features];
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
                case 'view':
                    this.viewBtn();
                    break;
                case 'add':
                    this.addFeature();
                    break;
                case 'title':
                    this.addTitle(action.name);
                    break;
                case 'no-title':
                    this.deleteTitle(action.name);
                break;
                case 'subtitle':
                    this.addSubtitle();
                    break;
                default:
                    break;
            }
        },
        viewBtn() {
            this.maker = false;
        },
        addFeature() {
            this.deleteFocus();
            this.changed = true;
            let feature = { id:`feature-${this.section.body.length}`, title: `${this.labels.feature} ${this.section.body.length + 1}`, icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'};
            this.section.body.push(feature);
        },
        setRightTitleBtn(title, newbtn) {
            let index = this.actions.indexOf(title);
            if(index > -1) {
                this.$set(this.actions, index, newbtn);
            }
        },
        addTitle(title) {
            this.title = true;
            this.deleteFocus();
            this.setRightTitleBtn(title, 'no-title');
        },
        deleteTitle(title) {
            this.deleteFocus();
            this.title = false;
            this.setRightTitleBtn(title, 'title');
            if(this.section.title) {
                this.section.title = null;
                this.changed = true;
            }

        },
        addSubtitle() {

            his.changed = true;
        },
        featureTitleIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.body.findIndex(x => x.id === feature);
            if(index > -1) this.section.body[index]['title'] = e.target.innerText;
            this.changed = true;
        },
        featureTextIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.body.findIndex(x => x.id === feature);
            if(index > -1) this.section.body[index]['text'] = e.target.innerText;
            this.changed = true;
        },
        curlClicked(data) {
            const index = this.section.body.findIndex(x => x.id === data.id);
            if(index > -1) {
                this.section.body.splice(index, 1);
                this.changed = true;
            }
        },
        deleteFocus() {
            if(this.focusedId) {
                let old = document.getElementById(this.focusedId);
                old.style.removeProperty('border');
                old.style.removeProperty('box-shadow');
            }
        },
        makerClicked(id) {
            this.deleteFocus();
            let newEl = document.getElementById(id);
            newEl.style.cssText = `border: 1px solid var(--v-primary-base);
                box-shadow: 0 0 3px var(--v-primary-base);`
            this.focusedId = id;
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
    .features-maker {
        position: relative;


        &__heading {
            font-family: "Josefin Sans", sans-serif;
            font-weight: 400;
            border: 2px dotted var(--v-grey1-base);
            height: 46px;
            border-radius: 4px;
            width: 50%;
            font-size: 1.75rem;
            text-align: center;
            &::-webkit-input-placeholder {
                font-family: "Josefin Sans", sans-serif;
            }
            &:-ms-input-placeholder {
                font-family: "Josefin Sans", sans-serif;
            }
            &:-moz-placeholder {
                font-family: "Josefin Sans", sans-serif;
            }


            &:focus {
                outline-offset: 0px;
                outline: none;
                border: 1px solid var(--v-primary-base);
                box-shadow: 0 0 3px var(--v-primary-base);
                -moz-box-shadow: 0 0 3px var(--v-primary-base);
                -webkit-box-shadow: 0 0 3px var(--v-primary-base);
            }
        }

        &__container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
            grid-auto-rows: min-content;
            grid-gap: 3.75rem;
            align-items: start;
        }
    }

    .feature-maker {
        display: grid;
        grid-template-columns: min-content 1fr;
        grid-row-gap: 1rem;
        grid-column-gap: 1.5rem;
        border: 2px dotted var(--v-grey1-base);
        //padding: .5rem;
        border-radius: 4px;
        position:relative;


        &__text {
            font-size: 1.125rem;
        }

        &__icon {
            grid-row: 1 /span 2;
            align-self: start;
            top: .3rem;
            left: .3rem;
        }

        &__menu {
            display: flex;
            align-items: center;
            background-color: var(--v-primary-lighten4);
            height: 7rem;
        }
    }
</style>
