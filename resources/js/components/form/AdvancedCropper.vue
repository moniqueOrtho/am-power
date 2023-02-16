<template>
    <div class="advanced-cropper" ref="background">
        <!-- <div class="advanced-cropper__error-wrapper">
            <CloseAlert
            alertColor="red-me"
            :alertMessage="error"
            type="error"
            spacing="my-2 mx-2"
            class="pa-4 advanced-cropper__error-box"
            />
        </div> -->
      <!-- <img :src="cropperImage.src" class="advanced-cropper__bg" :style="setBgSize"> -->


        <cropper
            class="advanced-cropper__cropper"
            :src="cropperImage.src"
            :transitions="true"
			:image-restriction="restrictionType"
            ref="cropper"
            :stencil-props="stencilProps"
        />
        <vertical-buttons>
			<square-button :title="action.title" @click="operation(action)" v-for="action in actions" :key="action.name">
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
        type: Object,
        default: () => {{}},// Square stencil : {aspectRatio: 1/1}
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
    created() {
      this.setImageData(this.image);
    },
    data() {
      return {
        restrictionType: 'fill-area',
        cropperImage: null,
        coordinates: {
            width: 0,
            height: 0,
            left: 0,
            top: 0,
        },
        error: ''
      };
    },
    methods: {
        setImageData(data) {
            this.cropperImage = data;
        },
        operation(action) {
            switch (action.name) {
                case 'crop':
                    this.crop()
                    break;
                case 'flip-horizontal':
                    this.flip(true, false)
                    break;
                case 'flip-vertical':
                    this.flip(false, true)
                    break;
                case 'rotate-right':
                    this.rotate(90)
                    break;
                case 'rotate-left':
                    this.rotate(-90)
                    break;

                default:
                    break;
            }
        },
        crop() {
            const { coordinates, canvas, } = this.$refs.cropper.getResult();
            console.log(coordinates)
			this.coordinates = coordinates;
            this.cropperImage.src = canvas.toDataURL();

        },
        flip(x, y) {
            const image = this.$refs.cropper.getResult();
            if (image.image.transforms.rotate % 180 !== 0) {
                this.$refs.cropper.flip(!x, !y);
            } else {
                this.$refs.cropper.flip(x, y);
            }
		},
		rotate(angle) {
			this.$refs.cropper.rotate(angle);
		},
		download() {
			//const result = this.$refs.cropper.getResult().canvas.toDataURL();


		},
		change(args) {
			//console.log(args)
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
                {name: 'crop', title: 'Crop', icon: 'mdi-crop'},
                {name: 'flip-horizontal', title: 'Flip Horizontal', icon: 'mdi-flip-horizontal'},
                {name: 'flip-vertical', title: 'Flip Vertical', icon: 'mdi-flip-vertical'},
                {name: 'rotate-right', title: 'Rotate Clockwise', icon: 'mdi-reload'},
                {name: 'rotate-left', title: 'Rotate Counter-Clockwise', icon: 'mdi-restore'},
            ];
        },
        // setBgSize() {
        //     let obj = {};

        //     if(this.coordinates.width > 0 && this.$refs.background.clientWidth ) {
        //         obj.width = `${this.coordinates.width}px`,
        //         obj.height = `${this.coordinates.height}px`
        //     }
        //     return obj;
        // }
    },
  };
  </script>

  <style lang="scss" scoped>

  .advanced-cropper {
    position: relative;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px dotted white;

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
        object-fit: contain;
    }

    &__cropper {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 10;
        transform: translate(-50%, -50%);
    }

  }
  </style>
