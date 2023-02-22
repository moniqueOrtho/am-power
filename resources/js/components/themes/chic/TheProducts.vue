<template>
    <section class="products">
        <div class="product"
            v-for="product in products"
            :key="product.name"
        >
            <img :src="product.src" :alt="product.alt" class="product__img">
            <h5 class="product__name">{{ product.title }}</h5>
            <div class="product__attribute" v-for="(attr, index) in product.features" :key="index">
                <v-icon class="product__icon" color="primary" size="2rem">{{ attr.icon }}</v-icon>
                <p>{{ attr.text }}</p>
            </div>
            <button class="chic__btn chic__btn--filled">Order</button>
        </div>
    </section>
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
            products: [
                {name: 'product-1', title: 'Product 1', src: balloon1, alt: 'One balloon', features: [{icon: 'mdi-web', text: 1, label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 400, label: 'Price'}]},
                {name: 'product-2', title: 'Product 2', src: balloon2, alt: 'Two balloons', features: [{icon: 'mdi-web', text: 2, label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 700, label: 'Price'}]},
                {name: 'product-3', title: 'Product 3', src: balloon3, alt: 'Three balloons', features: [{icon: 'mdi-web', text: '3-10', label: 'Website'}, {icon: 'mdi-material-design', text: 1, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 0, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1000, label: 'Price'}]},
                {name: 'product-4', title: 'Product 4', src: diagram, alt: 'Diagram', features: [{icon: 'mdi-web', text: 1, label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 800, label: 'Price'}]},
                {name: 'product-5', title: 'Product 5', src: parachute, alt: 'Parachute', features: [{icon: 'mdi-web', text: 2, label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1100, label: 'Price'}]},
                {name: 'product-6', title: 'Product 6', src: balloons, alt: 'Balloons', features: [{icon: 'mdi-web', text: '3-10', label: 'Website'}, {icon: 'mdi-material-design', text: 0, label: 'Standard design'}, {icon: 'mdi-pencil-ruler', text: 1, label: 'Own design'}, {icon: 'mdi-currency-eur', text: 1500, label: 'Price'}]}
            ]
        }
    }

}
</script>

<style lang="scss" scoped>
    .products {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(16rem, 1fr));
        grid-gap: 4.375rem;
        margin: 9.375rem;
    }
    .product {
        background-color: var(--v-light1-base);

        &__img {
            width: 100%;
        }

        &__name {

        }

        &__attribute {

            p {

            }
        }

        &__icon {

        }
    }

</style>
