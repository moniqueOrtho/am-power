<template>
    <div class="advanced-cropper" ref="background">
        <div class="advanced-cropper__error-wrapper">
            <CloseAlert
            alertColor="red-me"
            :alertMessage="error"
            type="error"
            spacing="my-2 mx-2"
            class="pa-4 advanced-cropper__error-box"
            />
        </div>
        <img :src="cropperImage.src" class="advanced-cropper__bg" v-if="!changed">

        <cropper
            class="advanced-cropper__cropper"
            :src="cropperImage.src"
            :transitions="true"
			:image-restriction="restrictionType"
            ref="cropper"
            :stencil-props="stencilProps"
        />
        <vertical-buttons>
            <v-tooltip right v-for="action in actions" :key="action.name">
                <template v-slot:activator="{ on, props }">
                    <square-button
                        :title="action.title"
                        @click="operation(action)"
                        v-on="on"
                        v-bind="props"
                    >
                        <v-icon color="white">{{ action.icon }}</v-icon>
                    </square-button>
                </template>
                <span>{{ action.title }}</span>
            </v-tooltip>

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
  import ImageMixin from "../../mixins/image.js";

  export default {
    name: 'advanced-cropper',
    components: { Cropper, EditorMenu, CloseAlert, VerticalButtons, SquareButton },
    mixins: [ ImageMixin ],
    props: {
      image: {
        type: Object,
        required: true,
      },
      stencilProps: {
        type: Object,
        default: () => {{}},// Square stencil : {aspectRatio: 1/1}
      },
      resetImg: {
        type: Boolean,
        default: false
      },
      upload: {
        type: Boolean,
        default: false
      },
    },
    emits: ['image-changed'],
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
        changed: false,
        cropped: false,
        error: '',
        text: {
            crop: 'Crop',
            flipHorizontal: 'Flip horizontal',
            flipVertical: 'Flip vertical',
            rotateClockwise: 'Rotate clockwise',
            rotateCounter: 'Rotate counter clockwise',
            reset: 'Reset'
        }
      };
    },
    methods: {
        setImageData(data) {
            this.cropperImage = Object.assign({}, data);
        },
        operation(action) {
            this.change();
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
                case 'reset':
                    this.reset()
                    break;

                default:
                    break;
            }
        },
        crop() {
            const { coordinates, canvas, } = this.$refs.cropper.getResult();
			this.coordinates = coordinates;
            this.deleteUrl(this.cropperImage.src);
            this.cropperImage.src = canvas.toDataURL();
            this.cropped = true;
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
        reset(parent = false) {
            this.changed = false;
            if(!parent) this.$emit('image-changed', false);
            if(this.cropped) {
                this.deleteUrl(this.cropperImage.src);
                this.cropperImage = Object.assign({}, this.image);
                this.coordinates = {
                    width: 0,
                    height: 0,
                    left: 0,
                    top: 0,
                }
                this.cropped = false;
            } else {
                this.$refs.cropper.reset();
            }

            this.error = '';
        },
		download() {
			//const result = this.$refs.cropper.getResult().canvas.toDataURL();


		},
		change() {
            if(!this.changed) {
                this.changed = true;
                this.$emit('image-changed', true)
            }
		},

    },
    watch: {
        resetImg(value) {
            this.reset(true);
        },
        upload(value) {
            if(value) this.prepareUpload();
        },
        image(value) {
            this.setImageData(value);
        }
    },
    computed: {
        actions() {
            let actions = [
                {name: 'crop', title: this.labels.crop, icon: 'mdi-crop'},
                {name: 'flip-horizontal', title: this.labels.flipHorizontal, icon: 'mdi-flip-horizontal'},
                {name: 'flip-vertical', title: this.labels.flipVertical, icon: 'mdi-flip-vertical'},
                {name: 'rotate-right', title: this.labels.rotateClockwise, icon: 'mdi-reload'},
                {name: 'rotate-left', title: this.labels.rotateCounter, icon: 'mdi-restore'},
            ];
            if(this.changed) actions.push({name: 'reset', title: this.labels.reset, icon: 'mdi-image-off'});
            return actions
        },
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
  };
  </script>

  <style lang="scss" scoped>

  .advanced-cropper {
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
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
