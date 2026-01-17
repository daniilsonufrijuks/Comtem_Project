
import { createStore } from 'vuex';

const store = createStore({
    state: {
        cart: JSON.parse(localStorage.getItem('cart')) || [], // Array to store cart items
        award: JSON.parse(localStorage.getItem('user_award')) || 0,
    },
    // state: {
    //     cart: [
    //         {
    //             id: 1,
    //             name: 'Sample Product',
    //             description: 'This is a test product.',
    //             price: 100,
    //             quantity: 2,
    //             image: '/path/to/image.jpg',
    //         },
    //     ],
    //},
    mutations: {
        ADD_TO_CART(state, product) {

            const existingItem = state.cart.find(item => item.id === product.id);

            if (existingItem) {
                // If item exists, just increase quantity
                existingItem.quantity += product.quantity || 1;
            } else {
                // If item doesn't exist, add new item with quantity
                state.cart.push({
                    ...product,
                    quantity: product.quantity || 1
                });
            }

            // state.cart.push({ ...product, quantity: product.quantity || 1 });


            // Save updated cart to localStorage
            localStorage.setItem('cart', JSON.stringify(state.cart));
        },

        // REMOVE_FROM_CART(state, productId) {
        //     state.cart = state.cart.filter((item) => item.id !== productId);
        //     localStorage.setItem('cart', JSON.stringify(state.cart));
        // },

        CLEAR_CART(state) {
            state.cart = [];
            localStorage.setItem('cart', JSON.stringify(state.cart));
        },

        SET_AWARD(state, award) {
            state.award = award;
            localStorage.setItem('user_award', JSON.stringify(award)); // Save to localStorage
        },
    },
    getters: {
        cartItems: (state) => state.cart,
        cartTotal: (state) =>
            state.cart.reduce((total, item) => total + item.price * item.quantity, 0),
        award: (state) => state.award,
    },
});

export default store;
