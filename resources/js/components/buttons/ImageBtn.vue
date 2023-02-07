<template>
    <div class="image-btn" :style="styleImageBox">


        <img :src="image.src " :alt="image.alt" class="image-btn__image" v-if="image.src && !options.clickable" />
        <img :src="image.src " :alt="image.alt" class="image-btn__image" v-else-if="image.src && options.clickable" @click="imageBoxClicked" />

        <div class="noImage" v-else @click="activateFileUpload">
            <v-icon class="image-btn__icon grey1--text text--light2 " :style="{'font-size' : options.iconSize}">
                {{ options.icon }}
            </v-icon>
            <p class="mt-2">{{ options.text }}</p>
            <input type="file" :ref="image.name" :name="image.name" :style="{display: 'none'}" @change="loadImage($event)">
        </div>

    </div>
  </template>

  <script>
  export default {
    name: "image-btn",
    props: {
      image : {
        // Required keys are: src, id, alt,
        type: Object,
        required: true
      },
      optionsBtn : {
        // See computed options
        type: Object,
        required: false
      },
    },
    emits: ['btn-clicked', 'image-upload'],
    data() {
      return {
        error: null,
        text: {
            noImage: 'No image has been selected!',
            noRightImgSize: 'The image is larger than 2MB!'
        }
      }
    },
    methods: {
      imageBoxClicked() {
        this.$emit("btn-clicked", this.image);
      },
      activateFileUpload() {
        console.log(this.image.name)
        this.$refs[this.image.name].click();
        this.error = null;
      },
      loadImage(e) {
        const {files} = e.target;
        if( files && files[0]) {
          let validated = this.validateImage(files[0]);
          if(validated) {
            this.setNewImage(files[0]);
          }
        }
      },
      validateImage(file) {
      // Is there a file?
        if(!file) {
          this.error = this.labels.noImage;
          return false;
        }
        // Validate type gif|jpg|png|jpeg|bmp
        let isGood = /\.(?=gif|jpg|png|jpeg|bmp)/gi.test(file.name);
        if(!isGood) {
          this.error = this.labels.noImage;
          return false;
        }
        // Validate size. Size must be smaller then 2mb
        if(file.size > 2000000) {
          this.error = this.labels.noRightImgSize;
          return false;
        }
        return true;
      },
      setNewImage(file) {
        let newImage = {};
        const blob = URL.createObjectURL(file);
        // Create a new FileReader to read this image binary data
        const reader = new FileReader();
        // Define a callback function to run, when FileReader finishes its job
        reader.onload = (e) => {
            // Note: arrow function used here, so that "this.imageEdited" refers to the image of Vue component
            newImage.name = file.name
            newImage.src = blob;
            newImage.alt = file.name
            // this.imageEdited['type'] = this.getMimeType(e.target.result, file.type);
        };
        // Start the reader job - read file as a data url (base64 format)
        reader.readAsArrayBuffer(file);
        console.log(file)
        this.$emit('image-upload', newImage, this.image.name)
    },

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
        styleImageBox() {
            let imageDotted = {
                    outline: "2px dotted var(--v-grey1-base)",
                    cursor: 'pointer'
                    }
                let imageLines = {
                    outline: "2px solid var(--v-grey1-darken2)",
                    'background-color': this.options.bgColor,
                    cursor: this.options.clickable ? 'pointer' : 'cursor'
                    }
                return this.image.src ? imageLines : imageDotted;
        },
        imageSrc() {
            return this.image.src;
        },
        options() {
            let options = {
                text: 'Add image',
                icon: 'mdi-image-plus',
                bgColor: 'transparent',
                iconsize: '4vh',
                clickable: true
            }, keys;
            if(this.optionsBtn) {
                keys = Object.keys(this.optionsBtn);
                keys.forEach(k => options[k] = this.optionsBtn[k]);
            }
            return options;
        }
    },
  };
  </script>

  <style lang="scss" scoped>
    .image-btn {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-content: center;

        &__image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }


      &__actions-btn {
        height: 34px;
        width: 34px;
        border-radius: 50%;
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        bottom: -17px;
        box-shadow: 0 3px 5px -1px rgba(0, 0, 0, 0.2),
          0 5px 8px 0 rgba(0, 0, 0, 0.14), 0 1px 14px 0 rgba(0, 0, 0, 0.12);
        &.edit {
          right: 44px;
          background-color: var(--v-accent-base);
          transition: all 0.5s ease;
          &:hover {
            background-color: var(--v-accent-lighten2);
          }
        }
        &.delete {
          right: 3px;
          background-color: var(--v-error-base);
          transition: all 0.5s ease;
          &:hover {
            background-color: var(--v-error-lighten2);
          }
        }
      }
      &__actions-icon {
        font-size: 1.125rem;
        color: var(--v-light1-base);
      }

      &__name {
        font-size: 15px;
        color: var(--v-grey1-base);
      }
    }
    .noImage {
      display: flex;
      flex-direction: column;
      text-align: center;
    }
  </style>
