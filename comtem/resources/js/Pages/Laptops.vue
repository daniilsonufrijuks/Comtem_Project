<template>
    <Navbar :routes="routes" />
    <Search />
    <Slider />
    <div class="main-container">
        <Visitit />
        <div class="products">
            <ProductCardDB v-for="product in products" :key="product.id" :product="product" />
        </div>
        <Contact />
    </div>
    <Footer />
</template>

<script>
import Visitit from '../Components/Visitit.vue';
import Slider from '../Components/Slider.vue';
import Contact from '../Components/Contact.vue';
import Search from '../Components/Search.vue';
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import ProductCard from "@/Components/ProductCard.vue";
import ProductCardDB from "@/Components/ProductCardDB.vue";

export default {
    name: 'ComponentsPage',
    components: {
        ProductCardDB,
        ProductCard,
        Navbar,
        Visitit,
        Slider,
        Contact,
        Search,
        Footer,
    },
    props: {
        routes: Object,
    },
    data() {
        return {
            products: [], // Store products fetched from API
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            fetch('/products/laptops') // Adjust API endpoint if necessary
                .then((response) => response.json())
                .then((data) => {
                    console.log('Fetched products:', data);
                    this.products = data;
                })
                .catch((error) => {
                    console.error('Error fetching products:', error);
                });
        },
    },
};
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    gap: 70px; /* Adjust as needed */
}
.products {
    display: flex;
    flex-wrap: wrap;
    gap: 20px; /* Adjust spacing between product cards */
    justify-content: center; /* Center product cards */
}
</style>
