<template>
<!--    <div class="product-card">-->
<!--        <img :src="product.img" alt="Product Image" class="product-img" />-->
<!--        <h5>{{ product.name }}</h5>-->
<!--        <p>{{ product.description }}</p>-->
<!--        <p><strong>Price:</strong> {{ product.price }}</p>-->
<!--    </div>-->

    <div class="product-cards">
        <div class="product-card">
            <img :src="product.image" alt="Product 4">
            <h5>{{ product.name }}</h5>
            <p>{{ product.description }}.</p>
            <p><strong>Price:</strong> {{ product.price }}</p>
            <button  @click="goToProductPage(product.id)">Buy</button>
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
            //Inertia.visit(`/product/${productId}`);
            window.location.href = `/product?id=${productId}`;
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
    max-width: 1200px;
    gap: 20px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    position: relative;
}

.product-card{
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 15px;
    width: 23%;
    text-align: center;
    padding: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    flex: 1 1 calc(50% - 10px); /* Каждая карточка занимает 50% ширины с небольшим отступом */
    margin-bottom: 50px; /* Отступ между карточками */
}

.product-card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.product-card img {
    max-width: 100%;
    height: fit-content;
    border-radius: 10px;
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


/* Media query for screens 500px or less */
@media (max-width: 500px) {
    .product-card {
        flex: 1 1 calc(50% - 5px); /* Для экранов меньше 500px, делаем карточки немного уже */
        margin-bottom: 15px;
    }
}
/* Cards near footer */
</style>
