<template>
    <button class="pressed" :style="setColor">
        <slot></slot>
    </button>
</template>

<script>
export default {
  name: 'BtnPressed',
  props: {
    color: {
      type: String,
      required: false
    }
  },
  computed: {
    setColor() {
      let color = this.color ? this.color : this.$vuetify.theme.themes.light.primary
      return {
        '--color' : color
      }
    }
  }
}
</script>

<style lang="scss" scoped>
    .pressed {
        padding: .5rem 1rem;
        font-size: 1rem;
        text-align: center;
        text-transform: uppercase;
        color: white;
        background-color: var(--color);
        border-radius: 4px;
        border-bottom: 1px solid rgba(0,0,0, .3);
        box-shadow: 0 6px rgba(0,0,0, .25);
        position: relative;
        overflow: hidden;

        &::after {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(90deg,transparent,rgba(255,255,255, .6), transparent);
            transition: .5s;
        }

        &:hover {

            color: var(--v-grey2-base);
        }
        &:hover::after {
            left: 100%;
        }
        &:active {
            background-color: var(--color);
            box-shadow: 0 3px rgba(0,0,0, .6);
            transform: translateY(4px);
            outline: none;
        }
        &:focus {
            outline: none;
        }
    }

</style>
