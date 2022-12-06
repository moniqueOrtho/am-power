<template>
    <v-form
      @submit.prevent="validateForm"
      ref="form"
      v-model="valid"
      lazy-validation
      autocomplete="off"
      class="pa-8 teams-form"
    >
      <h6 class="text-h6 text-uppercase primary--text mb-6" v-if="formInfo.title">
        {{ formInfo.title }}
      </h6>

      <div v-for="(element, index) in elements" :key="element.text">
        <div class="d-flex justify-space-between align-center primary--text">
          <h6 :class="`text-h6 ${element.selectAll ? '' : 'mb-3'}`">
            {{
              element.text !== "products"
                ? element.text.toUpperCase()
                : $tc("Products", 1).toUpperCase()
            }}
          </h6>
          <v-checkbox
            v-if="element.selectAll"
            v-model="selectAll"
            color="primary"
          >
            <template v-slot:label>
              <span class="form__selected-all--label">{{$t('select all')}}</span>
            </template>
          </v-checkbox>
        </div>

        <v-row
          v-for="(input, index) in element.inputs"
          :key="index"
          v-if="element.text !== 'products'"
        >
          <v-col
            v-for="i in input['row' + index]"
            :key="i.name"
            cols="12"
            :sm="i.sm"
            :md="i.md"
          >
            <template v-if="i.item === 'select'">
              <v-select
                :autofocus="i.tab === 1 ? true : false"
                v-model="editedItem[i.name]"
                :tabindex="i.tab"
                :items="i.items"
                item-text="text"
                item-value="value"
                :label="i.placeholder"
                outlined
                :rules="i.rules"
                filled
                color="primary"
              ></v-select>
            </template>
            <template v-if="i.item === 'text'">
              <v-text-field
                :autofocus="i.tab === 1 ? true : false"
                v-model="editedItem[i.name]"
                outlined
                :tabindex="i.tab"
                :label="i.placeholder"
                :prepend-inner-icon="i.icon"
                :type="i.type"
                :counter="i.counter"
                :rules="i.rules"
                clearable
                background-color="grey lighten-4"
                :disabled="i.disabled ? i.disabled : false"
                :error-messages=" i.name in errors ? errors[i.name][0] : '' "
                @input="inputIsSet"
              ></v-text-field>
            </template>

            <template v-if="i.item === 'textarea'">
              <v-textarea
                :autofocus="i.tab === 1 ? true : false"
                v-model="editedItem[i.name]"
                outlined
                :auto-grow="i.autoGrow"
                :tabindex="i.tab"
                :label="i.placeholder"
                :counter="i.counter"
                :rules="i.rules"
                :rows="i.rows"
                :row-height="i.rowHeight"
                clearable
                background-color="grey lighten-4"
                :disabled="i.disabled ? i.disabled : false"
                :error-messages=" i.name in errors ? errors[i.name][0] : '' "
                @input="inputIsSet"
              ></v-textarea>
            </template>

            <template v-if="i.item === 'checkbox'">
              <v-checkbox
                v-model="editedItem[i.vName]"
                :label="i.placeholder"
                :color="i.color ? i.color : 'primary'"
                :value="i.value"
              ></v-checkbox>
            </template>
            <!-- <advanced-cropper
              v-if="i.item === 'image'"
            /> -->
          </v-col>
        </v-row>

        <div class="pb-4" v-if="elements.length !== index + 1">
          <v-divider></v-divider>
        </div>
        <div class="mb-2" v-else>&nbsp;</div>
      </div>
      <div class="d-flex justify-end">
        <v-btn
          type="button"
          color="secondary"
          class="mr-4"
          @click="goBack"
          large
          >{{ $t("back") }}</v-btn
        >
        <v-btn type="submit" color="primary" large>{{
          formInfo.action ? $t(formInfo.action) : $t('save')
        }}</v-btn>
      </div>
    </v-form>
  </template>

  <script>
  //import AdvancedCropper from "~/components/form/AdvancedCropper";
  export default {
    name: "the-form",
    //components: { AdvancedCropper },
    props: {
      elements: {
        type: Array,
        required: true,
      },
      defaultItem: {
        type: Object,
        required: true,
      },
      formInfo: {
        type: Object,
        required: true,
      },
      errors: {
        type: Object,
      },
      resetForm: {
        type: Boolean,
        default: false,
      }
    },
    emits: ["form-input", "clear-errors", "loading-state", "close-dialog"],
    data() {
      return {
        valid: true,
        editedItem: null,
        submitRequest: false,
        selectAll: false
      };
    },
    created() {
      this.initialize();
    },
    methods: {
      initialize() {
        this.editedItem = Object.assign({}, this.defaultItem);
      },
      inputIsSet(item) {
        if (
          this.errors &&
          Object.keys(this.errors).length !== 0 &&
          this.errors.constructor === Object
        )
          this.$emit("clear-errors");
      },
      goBack() {
        if (this.formInfo.hrefBack) {
          this.$router.push(this.formInfo.hrefBack);
        } else {
          this.$emit("close-dialog");
        }
      },
      validateForm() {
        this.submitRequest = false;
        if (this.$refs.form.validate()) {
          if(this.formInfo.productsRequired) {
            this.submitRequest = true
          } else {
            this.$emit("form-input", this.editedItem);
          }

        };
      },
      submit(input) {
        this.editedItem["products"] = input;
        this.$emit("form-input", this.editedItem);
      },
      setLoading(payload) {
        this.$emit("loading-state", payload);
      },
    },
    watch: {
      resetForm(value) {
        if(value) this.initialize();
      },
      // succes() {
      //   if (this.succes) this.initialize();
      // },
      errors() {
        if (
          this.errors &&
          Object.keys(this.errors).length === 0 &&
          this.errors.constructor === Object
        ) {
          this.submitRequest = false;
        }
      },
      defaultItem() {
        this.initialize();
      },
      selectAll() {
        this.editedItem[this.formInfo.selectAllName] = this.selectAll ? this.formInfo.selectAll: [];
      },
    },
  };
  </script>

  <style lang="scss" scoped>
  .form {
    &__selected-all--label {
      color: var(--v-primary-base);
      font-weight: 500;
    }
  }
  </style>
