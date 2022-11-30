<template>
    <div class="card-top">
      <slot></slot>
    </div>
  </template>

  <script>
  export default {
    name: 'CardTop'
  }
  </script>

  <style lang="scss" scoped>
  @import "../../../sass/base/mixins.scss";
  @import "../../../sass/base/variables";
    .card-top {
      position: relative;
      background: rgba($color-black, .15);
      padding: 1.25rem;

      &:before {
          content: '';
          position: absolute;
          top: -2px;
          left: 0;
          height: 3px;
          width: 100%;
          background-color: var(--v-primary-base);
      }

      &.half {
        width: 50vw;
        @include respond(md) {
            width: 70vw;
        }

        @include respond(sm) {
            width: 80vw;
        }

        @include respond(xs) {
            width: 100%;
        }
      }
    }
  </style>
