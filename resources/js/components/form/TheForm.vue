<template>
    <v-form
        @submit.stop.prevent="save"
        ref="form"
        v-model="valid"
        lazy-validation
        class="pa-8 the-form"
    >
        <h6 class="text-h6 text-uppercase primary--text mb-6" >
            {{ formTitle }}
        </h6>

            <v-row>
                <v-col
                    v-for="(element, index) in elements"
                    :key="`${element.name}-${index}`"
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
                    <template v-if="element.input === 'textarea'">
                        <v-textarea
                            outlined
                            v-model="inputValue[element.name]"
                            :label="element.label"
                            :tabindex="(index + 1)"
                            clear-icon="mdi-eraser"
                            :type="element.type"
                            :rules="element.rules"
                            :counter="element.counter"
                            :row-height="element.rowHeight ? element.rowHeight : 15"
                            :rows="element.rows ? element.rows : 2"
                            clearable
                            background-color="grey lighten-4"
                            :required="element.required"
                            :error-messages="element.name in errors ? errors[element.name][0] : '' "
                            @input="inputChanged(element.name)"
                        ></v-textarea>
                    </template>
                    <template v-if="element.input === 'checkbox'">
                        <template v-if="element.type === 'selectAll'">
                            <v-divider></v-divider>
                            <v-checkbox
                                v-model="selectAll"
                                hide-details

                            >
                                <template v-slot:label>
                                    <span class="the-form__selected-all--label">{{ element.label }}</span>
                                </template>
                            </v-checkbox>

                        </template>


                        <v-checkbox
                            v-else
                            v-model="inputValue[element.name]"
                            :label="element.label"
                            :tabindex="(index + 1)"
                            :color="element.color ? element.color : 'primary'"
                            :rules="element.rules"
                            :value="element.value"
                        ></v-checkbox>
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
</template>

<script>
export default {
    props: {
        fields: {
            type: Array,
            default: () => []
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
        },
    },
    emits: ['close-dialog', 'save-input', 'clear-error'],
    created() {
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
            selectAll: false
        }
    },
    methods: {
        initialize() {
            this.inputValue = Object.assign({}, this.defaultItem);
        },
        setElements() {
            let inputs, searchResults, newObj;
            inputs = Object.keys(this.inputValue);
            inputs.forEach(input => {
                newObj = Object.assign({}, this.defaultInput);
                if(this.fields.length > 0) searchResults = this.fields.filter(x => x['name'] === input);
                if(searchResults) {
                    if(searchResults.length === 1) {
                        newObj = this.mergeAllElements(searchResults[0], newObj);
                        this.elements.push(newObj);
                    } else {
                        searchResults.forEach(result => {
                            newObj = this.mergeAllElements(result, newObj);
                            this.elements.push(newObj);
                            newObj = Object.assign({}, this.defaultInput);
                        })
                    }
                } else {
                   newObj['name'] = input;
                   newObj['label'] = input;
                   this.elements.push(newObj);
                }

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
            return {...newObj, ...newElement};
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
            } else if(rule === 'url') {
                return (v) => /[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/.test(v) || this.labels.urlInvalid
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
                this.$emit('clear-error', name)
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
        },
        selectAll(value) {
            let filtered;
            const selectAllEl = this.elements.find(x => x.type === 'selectAll');

            if(value) {
                filtered = this.elements.filter(x => x.name === selectAllEl.name && 'value' in x);
                filtered.forEach(el => this.inputValue[selectAllEl.name].push(el.value));
            } else {
                this.inputValue[selectAllEl.name] = []
            }
        }
    }

}
</script>

<style lang="scss" scoped>
@import "../../../sass/base/variables";
    .the-form {
        font-family: $body-font-family;
        &__selected-all--label {
            color: var(--v-primary-base);
            text-transform: uppercase;
            font-weight: 300;
            font-size: 1.25rem;
        }
    }
</style>
