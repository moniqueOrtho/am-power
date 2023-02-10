<template>
    <div class="story__pictures" :style="cssVars">
        <img
            v-for="(image, index) in this.section.body.images"
            :key="index"
            :src="image.src"
            :alt="image.alt"
            :class="image.class"
            @click="openImageDialog(image)"
        >
        <div class="story__background-editor" @click="openImageDialog(section.body.background)"></div>
        <image-dialog
        :image="image"
        :dialog="dialog"
        @dialog-closed="dialog = false"
        :labels="labels"
        ></image-dialog>
    </div>
</template>

<script>
import image1 from "../../../../images/laptop-en-koffie.jpg";
import image2 from "../../../../images/vrouw-met-laptop.jpg";
import image0 from "../../../../images/wood.jpg";
import ImageDialog from "../../dialogs/ImageDialog.vue";
export default {
    components: {ImageDialog},
    props: {
        data: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            dialog: false,
            image: {}
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
      },
      openImageDialog(image) {
        this.dialog = true;
        this.image = image;
      }
    },
    computed: {
        labels() {
            return this.$store.getters['labels/getTranslations'];
        },
        section() {
            let data = {};
            if(this.data.body !== null) {
                data = Object.assign({}, this.data);
            } else {
                data['body'] = {
                    background: {name: 'background', src: image0, alt: 'Houten planken', class: 'story__pictures'},
                    images: [
                        {name: 'img-0', src : image1, alt:'Laptop en koffie', class: 'story__img--1'},
                        {name: 'img-1', src : image2, alt:'Tevreden vrouw met laptop', class: 'story__img--2'}
                    ]
                }
            }
            return data;
        },
        cssVars() {
            return {
                '--color': this.convertHex(this.$vuetify.theme.themes.light.primary, 50),
                '--color2': this.convertHex(this.$vuetify.theme.themes.light.tertiary, 50),
                '--url': `url(${this.section.body.background.src})`
            }
        }
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
            cursor: pointer;
            position: relative;
        }

        &__background-editor {
            position:absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 5;
            transition: all 0.5s ease;
            &:hover {
                background-color: rgba($color: #000000, $alpha: .5);
            }
        }

        &__img--1 {
            width: 100%;
            height: 100%;
            grid-row: 2/6;
            grid-column: 2/6;
            box-shadow: 0 20px 50px rgba($color: #000000, $alpha: .2);
            object-fit: cover;
            z-index: 10;
            transition: all 0.5s ease;
            &:hover {
                -webkit-filter: brightness(.5);
                filter: brightness(.5);
            }
        }

        &__img--2 {
            width: 115%;
            grid-row: 4/6;
            grid-column: 4/7;
            z-index: 20;
            box-shadow: 0 20px 50px rgba($color: #000000, $alpha: .3);
            z-index: 15;
            transition: all 0.5s ease;
            &:hover {
                -webkit-filter: brightness(.5);
                filter: brightness(.5);
            }
        }
    }

</style>
