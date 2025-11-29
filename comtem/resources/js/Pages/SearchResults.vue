<template>
    <div class="container">
        <h2>Search Results for "{{ query }}"</h2>

        <p class="results-count" v-if="products.length > 0">
            Found {{ products.length }} product(s)
        </p>

        <div v-if="products.length === 0" class="no-results">
            <p>No products found for "{{ query }}".</p>
            <p>Try checking your spelling or using more general terms.</p>
        </div>

        <div class="product-list" v-else>
            <ProductCardDB
                v-for="p in products"
                :key="p.id"
                :product="p"
            />
        </div>
    </div>
</template>

<script>
import ProductCardDB from "@/Components/ProductCardDB.vue";

export default {
    props: {
        products: Array,
        query: String,
    },

    components: {
        ProductCardDB,
    },
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.results-count {
    color: #666;
    margin-bottom: 20px;
}

.no-results {
    text-align: center;
    padding: 40px 0;
    color: #666;
}

.product-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    justify-content: center;
}

/* For larger screens */
@media (min-width: 768px) {
    .product-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    .product-list {
        grid-template-columns: repeat(3, 1fr);
    }
}
</style>
