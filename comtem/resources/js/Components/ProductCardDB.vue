<template>
<!--    <div class="product-card">-->
<!--        <img :src="product.img" alt="Product Image" class="product-img" />-->
<!--        <h5>{{ product.name }}</h5>-->
<!--        <p>{{ product.description }}</p>-->
<!--        <p><strong>Price:</strong> {{ product.price }}</p>-->
<!--    </div>-->

    <div class="product-cards">
        <div class="product-card">
            <img :src="product.image" style="justify-content: center; align-items: center" class="product-img" alt="Product 4">
            <h5>{{ product.name }}</h5>
            <p>{{ product.description }}.</p>
            <p><strong>Price:</strong> {{ product.price }}</p>
            <button  @click="goToProductPage(this.product.id)">Buy</button>
        </div>
    </div>

</template>

<script>
import { useRouter } from 'vue-router';
import {Inertia} from "@inertiajs/inertia";

export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    methods: {
        // console.log(productId);
        // goToProductPage(productId) {
        //     console.log('t',productId);
        //     window.location.href = `/product?id=${productId}`;
        goToProductPage(productId) {
            // Use Inertia to navigate to the product page with the productId as a query param
            // Inertia.visit(`/product`, {
            //     method: 'get',
            //     query: { id: productId }, // Pass productId as a query parameter
            // });
            // Inertia.visit(`/product/${productId}`);
            if (!productId) {
                console.error('Product ID is missing!');
                return;
            }
            console.log(productId);
            console.log(`/product?id=${productId}`);
            //Inertia.visit(`/product/${productId}`);
            //Inertia.visit(`/product?id=${productId}`);
            window.location.href = `/product?id=${productId}`;
            //this.$router.push({ path: '/product', query: { id: productId } });
        },
    },

    // setup() {
    //     const router = useRouter();
    //
    //     const goToProductPage = (product) => {
    //         router.push({
    //             path: '/product',
    //             query: {
    //                 name: product.name,
    //                 description: product.description,
    //                 price: product.price,
    //                 img: product.img,
    //             },
    //         });
    //     };
    //
    //     return { goToProductPage };
    // },
};
</script>

<style scoped>
.product-card {
    margin: 10px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    text-align: center;
}
.product-cards {
    display: flex;
    justify-content: space-between;
    margin: 20px auto;
    width: 900px;
    gap: 20px;
    flex-wrap: wrap;
    position: relative;
}

.product-card{
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 15px;
    width: 23%;
    text-align: center;
    align-items: center;
    justify-content: center;
    padding: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    flex: 1 1 calc(50% - 10px); /* Каждая карточка занимает 50% ширины с небольшим отступом */
    margin-bottom: 50px; /* Отступ между карточками */
    flex-direction: column; /* Ensures the image and text stack */
}

.product-card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.product-card img {
    max-width: 100%;
    height: fit-content;
    border-radius: 10px;

    /* Center the image */
    display: block;
    margin: 0 auto; /* Horizontally centers the image */
}

.product-img {
    align-self: center;
}

.product-card p {
    font-size: 0.9em;
    color: #666;
    margin: 10px 0;
}

.product-card button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.product-card button:hover {
    background-color: #0056b3;
}


/* Media query for screens 950px or less */
@media (max-width: 950px) {
    .product-card {
        flex: 1 1 calc(50% - 5px);
        margin-bottom: 15px;
    }

    .product-cards {
        display: flex;
        justify-content: space-between;
        margin: 20px auto;
        width: auto;
        gap: 20px;
        flex-wrap: wrap;
        position: relative;
    }
}

@media (min-width: 1090px) {
    .product-cards {
        display: flex;
        justify-content: space-between;
        margin: 20px auto;
        width: 500px;
        gap: 20px;
        flex-wrap: wrap;
        position: relative;
    }
}
/* Cards near footer */
</style>
