<template>
    <div class="product-card">
        <img :src="product.image" class="product-img" alt="Product">

        <h5>{{ product.name }}</h5>
        <p class="description">{{ product.description }}</p>

        <p class="price">{{ product.price }} €</p>

        <button @click="goToProductPage(product.id)">Buy</button>
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
    width: 100%;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 16px;
    text-align: center;

    display: flex;
    flex-direction: column;
    justify-content: space-between;

    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.15);
}

/* image area */
.product-img {
    width: 100%;
    height: 180px;
    object-fit: contain;
    margin-bottom: 10px;
}

/* text */
.description {
    font-size: 14px;
    color: #666;
}

.price {
    font-weight: bold;
    margin: 10px 0;
}

/* button */
.product-card button {
    margin-top: auto;
    padding: 10px;
    background: #007BFF;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.product-card button:hover {
    background: #0056b3;
}

</style>
