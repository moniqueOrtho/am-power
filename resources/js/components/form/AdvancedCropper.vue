<template>
    <div class="advanced-cropper">
      <div class="advanced-cropper__error-wrapper">
        <CloseAlert
          alertColor="red-me"
          :alertMessage="error"
          type="error"
          spacing="my-2 mx-2"
          class="pa-4 advanced-cropper__error-box"
        />
      </div>
      <div class="advanced-cropper__image-box">
        <cropper
          class="cropper"
          :src="imageEdited.src"
          :auto-zoom="autoZoom"
          :stencil-props="stencilProps"
          :stencil-size="stencilSize"
          :image-restriction="restrictionType"
          ref="cropper"
        />
      </div>
    </div>
  </template>

  <script>
  import { Cropper } from "vue-advanced-cropper";
  import "vue-advanced-cropper/dist/style.css";
  import EditorMenu from "../tools/EditorMenu.vue";
  import CloseAlert from "../alerts/CloseAlert.vue";

  export default {
    name: 'advanced-cropper',
    components: { Cropper, EditorMenu, CloseAlert },
    props: {
      image: {
        type: Object,
        required: true,
      },
      autoZoom: {
        type: Boolean,
        default: false,
      },
      stencilProps: {
        type: Object,
        required: false,
      },
      stencilSize: {
        type: Object,
        required: false,
      },
      route: {
        type: String,
        required: true
      },
      upload: {
        type: Boolean,
        default: false
      },
      model: {
        type: Function,
        required: true
      }
    },
    emits: ['enable-loading', 'succes-message', 'error-upload', 'undo-upload'],
    created() {
      this.setImageData();
    },
    data() {
      return {
        imageEdited: null,
        rotate: false,
        restrictionType: 'fill-area',
        actions: [],
        error: ''
      };
    },
    methods: {
      init() {
        this.rotate = false;
        this.restrictionType = 'fill-area';
      },
      setImageData() {
        this.imageEdited = this.image;
      },
      exitAction(type) {
        if(type === 'rotate') this.rotate = false;
      },

    },
    watch: {
      restrictionType(value) {
        const index = this.tabsEdit.findIndex(item => item.name === 'stencil-free');
        if(value === 'none') {
          this.changeArrayWithIndex(this.tabsEdit, index, { name: 'stencil-free', icon: 'mdi-crop', color: 'accent lighten-1', tooltip: this.$t('Crop') })
        } else {
          this.changeArrayWithIndex(this.tabsEdit, index, { name: 'stencil-free', icon: 'mdi-crop-free', color: 'accent lighten-1', tooltip: this.$t('Free stencil') })
        }
      },
      upload(value) {
        if(value) this.prepareUpload();
      },
      image() {
        this.setImageData();
      }
    },
    computed: {},
  };
  </script>

  <style lang="scss" scoped>

  .advanced-cropper {
    &__error-wrapper {
      position: relative;
      margin: 0 8px;
      display: flex;
      justify-content: center;
      z-index: 100;
    }
    &__error-box {
      position: absolute;
      width: 100%;
    }

    &__image-box {
      outline: 2px solid var(--v-grey1-darken2);
      width: 95%;
      height: 95%;
      position: absolute;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
    }

    &__speed-dial {
      position: absolute;
      left: 24px;
      top: 24px;
    }
  }
  .cropper {
    background: var(--v-grey1-base);
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
  }
  </style>
