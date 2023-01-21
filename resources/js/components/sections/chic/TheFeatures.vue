<template>
    <div class="features-maker">
        <div class="editor-menu__container">
            <editor-menu
            :activator="{ open: 'mdi-content-save', closed: 'mdi-pencil-box-multiple'}"
            :actions="['add', 'title', 'subtitle', 'view']"
            :position="{top: false, right: true, bottom: true, left: false}"
            @big-activator="bigActivatorClicked"
            @action-btn="actionBtnClicked"
            >
            </editor-menu>
        </div>

        <div class="d-flex justify-center" v-if="maker">
            <h2
                class="chic__heading-2 features-maker__heading"
                role="textbox"
                contenteditable
                @blur="titleIsChanged"
            >{{ section.title }}</h2>
        </div>
        <div class="features-maker__container" v-if="maker">
            <div
            class="feature-maker"
            v-for="(feature, index) in section.features"
            :key="index"
            :id="feature.id"
            >
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
        <ChicFeatures :section="section" v-else/>


    </div>

</template>

<script>
import ChicFeatures from "../../themes/chic/TheFeatures.vue";
import EditorMenu from "../../tools/EditorMenu.vue";
export default {
    components: {ChicFeatures, EditorMenu},
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
            }
        }
    },
    methods: {
        initialize() {
            this.section.title = 'Onze kracht';
            this.title = true;
            this.section.subtitle = null;
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
                    this.addTitle();
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
            let feature = { id:`feature-${this.section.features.length}`, title: `feature ${this.section.features.length + 1}`, icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'};
            this.section.features.push(feature);
        },
        addTitle() {

        },
        addSubtitle() {

        },
        titleIsChanged(e) {
            this.section.title = e.target.innerText;
        },
        featureTitleIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.features.findIndex(x => x.id === feature);
            if(index > -1) this.section.features[index]['title'] = e.target.innerText;
        },
        featureTextIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.features.findIndex(x => x.id === feature);
            if(index > -1) this.section.features[index]['text'] = e.target.innerText;
        }
    }


}
</script>

<style lang="scss" scoped>
    .features-maker {
        grid-column: center-start / center-end;
        position: relative;


        &__heading {
            margin: 4.5rem 0;
            border: 2px dotted var(--v-grey1-base);
        }

        &__container {

            margin: 0 0 9rem 0;
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
            bottom: 0;
            right: -16px;
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

    [contenteditable] {
        outline: 0px solid transparent;
    }
</style>
