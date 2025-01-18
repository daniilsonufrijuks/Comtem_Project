<template>
    <!--    <Navbar :routes="routes" />-->
    <Navbar :routes="routes"/>
    <Search />
<!--    <Slider/>-->
    <div class="main-container">
<!--        <Visitit />-->
<!--        <Productsintro />-->
<!--        <Testimonial />-->
<!--        <div class="cart-container">-->
<!--            <h2>Your Cart</h2>-->

<!--            &lt;!&ndash; Check if cart is empty &ndash;&gt;-->
<!--            <div v-if="cartItems.length === 0">-->
<!--                <p>Your cart is empty.</p>-->
<!--            </div>-->

<!--            <div v-else>-->
<!--                <div v-for="(item, index) in cartItems" :key="item.id" class="cart-item">-->
<!--                    <img :src="item.img" alt="Item image" class="cart-item-img">-->
<!--                    <div class="cart-item-details">-->
<!--                        <h3>{{ item.name }}</h3>-->
<!--                        <p>{{ item.description }}</p>-->
<!--                        <p>Price: ${{ item.price }}</p>-->
<!--                        <p>Quantity: {{ item.quantity }}</p>-->
<!--                        <button @click="removeItem(index)">Remove</button>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="cart-total">-->
<!--                    <p>Total: ${{ totalPrice }}</p>-->
<!--                    <button @click="checkout">Proceed to Checkout</button>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="cart-container">-->
<!--            <h2>Your Cart</h2>-->
<!--            <div v-if="cartItems.length === 0">-->
<!--                <p>Your cart is empty.</p>-->
<!--            </div>-->
<!--            <div v-else class="cart-grid">-->
<!--                <div v-for="(item, index) in cartItems" :key="index" class="cart-card">-->
<!--                    <img :src="item.image" alt="Product image" class="cart-card-img">-->
<!--                    <div class="cart-card-details">-->
<!--                        <h3>{{ item.name }}</h3>-->
<!--                        <p>Category: {{ item.category }}</p>-->
<!--                        <p>Option: {{ item.option }}</p>-->
<!--                        <p>Quantity: {{ item.quantity }}</p>-->
<!--                        <p>Price: ${{ item.price }}</p>-->
<!--                        <p>Total: ${{ item.price * item.quantity }}</p>-->
<!--                        <button @click="removeItem(index)">Remove</button>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="cart-total" v-if="cartItems.length">-->
<!--                <p>Total Price: ${{ totalPrice }}</p>-->
<!--                <button @click="checkout">Proceed to Checkout</button>-->
<!--            </div>-->
<!--        </div>-->
<!--        &lt;!&ndash; Product Card used inside Cart.vue, emits add-to-cart event &ndash;&gt;-->
<!--        <ProductCard v-for="product in products" :key="product.id" :product="product" @add-to-cart="addToCart(this.product)" />-->
        <div class="cart-container">
            <div v-if="cartItems.length > 0">
                <div class="cart-item" v-for="item in cartItems" :key="item.id">
                    <img :src="item.image" alt="Product Image" class="cart-item-img" />
                    <div class="cart-item-details">
                        <h3>{{ item.name }}</h3>
                        <p>{{ item.description }}</p>
                        <p>Price: ${{ item.price }}</p>
                        <p>Quantity: {{ item.quantity }}</p>
<!--                        <button @click="removeFromCart(item.id)">Remove</button>-->
                    </div>
                </div>
                <div class="cart-total">
