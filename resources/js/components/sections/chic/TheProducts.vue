<template>
    <div class="products-maker__container">
        <editing-bar
            :actions="actions"
            :changed="maker && changed"
            @editor-active="bigActivatorClicked"
            @save-section="save"
            @title-actions="titleActions"
        ></editing-bar>
        <section class="products-maker" >
            <input class="chic__heading-4 chic__heading-4--dark products-maker__title editor__box editor__box--title" v-model="section.title" :placeholder="labels.title"  v-if="titles.title && maker">
            <div class="product-maker"
                v-for="product in section.body"
                :key="product.name"
            >
                <img :src="product.src" :alt="product.alt" class="product-maker__img">
                <font-awesome-icon icon="fa-solid fa-info-circle" class="product-maker__info" @click="openProductInfo(product)"/>
                <h5 class="product-maker__name">{{ product.title }}</h5>
                <div :class="`product-maker__attribute ${index < 2 ? 'product-maker__attribute--first-row': ''}`" v-for="(attr, index) in product.features" :key="index">
                    <v-tooltip left>
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                                class="product-maker__icon"
                                color="primary"
                                size="1.5rem"
                                v-bind="attrs"
                                v-on="on"
                            >{{ attr.icon }}</v-icon>
                        </template>
                        <span>{{ attr.label }}</span>
                    </v-tooltip>
                    <p>{{ attr.text }}</p>
                </div>
                <button class="chic__btn chic__btn--filled product-maker__btn">Order</button>
            </div>
        </section>
        <v-dialog
            v-model="dialog"
            max-width="80%"
        >
            <div class="info-maker" v-if="dialog">
                <font-awesome-icon icon="fa-solid fa-times" class="info-maker__close" @click="close"/>
                <img :src="info.src" :alt="info.alt" class="info-maker__img">
                <ribbon class="info-maker__price" :color="$vuetify.theme.themes.light.primary">
                    <p>&euro; {{ price }}</p>
                </ribbon>
                <div class="info-maker__features">
                    <div class="info-maker__attr"  v-for="attr in info.features">
                        <v-icon color="primary" size="1.5rem">mdi-check</v-icon>
                        <div class="info-maker__text">
                            <span>{{ attr.label }}: </span><span>{{  attr.text}}</span>
                        </div>
                    </div>
                </div>
                <div class="info-maker__info">

                        <h4 class="info-maker__title">{{ info.title }}</h4>


                    <div class="info-maker__paragraph">{{ getParagraph }}</div>
                </div>


            </div>
        </v-dialog>

    </div>

</template>

<script>
import balloon1 from "../../../../images/one-balloon.jpg";
import balloon2 from "../../../../images/two-balloons.jpg";
import balloon3 from "../../../../images/balloons4.jpg";
import diagram from "../../../../images/hand-met-diagram.jpg"
import parachute from "../../../../images/parachute.jpg";
import balloons from "../../../../images/small-balloons.jpg";
import Ribbon from "../../themesComponents/Ribbon.vue";

import CurlBtn from "../../buttons/CurlBtn.vue";
import CloseAlert from "../../alerts/CloseAlert.vue";
import EditingBar from '../../tools/EditingBar.vue';

export default {
    name: 'products-section',
    components: {Ribbon, CurlBtn, CloseAlert, EditingBar},
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
        }
    },
    emits: ['save-section', 'delete-message'],
    created() {
        this.initialize();
    },
    data() {
        return {
            dialog: false,
            info: {},
            price: null,
            section: null,
            maker: false,
            titles: {
                title: false,
                subtitle: false,
            },
            changed: false,
            actions: ['title', 'subtitle'],
            products: [
                {id: '1', title: 'Product 1', src: balloon1, alt: 'One balloon', features: [{icon: 'mdi-web', text: 1, label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 400, label: 'Price'}]},
                {id: '2', title: 'Product 2', src: balloon2, alt: 'Two balloons', features: [{icon: 'mdi-web', text: 2, label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 700, label: 'Price'}]},
                {id: '3', title: 'Product 3', src: balloon3, alt: 'Three balloons', features: [{icon: 'mdi-web', text: '3-10', label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1000, label: 'Price'}]},
                {id: '4', title: 'Product 4', src: diagram, alt: 'Diagram', features: [{icon: 'mdi-web', text: 1, label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 800, label: 'Price'}]},
                {id: '5', title: 'Product 5', src: parachute, alt: 'Parachute', features: [{icon: 'mdi-web', text: 2, label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1100, label: 'Price'}]},
                {id: '6', title: 'Product 6', src: balloons, alt: 'Balloons', features: [{icon: 'mdi-web', text: '3-10', label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1500, label: 'Price'}]}
            ],
            loremIpsem: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tristique senectus et netus et malesuada fames ac. Phasellus vestibulum lorem sed risus ultricies. Quam nulla porttitor massa id neque aliquam. Non sodales neque sodales ut etiam sit amet nisl. Nunc mattis enim ut tellus elementum sagittis vitae et. Habitasse platea dictumst quisque sagittis purus sit. Ac tincidunt vitae semper quis lectus nulla at volutpat. Maecenas pharetra convallis posuere morbi leo urna molestie at. Facilisi morbi tempus iaculis urna id volutpat lacus laoreet. Mi sit amet mauris commodo quis. Velit dignissim sodales ut eu sem integer. Amet mauris commodo quis imperdiet. Interdum varius sit amet mattis. Tristique et egestas quis ipsum. Aliquam etiam erat velit scelerisque in dictum non consectetur. Nunc sed id semper risus in hendrerit gravida. Gravida dictum fusce ut placerat orci.'
        }
    },
    methods: {
        initialize() {
            this.section = Object.assign({}, this.data);
            if(!this.data.body) {
                this.section.body = [...this.products];
            }

        },
        openProductInfo(product) {
            this.dialog = true;
            this.info = Object.assign({}, product);
            this.info.features = this.info.features.filter(i => i.label !== 'Price');
            this.price = product.features.find(i => i.label === 'Price').text;
        },
        close() {
            this.dialog = false;
            this.info = {}
            this.price = null;
        },
        bigActivatorClicked(closed) {
            this.maker = ! closed;
            if(this.error || this.success) {
                this.$emit('delete-message');
            }
        },
        titleActions(titleObj) {
            let key = Object.keys(titleObj)[0];
            console.log(key)
            this.$set(this.titles, key, titleObj[key]);
            if(this.section[key] && !titleObj[key]) {
                this.section[key] = null;
                this.changed = true;
            }
        },
        save() {
            this.changed = false;
            this.maker = false;
            this.$emit('save-section', {
                id : this.data === null ? null : this.data.id,
                sequence: this.sequence,
                component: 'TheProducts',
                title: this.section.title,
                subtitle: this.section.subtitle,
                body: JSON.stringify(this.section.body),
                text: this.section.text
            });
        }
    },
    computed: {
        labels() {
            return this.$store.getters['labels/getTranslations'];
        },
        getParagraph() {
            if(this.section.text.length === 0) {
                return this.loremIpsem
            } else {
                let index = this.section.text.findIndex(t => t.id === info.id)
                return index > -1 ? this.section.text[index] : this.loremIpsem;
            }
        },
    }

}
</script>

