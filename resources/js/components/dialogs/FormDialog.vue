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

        <v-card :loading="loading">
            <v-form
                @submit.stop.prevent="save"
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
                                    :rules="element.rules"
                                    :error-messages="element.name in errors ? errors[element.name][0] : '' "
                                    @change="inputChanged(element.name)"
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
                                    :rules="element.rules"
                                    :counter="element.counter"
                                    clearable
                                    background-color="grey lighten-4"
                                    :required="element.required"
                                    :error-messages="element.name in errors ? errors[element.name][0] : '' "
                                    @input="inputChanged(element.name)"
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
                    <v-btn type="submit" color="primary" large>{{labels.save}}</v-btn>
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
        loading: {
            type: Boolean,
            default: false
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
        },
        resetForm: {
            type: Boolean,
            default: false,
        }
    },
    emits: ['close-dialog', 'save-input', 'btn-clicked', 'clear-error'],
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
                counter: false,
                disabled: false,
                required: false,
                rules: []
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
            let changed, newObj = Object.assign({}, base), newElement = Object.assign({}, element);
            changed = Object.keys(element);
            changed.forEach(input => {
                if( input in base) {
                    delete newObj[input];
                }
                if(input === 'rules') {
                    newElement[input] = this.getRules(element.rules, element.label, element.counter);
                }
            })
            return {...newElement, ...newObj};
        },
        close() {
            this.$emit('close-dialog')
        },
        save() {
            if(this.$refs.form.validate()) {
                this.$emit('save-input', this.inputValue);
            }
        },
        rules(rule, name, counter) {
            if(rule === 'required') {
                return (v) => !!v || `${name} ${this.labels.required}`
            } else if(rule === 'email') {
                return (v) => /.+@.+\..+/.test(v) || this.labels.emailInvalid
            } else if(rule === 'max-counter') {
                let text = this.labels.maxCounter.replace('*vue*', counter);
                return (v) => (v && v.length <= counter) || `${name} ${text}`
            } else {
                return [];
            }
        },
        getRules(rule, name, counter) {
            let vueRules = [], vueRule, vueArr;
            if(typeof rule === 'string') {
                vueRule = this.rules(rule, name, counter);
                vueRules.push(vueRule);
            } else if( typeof rule === 'array') {
                vueRules = rule.map(r => {
                    return this.rules(r, name, counter);
                });
            } else if(typeof rule === 'object') {
                vueArr = Object.values(rule);
                vueRules = vueArr.map(r => {
                    return this.rules(r, name, counter);
                });
            } else {
                vueRules = [];
            }
            return vueRules;
        },
        inputChanged(name) {
            if(name in this.errors) {
                console.log(name)
                this.$emit('clear-error', name)
            }
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
        resetForm(value) {
            if(value) {
                this.$refs.form.reset();
                this.initialize();
            }
        }
    }

}
</script>

<style lang="scss" scoped>
@import "../../../sass/base/variables";
    .form-dialog {
        font-family: $body-font-family;
    }
</style>
