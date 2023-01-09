<template>
  <v-data-table
    :headers="headers"
    :items="ownItems"
    class="elevation-1 pa-4 crud-table"
    :no-results-text="labels.noResultText"
    :no-data-text="labels.noDataText"
    :footer-props="{
        'items-per-page-text' : labels.itemsPage
    }"
    :show-expand="lookForExpand"
    :expanded.sync="expanded"
    :single-expand="defaultExpand.singleExpand"
    :item-key="defaultExpand.itemKey"
    :search="search"
  >
    <template v-slot:top>
      <close-alert
        :alertColor="getAlertColor"
        :alertMessage="getAlertMessage"
        :type="getAlertType"
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

        <!-- This is the form dialog with button -->

        <v-dialog
            v-model="dialog"
            max-width="80vw"
        >
            <template v-slot:activator="{ on }" v-if="defaultCrud.create">
                <v-btn
                    color="primary"
                    dark
                    class="mb-2"
                    v-on="on"
                    @click="newClicked"
                >
                    {{labels.newItem}}
                </v-btn>
            </template>

            <v-card :loading="dataLoading">
                <TheForm
                :default-item="editedItem"
                :formTitle="formTitle"
                :fields="fields"
                @close-dialog="close"
                @save-input="save"
                :labels="labels"
                :errors="errors"
                @clear-error="clearError"
                :reset-form="resetForm"
                />
            </v-card>
        </v-dialog>

         <!-- This is de delete dialog and is handled with the dialogDelete Method -->
        <DeleteDialog
          :dialogDelete="dialogDelete"
          :title="deleteMessage"
          :labels="labels"
          @dialog-closed="dialogDeleteClosed"
          @delete-confirmed="deleteItemConfirm"
        />

      </v-toolbar>
    </template>

    <!-- Make rank number bold -->
    <template v-slot:item.rank="{ item }">
        <td class="font-weight-bold">{{ item.rank }}</td>
    </template>

    <template v-slot:expanded-item="{ headers, item }" v-if="lookForExpand">
      <td :colspan="headers.length" v-html="expandText(item)">
      </td>
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
        v-if="defaultCrud.update"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
        color="error"
        @click="deleteItem(item)"
        v-if="defaultCrud.delete"
      >
        mdi-delete
      </v-icon>
    </template>
    <!-- <template v-slot:no-data>
      <v-btn
        color="primary"
        @click="initialize"
      >
        Reset
      </v-btn>
    </template> -->
  </v-data-table>
</template>