<style lang="scss" scoped>
    .products-maker {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
        grid-gap: 4.375rem;
        margin: 9rem 0;
        &__container {
            position: relative;
        }
        &__title {
            grid-column: 1/-1;
            justify-self: center;
            text-align: center;
        }
    }
    .product-maker {
        background-color: var(--v-light1-base);
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-row-gap: 2.2rem;
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);

        &__img {
            width: 100%;
            grid-row: 1/2;
            grid-column: 1/-1;
            aspect-ratio: 3/2;
            object-fit: cover;
            z-index: 1;
        }

        &__info {
            grid-row: 1/2;
            grid-column: 2/3;
            z-index: 2;
            font-size: 2rem;
            color: var(--v-light1-base);
            justify-self: end;
            margin: .6rem;
            cursor: pointer;
            transition: all .4s;
            &:hover {
                color: var(--v-primary-base);
            }
        }

        &__name {
            grid-row: 1/2;
            grid-column: 1/-1;
            justify-self: center;
            align-self: end;
            width: 80%;
            z-index: 3;

            font-family: 'Josefin Sans', sans-serif;
            font-size: 1rem;
            text-align: center;
            padding: .8rem;
            background-color: var(--v-tertiary-base);
            color: var(--v-light1-base);
            font-weight: 400;
            transform: translateY(50%);
        }

        &__btn {
            grid-column: 1/-1;
        }

        &__attribute {
            font-size: 1rem;
            margin-left: 1.25rem;
            display: flex;
            align-items: center;

            &--first-row {
                margin-top: 1.6rem;
            }

            p {
                margin-bottom: 0;
            }
        }

        &__icon {
            margin-right: 1rem;
        }
    }

    .info-maker {
        background-color: var(--v-light1-base);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(23rem, 1fr));
        grid-gap: 1.5rem;
        padding: 1.5rem;
        border-top: 2.5rem solid var(--v-primary-base);
        border-left: none;
        position: relative;

        @media screen and (min-width: 1074px) {
            border-left: 2.5rem solid var(--v-primary-base);
            border-top: none;
        }


        &__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            grid-row: 1/2;
            grid-column: 1/1;
        }

        &__close {
            position: absolute;
            top: -2.25rem;

            font-size: 2rem;
            color: var(--v-light1-base);
            cursor: pointer;

            &:hover {
                color: var(--v-secondary-base);
            }

            @media screen and (max-width: 1073px) {
                left: .6rem;
            }

            @media screen and (min-width: 1074px) {
                color: var(--v-primary-base);
                top: .6rem;
                right: .6rem;
            }
        }

        &__price {
            grid-row: 1/2;
            grid-column: 1/1;
            z-index: 3;
            justify-self: end;
            margin-right: 0.5rem;

            p {
                padding: .5rem;
                color: var(--v-light1-base);
                font-weight: 400;
            }
        }

        &__features {
            grid-row: 2/3;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        &__attr {
            display: flex;
        }

        &__title {
            font-family: 'Josefin Sans', sans-serif;
            font-size: 1.2rem;
            font-weight: 400;
            border-bottom: 2px solid var(--v-primary-base);
            margin-bottom: 1rem;
            display: inline-block;
        }

        &__paragraph {

        }
    }

</style>
