<template>
    <div class="image-btn" :style="styleImageBox">

        <div class="noImage" v-if="image.src === ''" @click="imageBoxClicked">
            <v-icon class="image-btn__icon" :style="{'font-size' : options.iconSize}">
                {{ options.icon }}
            </v-icon>
            <p class="mt-2">{{ options.text }}</p>

        </div>

        <img :src="image.src" :alt="image.alt" class="image-btn__image image-btn__image--clickable" v-else-if="image.src && options.clickable" @click="imageBoxClicked" />
        <img :src="image.src" :alt="image.alt" class="image-btn__image image-btn__image--noclickable" v-else />

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
        }
    },
    computed: {
        styleImageBox() {
            return {
                '--outline' : this.image.src ? '2px solid var(--v-grey1-darken2)' : '2px dotted var(--v-grey1-base)',
                '--cursor' : this.options.clickable ? 'pointer' : 'cursor',
                '--bg-color' : this.options.bgColor
            }
        },
        options() {
            let options = {
                text: 'Add image',
                icon: 'mdi-image-plus',
                bgColor: 'inherit',
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
        background-color: var(--bg-color);
        cursor: var(--cursor);
        outline: var(--outline);
        overflow: hidden;
        transition: all 0.5s ease;
        &:hover .noImage {
            background-color: var(--v-accent-lighten3);
            color: var(--v-light1-base);
        }

        &:hover .noImage .image-btn__icon {
            color: var(--v-light1-base);
        }

        &:hover .image-btn__image--clickable {
            opacity: 50%;
        }

        &__image {
            width: 100%;
            height: 100%;

            &--clickable {
                object-fit: cover;
            }
            &--noclickable {
                object-fit: contain;
            }
        }

        &__icon {
            color: var(--v-grey1-base);
        }
    }
    .noImage {
        display: flex;
        flex-direction: column;
        text-align: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }
  </style>
