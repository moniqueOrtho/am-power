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
        <v-toolbar
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
        </v-toolbar>
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
  export default {
    name: "cropper-dialog",
    //components: { AdvancedCropper },
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
    data() {
      return {
        upload: false,
        loading: false,
        loadingDisabled: true,
        dialogState: false
      };
    },
    methods: {
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
    .image {
      &__card {
        width: 50%;
      }

    }

  </style>
