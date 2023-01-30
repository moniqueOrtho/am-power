<template>
    <div class="story">
        <editor-menu
        :container="false"
        direction="left"
        :actions="actions"
        :position="{top: true, right: true, bottom: false, left: false}"
        @big-activator="bigActivatorClicked"
        @action-btn="actionBtnClicked"
        :labels="labels"
        >
        </editor-menu>
        <input class="chic__heading-3 mb-5 editor__box editor__box--title" v-model="section.title" :placeholder="text.title"  @focus="deleteFocus">
        <textarea class="chic__subheading-2 chic__subheading-2--dark mb-8 editor__box editor__box--subtitle" v-model="section.subtitle" :placeholder="text.subtitle"  @focus="deleteFocus" rows="2"></textarea>
        <p class="story__text mb-10 mb-8 editor__box" contenteditable>{{ section.text }}</p>
        <button class="chic__btn">{{ section.body.button }}</button>
    </div>
</template>

<script>
import EditorMenu from "../../tools/EditorMenu.vue";
import CurlBtn from "../../buttons/CurlBtn.vue";
import CloseAlert from "../../alerts/CloseAlert.vue";
export default {
    components: {EditorMenu, CurlBtn, CloseAlert},
    props: {
        sequence: {
            type: Number,
            required: true
        },
        data: {
            type: Object,
            required: true
        },
        success: {
            type: String,
            default: ''
        },
        error: {
            type: String,
            default: ''
        },
        labels: {
            type: Object,
            required: false
        },
        emits: ['save-section', 'delete-message'],
    },
    created() {
        this.setLabels();
        this.initialize();
    },
    data() {
        return {
            section: {
                title: '',
                subtitle: '',
                text: '',
                body: {
                    button: 'Find your own home'
                }
            },
            text: {
                add: 'Add',
                title: 'Title',
                subtitle: 'Subtitle',
                save: 'Save',
                noTitle: 'No title',
                happyCustomers: 'Happy customers',
                bestDecision: 'The best decision for our company!'
            },
            changed: false,
            actions: ['add', 'no-title'],
            title: false,
            subtitle: false,
            focusedId: null
        }
    },
    computed: {
        getAlertColor() {
            return this.error ? 'red-me' : 'green-me';
        },
        getAlertMessage() {
            let message = '';
            if(this.error) message = this.error;
            if(this.success) message = this.success;
            return message;
        },
        getAlertType() {
            return this.error ? 'error' : 'success';
        },
    },
    methods: {
        initialize() {
            this.section.title = this.data.title ? this.data.title : this.text.happyCustomers;
            this.title = true;

            this.section.subtitle = this.data.subtitle ? this.data.subtitle : `“${this.text.bestDecision}”`;
            this.subtitle = true;

            if(this.data.body) {
                this.section.body = JSON.parse(this.data.body);
            }

            this.section.text = this.data.text ? this.data.text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus. Quidem consequatur harum volupta!';

        },
        setLabels() {
            if(this.labels) {
                let keys = Object.keys(this.text);
                keys.forEach(k => this.text[k] = this.labels[k]);
            }
        },
        bigActivatorClicked(closed) {
            this.maker = ! closed;
            if(this.error || this.success) {
                this.$emit('delete-message');
            }
        },
        actionBtnClicked(action) {
            switch (action.name) {
                case 'view':
                    this.viewBtn();
                    break;
                case 'add':
                    this.addFeature();
                    break;
                case 'title':
                    this.addTitle(action.name);
                    break;
                case 'no-title':
                    this.deleteTitle(action.name);
                break;
                case 'subtitle':
                    this.addSubtitle();
                    break;
                default:
                    break;
            }
        },
        viewBtn() {
            this.maker = false;
        },
        addFeature() {
            this.deleteFocus();
            this.changed = true;
            let feature = { id:`feature-${this.section.body.length}`, title: `${this.labels.feature} ${this.section.body.length + 1}`, icon: '', text: 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur distinctio necessitatibus pariatur voluptatibus.'};
            this.section.body.push(feature);
        },
        setRightTitleBtn(title, newbtn) {
            let index = this.actions.indexOf(title);
            if(index > -1) {
                this.$set(this.actions, index, newbtn);
            }
        },
        addTitle(title) {
            this.title = true;
            this.deleteFocus();
            this.setRightTitleBtn(title, 'no-title');
        },
        deleteTitle(title) {
            this.deleteFocus();
            this.title = false;
            this.setRightTitleBtn(title, 'title');
            if(this.section.title) {
                this.section.title = null;
                this.changed = true;
            }

        },
        addSubtitle() {

            his.changed = true;
        },
        featureTitleIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.body.findIndex(x => x.id === feature);
            if(index > -1) this.section.body[index]['title'] = e.target.innerText;
            this.changed = true;
        },
        featureTextIsChanged(e) {
            const feature = e.target.parentElement.id;
            let index = this.section.body.findIndex(x => x.id === feature);
            if(index > -1) this.section.body[index]['text'] = e.target.innerText;
            this.changed = true;
        },
        curlClicked(data) {
            const index = this.section.body.findIndex(x => x.id === data.id);
            if(index > -1) {
                this.section.body.splice(index, 1);
                this.changed = true;
            }
        },
        deleteFocus() {
            if(this.focusedId) {
                let old = document.getElementById(this.focusedId);
                old.style.removeProperty('border');
                old.style.removeProperty('box-shadow');
            }
        },
        makerClicked(id) {
            this.deleteFocus();
            let newEl = document.getElementById(id);
            newEl.style.cssText = `border: 1px solid var(--v-primary-base);
                box-shadow: 0 0 3px var(--v-primary-base);`
            this.focusedId = id;
        },
        save() {
            this.changed = false;
            this.maker = false;
            let text = this.section.body.map((s) => {
                return `<h6 class="text-h6">${s.title}</h6><p>${s.text}</p>`
            });
            this.$emit('save-section', {
                id : this.data === null ? null : this.data.id,
                sequence: this.sequence,
                component: 'TheFeatures',
                title: this.section.title,
                subtitle: this.section.subtitle,
                body: JSON.stringify(this.section.body),
                text: text.toString()

            });
        }

    }

}
</script>

<style lang="scss" scoped>
    .story {
        padding: 3.75rem 8vw;
        display: grid;
        align-content: center;
        justify-items: start;
        position: relative;


        &__text {
            font-size: 1rem;
            font-style: italic;
        }
    }

</style>
