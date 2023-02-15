<template>
    <div class="advanced-cropper">
        <!-- <div class="advanced-cropper__error-wrapper">
            <CloseAlert
            alertColor="red-me"
            :alertMessage="error"
            type="error"
            spacing="my-2 mx-2"
            class="pa-4 advanced-cropper__error-box"
            />
        </div> -->
      <img :src="image.src" class="advanced-cropper__bg">


        <cropper
            class="advanced-cropper__cropper"
            :src="image.src"
            @change="change"
            :transitions="true"
			image-restriction="fit-area"
            ref="cropper"
        />
        <vertical-buttons>
			<square-button :title="action.title"  v-for="action in actions" :key="action.name">
				<v-icon color="white">{{ action.icon }}</v-icon>
			</square-button>
		</vertical-buttons>
        <!-- <cropper
          class="cropper"
          :src="imageEdited.src"
          :auto-zoom="autoZoom"
          :stencil-props="stencilProps"
          :stencil-size="stencilSize"
          :image-restriction="restrictionType"
          ref="cropper"
        /> -->

    </div>
  </template>

  <script>
  import { Cropper } from "vue-advanced-cropper";
  import "vue-advanced-cropper/dist/style.css";
  import EditorMenu from "../tools/EditorMenu.vue";
  import CloseAlert from "../alerts/CloseAlert.vue";
  import VerticalButtons from "../buttons/VerticalButtons.vue";
  import SquareButton from "../buttons/SquareButton.vue";

  export default {
    name: 'advanced-cropper',
    components: { Cropper, EditorMenu, CloseAlert, VerticalButtons, SquareButton },
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
        type: [Object, Boolean],
        default: false,
      },
      stencilSize: {
        type: [Object, Boolean],
        default: false,
      },
      upload: {
        type: Boolean,
        default: false
      },
    },
    emits: ['enable-loading', 'succes-message', 'error-upload', 'undo-upload'],
    // created() {
    //   this.setImageData();
    // },
    data() {
      return {
        imageEdited: null,
        restrictionType: 'fill-area',

        error: ''
      };
    },
    methods: {

        flip(x, y) {
            // const { image } = this.$refs.cropper;
            // console.log(image)
			//const { image } = this.$refs.cropper.getResult();
			// if (image.transforms.rotate % 180 !== 0) {
			// 	this.$refs.cropper.flip(!x, !y);
			// } else {
			// 	this.$refs.cropper.flip(x, y);
			// }
		},
		rotate(angle) {
			//this.$refs.cropper.rotate(angle);
		},
		download() {
			//const result = this.$refs.cropper.getResult().canvas.toDataURL();


		},
		change(args) {
			console.log(args);
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
    computed: {
        actions() {
            return [
                {name: 'flip-horizontal', title: 'Flip Horizontal', icon: 'mdi-flip-horizontal', method: this.flip(true, false)},
                {name: 'flip-vertical', title: 'Flip Vertical', icon: 'mdi-flip-vertical', method: this.flip(false, true)},
                {name: 'rotate-right', title: 'Rotate Clockwise', icon: 'mdi-reload', method: this.rotate(90)},
                {name: 'rotate-left', title: 'Rotate Counter-Clockwise', icon: 'mdi-restore', method: this.rotate(-90)},
            ];
        }
    },
  };
  </script>

  <style lang="scss" scoped>

  .advanced-cropper {
    position: relative;
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
    &__bg {
        width: 100%;
        height: 100%;
        z-index: 5;
    }

    &__cropper {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 10;
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