<script>
import TheForm from "../form/TheForm.vue";
import DeleteDialog from "../dialogs/DeleteDialog.vue";
import CloseAlert from "../alerts/CloseAlert.vue";
export default {
    name: 'curd-table',
    components: { TheForm, CloseAlert, DeleteDialog },

    props: {
        info: {
            type: Object,
            default: () => {
                return {
                    editTitleObject : 'name',
                    succesMessageObject: 'name',
                    deleteMessageObject: 'name'
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
                    noDataText: 'No data available!',
                    itemsPage: 'Items per page',
                    newItem: 'New item',
                    editItem: 'Edit item',
                    save: 'Save',
                    succesMessage: 'has been created',
                    updateMessage: 'has been changed successfully.',
                    required: 'is required',
                    emailInvalid: 'E-mail must be valid',
                    urlInvalid: 'Url must be valid',
                    maxCounter: 'must be less than *vue* characters!',
                    deleteMessage: 'Are you sure you want to delete *vue*?',
                    deleteSuccessful: '*vue*  has been deleted.'
                }
            }
        },
        expand: {
            type: [Object, Boolean],
            default: false
        },
        crud: {
            type: [Object, Boolean],
            default: false
        }
    },
    created () {
        this.initialize();
        this.crudActions();
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
            errors: {},
            deleteMessage: '',
            errorMessage: '',
            dataLoading: false,
            resetForm: false,
            expanded: [],
            defaultExpand: {
                singleExpand: true,
                itemKey: 'id',
                text: 'expand'
            },
            defaultCrud: {
                create: true,
                update: true,
                delete: true
            }

        }
    },

    computed: {
        formTitle () {
        return this.editedIndex === -1 ? this.labels.newItem : `${this.labels.editItem} ${this.editedItem[this.info.editTitleObject]}`
        },
        getAlertColor() {
            return this.errorMessage ? 'red-me' : 'green-me';
        },
        getAlertMessage() {
            return this.errorMessage ? this.errorMessage : this.succesMessage;
        },
        getAlertType() {
            return this.errorMessage ? 'error' : 'success';
        },
        lookForExpand() {
            return this.headers.findIndex((v) => v.value === 'data-table-expand') > -1;
        },
        expandText() {
            return (item) => {
                return this.defaultExpand.text in item ? item[this.defaultExpand.text] : `<span>More info about ${item[this.defaultExpand.itemKey] }</span>`;
            }
        },
    },
    methods: {
        initialize () {
        // Number items if header contains 'rank'
        if(this.headers.findIndex(x => x.value === 'rank') > -1) {
            this.ownItems = this.rankedItems(this.items);
        } else {
            this.ownItems = [...this.items];
        }

        this.editedItem = Object.assign({}, this.defaultItem);
        this.resetMessages();
        if(this.lookForExpand) {
            this.setExpand();
        }
        },

        rankedItems(items) {
            let newObj = {};
            return items.map((x, i) => {
                newObj['rank'] = items.length - i;
                return {...x, ...newObj};
            })
        },

        resetMessages() {
            if(this.succesMessage) this.succesMessage = '';
            if(this.errorMessage) this.errorMessage = '';
        },

        setExpand() {
            if(this.expand) {
                this.setDefault(this.defaultExpand, this.expand);
            }
        },
        crudActions() {
            if(this.crud) {
                this.setDefault(this.defaultCrud, this.crud);
            }
        },
        setDefault(def, prop) {
            let keys= Object.keys(def);
            keys.forEach(key => {
                if(key in prop) {
                    def[key] = prop[key];
                }
            })
        },

        newClicked() {
            this.resetForm = true
        },

        editItem (item) {
        this.editedIndex = this.ownItems.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
        this.resetForm = true
        },

        deleteItem (item) {
        this.editedIndex = this.ownItems.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialogDelete = true;
        this.deleteMessage = this.labels.deleteMessage.replace('*vue*', item[this.info.deleteMessageObject]);
        this.resetForm = true
        },

        dialogDeleteClosed() {
            this.dialogDelete = false;
            this.close();
        },

        async deleteItemConfirm () {
            let obj = this.editedItem[this.info.deleteMessageObject], deleted;
            this.resetMessages();
            try {
                await axios.delete(`${this.request}/${this.editedItem.id}`)
                this.succesMessage = this.labels.deleteSuccessful.replace('*vue*', obj);
                if(this.headers.findIndex(x => x.value === 'rank') > -1) {
                    deleted = this.ownItems.filter(x => x[[this.info.deleteMessageObject]] !== obj);
                    this.ownItems = this.rankedItems(deleted);
                } else {
                    this.ownItems.splice(this.editedIndex, 1)
                }

            } catch (error) {
                console.log(error);
                this.errorMessage = error.response.data.message;
            } finally {
                this.closeDelete()
            }
        },

        close() {
        this.dialog = false
        this.resetForm = false;
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
            this.ownItems.unshift(newItem);
            if(this.headers.findIndex(x => x.value === 'rank') > -1) this.ownItems = this.rankedItems(this.ownItems);
            this.succesMessage = `${newItem[this.info.succesMessageObject]} ${this.labels.succesMessage}`;
        },
        async store(data) {
            this.dataLoading = true;
            this.resetForm = false;
            this.resetMessages();
            try {
                    if (this.editedIndex > -1) {
                        const editedItem = await axios.put(`${this.request}/${data.id}`, data)
                        this.setEditedItem(editedItem.data.data)
                    } else {
                        const newItem = await axios.post(this.request, data)
                        this.setCreatedItem(newItem.data.data)
                    }
                    this.close()
            } catch (error) {
                if(error) {
                    this.errors = error.response.data.errors;
                }
            } finally {
                this.dataLoading = false

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
        clearError (name) {
           delete this.errors[name];
        }
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
