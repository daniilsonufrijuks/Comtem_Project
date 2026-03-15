<template>
    <Navbar :routes="routes"/>
    <Search />
    <Slider/>
    <div class="main-container">
        <Visitit />
        <Categories />
        <PCCalc />
        <PCPriceCalculator />
<!--        <Productsintro />-->
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
import Productsintro from "../Components/Productsintro.vue";
import Contact from "../Components/Contact.vue";
import Search from "../Components/Search.vue";
import Categories from "../Components/Categories.vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import ProductCardDB from "@/Components/ProductCardDB.vue";
import PCCalc from "@/Components/PCCalc.vue";
import PCPriceCalculator from "@/Components/PCPriceCalc.vue";

export default {
    name: 'Market',
    components: {
        PCPriceCalculator,
        ProductCardDB,
        Navbar,
        Visitit,
        Slider,
        Productsintro,
        Contact,
        Search,
        Categories,
        Footer,
        PCCalc,
    },
    props: {
        routes: Object
    },
    data() {
        return {
            products: [], // Store products fetched from API
            filters: {
                price_min: 0,
                price_max: 100000,
            },
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        // fetchProducts() {
        //     fetch('/products/laptops') // Adjust API endpoint if necessary
        //         .then((response) => response.json())
        //         .then((data) => {
        //             console.log('Fetched products:', data);
        //             this.products = data;
        //         })
        //         .catch((error) => {
        //             console.error('Error fetching products:', error);
        //         });
        // },
        fetchProducts() {
            const params = new URLSearchParams({
                price_min: this.filters.price_min ?? 0,
                price_max: this.filters.price_max ?? 100000,
            }).toString();

            // Fetch both PCs and Laptops in parallel
            Promise.all([
                fetch(`/products/pcs?${params}`),
                fetch(`/products/laptops?${params}`)
            ])
                .then(([pcsResponse, laptopsResponse]) => {
                    if (!pcsResponse.ok || !laptopsResponse.ok) {
                        throw new Error('Error fetching products');
                    }
                    return Promise.all([pcsResponse.json(), laptopsResponse.json()]);
                })
                .then(([pcs, laptops]) => {
                    console.log('Fetched PCs:', pcs);
                    console.log('Fetched Laptops:', laptops);
                    this.products = [...pcs, ...laptops]; // Combine the results
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
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 25px;
    max-width: 1200px;
    margin: auto;
}

/* laptop */
@media (max-width: 1105px) {
    .products {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* tablet */
@media (max-width: 850px) {
    .products {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* mobile */
@media (max-width: 500px) {
    .products {
        grid-template-columns: 1fr;
    }
}


.filters {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

@media screen and (max-width: 768px) {
    .filters {
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .filters input,
    .filters select {
        width: 100%;
        max-width: 300px;
    }
}
</style>
