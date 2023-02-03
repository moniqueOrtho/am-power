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
        <!-- <v-toolbar
          dark
          color="primary"
        >
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
            <v-spacer></v-spacer>
            <v-tooltip left>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                        icon
                        dark
                        x-large
                        v-bind="attrs"
                        v-on="on"
                        @click="uploadImage"
                    >
                        <v-icon>mdi-content-save</v-icon>
                    </v-btn>
                </template>
                <span>{{ labels.save }}</span>
            </v-tooltip>
        </v-toolbar> -->
        <div class="image-dialog">
            <div class="image-dialog__sidebar primary" ref="sidebar">
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
                <div class="image-dialog__sidebar--container">
                    <div class="image-dialog__sidebar--image" :style="{height : `${this.sidebarWidth - 36}px`}" >
                        <image-btn
                            :image="{id: 1, src: '', alt: ''}"
                            :optionsBtn="{iconSize: '4rem'}"
                        ></image-btn>
                    </div>

                    <v-btn text dark class="px-4">Edit image</v-btn>


                    <!-- <div class="image-dialog__sidebar--menu">
                        <div class="navigation__item navigation__item--nav">
                            <button class="navigation__link--btn">Edit image</button>
                        </div>
                    </div> -->
                </div>


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
  export default {
    name: "cropper-dialog",
    components: { ImageBtn },
    props: {
        labels: {
            type: Object,
            default: () => {
                return {
                    back: 'Back',
                    save: 'Save',
                }
            }
        },
    //   image: {
    //     type: Object,
    //     required: true,
    //   },
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
    mounted() {
        this.ro = new ResizeObserver(this.onResize);
        this.ro.observe(this.$refs.sidebar)
    },
    beforeDestroy() {
        this.ro.unobserve(this.$refs.sidebar)
    },
    data() {
      return {
        upload: false,
        loading: false,
        loadingDisabled: true,
        dialogState: true,
        sidebarWidth: null,
        ro: null,
      };
    },
    methods: {
        onResize() {
            this.$emit('resize', this.$refs.sidebar.clientWidth)
            this.sidebarWidth = this.$refs.sidebar.clientWidth;
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
            grid-column: 1/span 2;
            grid-row: 1/3;
            display: grid;
            grid-template-rows: min-content;
            grid-template-columns: 1fr 1fr;
            align-items: start;
            &--save {
                grid-column: 2/3;
                grid-row: 1/2;
                justify-self: end;
            }

            &--container {
                grid-column: 1/3;
                display: flex;
                flex-direction: column;
                justify-content: center;
                height: 100%;
            }

            &--image {
                width: 100%;
            }
            &--menu {
                padding: 0 18px;
            }
        }
        &__heading {
            grid-column: 3/9;
        }
    }

  </style>
