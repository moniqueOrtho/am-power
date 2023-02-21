<template>
    <section class="products">
        <div class="product"
            v-for="product in products"
            :key="product.name"
        >
        {{ product.title }}
        </div>
    </section>
</template>

<script>
export default {
    name: 'products-section',
    data() {
        return {
            products: [
                {name: 'home-card-1', title: 'Home card 1'},
                {name: 'home-card-2', title: 'Home card 2'},
                {name: 'home-card-3', title: 'Home card 3'},
                {name: 'home-card-4', title: 'Home card 4'},
                {name: 'home-card-5', title: 'Home card 5'},
                {name: 'home-card-6', title: 'Home card 6'}
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
    }

</style>
