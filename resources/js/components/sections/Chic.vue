<template>
    <div class="chic light1">
        <div class="chic__item chic__item--1 primary">sidebar</div>
        <div class="chic__item chic__item--2 grey1">header</div>
        <div class="chic__item chic__item--3 tertiary">realtors</div>
        <component
            v-for="(section, index) in sections"
            :key="index"
            :is="section"
        ></component>
        <div class="chic__item chic__item--5 primary">story pictures</div>
        <div class="chic__item chic__item--6 secondary">story content</div>
        <div class="chic__item chic__item--7 tertiary">homes</div>
        <div class="chic__item chic__item--8 grey1">gallery</div>
        <div class="chic__item chic__item--9 tertiary">footer</div>
    </div>

</template>

<script>
import TheFeatures from './chic/TheFeatures.vue';
export default {
    components: {TheFeatures},
    props: {
        page : {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            sections: ['TheFeatures'],
            colors: {
                primary: '#c69963',
                //secondary: '#e78856',
                secondary: '#B28451', // sunray yellow
                tertiary: '#101d2c', // gold

                light1: '#f9f7f6',
                light2: '#aaa',

                grey1: '#54483A',
                grey2: '#6D5D4B',
            }
        }
    },
    created() {
        console.log(this.page);
        this.setColors();
    },
    methods: {
        setColors() {
            const keys = Object.keys(this.colors);
            keys.forEach(key => {
                this.$vuetify.theme.themes.light[key] = this.colors[key];
            });
        }
    }

}
</script>

<style lang="scss" scoped>
    .chic {
        font-family: 'Nunito', sans-serif;
        display: grid;
        grid-template-rows: 80vh auto 40vw repeat(3, min-content);
        grid-template-columns: [sidebar-start] 5rem [sidebar-end full-start] minmax(3.75rem, 1fr) [center-start] repeat(8, [col-start] minmax(min-content, 9rem)  [col-end]) [center-end] minmax(3.75rem, 1fr) [full-end];

        &__item {
            padding: 40px;
            font-size: 2rem;
            &--1 {
                grid-column: sidebar-start / sidebar-end;
                grid-row: 1 / -1;
            }

            &--2 {
                grid-column: full-start / col-end 6;

            }

            &--3 {
                grid-column: col-start 7 / full-end;

            }

            &--5 {
                grid-column: full-start / col-end 4;

            }

            &--6 {
                grid-column: col-start 5 / full-end;

            }

            &--7 {
                grid-column: center-start / center-end;

            }

            &--8,
            &--9 {
                grid-column: full-start / full-end;

            }

        }
    }
</style>
