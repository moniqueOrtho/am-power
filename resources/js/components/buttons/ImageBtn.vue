<template>
    <div class="image-btn" :style="styleImageBox" @click="imageBoxClicked">


        <img :src="image.src " :alt="image.alt" class="image-btn__image" v-if="image.src"/>

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
    emits: ['btn-clicked'],
    methods: {
      imageBoxClicked() {
        this.$emit("btn-clicked", this.image);
      },
      activateFileUpload() {
        this.$refs[this.image.name].click();
      },
      loadImage(e) {
        console.log(e)
      }

    },
    computed: {
      styleImageBox() {
        let imageDotted = {
                outline: "2px dotted var(--v-grey1-base)",
                }
            let imageLines = {
                outline: "2px solid var(--v-grey1-darken2)",
                'background-color': this.options.bgColor
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
            iconsize: '4vh'
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
      cursor: pointer;
    }
  </style>
