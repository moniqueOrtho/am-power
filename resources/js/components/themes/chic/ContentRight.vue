<template>
    <div class="story">
        <h3 class="chic__heading-3 mb-5">{{ section.title }}</h3>
        <h2 class="chic__subheading-2 chic__subheading-2--dark mb-8">&ldquo;{{ section.subtitle }}&rdquo;</h2>
        <p class="story__text mb-10 mb-8">{{ section.text }}</p>
        <button class="chic__btn">{{ section.body.button }}</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            section: {
                title: 'Happy Customers',
                subtitle: 'The best decision of our lives',
                text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus. Quidem consequatur harum volupta!',
                body: {
                    button: 'Find your own home'
                }
            }
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
    }

</style>
