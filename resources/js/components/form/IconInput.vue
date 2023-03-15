<template>
    <div class="container">
      <div class="icon-input" :class="{'shake' : invalid}">
        <span class="icon-input__icon" :class="invalid || initialState ? 'light1 grey1--text' : 'success white--text'">
          <font-awesome-icon :icon="icon" v-if="invalid || initialState"/>
          <font-awesome-icon icon="fa-solid fa-check" v-else/>
        </span>
        <label :id="`label-${name}`" :for="`visible-${name}`" class="icon-input__label" :class="{'error-line' : invalid, 'focus-label': focus}">
          <input
          :type="type === 'password' && visible ? 'text' : type"
          class="icon-input__input"
          :name="`visible-${name}`"
          @focus="inputFocused(`label-${name}`)"
          @blur="inputUnfocused(`label-${name}`)"
          v-model="input"
          :required="required"
          :disabled="disabled"
          :autocomplete="autocomplete"
          placeholder=" "
          :ref="name"
          >
          <span class="icon-input__placeholder">{{placeholder}}</span>
          <button
            type="button"
            class="icon-input__visibility"
            v-if="type === 'password'"
            @click.prevent="changeVisibility">
            <font-awesome-icon :icon="visible ? 'fa-solid fa-eye-slash': 'fa-solid fa-eye'"/>
          </button>
        </label>
      </div>
      <p class="icon-input__error-message" v-if="componentError">{{ error }}</p>
    </div>

  </template>

  <script>
      export default {
        name: 'IconInput',
        props: {
            name: {
                type: String,
                required: true
            },
            type: {
                type: String,
                default: 'text'
            },
            placeholder: {
                type: String,
                default: ''
            },
            required: {
                type: Boolean,
                required: false
            },
            autocomplete: {
                type: String,
                default: 'on'
            },
            icon: {
                type: String,
                default: 'fa-solid fa-user-circle'
            },
            min: {
                type: Number,
                required: false
            },
            max: {
                type: Number,
                required: false
            },
            error: {
                type: String,
                required: false
            },
            oldValue: {
                type: String,
                required: false
            }
        },
        data() {
            return {
              visible: false,
              focus: false,
              disabled: true,
              invalid: false,
              inputUser: '',
              initialState: true,
              componentError: false
            }
        },
        mounted() {
          setTimeout(()=>{this.disabled = false}, 800);
          if(this.oldValue) this.inputUser = this.oldValue;
          if(this.max) this.$refs[this.name].setAttribute("max_length", this.max);
          if(this.min) this.$refs[this.name].setAttribute("min_length", this.min);
          if(this.min && this.max) this.$refs[this.name].setAttribute("pattern", `.{${this.min},${this.max}}`);
          if(this.min && !this.max) this.$refs[this.name].setAttribute("pattern", `.{0}| .{${this.min},}`);
          if(!this.min && this.max) this.$refs[this.name].setAttribute("pattern", `.{0}| .{,${this.max}}`);
          if(this.error) this.componentError = true;
        },
        computed: {
          input: {
            get() {
              return this.inputUser;
            },
            set(val) {
              document.getElementById(this.name).value = val;
              this.invalid = !this.isValidate(val);
              this.initialState = false;
              this.inputUser = val;
              if(this.componentError) this.componentError = false;
            }
          }
        },
        methods: {
            changeVisibility() {
                this.visible = !this.visible;
            },
            inputFocused(id) {
                this.focus = true;
                if(this.required && this.inputUser === '') this.invalid = true;
            },
            inputUnfocused(id) {
              this.focus = false;
            },
            isValidate(val) {
                if(this.type === 'email') {
                    return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(val)
                }
                if(this.min !== 0) return val.length >= this.min;
                if(this.max !== 0) return val.length <= this.max;
                return true;
            },
        },
        watch: {
            inputUser(val) {
              if(val !== '') this.initialState = false;
              if(this.required && val !== '') this.invalid = !this.isValidate(val);
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
          font-family: $body-font-family;
          width: 100%;
          height: 90%;
          border: 0;
          outline: 0;
          color: var(--v-grey2-base);
          border-bottom: 3px solid transparent;
          display: block;
          position: relative;
          z-index: 40;

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

  /* Change autocomplete styles in WebKit */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    textarea:-webkit-autofill:hover,
    textarea:-webkit-autofill:focus,
    select:-webkit-autofill,
    select:-webkit-autofill:hover,
    select:-webkit-autofill:focus {

    -webkit-text-fill-color: var(--v-grey2-base);
    transition: background-color 5000s ease-in-out 0s;
    }

  </style>
