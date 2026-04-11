<template>
<!--    <Navbar :routes="routes" />-->
    <Banner/>
    <RobotAssistant />
    <Navbar :routes="routes"/>
    <Search />
    <Slider/>
    <div class="main-container">
        <Visitit />
        <div class="products">
            <ProductCardDB v-for="product in products" :key="product.id" :product="product" />
        </div>
<!--        <Productsintro />-->
        <Testimonial />
        <chatwithai />
        <SectionCom />
        <Contact />
        <BetaPopUp />
    </div>
    <Footer />
</template>

<script>
import Visitit from '../Components/Visitit.vue';
import Slider from '../Components/Slider.vue';
import Productsintro from "../Components/Productsintro.vue";
import Contact from "../Components/Contact.vue";
import Search from "../Components/Search.vue";
import Testimonial from "../Components/Testimonial.vue";
import AboutUsText from "../Components/AboutUsText.vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import ProductCardDB from "@/Components/ProductCardDB.vue";
import chatwithai from "@/Components/chatwithai.vue";
import Banner from "@/Components/Banner.vue";
import SectionCom from "@/Components/SectionCom.vue";
import RobotAssistant from "@/Components/Robot.vue";
import BetaPopUp from "@/Components/BetaPopUp.vue";

export default {
    name: 'Home',
    components: {
        RobotAssistant,
        SectionCom,
        ProductCardDB,
        Navbar,
        Visitit,
        Slider,
        Productsintro,
        Contact,
        Search,
        Testimonial,
        AboutUsText,
        Footer,
        chatwithai,
        Banner,
        BetaPopUp,
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

            fetch(`/products/laptops?${params}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Fetched products:", data);
                    this.products = data;
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
    },
};
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    gap: 70px;
    padding-bottom: 80px;
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
