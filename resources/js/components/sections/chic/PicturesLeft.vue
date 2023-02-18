<template>
    <div class="story__pictures" :style="cssVars">
        <img
            v-for="(image, index) in editedSection.body.images"
            :key="index"
            :src="image.src"
            :alt="image.alt"
            :class="`story__img ${image.class}`"
            @click="openImageDialog(image)"
            :style="getImageStyle(image)"
        >
        <div class="story__background-editor" @click="openImageDialog(editedSection.body.background)"></div>
        <image-dialog
            :image="image"
            :dialog="dialog"
            @dialog-closed="dialog = false"
            @set-image="setNewImage"
            :labels="labels"
        ></image-dialog>
    </div>
</template>

<script>
import image1 from "../../../../images/laptop-en-koffie.jpg";
import image2 from "../../../../images/vrouw-met-laptop.jpg";
import image0 from "../../../../images/wood.jpg";
import ImageDialog from "../../dialogs/ImageDialog.vue";
import ColorsMixin from "../../../mixins/colors.js";
export default {
    components: {ImageDialog},
    mixins: [ColorsMixin],
    props: {
        data: {
            type: Object,
            required: true
        },
    },
    created() {
        this.initialize();

    },
    data() {
        return {
            dialog: false,
            image: {},
            editedSection: {}
        }
    },
    methods: {
        initialize() {
            this.editedSection = Object.assign({}, this.data);
            if(this.data.body === null) {
                this.editedSection.body = {
                    background: {name: 'background', src: image0, alt: 'Houten planken', class: 'story__pictures'},
                    images: [
                        {id: 'img-0', name: 'img-0', src : image1, alt:'Laptop en koffie', class: 'story__img--1', aspectRatio: '1/1'},
                        {id: 'img-1', name: 'img-1', src : image2, alt:'Tevreden vrouw met laptop', class: 'story__img--2', aspectRatio: '3/2'}
                    ]
                }
            }
        },
        openImageDialog(image) {
            this.dialog = true;
            this.image = image;
        },
        setNewImage(image) {
            let index = this.editedSection.body.images.findIndex(i => i.class === image.class);
            this.editedSection.body.images[index] = image;
        }
    },
    computed: {
        labels() {
            return this.$store.getters['labels/getTranslations'];
        },
        cssVars() {
            return {
                '--color': this.convertHex(this.$vuetify.theme.themes.light.primary, 50),
                '--color2': this.convertHex(this.$vuetify.theme.themes.light.tertiary, 50),
                '--url': `url(${this.editedSection.body.background.src})`
            }
        },
        getImageStyle() {
            return (image) => {
                return ('aspectRatio' in image) ? { aspectRatio : image.aspectRatio} : {aspectRatio : 'auto'};
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

        &__img {
            overflow: hidden;
            object-fit: cover;

            &--1 {
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

            &--2 {
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


    }

</style>
