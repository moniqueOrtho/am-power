<template>
    <div class="crud-table">

      <v-data-table
        :headers="headers"
        :items="setItems"
        :sort-by="tableInfo.sortBy"
        class="elevation-1 pa-3"
        :expanded.sync="expanded"
        :item-key="tableInfo.itemKey"
        :search="search"
        :show-expand="expand"
        :no-results-text="tableInfo.noResultText"
      >

        <template v-slot:top>
          <close-alert
            alertColor="green-me"
            :alertMessage="alertMessage"
            type="succes"
            spacing="my-3"
            :closeAction="true"
            @closeAlert="closeSuccesBox"
          ></close-alert>
          <v-toolbar
            flat
          >
            <v-text-field
              v-model="search"
              append-icon="mdi-magnify"
              :label="tableInfo.searchLabel"
              single-line
              hide-details
            ></v-text-field>
            <v-spacer></v-spacer>

            <!-- New Button go to new page, when editByPage is true, or activate dialog -->
            <v-btn
                color="primary"
                dark
                class="mb-2"
                @click="goToNewPage"
                v-if="tableInfo.editByPage && !'noCrud' in tableInfo"
              >
                {{ tableInfo.newBtn }}
            </v-btn>
            <!-- This is the form dialog with button and is handled with the dialogForm property -->
              <FormDialog
                :loading="loadingDialog"
                :resetForm="getResetForm"
                :dialogForm="dialog"
                :elements="formItems"
                :defaultItem="valuesFormItems"
                :formInfo="formAction"
                @form-input="passData"
                :errors="errors"
                @btn-clicked="dialog = true"
                @dialog-closed="dialog = false"
                @clear-errors="clearErrors"
                v-if=" !tableInfo.editByPage && headers.find(header => header.value !== 'actions')"
              />

            <!-- This is de delete dialog and is handled with the dialogDelete Method -->
            <DeleteDialog
              :deletedItem="deletedItem"
              :dialogDelete="dialogDelete"
              :title="$t('delete message', [deletedItem.name])"
              @dialog-closed="dialogDelete = false"
              @delete-confirmed="deleteItemConfirm"
              v-if=" !tableInfo.editByPage && headers.find(header => header.value !== 'actions')"
            />

          </v-toolbar>
        </template>
        <!-- The active switch button on the table -->
        <template v-slot:item.active="{ item }">
          <v-switch v-model="item.active" @change="handleActive(item)"></v-switch>
        </template>

        <!-- Icons on the table -->
        <template v-slot:item.icon="{ item }">
          <v-icon

            class="mr-2"
            color="accent"
          >
            mdi-{{item.icon}}
          </v-icon>
        </template>

        <!-- These are the action buttons (edit and delete buttons) on the table -->
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
            {{ $t('reset')}}
          </v-btn>
        </template>
        <!-- Slide-out menu in the table -->
        <template v-slot:expanded-item="{ headers, item }" v-if="expand">
          <td :colspan="headers.length" class="crud-table__expand">
            <p
              class="crud-table__expand--header primary--text"
              v-if="expandElements.headerTitle">{{expandElements.headerTitle}} {{ item[expandElements.headerItem] }}</p>
            <p
              class="crud-table__expand--rows"
              v-for="n in expandElements.numberRows"
              v-if="item[`row${n}`]">{{ item[`row${n}`] }}</p>
            <p
              class="primary--text crud-table__expand--footer"
              v-if="expandElements.footerTitle">{{expandElements.footerTitle}} {{item[expandElements.footerItem]}}</p>
          </td>

        </template>
      </v-data-table>
    </div>
    </template>

    <script>
    import CloseAlert from "~/components/alerts/CloseAlert.vue";
    import FormDialog from "~/components/dialogs/FormDialog";
    import DeleteDialog from "~/components/dialogs/DeleteDialog";
    export default {
      components: { CloseAlert, FormDialog, DeleteDialog },
      props: {
        tableInfo: {
          type: Object,
          default: () => {
            return {
                sortBy : 'index',
                itemKey : 'id',
                editByPage : false,
                newBtn : 'Nieuw',
                editTitle: 'Aanpassen',
                noResultText: '',
                searchLabel: '',

            }
          },
        },
        items: {
          type: Array,
          required: true,
        },
        headers: {
          type: Array,
          required: true,
        },
        formItems : {
            type: Array,
            required: true,
        },
        expand: {
          type: Boolean,
          default: false,
        },
        expandElements: {
          type: Object,
          default: () => {
            return {
                headerTitle: '',
                headerItem: '', // Item for the header in the expand from items
                numberRows: 0,
                footerTitle: '',
                footerItem: '' // Item for the footer in the expand from items
            }
          },
        },
        defaultItem: {
          type: Object,
          required: false,
        },
        dialogSucces: {
          type: Object,
        },
        dialogError: {
          type: Object,
        },
        succesMessage: {
          type: String,
        },
        loading: {
          type: Boolean,
          default: false,
        },
      },
      data: () => ({
        search: "",
        dialog: false,
        dialogDelete: false,
        loadingDialog: false,
        deletedItem: {},
        expanded: [],
        setItems: [],
        editedItem: null,
        editedId: null,
        editedIndex: -1,
        pageLoad: null,
        errorMessage: "",
        errors: {},
      }),

      computed: {
        getResetForm() {
          console.log(this.tableInfo)
          return (this.tableInfo && 'dialog' in this.tableInfo && 'resetForm' in this.tableInfo.dialog) ? this.tableInfo.dialog.resetForm : false;
        },
        formTitle() {
          return this.editedIndex === -1
            ? this.tableInfo.newBtn
            : this.tableInfo.editTitle;
        },
        formAction() {
          return {
            title:
              this.editedIndex === -1
                ? this.tableInfo.newBtn
                : this.tableInfo.editTitle,
            action: this.editedIndex === -1 ? "create" : "update",
            selectAll: this.tableInfo.dialog.selectAll
              ? this.tableInfo.dialog.selectAll
              : null,
            selectAllName: this.tableInfo.dialog.selectAllName
              ? this.tableInfo.dialog.selectAllName
              : null,
            dialogWidth: this.tableInfo.dialog.width ? this.tableInfo.dialog.width : '80vw',
            newBtn: this.tableInfo.newBtn,
            icon: this.tableInfo.icon ? this.tableInfo.icon : false
          };
        },
        alertMessage() {
          return this.succesMessage
            ? this.succesMessage
            : this.$store.state.admin.succesMessage;
        },
        valuesFormItems() {
          return this.editedIndex === -1 ? this.defaultItem : this.editedItem;
        },
      },

      watch: {
        dialog(val) {
          val || this.close();
        },
        dialogDelete(val) {
          val || this.closeDelete();
        },
        loading() {
          if (!this.loading) this.initialize();
        },
        $route() {
          //this.routeArr = this.$route.path.split('/');
          if (this.$store.state.admin[this.tableInfo.admin])
            this.pageLoading = false;
        },
        dialogSucces() {
          if (
            this.dialogSucces &&
            Object.keys(this.dialogSucces).length !== 0 &&
            this.dialogSucces.constructor === Object
          ) {
            this.loadingDialog = false;
            this.editedItem = { ...this.editedItem, ...this.dialogSucces };
            this.save();
          }
        },
        dialogError() {
          if (
            this.dialogError &&
            Object.keys(this.dialogError).length !== 0 &&
            this.dialogError.constructor === Object
          ) {
            this.loadingDialog = false;
            this.errorMessage = this.dialogError.message;
            if(this.dialogError.errors) this.errors = this.dialogError.errors;
          }
        },
      },
      created() {
        this.editedItem = this.defaultItem;
        this.initialize();
      },

      methods: {
        initialize() {
          this.setItems = this.items;
          this.pageLoad = this.loading;
        },
        goToNewPage() {
          this.$router.push(this.tableInfo.newHref);
        },
        closeDialog() {
          this.dialog = false;
        },
        editItem(item) {
          if (this.tableInfo.editByPage) {
            this.$router.push(this.tableInfo.editHref + item.id);
          } else {
            this.editedIndex = this.setItems.indexOf(item);
            if (item.id) this.editedId = item.id;
            const itemArray = Object.entries(item);
            const filtered = itemArray.filter(
              ([key, value]) => key in this.defaultItem
            );
            this.editedItem = Object.fromEntries(filtered);
            //this.editedItem = Object.assign({}, item);
            this.dialog = true;
          }
        },
        handleActive(item) {
          // Set hidden select input
        },
        clearErrors() {
          this.errorMessage = "";
          this.errors = {};
        },
        deleteItem(item) {

          this.deletedItem["name"] = item.label
            ? item.label
            : item[this.tableInfo.deletedItem];
          this.deletedItem["id"] = item.id;
          this.deletedItem["type"] = this.tableInfo.multipleTableKey
            ? item[this.tableInfo.multipleTableKey]
            : null;
          this.editedIndex = this.setItems.indexOf(item);
          this.editedItem = Object.assign({}, item);
          this.dialogDelete = true;
        },

        deleteItemConfirm() {
          // Set hidden input
          this.setItems.splice(this.editedIndex, 1);
          this.closeDelete();
        },

        close() {
          this.dialog = false;
          this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
          });
        },

        closeDelete() {
          this.dialogDelete = false;
          this.$nextTick(() => {
            this.editedItem = Object.assign({}, this.defaultItem);
            this.editedIndex = -1;
          });
        },
        save() {
          if (this.editedIndex > -1) {
            Object.assign(this.setItems[this.editedIndex], this.editedItem);
          } else {
            this.setItems.unshift(this.editedItem);
          }
          this.close();
        },
        passData(input) {
          this.loadingDialog = true;
          this.editedItem = input;
          if (this.editedIndex !== -1) input.id = this.editedId;
          // Set form data
        },
      },
    };
    </script>

    <style lang="scss" scoped>
    .crud-table {
      min-width: 100%;
      &__expand {
        &:first-child {
          padding-top: 14px;
        }
        &:last-child {
          padding-bottom: 14px;
        }
        & p {
          margin-bottom: 0;
        }
      }
      &__delete {
        word-break: normal;
      }
    }
    </style>
