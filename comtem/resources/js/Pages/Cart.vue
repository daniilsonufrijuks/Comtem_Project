<template>
    <Navbar :routes="routes"/>
    <Search />
    <div class="main-container">
        <div class="cart-container">
            <h1 class="cart-title">Shopping Cart</h1>

            <div v-if="cartItems.length > 0" class="cart-content">
                <div class="cart-items-list">
                    <div class="cart-card" v-for="item in cartItems" :key="item.id">
                        <img :src="item.image" alt="Product Image" class="cart-card-img" />
                        <div class="cart-card-details">
                            <h3 class="cart-card-title">{{ item.name }}</h3>
                            <p class="cart-card-description">{{ item.description }}</p>
                            <div class="cart-card-footer">
                                <div class="cart-card-price">
                                    <span class="price-label">Price:</span>
                                    <span class="price-value">${{ item.price }}</span>
                                </div>
                                <div class="cart-card-quantity">
                                    <span class="quantity-label">Quantity:</span>
                                    <span class="quantity-value">{{ item.quantity }}</span>
                                </div>
                                <div class="cart-card-subtotal">
                                    <span class="subtotal-label">Subtotal:</span>
                                    <span class="subtotal-value">${{ (item.price * item.quantity).toFixed(2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="cart-summary">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>${{ cartTotal.toFixed(2) }}</span>
                    </div>

                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>
                            <span v-if="shippingCost === 0" class="free">FREE 🎉</span>
                            <span v-else>${{ shippingCost.toFixed(2) ?? '0.00' }}</span>
                        </span>
                    </div>

                    <div class="summary-row total">
                        <span>Total</span>
                        <span>${{ finalTotal.toFixed(2) ?? '0.00'}}</span>
                    </div>

                    <button class="btn-clear" @click="clearCart">Clear Cart</button>
                    <button class="btn-checkout" @click="proceedToCheckout">
                        Proceed to Checkout
                    </button>
                </div>
            </div>

            <div v-else class="empty-cart">
                <p>Your cart is empty.</p>
            </div>
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
import Testimonial from "../Components/Testimonial.vue";
import AboutUsText from "../Components/AboutUsText.vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import ProductCard from "@/Components/ProductCard.vue";
import {mapState, useStore} from "vuex";
import {computed, onMounted, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
import {loadStripe} from "@stripe/stripe-js";

export default {
    name: 'Cart',
    components: {
        ProductCard,
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
        routes: Object,
        cartItems: {
            type: Array,
            required: true,
        },
        products: {
            type: Object,
            required: true,
        },
    },
    setup() {
        const store = useStore();
        const page = usePage();

        // Fetch user award from backend
        const fetchUserAward = async () => {
            try {
                console.log('Fetching user award...');
                const response = await axios.get('/user/award');
                console.log('Award response:', response.data);
                if (response.data && response.data.award !== undefined) {
                    store.commit('SET_AWARD', response.data.award);
                }
            } catch (error) {
                console.error("Error fetching user award:", error);
            }
        };

        const fetchUserAddress = async () => {
            try {
                const response = await axios.get('/user/profile');
                if (response.data && response.data.address) {
                    store.commit('SET_ADDRESS', response.data.address);
                    console.log('Fetched and stored shipping address:', response.data.address);
                }
            } catch (error) {
                console.error("Error fetching user address:", error);
            }
        };

        onMounted(async () => {
            console.log('Cart component mounted, isAuthenticated:', isAuthenticated.value);
            if (isAuthenticated.value) {
                await fetchUserAward();
                await fetchUserAddress();
            }
        });

        const cartItems = computed(() => store.getters.cartItems);
        const cartTotal = computed(() => store.getters.cartTotal);
        const awardAmount = computed(() => store.getters.award ?? 0);
        const shippingAddress = computed(() => store.getters.address);
        const isAuthenticated = computed(() => {
            return !!(page.props.auth && page.props.auth.user);
        });

        const shippingCost = computed(() => {
            const award = parseInt(awardAmount.value);
            console.log('Calculating shipping with award:', award);
            return award > 100 ? 0 : 3;
        });

        const finalTotal = computed(() => {
            return cartTotal.value + shippingCost.value;
        });

        const clearCart = () => {
            store.commit('CLEAR_CART');
        };

        // Main checkout method
        const proceedToCheckout = async () => {
            try {
                console.log('Cart items before checkout:', store.state.cart, Array.isArray(store.state.cart));

                if (Array.isArray(store.state.cart) && store.state.cart.length > 0) {
                    const sanitizedCart = store.state.cart.map(item => ({
                        id: item.id,
                        name: item.name,
                        price: parseFloat(item.price),
                        quantity: parseInt(item.quantity),
                        description: item.description || '',
                        image: item.image || '',
                        category: item.category || '',
                        total_price: parseFloat(item.price) * item.quantity,
                        shipping_address: shippingAddress.value,
                    }));

                    if (!isAuthenticated.value) {
                        window.location.href = `/login`;
                        console.log("Please log in first.");
                        return;
                    }

                    // Check if user has family card available
                    try {
                        const familyCardResponse = await axios.get('/family/card/available');
                        const canUseFamilyCard = familyCardResponse.data.available;

                        if (canUseFamilyCard) {
                            // Ask user which payment method to use
                            const useFamilyCard = confirm('You have a family card available. Would you like to use it? (Cancel to use regular checkout)');

                            if (useFamilyCard) {
                                // Use family card payment
                                const response = await axios.post('/family/checkout', {
                                    items: sanitizedCart,
                                    shipping_address: shippingAddress.value,
                                });

                                if (response.data.success) {
                                    // Create order record
                                    const userResponse = await axios.get('/user/award');
                                    const currentAward = userResponse.data?.award || 0;

                                    const orderResponse = await axios.post('/order', {
                                        items: sanitizedCart,
                                        total: store.state.cart.reduce(
                                            (sum, item) => sum + parseFloat(item.price) * parseInt(item.quantity),
                                            0
                                        ),
                                        award: awardAmount.value,
                                        shipping: shippingCost.value,
                                        shipping_address: shippingAddress.value,
                                        payment_method: 'family_card',
                                        payment_intent_id: response.data.payment_intent_id,
                                    });

                                    // Clear cart and redirect
                                    store.commit('CLEAR_CART');
                                    alert('Payment successful using family card!');
                                    window.location.href = '/';
                                    return;
                                }
                            }
                        }
                    } catch (familyCardError) {
                        console.log('No family card available or error:', familyCardError);
                        // Continue with regular checkout
                    }

                    // Regular checkout flow
                    const userResponse = await axios.get('/user/award');
                    const currentAward = userResponse.data?.award || 0;

                    // First create order
                    const orderResponse = await axios.post('/order', {
                        items: sanitizedCart,
                        total: store.state.cart.reduce(
                            (sum, item) => sum + parseFloat(item.price) * parseInt(item.quantity),
                            0
                        ),
                        award: awardAmount.value,
                        shipping: shippingCost.value,
                        shipping_address: shippingAddress.value,
                        payment_method: 'stripe',
                        status: 'pending'
                    });

                    // Then create Stripe checkout session
                    const response = await axios.post('/stripe/checkout', {
                        items: sanitizedCart,
                    });

                    if (response.data.id) {
                        const stripeKey = import.meta.env.VITE_STRIPE_KEY;

                        if (!stripeKey) {
                            console.error('Stripe public key is not defined in the environment variables.');
                            return;
                        }

                        const stripe = await loadStripe(stripeKey);

                        // Redirect to Stripe Checkout
                        const { error } = await stripe.redirectToCheckout({
                            sessionId: response.data.id,
                        });

                        if (error) {
                            console.error('Stripe redirect error:', error);
                            alert('Error redirecting to payment: ' + error.message);
                        } else {
                            // Clear cart only after successful redirect
                            store.commit('CLEAR_CART');
                        }
                    } else {
                        throw new Error('No session ID returned from Stripe');
                    }
                }
            } catch (error) {
                console.error("Error during checkout:", error);
                alert('Payment failed: ' + (error.response?.data?.error || error.message));
            }
        };

        return {
            cartItems,
            cartTotal,
            clearCart,
            proceedToCheckout,
            finalTotal,
            shippingCost,
            awardAmount,
            shippingAddress,
        };
    }
}
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    gap: 70px;
}

.cart-container {
    padding: 40px 20px;
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
}

.cart-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 30px;
    color: #333;
}

.cart-content {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.cart-items-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.cart-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.cart-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.cart-card-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.cart-card-details {
    padding: 20px;
}

.cart-card-title {
    margin: 0 0 10px 0;
    font-size: 20px;
    font-weight: 600;
    color: #333;
}

.cart-card-description {
    margin: 0 0 15px 0;
    font-size: 14px;
    color: #666;
    line-height: 1.5;
}

.cart-card-footer {
    display: flex;
    flex-direction: column;
    gap: 10px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.cart-card-price,
.cart-card-quantity,
.cart-card-subtotal {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.price-label,
.quantity-label,
.subtotal-label {
    font-size: 14px;
    color: #666;
}

.price-value,
.quantity-value {
    font-size: 16px;
    font-weight: 600;
    color: #420d65;
}

.subtotal-value {
    font-size: 18px;
    font-weight: bold;
    color: #420d65;
}

.cart-summary {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 30px;
    position: sticky;
    top: 20px;
}

.cart-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 20px;
    border-bottom: 2px solid #420d65;
    margin-bottom: 20px;
}

.total-label {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.total-value {
    font-size: 32px;
    font-weight: bold;
    color: #420d65;
}

.cart-actions {
    display: flex;
    gap: 15px;
    flex-direction: column;
}

.btn-clear,
.btn-checkout {
    padding: 15px 30px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-clear {
    background-color: #f5f5f5;
    color: #666;
}

.btn-clear:hover {
    background-color: #e0e0e0;
    color: #333;
}

.btn-checkout {
    background-color: #420d65;
    color: white;
}

.btn-checkout:hover {
    background-color: #6a0dad;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(66, 13, 101, 0.3);
}

.empty-cart {
    text-align: center;
    padding: 60px 20px;
    font-size: 18px;
    color: #666;
}

@media (max-width: 768px) {
    .cart-items-list {
        grid-template-columns: 1fr;
    }

    .cart-title {
        font-size: 24px;
    }

    .total-label {
        font-size: 20px;
    }

    .total-value {
        font-size: 28px;
    }
}
</style>
