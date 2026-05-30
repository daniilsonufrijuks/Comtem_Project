<template>
    <a class="product-card" :href="`/product?id=${product.id}`">
        <img :src="product.image" class="product-img" alt="Product">
        <h5>{{ product.name }}</h5>
        <p class="description">{{ product.description }}</p>
        <p class="price">{{ product.price }} €</p>
        <span class="buy-btn view-btn">{{ t('buy') }}</span>
        <span class="buy-btn cart-btn" @click.prevent.stop="addToCart(product)">
            {{ t('product_add_to_cart') }}
        </span>
    </a>
    <div v-if="showNotification" class="notification">
        {{ t('product_added') }}
    </div>
</template>

<script>
import { useRouter } from 'vue-router';
import {Inertia} from "@inertiajs/inertia";
import { useTranslation } from '../Composables/useTranslation';
import store from "@/Cart/cart.js";
import {useStore} from "vuex";
import {ref} from "vue";
export default {
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    setup() {
        const { t } = useTranslation();

        const store = useStore();
        const quantity = ref(1);
        const showNotification = ref(false);
        const selectedVariation = ref(null);

        const validateQuantity = () => {
            let raw = quantity.value;
            // Convert to number, then integer, then ensure >= 1
            let intValue = parseInt(raw, 10);
            if (isNaN(intValue) || intValue < 1) {
                intValue = 1;
            }
            quantity.value = intValue;
        };

        const selectVariation = (variation) => {
            selectedVariation.value = variation;
        };


        const addToCart = (product) => {
            store.commit("ADD_TO_CART", {
                ...product,
                variation_id: null,
                variation_name: null,
                quantity: 1,
                price: parseFloat(product.price),
            });
            showNotification.value = true;
            setTimeout(() => (showNotification.value = false), 2500);
        };


        return { t, addToCart, validateQuantity, selectVariation, showNotification, };
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

.buy-btn {
    margin-top: auto;
    padding: 8px 12px;
    background: #007BFF;
    color: white;
    border-radius: 6px;
    font-size: 14px;
    display: block;
}

.product-card button:hover {
    background: #0056b3;
}


.product-card:hover .buy-btn {
    background: #0056b3;
}

.notification {
    position: fixed;
    bottom: 100px;
    right: 20px;
    background: #7a3a7b;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    z-index: 1000;
}

.slide-enter-active, .slide-leave-active { transition: all 0.5s ease; }
.slide-enter-from, .slide-leave-to { transform: translateX(120%); opacity: 0; }

.view-btn { background: #555; margin-bottom: 6px; }
.cart-btn { background: #007BFF; }
.cart-btn:hover { background: #0056b3; }


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
