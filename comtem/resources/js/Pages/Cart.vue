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
        <div class="cart-container">
            <h2>Your Cart</h2>
            <div v-if="cartItems.length === 0">
                <p>Your cart is empty.</p>
            </div>
            <div v-else class="cart-grid">
                <div v-for="(item, index) in cartItems" :key="item.id + item.option" class="cart-card">
                    <img :src="item.image" alt="Product image" class="cart-card-img">
                    <div class="cart-card-details">
                        <h3>{{ item.name }}</h3>
                        <p>Category: {{ item.category }}</p>
                        <p>Option: {{ item.option }}</p>
                        <p>Quantity: {{ item.quantity }}</p>
                        <p>Price: ${{ item.price }}</p>
                        <p>Total: ${{ item.price * item.quantity }}</p>
                        <button @click="removeItem(index)">Remove</button>
                    </div>
                </div>
            </div>
            <div class="cart-total" v-if="cartItems.length">
                <p>Total Price: ${{ totalPrice }}</p>
                <button @click="checkout">Proceed to Checkout</button>
            </div>
        </div>
        <!-- Product Card used inside Cart.vue, emits add-to-cart event -->
        <ProductCard v-for="product in products" :key="product.id" :product="product" @add-to-cart="addToCart" />
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
    data() {
        // return {
        //     routes: {}, // Pass routes if necessary
        //     selectedProduct: null, // The product to display
        //     //cartItems: [], // Array to hold cart items
        //     cartItems: JSON.parse(localStorage.getItem('cartItems')) || [],
        //
        // };
        const storedCart = JSON.parse(localStorage.getItem('cartItems'));
        console.log('Loaded cart from localStorage:', storedCart);
        return {
            cartItems: storedCart || [],
        };
    },
    computed: {
        // totalPrice() {
        //     return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
        // },
        totalPrice() {
            return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
        },
    },
    methods: {
        handleAddToCart(product) {
            // const existingItem = this.cartItems.find((item) => item.id === product.id);
            // if (existingItem) {
            //     existingItem.quantity += product.quantity;
            // } else {
            //     this.cartItems.push(product);
            // }
            //this.cartItems.push(product);
            this.$emit('add-to-cart', product);
        },
        // removeItem(index) {
        //     this.cartItems.splice(index, 1);
        // },
        // checkout() {
        //     alert("Proceeding to checkout!");
        // },
        // removeItem(index) {
        //     this.$emit("remove-item", index);
        // },
        // checkout() {
        //     alert("Proceeding to checkout!");
        // },
        addToCart(product) {
            console.log("---")
            console.log('Product added:', product);
            console.log(this.cartItems);
            const existingItem = this.cartItems.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity += product.quantity;  // Increase quantity if item already in cart
            } else {
                this.cartItems.push(product);  // Add new item to cart
            }

            // this.cartItems = [...this.cartItems];  // Create a new array to force reactivity
            localStorage.setItem('cartItems', JSON.stringify(this.cartItems));  // Save updated cart to localStorage
            console.log('Cart items after add:', this.cartItems);  // Ensure the cart is updated correctly
        },
        // Remove item from cart and update localStorage
        removeItem(index) {
            this.cartItems.splice(index, 1);
            localStorage.setItem('cartItems', JSON.stringify(this.cartItems));  // Save to localStorage
        },
        // Checkout action (you can customize this further)
        checkout() {
            alert("Proceeding to checkout!");
        },
    },
    // data() {
    //     return {
    //         cartItems: [],  // Array to hold cart items
    //     };
    // },
    // computed: {
    //     // Calculate total price of items in the cart
    //     totalPrice() {
    //         return this.cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
    //     }
    // },
    // methods: {
    //     // Fetch cart items from the API or local storage
    //     async fetchCartItems() {
    //         try {
    //             const response = await axios.get('/cart/items'); // Replace with your API endpoint
    //             this.cartItems = response.data.items || [];
    //         } catch (error) {
    //             console.error('Error fetching cart items:', error);
    //         }
    //     },
    //
    //     // Remove an item from the cart
    //     async removeItem(index) {
    //         try {
    //             const itemId = this.cartItems[index].id;
    //             await axios.delete(`/cart/items/${itemId}`);  // Replace with your API endpoint for removing items
    //             this.cartItems.splice(index, 1);  // Remove item from local array
    //         } catch (error) {
    //             console.error('Error removing item:', error);
    //         }
    //     },
    //
    //     // Proceed to checkout
    //     checkout() {
    //         this.$router.push('/checkout');  // Navigate to checkout page
    //     }
    // },
    // mounted() {
    //     this.fetchCartItems();  // Fetch cart items when the component is mounted
    // }
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
</style>