<!--                    <span>Total: ${{ cartTotal }}</span>-->
                    <button @click="clearCart">Clear Cart</button>
                </div>
            </div>
            <p v-else>Your cart is empty.</p>
            <div class="cart-total">
                <span>Total: ${{ cartTotal }}</span>
                <button @click="proceedToCheckout">Proceed to Checkout</button>
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
import {computed} from "vue";

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

        const cartItems = computed(() => store.getters.cartItems);
        console.log(cartItems.value);
        const cartTotal = computed(() => store.getters.cartTotal);

        // const removeFromCart = (id) => {
        //     store.commit('REMOVE_FROM_CART', id);
        // };

        const clearCart = () => {
            store.commit('CLEAR_CART');
        };

        // Proceed to checkout
        const proceedToCheckout = async () => {
            try {
                // Log the cart data before sending it
                console.log('Cart items before checkout:', store.state.cart);

                // // Check if the user is logged in
                // const response = await axios.get('/auth/user');
                // if (!response.data.loggedIn) {
                //     // If not logged in, redirect to login page
                //     window.location.href = `/login`;
                //     console.log("first log in")
                // } else {
                //     // If logged in, proceed with checkout
                //     console.log("!!!");
                //     const orderResponse = await axios.post('/checkout', {
                //         items: store.state.cart, // Ensure this is an array
                //     });
                //
                //     console.log('Order Response:', orderResponse.data);
                //
                //     // Optionally, clear the cart after successful checkout
                //     store.commit('CLEAR_CART');
                //
                //     window.location.href = `/home`;
                // }
                // Ensure cart is an array and sanitize data
                if (Array.isArray(store.state.cart) && store.state.cart.length > 0) {
                    const sanitizedCart = store.state.cart.map(item => ({
                        id: item.id,
                        name: item.name,
                        price: item.price,
                        quantity: item.quantity,
                    }));

                    const response = await axios.get('/auth/user');
                    if (!response.data.loggedIn) {
                        // If user is not logged in, redirect to login
                        window.location.href = `/login`;
                        console.log("Please log in first.");
                    } else {
                        // Send sanitized cart data to the backend
                        const orderResponse = await axios.post('/checkout', {
                            items: sanitizedCart,
                            total: store.state.cart.reduce((sum, item) => sum + item.price * item.quantity, 0),
                        });

                        console.log('Order Response:', orderResponse.data);

                        // Optionally clear cart after successful checkout
                        store.commit('CLEAR_CART');

                        // Redirect to home page
                        window.location.href = `/`;
                    }
                }
            } catch (error) {
                console.error("Error during checkout:", error);
            }
        };

        return {
            cartItems,
            cartTotal,
            // removeFromCart,
            clearCart,
            proceedToCheckout,
        };

    },
//     setup() {
//         const store = useStore();
//
//         const cartItems = computed(() => store.getters.cartItems);
//         const cartTotal = computed(() =>
//             store.state.cart.reduce((sum, item) => sum + item.price * item.quantity, 0)
//         );
//
//         const clearCart = () => {
//             store.commit("CLEAR_CART");
//         };
//
//         const proceedToCheckout = async () => {
//             try {
//                 console.log("Cart items before checkout:", store.state.cart);
//
//                 if (Array.isArray(store.state.cart) && store.state.cart.length > 0) {
//                     const sanitizedCart = store.state.cart.map((item) => ({
//                         id: item.id, // Ensure the item has an id
//                         name: item.name,
//                         price: item.price,
//                         quantity: item.quantity,
//                     }));
//
// // Log the sanitized cart to ensure it has 'id' for each item
//                     console.log("Sanitized Cart:", sanitizedCart);
//
// // Proceed with checkout request if cart is valid
//                     const response = await axios.get("/auth/user");
//                     if (!response.data.loggedIn) {
//                         window.location.href = `/login`;
//                     } else {
//                         try {
//                             const orderResponse = await axios.post('/checkout', {
//                                 items: sanitizedCart,  // Send sanitizedCart here
//                                 total: cartTotal.value, // Send the total as well
//                             });
//                             console.log("Order Response:", orderResponse.data);
//                         } catch (error) {
//                             console.error("Error during checkout:", error.response ? error.response.data : error.message);
//                         }
//
//                         store.commit("CLEAR_CART");
//                         window.location.href = `/`;
//                     }
//                 } else {
//                     console.log("No items in the cart.");
//                 }
//             } catch (error) {
//                 console.error("Error during checkout:", error);
//             }
//         };
//
//         return {
//             cartItems,
//             cartTotal,
//             clearCart,
//             proceedToCheckout,
//         };
//     },
}
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    gap: 70px; /* Adjust as needed */
}
 .cart-container {
     padding: 20px;
 }

.cart-item {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.cart-item-img {
    width: 100px;
    height: auto;
}

.cart-item-details {
    flex-grow: 1;
}

.cart-item-details h3 {
    margin: 0;
}

.cart-total {
    margin-top: 20px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
}

.cart-total button {
    padding: 10px 20px;
    background-color: #420d65;
    color: white;
    border: none;
    cursor: pointer;
}

.cart-total button:hover {
    background-color: #6a0dad;
}

.cart-item {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-bottom: 20px;
}

.cart-item-img {
    width: 100px;
    height: auto;
}

.cart-total {
    margin-top: 20px;
    font-size: 18px;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.cart-total button {
    background-color: #420d65;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
}

.cart-total button:hover {
    background-color: #6a0dad;
}
</style>
