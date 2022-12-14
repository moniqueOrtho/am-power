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
                @click.stop="$emit('btn-clicked')"
            >
                {{labels.newItem}}
            </v-btn>
        </template>

        <v-card>
            <v-form
                @submit.prevent="validateForm"
                ref="form"
                v-model="valid"
                lazy-validation
                class="pa-8 form-dialog"
            >
                <h6 class="text-h6 text-uppercase primary--text mb-6" >
                    {{ formTitle }}
                </h6>

                    <v-row>
                        <v-col
                            v-for="(element, index) in elements"
                            :key="element.name"
                            cols="12"
                            :sm="element.sm"
                            :md="element.md"
                        >
                            <template v-if="element.input === 'select'">
                                <v-select
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
                            </template>
                            <template v-if="element.input === 'text'">
                                <v-text-field
                                    outlined
                                    v-model="inputValue[element.name]"
                                    :label="element.label"
                                    :tabindex="(index + 1)"
                                    :prepend-inner-icon="element.icon"
                                    :type="element.type"
                                    :counter="element.counter"
                                    clearable
                                    background-color="grey lighten-4"
                                    :error-messages="element.name in errors ? errors[i.name][0] : '' "
                                ></v-text-field>
                            </template>
                        </v-col>
                    </v-row>


                <v-divider></v-divider>
                <div class="d-flex justify-end mt-3">
                    <v-btn
                        type="button"
                        color="secondary"
                        class="mr-4"
                        @click.stop="close"
                        large
                        >{{labels.cancel}}</v-btn
                    >
                    <v-btn type="button" color="primary" large @click="save">{{labels.save}}</v-btn>
                </div>

            </v-form>




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
        },
        labels: {
            type: Object,
            required: true
        }
    },
    emits: ['close-dialog', 'save-input', 'btn-clicked'],
    mounted() {
        this.initialize();
        this.setElements();
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
        },
        setElements() {
            let inputs, searchResult, newObj;

            inputs = Object.keys(this.inputValue);
            inputs.forEach(input => {
                newObj = Object.assign({}, this.defaultInput);
                if(this.fields.length > 0) searchResult = this.fields.find(x => x['name'] === input);
                if(searchResult) {
                    newObj = this.mergeAllElements(searchResult, newObj);
                } else {
                   newObj['name'] = input;
                   newObj['label'] = input
                }
                this.elements.push(newObj);
            });
        },
        mergeAllElements(element, base) {
            let changed, newObj = Object.assign({}, base);
            changed = Object.keys(element);
            changed.forEach(input => {
                if( input in base) {
                    delete newObj[input];
                }
            })
            return {...element, ...newObj};
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
    },
    watch: {
        defaultItem() {
            this.initialize();
        },
    }

}
</script>

<style lang="scss" scoped>
@import "../../../sass/base/variables";
    .form-dialog {
        font-family: $body-font-family;
    }
</style>
