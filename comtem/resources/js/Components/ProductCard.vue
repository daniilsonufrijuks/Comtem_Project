<template>
    <section id="productdetails" v-if="product">
        <div class="single-pro-image">
            <img
                :src="product.image || ''"
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

            <h4 class="section-title">Key Features</h4>
            <ul class="features">
                <li>⚡ High-performance electronic components</li>
                <li>🔋 Energy-efficient and reliable operation</li>
                <li>🛡️ Durable construction for long-term use</li>
                <li>🎛️ User-friendly and intuitive design</li>
                <li>🔌 Compatible with standard accessories</li>
            </ul>

            <!-- SPECS -->
            <h4 class="section-title">Technical Specifications</h4>
            <ul class="specs">
                <li><strong>Category:</strong> {{ product.category }}</li>
                <li><strong>Price:</strong> ${{ product.price }}</li>
                <li><strong>Availability:</strong> In Stock</li>
            </ul>
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
/* ---------- LAYOUT ---------- */
#productdetails {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    margin: 100px auto;
    width: 90%;
    max-width: 1200px;
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}

/* ---------- IMAGE ---------- */
.single-pro-image {
    flex: 1 1 40%;
    display: flex;
    justify-content: center;
    align-items: center;
    max-height: 420px;
}
.clickable-img {
    max-width: 100%;
    max-height: 420px;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 12px;
    cursor: zoom-in;
    transition: transform 0.3s ease;
}
.clickable-img:hover {
    transform: scale(1.03);
}

/* ---------- DETAILS ---------- */
.single-pro-details {
    flex: 1 1 50%;
}

.category {
    text-transform: uppercase;
    color: #777;
    font-size: 13px;
    letter-spacing: 1px;
}

.product-name {
    font-size: 28px;
    font-weight: 700;
    margin: 10px 0;
}

.product-price {
    font-size: 24px;
    color: #7a3a7b;
    font-weight: 800;
    margin-bottom: 20px;
}

.section-title {
    margin-top: 28px;
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 700;
}

/* ---------- DESCRIPTION ---------- */
.description {
    line-height: 1.8;
    color: #333;
    font-size: 15.5px;
}

/* ---------- FEATURES ---------- */
.features {
    list-style: none;
    padding: 0;
    margin-top: 10px;
}
.features li {
    padding: 10px 14px;
    margin-bottom: 10px;
    background: #f8f9fb;
    border-left: 4px solid #7a3a7b;
    border-radius: 6px;
    font-size: 14.5px;
}

/* ---------- SPECS ---------- */
.specs {
    font-size: 14.5px;
    color: #444;
}
.specs li {
    margin-bottom: 6px;
}

/* ---------- CART ---------- */
.quantity-add {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.quantity-add input {
    width: 60px;
    height: 40px;
    border-radius: 6px;
    border: 1px solid #ccc;
    padding-left: 10px;
}
.add-btn {
    background: #7a3a7b;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}
.add-btn:hover {
    background: #8e4b8f;
}

/* ---------- MODAL ---------- */
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
}
.modal-img {
    width: 90vw;
    height: 90vh;
    object-fit: contain;
    border-radius: 12px;
    animation: zoomIn 0.25s ease;
}
/* ---------- NOTIFICATION ---------- */
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

/* ---------- ANIMATION ---------- */
.slide-enter-active,
.slide-leave-active {
    transition: all 0.5s ease;
}
.slide-enter-from,
.slide-leave-to {
    transform: translateX(120%);
    opacity: 0;
}

/* ---------- RESPONSIVE ---------- */
@media (max-width: 768px) {
    #productdetails {
        flex-direction: column;
        padding: 25px;
    }
}

@keyframes zoomIn {
    from {
        transform: scale(0.85);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
</style>
