<template>
    <v-dialog v-model="setDialog" max-width="600px">
      <v-card class="error lighten-1">
        <div class="delete-dialog">
          <div class="delete-dialog__icon error darken-1">
            <v-icon color="light1" size="40px">mdi-delete</v-icon>
          </div>
          <div class="delete-dialog__body ma-6">
            <h6 class="text-h6 font-weight-medium light1--text mb-6">
              {{ title }}
            </h6>
            <div class="delete-dialog__actions">
              <div >
                <v-btn color="light1"  @click="close" class="mr-3">{{ labels.cancel }}</v-btn>
                <v-btn color="error darken-1"  @click="deleteItemConfirm" >Ok</v-btn>
              </div>
            </div>
          </div>
        </div>
      </v-card>
    </v-dialog>
  </template>

  <script>
  export default {
    name: "delete-dialog",
    props: {
      dialogDelete: {
        type: Boolean,
        default: false
      },
      title: {
        type: String,
        required: false
      },
      labels: {
        type: Object,
        required: true
      }
    },
    emits: ["dialog-closed", "delete-confirmed"],
    methods: {
      deleteItemConfirm() {
        this.close();
        this.$emit("delete-confirmed");
      },
      close() {
        this.$emit('dialog-closed');
      }
    },
    computed: {
      setDialog: {
        set(value) {
          this.close();
        },
        get() {
          return this.dialogDelete;
        }
      }
    }
  };
  </script>

  <style lang="scss" scoped>
    .delete-dialog {
      word-break: normal;
      display: flex;
      &__icon {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 60px;
      }

      &__body {
        width: 100%;
      }

      &__actions {
        display: flex;
        justify-content: flex-end;
      }
    }
  </style>
