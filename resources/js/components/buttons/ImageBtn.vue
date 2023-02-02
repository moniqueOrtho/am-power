<template>
    <div class="image-btn">
      <div
        class="image-btn__image-box"
        :style="styleImageBox"
        @click="imageBoxClicked"
      >
        <div class="image-btn__image-container" v-if="image.src">
          <img :src="image.src " :alt="image.title" class="images" />
        </div>
        <div class="noImage" v-else>
          <v-icon class="image-btn__icon grey1--text text--lighten-2 " :style="iconSize">
            {{ optionsBtn.icon }}
          </v-icon>
          <p class="mt-2">{{ optionsBtn.text }}</p>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    name: "image-btn",
    props: {
      image : {
        // Required keys are: src, id, title,
        type: Object,
        required: true
      },
      optionsBtn : {
        // Required keys are: text (button), icon, size
        // Options key are : bgColor, iconsize
        type: Object,
        required: true
      },
    },
    emits: ['btn-clicked'],
    methods: {
      imageBoxClicked() {
        this.$emit("btn-clicked", this.image);
      }

    },
    computed: {
      styleImageBox() {
        let imageDotted = {
              outline: "2px dotted var(--v-grey1-base)",
              cursor: "pointer",
            }
        let imageLines = {
              outline: "2px solid var(--v-grey1-darken2)",
              cursor: "no-drop",
              'background-color': this.optionsBtn.bgColor ? this.optionsBtn.bgColor : "transparent"
            }
        return this.image.src
          ? {...imageLines, ...this.optionsBtn.size}
          : {...imageDotted, ...this.optionsBtn.size}
      },
      imageSrc() {
        return this.image.src;
      },
      iconSize() {
        return this.optionsBtn.iconSize ? {'font-size' : this.optionsBtn.iconSize} : {'font-size' : '4vh'};
      }
    },
  };
  </script>

  <style lang="scss" scoped>
    .image-btn {
      &__image-box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-content: center;
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
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
      // &__image-container {
      //   display: flex;
      //   position: relative;
      //   width: 100%;
      //   height: 100%;
      //   justify-content: center;
      //   background-color: var(--v-grey1-base);
      // }
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
