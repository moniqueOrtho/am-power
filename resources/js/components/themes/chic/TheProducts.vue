<template>
    <div class="products__container">
        <section class="products">
            <div class="product"
                v-for="product in products"
                :key="product.name"
            >
                <img :src="product.src" :alt="product.alt" class="product__img">
                <font-awesome-icon icon="fa-solid fa-info-circle" class="product__info" @click="openProductInfo(product)"/>
                <h5 class="product__name">{{ product.title }}</h5>
                <div :class="`product__attribute ${index < 2 ? 'product__attribute--first-row': ''}`" v-for="(attr, index) in product.features" :key="index">
                    <v-tooltip left>
                        <template v-slot:activator="{ on, attrs }">
                            <v-icon
                                class="product__icon"
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
                <button class="chic__btn chic__btn--filled product__btn">Order</button>
            </div>
        </section>
        <v-dialog
            v-model="dialog"
            max-width="80%"
        >
            <div class="info-dialog">
                <img :src="info.src" :alt="info.alt" class="info-dialog__img">
                <div class="info-dialog__price">
                    <p>&euro; {{ price }}</p>
                </div>
                <div class="info-dialog__features">
                    <div class="info-dialog__attr"  v-for="attr in info.features">
                        <v-icon color="primary" size="1.5rem">mdi-check</v-icon>
                        <div class="info-dialog__text">
                            <span>{{ attr.text }} </span><span>{{ attr.label }}</span>
                        </div>
                    </div>
                </div>
                <div class="info-dialog__info">
                    <h4 class="info-dialog__title">{{ info.title }}</h4>
                    <div class="info-dialog__paragraph">{{ getParagraph }}</div>
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

export default {
    name: 'products-section',
    data() {
        return {
            dialog: false,
            info: {},
            price: null,
            products: [
                {id: '1', title: 'Product 1', src: balloon1, alt: 'One balloon', features: [{icon: 'mdi-web', text: 1, label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 400, label: 'Price'}]},
                {id: '2', title: 'Product 2', src: balloon2, alt: 'Two balloons', features: [{icon: 'mdi-web', text: 2, label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 700, label: 'Price'}]},
                {id: '3', title: 'Product 3', src: balloon3, alt: 'Three balloons', features: [{icon: 'mdi-web', text: '3-10', label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1000, label: 'Price'}]},
                {id: '4', title: 'Product 4', src: diagram, alt: 'Diagram', features: [{icon: 'mdi-web', text: 1, label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 800, label: 'Price'}]},
                {id: '5', title: 'Product 5', src: parachute, alt: 'Parachute', features: [{icon: 'mdi-web', text: 2, label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1100, label: 'Price'}]},
                {id: '6', title: 'Product 6', src: balloons, alt: 'Balloons', features: [{icon: 'mdi-web', text: '3-10', label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1500, label: 'Price'}]}
            ],
            text: [],
            loremIpsem: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Tristique senectus et netus et malesuada fames ac. Phasellus vestibulum lorem sed risus ultricies. Quam nulla porttitor massa id neque aliquam. Non sodales neque sodales ut etiam sit amet nisl. Nunc mattis enim ut tellus elementum sagittis vitae et. Habitasse platea dictumst quisque sagittis purus sit. Ac tincidunt vitae semper quis lectus nulla at volutpat. Maecenas pharetra convallis posuere morbi leo urna molestie at. Facilisi morbi tempus iaculis urna id volutpat lacus laoreet. Mi sit amet mauris commodo quis. Velit dignissim sodales ut eu sem integer. Amet mauris commodo quis imperdiet. Interdum varius sit amet mattis. Tristique et egestas quis ipsum. Aliquam etiam erat velit scelerisque in dictum non consectetur. Nunc sed id semper risus in hendrerit gravida. Gravida dictum fusce ut placerat orci.'
        }
    },
    methods: {
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
        }
    },
    computed: {
        getParagraph() {
            if(this.text.length === 0) {
                return this.loremIpsem
            } else {
                let index = this.text.findIndex(t => t.id === info.id)
                return index > -1 ? this.text[index] : this.loremIpsem;
            }
        }
    }

}
</script>

<style lang="scss" scoped>
    .products {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
        grid-gap: 4.375rem;
        margin: 9rem 0;
    }
    .product {
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

    .info-dialog {
        background-color: var(--v-light1-base);
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(23rem, 1fr));
        grid-gap: 1.5rem;
        padding: 1.5rem;

        &__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            grid-row: 1/2;
            grid-column: 1/1;
        }

        &__price {
            grid-row: 1/2;
            grid-column: 1/1;
            z-index: 3;
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



        &__text {

        }

        &__title {

        }

        &__paragraph {

        }
    }

</style>
