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

        <div class="image-dialog" v-if="dialogState">
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
                        <v-tooltip left>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    dark
                                    x-large
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="uploadEditedImage"
                                    class="image-dialog__sidebar--save"
                                >
                                    <v-icon>mdi-content-save</v-icon>
                                </v-btn>
                            </template>
                            <span>{{ labels.save }}</span>
                        </v-tooltip>
                    </div>
                    <div class="image-dialog__sidebar--container">
                        <div class="pa-3">
                            <image-btn
                                :image="editedImg"
                                :optionsBtn="{iconSize: '4rem', text: labels.addImage, clickable: false}"
                            ></image-btn>
                        </div>
                        <div class="text-center">
                            <text-btn
                                :text="edit ? labels.otherImage :labels.editImage"
                                :color="$vuetify.theme.themes.light.light1"
                                :hoverColor="$vuetify.theme.themes.light.primary"
                                size="1rem"
                                @do-action="edit = !edit">
                            </text-btn>
                        </div>
                        <div class="px-3 py-12 ">
                            <v-text-field
                                :label="labels.description"
                                v-model="editedImg.alt"
                                color="primary"
                                dark
                                v-if="!edit && dialogState"
                            ></v-text-field>
                        </div>
                    </div>
                </div>


            </div>
            <div class="image-dialog__heading light1">
                <h6 class="text-h6 text-uppercase">{{ labels.ownImages }}</h6>
                <h6 class="text-h6 primary--text">{{ labels.chooseAnotherImg }}</h6>
            </div>
            <div class="image-dialog__images white">
                <image-btn
                    v-for="image in images"
                    :key="image.name"
                    :image="image"
                    :optionsBtn="{iconSize: '4rem', text: labels.addImage, bgColor: image.name !== 'file' ? $vuetify.theme.themes.light.accent : 'transparent', clickable: image.name === editedImg.name ? false : true }"
                    @btn-clicked="allNewImgUploads"
                ></image-btn>
                <input type="file" ref="file" name="file" :style="{display: 'none'}" @change="getImageFile">
            </div>
        </div>
        <!-- <h6 class="text-h6 text-uppercase primary--text pa-5">
          Plaatje toevoegen
        </h6>
        <div class="image__container d-flex justify-center align-center grey1 lighten-2">
          <v-card class="pa-3 ma-8 image__card images__wrapper--square" elevation="4">
                <p>Hier komt het plaatje</p>
                <AdvancedCropper
                :image="image"
                :autoZoom="autoZoom"
                :stencilProps="stencilProps"
                :stencilSize="stencilSize"
                :route="route"
                :upload="upload"
                :model="model"
                @enable-loading="loadingDisabled = false"
                @succes-message="setSuccesMessage"
                @error-upload="loading = false"
                @undo-upload="upload = false"
                />
          </v-card>
        </div> -->
      </v-card>
    </v-dialog>
  </template>

  <script>
  //import AdvancedCropper from '../form/AdvancedCropper.vue';
  import ImageBtn from '../buttons/ImageBtn.vue';
  import TextBtn from '../buttons/TextBtn.vue';
  import ImageMixin from '../../mixins/image.js';
  export default {
    name: "image-dialog",
    components: { ImageBtn, TextBtn },
    mixins: [ImageMixin],
    props: {
        image: {
            type: Object,
            default: () => { return {id: 1, name: '', src: '', alt: ''} },
        },
        dialog: {
            type: Boolean,
            default: false,
        },
    //   autoZoom: {
    //     type: Boolean,
    //     default: false,
    //   },
    //   stencilProps: {
    //     type: Object,
    //     required: false,
    //   },
    //   stencilSize: {
    //     type: Object,
    //     required: false,
    //   },
    //   route: {
    //     type: String,
    //     required: true
    //   },
    //   model: {
    //     type: Function,
    //     required: true
    //   }
    },
    emits: ["dialog-closed"],
    data() {
      return {
        editedImg: null,
        images: [{id: 'file', name: 'file', src : '', alt:'', class: ''}],
        newImage: {},
        refName: '',
        upload: false,
        loadingDisabled: true,
        dialogState: false,
        edit: false,
        error: null,
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
        editPicture(value) {
            this.edit = value;
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
            this.editedImg = image;
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
        setNewImage(image) {
            console.log(image)
            let newImage = Object.assign({}, image);
            newImage.src = image.src.original;
            newImage.class = this.image.class;
            this.editedImg = newImage;
            this.images.push(image)
        },

        uploadEditedImage() {
            this.loading = true;
            this.upload = true;
            this.loadingDisabled = true;
        },
        setSuccesMessage(message) {
            this.loading = false;
            this.loadingDisabled = true;
            this.$emit('succes-message', message);
            this.close();
        },
        close() {
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
    },

    watch: {
        dialog(value) {
            this.dialogState = value;
            if(value) {
                this.editedImg = Object.assign({}, this.image);
                this.setNewImage(this.editedImg);
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
        }
        &__heading {
            grid-column: 3/9;
            display: flex;
            flex-direction: column;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 0 2rem;
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
    }

  </style>
