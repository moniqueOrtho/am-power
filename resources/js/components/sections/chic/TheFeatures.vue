<template>
    <div class="features-maker">
        <div class="editor-menu__container">
            <editor-menu
            :actions="actions"
            :position="{top: true, right: false, bottom: false, left: true}"
            @big-activator="bigActivatorClicked"
            @action-btn="actionBtnClicked"
            >
            </editor-menu>
        </div>

        <div class="d-flex justify-center" v-if="maker && title">
            <input type="text" placeholder="Title" v-model="section.title" class="chic__heading-2 features-maker__heading" :style="styleHeading">
        </div>
        <div class="features-maker__container" v-if="maker" :style="styleContainer">
            <div

            class="feature-maker"
            v-for="(feature, index) in section.features"
            :key="index"
            :id="feature.id"
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
        <div class="save-btn__container" v-if="changed && maker">
            <div class="pulsate" >
                <v-fab-transition>
                <v-btn color="primary" fab dark >
                    <v-icon> mdi-content-save </v-icon>
                </v-btn>
                </v-fab-transition>
            </div>
        </div>

    </div>

</template>

<script>
import TheFeatures from "../../themes/chic/TheFeatures.vue";
import EditorMenu from "../../tools/EditorMenu.vue";
import CurlBtn from "../../buttons/CurlBtn.vue";
export default {
    components: {TheFeatures, EditorMenu, CurlBtn},
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    created() {
        this.initialize();
    },
    data() {
        return {
            maker: false,
            features: [
                {id:"feature-0", title: 'feature 1', icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'},
                {id:"feature-1", title: 'feature 2', icon: '', text: 'Voluptatum mollitia quae. Vero ipsum sapiente molestias accusamus rerum sed a eligendi aut quia.'},
                {id:"feature-2", title: 'feature 3', icon: '', text: 'Tenetur distinctio necessitatibus pariatur voluptatibus quidem consequatur harum.'},
                {id:"feature-3", title: 'feature 4', icon: '', text: 'Vero ipsum sapiente molestias accusamus rerum. Lorem, ipsum dolor sit amet consectetur adipisicing elit.'},
                {id:"feature-4", title: 'feature 5', icon: '', text: 'Quidem consequatur harum, voluptatum mollitia quae. Tenetur distinctio necessitatibus pariatur voluptatibus.'},
            ],
            section: {
                title: null,
                subtitle: null,
                features: []
            },
            changed: false,
            actions: ['add', 'title'],
            title: false,
            subtitle: false,
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
        }
    },
    methods: {
        initialize() {
            if(this.data.title) {
                this.section.title = this.data.title;
                this.title = true;
            }
            if(this.data.subtitle) {
                this.section.subtitle = this.data.subtitle;
                this.subtitle = true;
            }

            this.section.features = [...this.features];
        },
        bigActivatorClicked(closed) {
            this.maker = ! closed;
        },
        actionBtnClicked(action) {
            console.log(action)
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
            this.changed = true;
            let feature = { id:`feature-${this.section.features.length}`, title: `feature ${this.section.features.length + 1}`, icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'};
            this.section.features.push(feature);
        },
        addTitle(title) {
            this.title = true;
            let index = this.actions.indexOf(title);
            if(index > -1) {
                this.$set(this.actions, index, 'no-title');
            }
        },
        deleteTitle(title) {
            this.title = false;
            let index = this.actions.indexOf(title);
            if(index > -1) {
                this.$set(this.actions, index, 'title');
            }
            if(this.section.title) {
                this.section.title = null;
                this.changed = true;
            }

        },
        addSubtitle() {

            his.changed = true;
        },
        titleIsChanged(e) {
            this.section.title = e.target.innerText;
            this.changed = true;
        },
        featureTitleIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.features.findIndex(x => x.id === feature);
            if(index > -1) this.section.features[index]['title'] = e.target.innerText;
            this.changed = true;
        },
        featureTextIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.features.findIndex(x => x.id === feature);
            if(index > -1) this.section.features[index]['text'] = e.target.innerText;
            this.changed = true;
        },
        curlClicked(data) {
            const index = this.section.features.findIndex(x => x.id === data.id);
            if(index > -1) {
                this.section.features.splice(index, 1);
                this.changed = true;
            }
        }
    }


}
</script>

<style lang="scss" scoped>
    .features-maker {
        grid-column: center-start / center-end;
        position: relative;


        &__heading {
            border: 2px dotted var(--v-grey1-base);
            height: 46px;
            border-radius: 4px;
        }

        &__container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
            grid-auto-rows: min-content;
            grid-gap: 3.75rem;
            align-items: start;
        }
    }
    .editor-menu {
        &__container {
            position: absolute;
            top: 0;
            left: -16px;
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
    .save-btn__container {
        position: absolute;
        bottom: 1rem;
        right: 0;
    }


    [contenteditable] {
        outline: 0px solid transparent;
    }
</style>
