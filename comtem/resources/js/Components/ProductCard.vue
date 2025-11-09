<template>
    <section id="productdetails" v-if="product">
        <div class="single-pro-image">
            <img
                :src="product.image || ''"
                width="100%"
                id="MainImg"
                alt="Product image"
                class="clickable-img"
                @click="openModal = true"
            />
        </div>

        <div class="single-pro-details">
            <h6 class="category">{{ product.category }}</h6>
            <h4 class="product-name">{{ product.name }}</h4>
            <h2 class="product-price">${{ product.price }}</h2>

            <div class="quantity-add">
                <input type="number" v-model="quantity" min="1" />
                <button class="add-btn" @click="addToCart(product)">
                    🛒 Add to Cart
                </button>
            </div>

            <h4>Product Details</h4>
            <p class="description">{{ product.description }}</p>
<!--            <div-->
<!--                v-if="product.variations && product.variations.length"-->
<!--                class="variation-cards"-->
<!--            >-->
<!--                <p class="variation-label">Available Options:</p>-->
<!--                <div class="variation-list">-->
<!--                    <div-->
<!--                        v-for="variation in product.variations"-->
<!--                        :key="variation.id"-->
<!--                        :class="[-->
<!--                        'variation-card',-->
<!--                        { selected: selectedVariation && selectedVariation.id === variation.id }-->
<!--                      ]"-->
<!--                        @click="selectVariation(variation)"-->
<!--                    >-->
<!--                        <h5>{{ variation.name }}</h5>-->
<!--                        <span>${{ variation.price || product.price }}</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>

        <!-- Image modal -->
        <div v-if="openModal" class="modal" @click="openModal = false">
            <img :src="product.image" alt="Large product view" class="modal-img" />
        </div>

    </section>

    <p v-else>Loading product details...</p>

    <!-- Notification -->
    <transition name="slide">
        <div v-if="showNotification" class="notification">
            ✅ Item added to cart!
        </div>
    </transition>
</template>

<script>
import { useStore } from "vuex";
import {computed, ref} from "vue";

export default {
    props: ["product"],
    setup(props) {
        const store = useStore();
        const quantity = ref(1);
        const showNotification = ref(false);
        const openModal = ref(false);
        const selectedVariation = ref(null);

        // if (props.product.variations?.length) {
        //     selectedVariation.value = props.product.variations[0];
        // }

        const displayPrice = computed(() => {
            const variationPrice = parseFloat(selectedVariation.value?.price || 0);
            const basePrice = parseFloat(props.product.price || 0);
            return variationPrice + basePrice;
        });

        const selectVariation = (variation) => {
            selectedVariation.value = variation;
        };

        const addToCart = (product) => {
            store.commit("ADD_TO_CART", { ...product,  variation_id: selectedVariation.value?.id || null,
                variation_name: selectedVariation.value?.name || null, quantity: quantity.value, price: displayPrice.value, });
            showNotification.value = true;
            setTimeout(() => (showNotification.value = false), 2500);
        };

        return {
            quantity, addToCart, showNotification, openModal, selectVariation,  selectedVariation,
        };
    },
};
</script>

<style scoped>
/* ---------------- PRODUCT CARD ---------------- */
#productdetails {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: flex-start;
    gap: 40px;
    margin: 100px auto;
    width: 90%;
    max-width: 1200px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    padding: 40px;
    transition: box-shadow 0.3s ease;
}
#productdetails:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.single-pro-image {
    flex: 1 1 40%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.clickable-img {
    width: 100%;
    border-radius: 12px;
    cursor: zoom-in;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.clickable-img:hover {
    transform: scale(1.03);
}

.single-pro-details {
    flex: 1 1 50%;
    padding-top: 10px;
}

.category {
    text-transform: uppercase;
    color: #777;
    font-size: 13px;
    letter-spacing: 1px;
}

.product-name {
    font-size: 26px;
    font-weight: 700;
    margin: 10px 0;
}

.product-price {
    font-size: 22px;
    color: #7a3a7b;
    font-weight: 800;
    margin-bottom: 20px;
}

.quantity-add {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 25px;
}

.quantity-add input {
    width: 60px;
    height: 40px;
    padding-left: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.add-btn {
    background-color: #7a3a7b;
    color: #fff;
    padding: 10px 18px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: background-color 0.3s, transform 0.2s;
}
.add-btn:hover {
    background-color: #8e4b8f;
    transform: scale(1.05);
}

.description {
    line-height: 1.6;
    color: #444;
    font-size: 15px;
}

/* ---------------- IMAGE MODAL ---------------- */
.modal {
    position: fixed;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.85);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
    cursor: zoom-out;
}

.modal-img {
    max-width: 80%;
    max-height: 80%;
    border-radius: 12px;
    object-fit: contain;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* ---------------- NOTIFICATION ---------------- */
.notification {
    position: fixed;
    bottom: 100px;
    right: 20px;
    background-color: #7a3a7b;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    font-size: 16px;
    z-index: 1000;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.5s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(120%);
    opacity: 0;
}

/* ---------------- RESPONSIVE ---------------- */
@media (max-width: 768px) {
    #productdetails {
        flex-direction: column;
        padding: 25px;
    }

    .single-pro-image,
    .single-pro-details {
        width: 100%;
    }

    .product-name {
        font-size: 22px;
    }

    .product-price {
        font-size: 20px;
    }
}



.variation-cards {
    margin-bottom: 25px;
}

.variation-label {
    font-weight: 600;
    margin-bottom: 10px;
    color: #444;
}

.variation-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.variation-card {
    flex: 0 0 auto;
    padding: 10px 16px;
    background-color: #f7f7f7;
    border: 2px solid transparent;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.25s ease;
    text-align: center;
    min-width: 100px;
}

.variation-card:hover {
    background-color: #f0e6f0;
    border-color: #d1b3d1;
}

.variation-card.selected {
    border-color: #7a3a7b;
    background-color: #f4e4f4;
    box-shadow: 0 0 8px rgba(122, 58, 123, 0.3);
}

.variation-card h5 {
    font-size: 15px;
    margin-bottom: 4px;
    color: #333;
}

.variation-card span {
    font-size: 14px;
    color: #7a3a7b;
    font-weight: 600;
}
</style>
