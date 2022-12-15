<template>
  <v-data-table
    :headers="headers"
    :items="ownItems"
    sort-by="id"
    class="elevation-1 pa-4 crud-table"
    :no-results-text="labels.noResultText"
    :footer-props="{
        'items-per-page-text' : labels.itemsPage
    }"
  >
    <template v-slot:top>
      <close-alert
        alertColor="green-me"
        :alertMessage="succesMessage"
        type="succes"
        spacing="my-3"
        :closeAction="true"
      ></close-alert>
      <v-toolbar
        flat
      >
      <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          :label="labels.search"
          single-line
          hide-details
        ></v-text-field>
        <v-spacer></v-spacer>

        <!-- This is the form dialog with button and is handled with the dialogForm property -->
        <form-dialog
          :default-item="editedItem"
          :formTitle="formTitle"
          :dialogForm="dialog"
          :fields="fields"
          @close-dialog="close"
          @save-input="save"
          @btn-clicked="dialog = true"
          :labels="labels"
        ></form-dialog>

        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">{{labels.cancel}}</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>

    <!-- Icons on the table -->
    <template v-slot:item.gender="{ item }">
      <v-icon

        class="mr-2"
        color="primary"
      >
        {{item.gender === 'male' ? 'mdi-gender-male' : 'mdi-gender-female'}}
      </v-icon>
    </template>

    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        color="primary"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        color="error"
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
    <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template>
  </v-data-table>
</template>

<script>
import FormDialog from "../dialogs/FormDialog.vue";
import CloseAlert from "../alerts/CloseAlert.vue";
export default {
    name: 'curd-table',
    components: { FormDialog, CloseAlert },

    props: {
        info: {
            type: Object,
            default: () => {
                return {
                    editTitleObject : 'name',
                    succesMessageObject: 'name'
                }
            }
        },
        request: {
            type: String,
            default: ''
        },
        fields: {
            type: Array,
            default: () => []
        },
        headers: {
        type: Array,
        required: true
        },
        items: {
        type: Array,
        default: () => []
        },
        defaultItem: {
        type: Object,
        default: () => {}
        },
        labels: {
            type: Object,
            default: () => {
                return {
                    search: 'Search',
                    cancel: 'Cancel',
                    noResultText: 'No matching records found',
                    itemsPage: 'Items per page',
                    newItem: 'New item',
                    editItem: 'Edit item',
                    save: 'Save',
                    succesMessage: 'has been created',
                    updateMessage: 'has been changed successfully.',
                    required: 'is required',
                    emailInvalid: 'E-mail must be valid',
                    maxCounter: 'must be less than *vue* characters!'
                }
            }
        }
    },
    created () {
        this.initialize();
    },
    data() {
        return {
            dialog: false,
            dialogDelete: false,
            editedIndex: -1,
            editedItem: null,
            ownItems: [],
            search: '',
            loaded: false,
            succesMessage: '',
        }
    },

    computed: {
        formTitle () {
        return this.editedIndex === -1 ? this.labels.newItem : `${this.labels.editItem} ${this.editedItem[this.info.editTitleObject]}`
        },
    },
    methods: {
        initialize () {
        this.ownItems = [...this.items];
        this.editedItem = Object.assign({}, this.defaultItem);
        },

        editItem (item) {
        this.editedIndex = this.ownItems.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
        },

        deleteItem (item) {
        this.editedIndex = this.ownItems.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true
        },

        deleteItemConfirm () {
        this.ownItems.splice(this.editedIndex, 1)
        this.closeDelete()
        },

        close() {
        this.dialog = false
        this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem)
            this.editedIndex = -1
        })
        },

        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
        setEditedItem(editedItem) {
            Object.assign(this.ownItems[this.editedIndex], editedItem);
            this.succesMessage = `${editedItem[this.info.succesMessageObject]} ${this.labels.updateMessage}`;
        },
        setCreatedItem(newItem) {
            this.ownItems.push(newItem);
            this.succesMessage = `${newItem[this.info.succesMessageObject]} ${this.labels.succesMessage}`;
        },
        async store(data) {
            try {
                    if (this.editedIndex > -1) {
                        const editedItem = await axios.put(`${this.request}/${data.id}`, data)
                        this.setEditedItem(editedItem.data.data)
                    } else {
                        const newItem = await axios.post(this.request, data)
                        this.setCreatedItem(newItem.data.data)
                        console.log(newItem)
                    }
                    this.close()
            } catch (error) {
                if(error) console.log(error);
            } finally {
                this.loaded = true

            }
        },
        save (data) {
            this.succesMessage = '';
            if(this.request) {
                this.store(data)
            } else {
                if (this.editedIndex > -1) {
                    this.setEditedItem(data)
                } else {
                    this.setCreatedItem(data)
                }
                this.close()
            }
        },
    },
    watch: {
        dialog (val) {
        val || this.close()
        },
        dialogDelete (val) {
        val || this.closeDelete()
        },
    },
}
</script >

<style lang="scss" scoped>
@import "../../../sass/base/variables";
    .crud-table {
        font-family: $body-font-family;
    }
</style>
