<template>
    <div class="container">
      <div class="icon-input" :class="{'shake' : errors}">
        <span class="icon-input__icon" :class="errors || initialState ? 'light1 grey1--text' : 'success white--text'">
          <font-awesome-icon :icon="icon" v-if="errors || initialState"/>
          <font-awesome-icon icon="fa-solid fa-check" v-else/>
        </span>
        <label :id="`label-${name}`" :for="name" class="icon-input__label" :class="{'error-line' : errors, 'focus-label': focus}">
          <input
          :id="name"
          :type="type === 'password' && visible ? 'text' : type"
          class="icon-input__input"
          :name="name"
          @focus="inputFocused(`label-${name}`)"
          @blur="inputUnfocused(`label-${name}`)"
          :value="inputUser"
          v-model="inputVal"
          :required="required"
          :disabled="disabled"
          :autocomplete="autocomplete"
          placeholder=" "
          >
          <span class="icon-input__placeholder">{{placeholder}}</span>
          <button
            class="icon-input__visibility"
            v-if="type === 'password'"
            @click.prevent="changeVisibility">
            <font-awesome-icon :icon="visible ? 'fa-solid fa-eye-slash': 'fa-solid fa-eye'"/>
          </button>
        </label>
      </div>
      <div class="icon-input__error-message">
        <slot name="error"></slot>
      </div>
    </div>

  </template>

  <script>
      export default {
        name: 'IconInput',
        props: ['name', 'type', 'placeholder', 'required', 'autocomplete', 'icon'],
        data() {
            return {
              visible: false,
              focus: false,
              disabled: true,
              errors: false,
              inputUser: '',
              inputObject: {},
              initialState: true,
            }
        },
        mounted() {
          setTimeout(()=>{this.disabled = false}, 800);
        },
        computed: {
          inputVal: {
            get() {
              return this.inputUser;
            },
            set(val) {
              this.initialState = false;
              this.inputUser = val;
              this.inputObject[this.name] = this.inputUser;
            }
          }
        },
        methods: {
            changeVisibility() {
                this.visible = !this.visible;
            },
            inputFocused(id) {
                this.focus = true;
                if(this.required && this.inputUser === '') this.errors = true;
            },
            inputUnfocused(id) {
              this.focus = false;
            },
        },
        watch: {
            inputUser() {
              if(this.inputUser !== '') this.initialState = false;
              if(this.required && this.inputUser !== '') this.errors = false;
            },
        }
      }
  </script>

  <style lang="scss" scoped>
  @import "../../../sass/base/variables";
  .container {
    display: flex;
    flex-direction: column;
  }
  .icon-input {
      // padding: .75rem 1.25rem;
      display: flex;
      flex-wrap: wrap;

      &__icon {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 1.5rem;
        font-size: 1.5rem;
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
      }

      &__label {
        flex: 1;
        width: 100%;
        height: 100%;
        display: flex;
        background: white;
        margin-bottom: 0;
        padding: 1rem 1.25rem;
        position: relative;
        cursor: pointer;
        transition: all .2s ease;
        border-bottom: 3px solid transparent;
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
      }

      &__input {
          font-size: 1.125rem;
          padding-top: .94rem;
          font-family: $font-family-base;
          width: 100%;
          height: 90%;
          border: 0;
          outline: 0;
          color: var(--v-grey2-base);
          border-bottom: 3px solid transparent;
          display: block;
          position: relative;

          &:focus,
          &:not(:placeholder-shown) {
              ~ .icon-input__placeholder {
                  font-size: .625rem;
                  outline: none;
                  top: -8px;
                  transform: translateY(0);
              }

          }
          &:focus:invalid ~ .icon-input__placeholder {
            color: var(--v-error-base);
            font-weight: 700;
          }
      }

      &__placeholder {
        padding: .94rem 0;
        font-family: inherit;
        font-size: 1.125rem;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        transition: 0.2s ease;
        color: var(--v-grey1-base);
      }

      &__visibility {
        cursor: pointer;
        margin-bottom: 0;
        font-size: 1.5625rem;
        color: var(--v-grey1-base);
        margin-left: 1.25rem;
        &:focus {
          outline: none;
        }
      }
      &__error {
          flex-basis: 100%;
          height: 0;
          color: var(--v-error-base);
          font-size: 1rem;
          font-weight: 700;
      }

      &__error-message {
        margin: 0 4.5rem;
        color: var(--v-error-base);
      }
  }

  .focus-label {
      transform: scale(1.05);
      box-shadow: 0 10px 20px rgba($color-black, .3);
      border-bottom: 3px solid var(--v-primary-base);
  }

  .error-line {
      border-bottom: 3px solid var(--v-error-base);
  }

  </style>
