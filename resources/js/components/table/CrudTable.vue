<template>
  <v-data-table
    :headers="headers"
    :items="ownItems"
    sort-by="id"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar
        flat
      >
        <v-toolbar-title>My CRUD</v-toolbar-title>
        <v-divider
          class="mx-4"
          inset
          vertical
        ></v-divider>
        <v-spacer></v-spacer>

        <!-- This is the form dialog with button and is handled with the dialogForm property -->
        <form-dialog
          :default-item="editedItem"
          :formTitle="formTitle"
          :dialogForm="dialog"
          @close-dialog="close"
          @saveInput="save"

        ></form-dialog>

        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        class="mr-2"
        @click="editItem(item)"
      >
        mdi-pencil
      </v-icon>
      <v-icon
        small
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
export default {
    name: 'curd-table',
    components: { FormDialog },

    props: {
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
        }
    },
    created () {
        this.initialize()
    },
    data() {
        return {
        dialog: false,
        dialogDelete: false,
        editedIndex: -1,
        editedItem: null,
        ownItems: []
        }
    },

    computed: {
        formTitle () {
        return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
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

        save (data) {
        if (this.editedIndex > -1) {
            Object.assign(this.ownItems[this.editedIndex], data)
        } else {
            this.ownItems.push(data)
        }
        this.close()
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

</style>
