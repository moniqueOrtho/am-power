<template>
    <section id="chic-features" class="chic-features">
        <div
            class="feature"
            v-for="(feature, index) in features"
            :key="index"
        >
        <v-icon class="feature__icon" color="primary">{{ feature.icon ? feature.icon : 'mdi-shape-plus' }}</v-icon>
        <h4 class="chic__heading-4 chic__heading-4--dark">{{ feature.title }}</h4>
        <div class="feature__text">{{ feature.text }}</div>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            features: [
                {title: 'feature 1', icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'},
                {title: 'feature 2', icon: '', text: 'Voluptatum mollitia quae. Vero ipsum sapiente molestias accusamus rerum sed a eligendi aut quia.'},
                {title: 'feature 3', icon: '', text: 'Tenetur distinctio necessitatibus pariatur voluptatibus quidem consequatur harum.'},
                {title: 'feature 4', icon: '', text: 'Vero ipsum sapiente molestias accusamus rerum. Lorem, ipsum dolor sit amet consectetur adipisicing elit.'},
                {title: 'feature 5', icon: '', text: 'Quidem consequatur harum, voluptatum mollitia quae. Tenetur distinctio necessitatibus pariatur voluptatibus.'},
                {title: 'feature 6', icon: '', text: 'Pariatur voluptatibus quidem consequatur harum, voluptatum mollitia quae.'},
            ]
        }
    }

}
</script>

<style lang="scss" scoped>
    .chic-features {
        grid-column: center-start / center-end;

        margin: 9rem 0;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 3.75rem;


    }
    .feature {
        display: grid;
        &__text {
            font-size: 1.125rem;
        }

        &__icon {
            font-size: 2.8rem;
        }
    }
</style>
