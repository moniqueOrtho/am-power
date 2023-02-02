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

      <div class="advanced-cropper__speed-dial" v-if="imageEdited.src">
        <editor-menu
        :container="false"
        direction="left"
        :actions="actions"
        :position="{top: true, right: true, bottom: false, left: false}"
        @big-activator="bigActivatorClicked"
        @action-btn="actionBtnClicked"
        :labels="labels"
        ></editor-menu>
      </div>
      <input
        type="file"
        ref="file"
        style="display: none"
        name="image"
        @change="loadImage($event)"
      />

      <ImageBtn
        v-if="!edit"
        :image="{
          id: imageEdited.id,
          title: item.title,
          src: imageEdited.src,
        }"
        :optionsBtn="{
          text: $t('Select image'),
          icon: 'mdi-image-search',
          size: { width: '95%', height: '95%' },
          iconSize: '8vh',
          bgColor: 'var(--v-grey1-base)'
        }"
        :spdOptions="{
          top: true,
          right: true,
          transition: 'slide-y-transition',
        }"
        :spdBtnTabs="[
          { name: 'edit', icon: 'mdi-pencil', color: 'accent' },
          { name: 'delete', icon: 'mdi-delete', color: 'error' },
        ]"
        @btn-clicked="ImageBtnClicked"
      />
      <div class="advanced-cropper__image-box" v-else>
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
  import ImageBtn from "../buttons/ImageBtn.vue";

  export default {
    name: 'advanced-cropper',
    components: { Cropper, EditorMenu, CloseAlert, ImageBtn },
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
        item: {},
        imageEdited: null,
        edit: false,
        rotate: false,
        restrictionType: 'fill-area',
        actions: [],
        error: ''
      };
    },
    methods: {
      init() {
        this.edit = false;
        this.rotate = false;
        this.restrictionType = 'fill-area';
      },
      setImageData() {
        this.imageEdited = this.image;
      },
      ImageBtnClicked() {
        this.$refs.file.click();
      },
      deleteUrl() {
        if (this.imageEdited.src) {
          URL.revokeObjectURL(this.imageEdited.src);
        }
      },
      emptyFileInput() {
        this.$refs.file.value = "";
        this.$emit('undo-upload');
      },
      loadImage(event) {
        const { files } = event.target;
        if (files && files[0]) {
          let validated = this.validateImage(files[0]);
          if(validated) {
            this.$emit('enable-loading', true);
            this.deleteUrl();
            this.setNewImage(files[0]);
          } else {
            this.emptyFileInput();
          }
        }
      },
      exitAction(type) {
        if(type === 'crop') this.edit = false;
        if(type === 'rotate') this.rotate = false;
      },
      editImage() {
        this.edit = true;
      },
      deleteImage() {
        this.imageEdited.src = null;
        delete this.imageEdited.type;
        this.deleteUrl();
        this.init();
      },
      prepareUpload() {
        let form;
        if(this.edit) {
          const { canvas } = this.$refs.cropper.getResult();
          if(canvas) {
            form = new FormData();
            canvas.toBlob(blob => {
              form.append('image', blob);
              this.uploadImage(this.route, form, this.image.id, this.model);
            }, this.imageEdited.type );
          }
          this.edit = false;
        } else {
          const file = this.$refs.file.files[0];
          let validated = this.validateImage(file);
          if(validated) {
            form = new FormData();
            form.append('image', file);
            this.uploadImage(this.route, form, this.image.id, this.model);
          }
        }
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
      // image() {
      //   if (
      //     JSON.stringify(this.image) !== "{}" &&
      //     this.image.action &&
      //     this.image.action === "btnClicked"
      //   )
      //     this.$refs.file.click();
      // },
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
