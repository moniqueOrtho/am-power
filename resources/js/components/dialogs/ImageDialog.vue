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

        <div class="image-dialog">
            <div class="image-dialog__sidebar accent" :class="edit ? 'image-dialog__sidebar--open' : 'image-dialog__sidebar--closed'">
                <v-container>
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
                            <span>{{ labels.back }}</span>
                        </v-tooltip>
                        <v-tooltip left>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    icon
                                    dark
                                    x-large
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="uploadImage"
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
                                :optionsBtn="{iconSize: '4rem', text: labels.addImage}"
                            ></image-btn>
                        </div>
                        <div class="text-center">
                            <text-btn
                                :text="edit ? labels.otherImage :labels.editImage"
                                :color="$vuetify.theme.themes.light.light1"
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
                </v-container>


            </div>
            <div class="image-dialog__heading light1">heading</div>
            <div class="image-dialog__images white">images</div>
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
  export default {
    name: "image-dialog",
    components: { ImageBtn, TextBtn },
    props: {
        labels: {
            type: Object,
            default: () => {
                return {
                    back: 'Back',
                    save: 'Save',
                    editImage: 'Edit Image',
                    ownImages: 'Own Image',
                    addImage: 'Add image',
                    otherImage: 'Other image',
                    description: 'Description'
                }
            }
        },
      image: {
        type: Object,
        default: () => { return {id: 1, src: '', alt: ''} },
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
        upload: false,
        loading: false,
        loadingDisabled: true,
        dialogState: false,
        edit: false
      };
    },
    methods: {

        editPicture(value) {
            this.edit = value;
        },
        uploadImage() {
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

    watch: {
        dialog(value) {
            this.dialogState = value;
            if(value) {
                this.editedImg = Object.assign({}, this.image);
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
        }
    }

  </style>
