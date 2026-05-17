<template>
    <Navbar :routes="routes"/>
    <Search />
    <Slider/>
    <div class="main-container">
<!--        <Visitit />-->
<!--        <Productsintro />-->
        <h1 class="aboutus">About us</h1>
        <AboutUsText />
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
import Testimonial from "../Components/Testimonial.vue";
import AboutUsText from "../Components/AboutUsText.vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";

export default {
    name: 'Home',
    components: {
        Navbar,
        Visitit,
        Slider,
        Productsintro,
        Contact,
        Search,
        Testimonial,
        AboutUsText,
        Footer
    },
    props: {
        routes: Object
    }
}
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    gap: 70px;
    padding-bottom: 80px;
    justify-content: center;
}

h1 {
    max-width: 1200px;
    margin: 40px auto 0;
    padding: 0 16px;
    text-align: center;
    font-size: 2.2rem;
    font-weight: 700;
    color: #222;
}

/*  MOBILE */
@media (max-width: 576px) {
    h1 {
        font-size: 1.6rem;
    }

}
</style>
