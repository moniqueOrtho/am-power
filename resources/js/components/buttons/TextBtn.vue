<template>
    <button type="button" :style="getStyle" @click="action">
      {{ text }}
    </button>
  </template>

  <script>
  export default {
    name: 'BtnInline',
    props: {
      text: {
        type: String,
        required: true
      },
      size: {
        type: String,
        default: 'inherit'
      },
      color: {
        type: String,
        default: '#8672c0'
      },
      hoverColor: {
        type: String,
        default: '#333333'
      }
    },
    emits: ['do-action'],
    methods: {
      action() {
        this.$emit('do-action');
      }
    },
    computed: {
      getStyle() {
        return {
          fontSize: this.size,
          '--color': this.color,
          '--color-hover': this.hoverColor
        }
      }
    }
  }
  </script>

  <style lang="scss" scoped>
    button {
      //font-size: inherit;
    border-bottom: 1px solid var(--color);
    background-color: transparent;
    transition: all .2s;
    color: var(--color);
  }
  button:hover {
    color: var(--color-hover);
    animation: pulsate 1s infinite;
    border-bottom: 1px solid var(--color-hover);
  }

  button:focus {
    outline: none;
    //animation: pulsate 1s infinite;
  }
  </style>
