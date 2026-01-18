<template>
    <div class="search-results-page">
        <Navbar :routes="routes"/>
        <Search />
        <div class="container">
            <h2>Search Results for "{{ query }}"</h2>

            <p class="results-count" v-if="products.length > 0">
                Found {{ products.length }} product(s)
            </p>

            <div v-if="products.length === 0" class="no-results">
                <p>No products found for "{{ query }}".</p>
                <p>Try checking your spelling or using more general terms.</p>
            </div>

            <div class="products" v-else>
                <ProductCardDB
                    v-for="p in products"
                    :key="p.id"
                    :product="p"
                />
            </div>
        </div>
    </div>
</template>


<script>
import ProductCardDB from "@/Components/ProductCardDB.vue";
import Search from "@/Components/Search.vue";
import Navbar from "@/Components/Navbar.vue";

export default {
    props: {
        routes: Object,
        products: Array,
        query: String,
    },

    components: {
        Search,
        ProductCardDB,
        Navbar
    },
};
</script>

<style scoped>
.search-results-page {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

.container {
    flex: 1;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 40px;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    box-sizing: border-box;
}

h2 {
    font-size: 1.8rem;
    margin-bottom: 0;
    word-break: break-word;
}

.results-count {
    color: #666;
    margin: 0;
    font-size: 1rem;
}

.no-results {
    text-align: center;
    padding: 40px 20px;
    color: #666;
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.products {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* Two equal columns */
    gap: 20px; /* Adjust spacing between product cards */
    justify-content: center; /* Center product cards */
    max-width: 1000px; /* Optional: Set a max width for better alignment */
    margin: 0 auto; /* Center the grid */
}

@media (max-width: 1120px) {
    .products {
        grid-template-columns: 1fr; /* One column on smaller screens */
    }
}


</style>
