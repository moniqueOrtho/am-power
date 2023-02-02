<template>
    <div class="chic white">
        <component
            v-for="(section, index) in sections" :key="index"
            :is="section.component"
            :sequence="index"
            :data="section"
            :labels="text"
            :class="section.class"
            :success="succesIndex === index ? text.sectionSaved : ''"
            :error="errorIndex === index ? text.sectionNotSaved : ''"
            @save-section="saveSection"
            @delete-message="resetMessages"
        ></component>
    </div>

</template>

<script>
import TheFeatures from './chic/TheFeatures.vue';
import ContentRight from './chic/ContentRight.vue';
import PicturesLeft from './chic/PicturesLeft.vue';
import TheFooter from '../themes/chic/TheFooter.vue';
import HeaderOne from '../themes/chic/HeaderOne.vue';
import HeaderTwo from '../themes/chic/HeaderTwo.vue';
import TheGallery from '../themes/chic/TheGallery.vue';
import TheHomes from '../themes/chic/TheProducts.vue';
import TheSidebar from '../themes/chic/TheSidebar.vue';

export default {
    components: {TheFeatures, ContentRight, PicturesLeft, TheFooter, HeaderOne, HeaderTwo, TheGallery, TheHomes, TheSidebar},
    props: {
        page : {
            type: Object,
            required: true
        },
        labels: {
            type: Object,
            required: false
        }
    },
    data() {
        return {
            sections: [
                {component: 'TheSidebar', class:'chic__item chic__the-sidebar primary', title: null, subtitle: null, body: null, text: null},
                {component: 'HeaderOne', class:'chic__item chic__header-one grey1', title: null, subtitle: null, body: null, text: null},
                {component: 'HeaderTwo', class:'chic__item chic__header-two tertiary', title: null, subtitle: null, body: null, text: null},
                {component: 'TheFeatures', class:'chic__the-features', title: null, subtitle: null, body: null, text: null},
                {component: 'PicturesLeft', class:'chic__pictures-left', title: null, subtitle: null, body: null, text: null},
                {component: 'ContentRight', class:'chic__content-right light1', title: null, subtitle: null, body: null, text: null},
                {component: 'TheHomes', class:'chic__item chic__the-homes tertiary', title: null, subtitle: null, body: null, text: null},
                {component: 'TheGallery', class:'chic__item chic__the-gallery grey1', title: null, subtitle: null, body: null, text: null},
                {component: 'TheFooter', class:'chic__item chic__the-footer tertiary', title: null, subtitle: null, body: null, text: null},
            ],
            colors: {
                primary: '#c69963',
                //secondary: '#e78856',
                secondary: '#B28451', // sunray yellow
                tertiary: '#101d2c', // gold

                light1: '#f9f7f6',
                light2: '#aaa',

                grey1: '#54483A',
                grey2: '#6D5D4B',
            },
            succesIndex: -1,
            errorIndex: -1,
            text: {
                feature: 'Feature',
                add: 'Add',
                title: 'Title',
                subtitle: 'Subtitle',
                save: 'Save',
                noTitle: 'No title',
                noSubtitle: 'No subtitle',
                sectionSaved: 'Section has been saved successful!',
                sectionNotSaved: 'Paragraph has not been saved',
                happyCustomers: 'Happy customers',
                bestDecision: 'The best decision for our company!',
                moreReviews: 'More reviews',
                text: 'Text',
                button: 'Button',
                layout: 'Layout',
                new2: 'new',
                closed: 'Closed',
                back: 'Back'
            }
        }
    },
    created() {
        //console.log(this.page);
        this.setColors();
        this.setLabels();
        this.setSections();
    },
    methods: {
        setColors() {
            const keys = Object.keys(this.colors);
            keys.forEach(key => {
                this.$vuetify.theme.themes.light[key] = this.colors[key];
            });
        },
        setLabels() {
            if(this.labels) {
                let keys = Object.keys(this.labels);
                keys.forEach(k => this.text[k] = this.labels[k]);
            }
        },
        setSections() {
            let index, section;
            if(Object.keys(this.page.sections).length > 0) {
                for(index in this.page.sections) {
                    section = this.page.sections[index]
                    this.setData(section, section.sequence);
                }
            }
        },
        resetMessages() {
            this.succesIndex = -1;
            this.errorIndex = -1;
        },
        setData(data, sectionIndex) {
            let keys = Object.keys(data);
            keys.forEach(k => {
                this.sections[sectionIndex][k] = data[k];
            })
        },
        setSavedSection(data, key) {
            let index = this.sections.findIndex(s => s[key] === data[key])
            this.setData(data, index);
        },
        async saveSection(section) {
            this.resetMessages();
            let newSection;
            newSection = Object.assign({}, section);

            try {
                if(section.id) {
                    const updated = await axios.put(`/sections/${section.id}`, newSection);
                    this.succesIndex = updated.data.data.sequence;
                    this.setSavedSection(updated.data.data, 'id');
                } else {
                    const stored = await axios.post(`/pages/${this.page.id}/sections`, newSection);
                    this.succesIndex = stored.data.data.sequence;
                    this.setSavedSection(stored.data.data, 'sequence');
                }

            } catch (error) {
                console.log(error);
                if(error) this.errorIndex = section.sequence;
            }
        }
    },


}
</script>

<style lang="scss" scoped>
    .chic {
        font-family: 'Nunito', sans-serif;
        display: grid;
        grid-template-rows: 80vh auto 40vw repeat(3, min-content);
        grid-template-columns: [sidebar-start] 5rem [sidebar-end full-start] minmax(3.75rem, 1fr) [center-start] repeat(8, [col-start] minmax(min-content, 9rem)  [col-end]) [center-end] minmax(3.75rem, 1fr) [full-end];

        &__the-sidebar {
            grid-column: sidebar-start / sidebar-end;
            grid-row: 1 / -1;
        }

        &__header-one {
            grid-column: full-start / col-end 6;
        }

        &__header-two {
            grid-column: col-start 7 / full-end;
        }

        &__the-features {
                grid-column: center-start / center-end;
            }

        &__pictures-left {
            grid-column: full-start / col-end 4;
        }

        &__content-right {
            grid-column: col-start 5 / full-end;
        }

        &__the-homes {
            grid-column: center-start / center-end;
        }

        &__the-gallery {
            grid-column: full-start / full-end;
        }

        &__the-footer {
            grid-column: full-start / full-end;
        }



        &__item {
            padding: 40px;
            font-size: 2rem;

        }
    }
</style>
