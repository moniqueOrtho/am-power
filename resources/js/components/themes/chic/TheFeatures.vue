<template>
    <section id="chic-features" class="chic-features">
        <div class="d-flex justify-center" v-if="title">
            <h2 class="chic__heading-2 chic-features__heading" :style="styleHeading">{{ title }}</h2>
        </div>
        <div class="chic-features__container" :style="styleContainer">
            <div
            class="feature"
            v-for="(feature, index) in features"
                :key="index"
            >
                <v-icon class="feature__icon" color="primary">{{ feature.icon ? feature.icon : 'mdi-shape-plus' }}</v-icon>
                <h4 class="chic__heading-4 chic__heading-4--dark">{{ feature.title }}</h4>
                <div class="feature__text">{{ feature.text }}</div>
            </div>
        </div>

    </section>
</template>

<script>
export default {
    props : {
        section: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            title: '',
            subtitle: '',
            features: null
        }
    },
    created() {
       this.initialize();
    },
    computed: {
        styleContainer() {
            return this.title ? {margin: '0 0 9rem 0'} : {margin: '9rem 0'};
        },
        styleHeading() {
            return this.title ? { margin: '4.5rem 0'} : {margin: '0 0 4.5rem 0'}
        }
    },
    methods: {
        initialize() {
            if('title' in this.section && this.section.title !== null) this.title = this.section.title;
            if('subtitle' in this.section && this.section.subtitle !== null) this.subtitle = this.section.subtitle;
            if('features' in this.section && this.section.features !== null) this.features = [ ...this.section.features ];
        }
    }

}
</script>

<style lang="scss" scoped>
    .chic-features {

        &__container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
            grid-gap: 3.75rem;
            align-items: start;
        }

        &__heading {
            border: 2px dotted transparent;
        }


    }
    .feature {
        display: grid;
        grid-template-columns: min-content 1fr;
        grid-row-gap: 1rem;
        grid-column-gap: 1.5rem;
        border: 2px dotted transparent;

        &__text {
            font-size: 1.125rem;
        }

        &__icon {
            font-size: 2.8rem;
            grid-row: 1 /span 2;
            align-self: start;
        }
    }
</style>
