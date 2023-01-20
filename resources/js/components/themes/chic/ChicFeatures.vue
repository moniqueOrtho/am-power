<template>
    <section id="chic-features" class="chic-features">
        <div class="d-flex justify-center">
            <h2 class="chic__heading-2 chic-features__heading">Onze kracht</h2>
        </div>
        <div class="chic-features__container">
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
        features: {
            type: Array,
            required: true
        }
    }

}
</script>

<style lang="scss" scoped>
    .chic-features {
        grid-column: center-start / center-end;

        &__container {
            margin: 0 0 9rem 0;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
            grid-gap: 3.75rem;
            align-items: start;
        }

        &__heading {
            margin: 4.5rem 0;
        }


    }
    .feature {
        display: grid;
        grid-template-columns: min-content 1fr;
        grid-row-gap: 1rem;
        grid-column-gap: 1.5rem;

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
