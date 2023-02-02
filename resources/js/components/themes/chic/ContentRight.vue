<template>
    <div class="story">
        <h3 class="chic__heading-3 mb-5" v-if="section.title">{{ section.title }}</h3>
        <h2 class="chic__subheading-2 chic__subheading-2--dark mb-8" v-if="section.subtitle">{{ section.subtitle }}</h2>
        <p class="story__text mb-10 mb-8">{{ section.text }}</p>
        <div class="story__button-container">
            <a
                v-for="button in this.section.body"
                :key="button.button"
                class="chic__btn"
                :class="button.class"
                :href="button.href"
            >{{ button.text }}</a>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    computed: {
        section() {
            let data = Object.assign({}, this.data);
            return data;
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


        &__text {
            font-size: 1rem;
            font-style: italic;
        }

        &__button-container {
            width: 100%;
            display: grid;
            grid-template-columns: repeat(auto-fill, 12rem);
            grid-row-gap: 1rem;
            align-items: center;
            align-content: start;
            justify-content: space-between;
        }
    }

</style>
