<template>
    <transition name="alert">
        <div :class="['alert', alertColor, spacing]" v-if="alert">
            <div class="alert__message">
                <span class="alert__check"><font-awesome-icon icon="fa-solid fa-circle-check" v-if="type === 'success'" /></span>
                <span class="alert__check"><font-awesome-icon icon="fa-solid fa-circle-exclamation" v-if="type === 'error'" /></span>
                <span class="alert__msg">{{alertMessage}}</span>
            </div>
            <button class="alert__crose" @click="closealertBox"><font-awesome-icon icon="fa-solid fa-xmark" /></button>
        </div>
    </transition>
</template>
<script>

export default {
    props: ['alertColor', 'alertMessage', 'type', 'spacing', 'closeAction'],
    emits: ['closeAlert'],
    data() {
      return {
        alert : false
      }
    },
    created() {
      if(this.alertMessage !== '') this.alert = true;
    },
    methods: {
      closealertBox() {
        this.alert = false;
        if(this.closeAction) this.$emit('closeAlert');
      }
    },
    watch: {
      alertMessage() {
        this.alert = this.alertMessage !== '' ? true : false;
      }
    }
}
</script>

<style lang="scss" scoped>
    .alert {
        font-style: italic;
        font-size: 1.125rem;
        display: flex;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        padding: .625rem 1.25rem;
        border-radius: 4px;
        align-items: center;
        line-height: 1;
        &.green-me {
          background-color: var(--v-success-lighten3);
          border-left: 7px solid var(--v-success-base);
          color: var(--v-success-base);
        }
        &.red-me {
          background-color: var(--v-error-lighten4);
          border-left: 7px solid var(--v-error-base);
          color: var(--v-error-base);
        }
        &.yellow-me {
          background-color: var(--v-secondary-lighten4);
          border-left: 7px solid var(--v-secondary-base);
          color: var(--v-secondary-base);
        }

        &__message {
            display: flex;
            align-items: center;
        }

        &__check,
        &__crose {
            font-size: 1.875rem;
        }
        &__msg {
            margin: 0 1.25rem;
            color: var(--v-grey2-base);
        }
        &__crose {
            cursor: pointer;
            line-height: inherit;
            background-color: transparent;
            &:hover {
              color: var(--v-grey2-base);
            }
        }
    }
    .alert-enter {
        opacity: 0;
        transform: translateX(6.25rem);
    }
    .alert-enter-active {
        transition: all .4s ease-out;
    }
    .alert-enter-to {
        opacity: 1;
        transform: translateX(0rem);
    }
    .alert-leave {
        opacity: 1;
        transform: translateX(0rem);
    }
    .alert-leave-active {
        transition: all .4s ease-in;
    }
    .alert-leave-to {
        opacity: 0;
        transform: translateX(6.25rem);
    }

</style>
