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
    height: 100%;
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

.product-img {
    width: 100%;
    height: 160px;
    object-fit: contain;
    margin-bottom: 10px;
}

.product-card h5 {
    font-size: 15px;
    margin: 6px 0;
}

.description {
    font-size: 13px;
    color: #666;
    margin: 6px 0;
    flex-grow: 1;
}

.price {
    font-weight: bold;
    margin: 10px 0;
    font-size: 15px;
}

.product-card button {
    margin-top: auto;
    padding: 8px 12px;
    background: #007BFF;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.product-card button:hover {
    background: #0056b3;
}

/* MOBILE */
@media (max-width: 768px) {
    .product-card {
        padding: 12px;
        border-radius: 10px;
    }

    .product-img {
        height: 110px;
    }

    .product-card h5 {
        font-size: 13px;
    }

    .description {
        font-size: 11px;
    }

    .price {
        font-size: 13px;
    }

    .product-card button {
        padding: 6px 10px;
        font-size: 12px;
    }
}
</style>
