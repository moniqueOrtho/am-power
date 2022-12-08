<template>
    <v-dialog
        v-model="setDialog"
        max-width="80vw"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
            >
                New Item
            </v-btn>
        </template>

        <v-card>
            <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
            </v-card-title>

        <div class="mx-3">
            <v-container>
                <v-form
                    @submit.prevent="validateForm"
                    ref="form"
                    v-model="valid"
                    lazy-validation
                >
                    <v-row>
                        <v-col
                        v-for="(element, index) in elements"
                        :key="index"
                        cols="12"
                        :sm="element.sm"
                        :md="element.md"
                        >
                            <v-select
                                v-if="element.input === 'select'"
                                v-model="inputValue[element.name]"
                                outlined
                                :tabindex="(index + 1)"
                                :items="element.items"
                                item-text="text"
                                item-value="value"
                                :label="element.label"
                                filled
                                color="primary"
                            >
                            </v-select>
                            <v-text-field
                                v-if="element.input === 'text'"
                                outlined
                                v-model="inputValue[element.name]"
                                :label="element.label"

                                :prepend-inner-icon="element.icon"
                                :type="element.type"
                                :counter="element.counter"
                                clearable
                                background-color="grey lighten-4"
                                :disabled="element.disabled"
                                :error-messages="element.name in errors ? errors[i.name][0] : '' "
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </div>
        <v-divider></v-divider>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
            type="button"
            color="secondary"
            @click="close"
            large
            >
            Cancel
            </v-btn>
            <v-btn
            type="button"
            color="primary" large
            @click="save"
            >
            Save
            </v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    props: {
        fields: {
            type: Array,
            default: () => []
        },
        dialogForm: {
            type: Boolean,
            default: false
        },
        defaultItem: {
            type: Object,
            required: true
        },
        errors: {
            type: Object,
            default: () => ({})
        },
        formTitle: {
            type: String,
            default: ''
        }
    },
    emits: ['close-dialog', 'save-input'],
    created() {
        this.initialize();
    },
    data() {
        return {
            dialog: false,
            inputValue: null,
            defaultInput: { // If selectbox add then input: 'select' and items: ['text': , 'value']
                sm: 6,
                md: 4,
                name: '',
                label: '',
                input: 'text',
                icon: 'mdi-pencil',
                type: 'text',
                counter: 20,
                disabled: false
            },
            elements: [],
            valid: true,
        }
    },
    methods: {
        initialize() {
            this.inputValue = Object.assign({}, this.defaultItem);
            this.setElements();
        },
        setElements() {
            let inputs, searchResult;
            inputs = Object.keys(this.inputValue);

            inputs.forEach((input) => {
                if(this.fields.length > 0) searchResult = this.fields.find(x => x['name'] === input);
                if(searchResult) {
                    this.mergeAllElements()
                } else {
                    this.elements.push( {...this.defaultInput, ...{name: input, label: input } } )
                }
            })


        },
        mergeAllElements(element) {
            let defaultInputArr, newObj = {};
            defaultInputArr = Object.keys(this.defaultInput);
            defaultInputArr.forEach(input => {
                if(!input in element) {
                    newObj[input] = this.defaultInput[input];
                }
            })
            this.elements.push({...newObj, ...element});

        },
        close() {
            this.$emit('close-dialog')
        },
        save() {
            this.$emit('save-input', this.inputValue)
        }
    },
    computed: {
        setDialog: {
        set(value) {
            this.close();
        },
        get() {
            return this.dialogForm
        }
        }
    }

}
</script>

<style lang="scss" scoped>

</style>
