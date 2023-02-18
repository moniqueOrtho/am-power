<template>
    <v-dialog
        v-model="dialogState"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition">
      <v-overlay :value="loading">
        <v-progress-circular
          indeterminate
          size="64"
        ></v-progress-circular>
      </v-overlay>
      <v-card tile>

        <div class="image-dialog" :class="{fixed: edit}" v-if="dialogState" >
            <div class="image-dialog__sidebar accent" :class="edit ? 'image-dialog__sidebar--open' : 'image-dialog__sidebar--closed'">
                <div class="max-container">
                    <div class="d-flex justify-space-between">
                        <v-tooltip right>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    dark
                                    x-large
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="close"
                                >
                                    <v-icon>mdi-close</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ labels.close }}</span>
                        </v-tooltip>
                        <v-tooltip left v-if="imageChanged">
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    dark
                                    x-large
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="save"
                                    class="image-dialog__sidebar--save"
                                >
                                    <v-icon>mdi-content-save</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ labels.save }}</span>
                        </v-tooltip>
                    </div>
                    <div class="image-dialog__sidebar--container">
                        <div class="pa-3 image-dialog__sidebar--image">
                            <image-btn
                                :image="editedImg"
                                :optionsBtn="{iconSize: '4rem', text: labels.addImage, clickable: false}"
                                v-if="!edit"
                            ></image-btn>
                            <AdvancedCropper
                                v-else
                                :image="editedImg"
                                :resetImg="reset"
                                :upload="upload"
                                :stencil-props="getStencilProps"
                                @image-changed="imageStatus"
                                @edited-uploaded="editedImageUploaded"
                            />
                        </div>
                        <div class="text-center">
                            <text-btn
                                :text="edit ? labels.otherImage :labels.editImage"
                                :color="$vuetify.theme.themes.light.light1"
                                :hoverColor="$vuetify.theme.themes.light.primary"
                                size="1rem"
                                @do-action="editPicture(!edit)">
                            </text-btn>
                        </div>
                        <div class="px-3 py-12 " v-if="!edit">
                            <v-text-field

                                :label="labels.description"
                                v-model="editedImg.alt"
                                color="primary"
                                dark
                                :append-icon="editedImg.altChanged ? 'mdi-content-save': ''"
                                @click:append="updateImage"
                                @input="setUpdateBtn"
                            ></v-text-field>
                        </div>
                    </div>
                </div>


            </div>
            <div class="image-dialog__heading light1">
                <h6 class="text-h6 text-uppercase">{{ labels.ownImages }}</h6>
                <h6 class="text-h6 primary--text">{{ labels.chooseAnotherImg }}</h6>

                <div class="image-dialog__error-box">
                    <CloseAlert
                        alertColor="red-me"
                        :alertMessage="error"
                        type="error"
                        spacing="my-2 mx-2"
                    />
                </div>

            </div>

            <div class="image-dialog__images white">
                <div class="image-dialog__image" v-for="image in images" :key="image.name">
                    <curl-btn :obj="image" @curl-action="deleteImage" v-if="image.name !== editedImg.name && image.name !== 'file'"></curl-btn>
                    <image-btn
                        :image="image"
                        :optionsBtn="{iconSize: '4rem', text: labels.addImage, bgColor: image.name !== 'file' ? $vuetify.theme.themes.light.accent : 'transparent', clickable: image.name === editedImg.name ? false : true }"
                        @btn-clicked="allNewImgUploads"
                    ></image-btn>
                </div>

                <input type="file" ref="file" name="file" :style="{display: 'none'}" @change="getImageFile">
            </div>
        </div>
      </v-card>
    </v-dialog>
  </template>

  <script>

  import ImageBtn from '../buttons/ImageBtn.vue';
  import TextBtn from '../buttons/TextBtn.vue';
  import ImageMixin from '../../mixins/image.js';
  import MoveMixin from '../../mixins/move.js';
  import CurlBtn from '../buttons/CurlBtn.vue';
  import AdvancedCropper from '../form/AdvancedCropper.vue';
  import CloseAlert from '../alerts/CloseAlert.vue';
  export default {
    name: "image-dialog",
    components: { ImageBtn, TextBtn, CurlBtn, AdvancedCropper, CloseAlert },
    mixins: [ImageMixin, MoveMixin],
    props: {
        image: {
            type: Object,
            default: () => { return {id: 1, name: '', src: '', alt: ''} },
        },
        dialog: {
            type: Boolean,
            default: false,
        },
    },
    emits: ["dialog-closed", "set-image"],
    data() {
      return {
        editedImg: null,
        images: [],
        imageUpload: {id: 'file', name: 'file', src : '', alt:'', class: '', aspectRatio: 'auto'},
        newImage: {},
        refName: '',
        upload: false,
        reset: false,
        loadingDisabled: true,
        dialogState: false,
        edit: false,
        error: '',
        imageChanged: false,
        text: {
            close: 'Close',
            save: 'Save',
            addImage: 'Add Image',
            otherImage: 'Other image',
            editImage: 'Edit image',
            description: 'Description',
            ownImages: 'Own images',
            chooseAnotherImg: 'Choose another image',
            noImage: 'No image has been selected!',
            noRightImgSize: 'The image is larger than 2MB!'
        }
      };
    },
    methods: {
        init() {
            this.images = [];
            this.newImage = {};
            this.refName = '';
            this.upload = false;
            this.reset= false;
            this.edit = false;
            this.error = '';
            this.imageChanged = false;
            this.loading = false;
        },
        editPicture(value) {
            this.edit = value;
            this.reset = !value;
            if(!value && this.imageChanged && this.editedImg.id === this.image.id) this.imageChanged = false;
        },
        allNewImgUploads(image) {
            (image.name ==='file') ? this.activateFileUpload(image.name) : this.changePicture(image);
        },
        activateFileUpload(name) {
            this.refName = name;
            this.newImage = {};
            this.$refs[this.refName].click();
        },
        changePicture(image) {
            this.imageChanged =  this.image.src === image.src ? false : true;
            this.editedImg = Object.assign({}, image);
            this.setImage(image);
        },
        getImageFile(e) {
            const {files} = e.target;
            if( files && files[0]) {
                let validated = this.validateImage(files[0]);
                if(validated) {
                    if (this.newImage.src) {
                        this.deleteUrl(this.newImage.src);
                    }
                    this.prepareNewImage(files[0]);
                } else {
                    this.emptyFileInput(this.refName);
                }
            }
        },
        prepareNewImage(file) {
            let form = new FormData();
            form.append('image', file);
            this.uploadImage(form, this.refName);
        },
        transformData(data, makeEditable = false) {
            let image =  {
                    id: data.id, name: data.name, src : data.sizes.original, alt : data.alt, class: this.editedImg.class,
                    altChanged: false, aspectRatio: 'aspectRatio' in this.editedImg ? this.editedImg.aspectRatio : 'auto'
                }
            this.setImage(image);
            if(makeEditable) this.editedImg = image;
        },
        setImage(image) {
            let newImage = Object.assign({}, image), from = this.images.findIndex(x => x.id === image.id);
            if (from > -1) {
                this.arrayMove(this.images, from, 1)
            } else {
                this.arrayAddTo(this.images, newImage, 1);
            }
        },
        async deleteImage(image) {
            try {
                await axios.delete(`/images/${image.id}`);
                this.removeFormImages(image.id)
            } catch (error) {
                console.log(error)
                this.error = error.message
            }
        },
        removeFormImages(id) {
            this.images = this.images.filter(x => x.id !== id );
            this.$store.dispatch('images/deleteImage', id);
        },
        setUpdateBtn() {
            if(!this.editedImg.altChanged) this.editedImg.altChanged = true;
        },
        async updateImage() {
            try {
                const updated =  await axios.put(`/images/${this.editedImg.id}`, this.editedImg);
                let index = this.images.findIndex(x => x.id === updated.data.data.id);
                this.editedImg.altChanged = false;
                this.images[index].alt = updated.data.data.alt;
                this.$store.dispatch('images/updateImage', updated.data.data);
            } catch (error) {
                console.log(error)
                this.error = error.message
            }

        },
        setNewImage(image) {
            this.$emit('set-image', image);
            this.$emit('dialog-closed');
            if(this.upload) this.upload = false;
        },
        imageStatus(changed) {
            this.imageChanged = changed;
        },
        save() {
            if(this.edit && this.imageChanged) {
                this.loading = true;
                this.upload = true;
            } else {
                this.setNewImage(this.editedImg);
            }
        },
        editedImageUploaded(image) {
            this.setNewImage(image);
            this.init();
        },
        setSuccesMessage(message) {
            this.loading = false;
            this.loadingDisabled = true;
            this.$emit('succes-message', message);
            this.close();
        },
        close() {
            this.edit = false;
            this.imageChanged =  this.image.src === this.editedImg.src ? false : true;
            this.$emit('dialog-closed');
        }
    },
    computed: {
        labels() {
            const labels = this.$store.getters['labels/getTranslations'];
            if(labels && Object.keys(labels) !== 0 && Object.getPrototypeOf(labels) === Object.prototype) {
                let keys = Object.keys(this.text);
                keys.forEach(k => {
                    if(k in labels) this.text[k] = labels[k]
                })
            }
            return this.text;
        },
        getStencilProps() {
            if('aspectRatio' in this.image) {
                let arr = this.image.aspectRatio.split('/');
                return {aspectRatio: arr[0]/arr[1]}
            } else {
                return {movable: true}
            }
        }
    },

    watch: {
        dialog(value) {
            this.dialogState = value;
            this.images.push(this.imageUpload);
            if(value) {
                this.editedImg = Object.assign({}, this.image);
                const ownImages = this.$store.getters['images/getImages'];
                ownImages.forEach(i => {
                    if(i.id !== this.editedImg.id) {
                        this.transformData(i)
                    }
                })
                this.setImage(this.editedImg);
            } else {
                this.imageChanged = false;
                this.images = [];
            }
        }
    }
  };
  </script>

  <style lang="scss" scoped>
    .image-dialog {
        display: grid;
        grid-template-rows: 15vh minmax(85vh, min-content);
        grid-template-columns: repeat(8, 1fr);
        min-height: 100vh;

        &__sidebar {
            grid-row: 1/3;
            grid-column: 1/span 2;
            z-index: 100;
            transition: width cubic-bezier(0, 0.52, 1, 1) .4s;
            &--open {
                width: 100vw;
                            }
            &--closed {
                width: 100%;
            }
            &--save {
                grid-column: 2/3;
                grid-row: 1/2;
                justify-self: end;
            }

            &--container {
                grid-column: 1/3;

            }

            &--image {
                aspect-ratio: 3/2;
            }
        }
        &__heading {
            grid-column: 3/9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 0 2rem;
            position: relative;
        }
        &__error-box {
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            display: flex;
            align-items: center;
        }

        &__images {
            grid-column: 3/9;
            padding: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fill, 12rem);
            grid-auto-rows: 12rem;
            grid-row-gap: 1.5rem;
            grid-column-gap: 1rem;
        }

        &__image {
            position: relative;
        }
    }
    .fixed {
        position: fixed;
    }

  </style>
