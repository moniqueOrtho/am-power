<template>
    <div class="features-maker">
        <div class="editor-menu__container">
            <editor-menu
            :activator="{ open: 'mdi-eye', closed: 'mdi-pencil-box-multiple'}"
            :actions="['add', 'delete']"
            :position="{top: false, right: true, bottom: true, left: false}"
            >
            </editor-menu>
        </div>

        <div class="d-flex justify-center" v-if="maker">
            <h2 class="chic__heading-2 features-maker__heading">Onze kracht</h2>
        </div>
        <div class="features-maker__container" v-if="maker">
            <div
            class="feature-maker"
            v-for="(feature, index) in features"
            :key="index"
            >
                <v-icon class="feature-maker__icon" color="primary">{{ feature.icon ? feature.icon : 'mdi-shape-plus' }}</v-icon>
                <h4 class="chic__heading-4 chic__heading-4--dark">{{ feature.title }}</h4>
                <div class="feature-maker__text">{{ feature.text }}</div>
            </div>
        </div>
        <ChicFeatures :features="features" v-else/>


    </div>

</template>

<script>
import ChicFeatures from "../../themes/chic/ChicFeatures.vue";
import EditorMenu from "../../tools/EditorMenu.vue";
export default {
    components: {ChicFeatures, EditorMenu},
    data() {
        return {
            maker: true,
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
    .features-maker {
        grid-column: center-start / center-end;
        position: relative;


        &__heading {
            margin: 4.5rem 0;
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
            font-size: 2.8rem;
            grid-row: 1 /span 2;
            align-self: start;
        }
    }
</style>
