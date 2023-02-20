<template>
    <div class="story-editor">
        <div class="editor-btn__container">
            <v-fab-transition>
                <v-btn
                    color="primary"
                    dark
                    fab
                    @click="edit = !edit"
                >
                    <v-icon v-if="!edit">mdi-pencil-box-multiple</v-icon>
                    <v-icon v-else >mdi-close</v-icon>

                </v-btn>
            </v-fab-transition>
        </div>
        <div class="story-editor__pictures" :style="cssVars" v-if="edit">

            <img
                v-for="(image, index) in section.body.images"
                :key="index"
                :src="image.src"
                :alt="image.alt"
                :class="`edit-img story-editor__img ${image.class}`"
                @click="openImageDialog(image)"
                :style="getImageStyle(image)"
            >
            <div class="story-editor__background-editor edit-img" @click="openImageDialog(section.body.background)"></div>

            <image-dialog
                :image="image"
                :dialog="dialog"
                @dialog-closed="dialog = false"
                @set-image="setNewImage"
                :labels="labels"
            ></image-dialog>
        </div>
        <pictures-left :data="section" v-else></pictures-left>
        <div class="message__container message__container--left">
            <close-alert
                :alertColor="getAlertColor"
                :alert-message="getAlertMessage"
                :type="getAlertType"
                spacing="my-3"
                :closeAction="true"
            ></close-alert>
        </div>
    </div>

</template>

<script>
import image1 from "../../../../images/laptop-en-koffie.jpg";
import image2 from "../../../../images/vrouw-met-laptop.jpg";
import image0 from "../../../../images/wood.jpg";
import ImageDialog from "../../dialogs/ImageDialog.vue";
import ColorsMixin from "../../../mixins/colors.js";
import CloseAlert from '../../alerts/CloseAlert.vue';
import PicturesLeft from '../../themes/chic/PicturesLeft.vue';
export default {
    components: {ImageDialog, CloseAlert, PicturesLeft},
    mixins: [ColorsMixin],
    props: {
        data: {
            type: Object,
            required: true
        },
        sequence: {
            type: Number,
            required: true
        },
        success: {
            type: String,
            default: ''
        },
        error: {
            type: String,
            default: ''
        },
    },
    emits: ['save-section', 'delete-message'],
    created() {
        this.initialize();
    },
    data() {
        return {
            dialog: false,
            image: {},
            section: {},
            edit: false
        }
    },
    methods: {
        initialize() {
            this.section = Object.assign({}, this.data);
            if(this.data.body === null) {
                this.section.body = {
                    background: {name: 'background', src: image0, alt: 'Houten planken', class: 'story-editor__pictures'},
                    images: [
                        {id: 'img-0', name: 'img-0', src : image1, alt:'Laptop en koffie', class: 'story-editor__img--1', aspectRatio: '1/1'},
                        {id: 'img-1', name: 'img-1', src : image2, alt:'Tevreden vrouw met laptop', class: 'story-editor__img--2', aspectRatio: '3/2'}
                    ]
                }
            }
            if(typeof this.section.body === 'string') this.section.body = JSON.parse(this.section.body);

        },
        openImageDialog(image) {
            this.dialog = true;
            this.image = image;
        },
        setNewImage(image) {
            let index = this.section.body.images.findIndex(i => i.class === image.class);
            this.section.body.images[index] = image;

            this.$emit('save-section', {
                id : 'id' in this.section ? this.section.id : null,
                sequence: this.sequence,
                component: this.section.component,
                title: this.section.title,
                subtitle: this.section.subtitle,
                body: JSON.stringify(this.section.body),
                text: this.section.text
            });
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
                '--url': `url(${this.section.body.background.src})`
            }
        },
        getImageStyle() {
            return (image) => {
                return ('aspectRatio' in image) ? { aspectRatio : image.aspectRatio} : {aspectRatio : 'auto'};
            }
        },
        getAlertColor() {
            return this.error ? 'red-me' : 'green-me';
        },
        getAlertMessage() {
            let message = '';
            if(this.error) message = this.error;
            if(this.success) message = this.success;
            return message;
        },
        getAlertType() {
            return this.error ? 'error' : 'success';
        },
    }
}
</script>

<style lang="scss" scoped>

    .story-editor {
        position: relative;
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
            height: 100%;
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
            position: relative;


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
