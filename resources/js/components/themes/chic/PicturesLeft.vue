<template>
    <div class="story__pictures" :style="cssVars">
        <img
            :src="image.src"
            :alt="image.alt"
            :class="image.class"
            v-for="(image, index) in this.section.body.images"
            :key="index"
            :style="getImageStyle(image)"
        >
    </div>
</template>

<script>
import image1 from "../../../../images/laptop-en-koffie.jpg";
import image2 from "../../../../images/vrouw-met-laptop.jpg";
import image0 from "../../../../images/wood.jpg";
export default {
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    methods: {
        convertHex (color, opacity) {
        color = color.replace('#', '')
        let r = parseInt(color.substring(0, 2), 16)
        let g = parseInt(color.substring(2, 4), 16)
        let b = parseInt(color.substring(4, 6), 16)
        let result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')'
        return result
      }
    },
    computed: {
        section() {
            let section = {};
            if(this.data.body !== null) {
                section = JSON.parse(JSON.stringify(this.data));
                if(typeof section.body === 'string') {
                    section.body = JSON.parse(section.body);
                }
                section.body.background.class = 'story__pictures'
                section.body.images.forEach((image, index) => {
                    image.class = `story__img--${index + 1}`
                });
            } else {
                section['body'] = {
                    background: image0,
                    images: [
                        {src : image1, alt:'Laptop en koffie', class: 'story__img--1'},
                        {src : image2, alt:'Tevreden vrouw met laptop', class: 'story__img--2'}
                    ]
                }
            }
            return section;
        },
        cssVars() {
            return {
                '--color': this.convertHex(this.$vuetify.theme.themes.light.primary, 50),
                '--color2': this.convertHex(this.$vuetify.theme.themes.light.tertiary, 50),
                '--url': `url(${this.section.body.background.src})`
            }
        },
        getImageStyle() {
            return (image) => {
                return ('aspectRatio' in image) ? { aspectRatio : image.aspectRatio} : {aspectRatio : 'auto'};
            }
        },
    }
}
</script>

<style lang="scss" scoped>

    .story {

        &__pictures {
            display: grid;
            grid-template-rows: repeat(6, 1fr);
            grid-template-columns: repeat(6, 1fr);
            align-items: center;
            background: linear-gradient(-90deg, var(--color), var(--color2)), var(--url);
            background-repeat: no-repeat; /* Do not repeat the image */
            background-size: cover;
            height: 100%;
        }

        &__img--1 {
            width: 100%;
            height: 100%;
            grid-row: 2/6;
            grid-column: 2/6;
            box-shadow: 0 20px 50px rgba($color: #000000, $alpha: .2);
            object-fit: cover;
        }

        &__img--2 {
            width: 115%;
            grid-row: 4/6;
            grid-column: 4/7;
            z-index: 20;
            box-shadow: 0 20px 50px rgba($color: #000000, $alpha: .3);
        }
    }

</style>
