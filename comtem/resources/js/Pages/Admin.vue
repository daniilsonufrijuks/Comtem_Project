<template>
    <div>
        <h1><b>Admin Dashboard</b></h1>

        <!-- Orders Table -->
        <h2>Orders</h2>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>items</th>
                <th>status</th>
                <th>total</th>
                <th>ordered_at</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="order in orders" :key="order.id">
                <td>{{ order.id }}</td>
                <td>{{ order.user_id }}</td>
                <td>{{ order.items }}</td>
                <td>{{ order.status }}</td>
                <td>{{ order.total }}</td>
                <td>{{ order.created_at }}</td>
            </tr>
            </tbody>
        </table>

        <h2>Products</h2>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Description</th>
                <th>Image</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="product in products" :key="product.id">
                <td>{{ product.name }}</td>
                <td>{{ product.price }}</td>
                <td>{{ product.category }}</td>
                <td>{{ product.description }}</td>
<!--                <td><img :src="'/storage/' + product.image" alt="Product Image" /></td>-->
            </tr>
            </tbody>
        </table>

        <!-- Add Product Form -->
        <h2>Add Product</h2>
        <form @submit.prevent="addProduct">
            <div>
                <label for="name">Name:</label>
                <input id="name" v-model="newProduct.name" type="text" required />
            </div>
            <div>
                <label for="price">Price:</label>
                <input id="price" v-model="newProduct.price" type="number" required />
            </div>
            <div>
                <label for="category">Category:</label>
                <input id="category" v-model="newProduct.category" type="text" required />
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" v-model="newProduct.description"></textarea>
            </div>
            <div>
                <label for="img">Image:</label>
                <input id="img" type="file" @change="handleFileUpload" />
            </div>
            <button type="submit">Add Product</button>
        </form>
    </div>
</template>

<script>

export default {
    props: {
        orders: {
            type: Array,
            required: true,
        },
        products: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            newProduct: {
                name: '',
                price: '',
                category: '',
                description: '',
            },
            imageFile: null,
            products: [],
            orders: [],
        };
    },
    mounted() {
        this.fetchOrders();
        this.fetchProducts();
    },
    computed: {

    },
    methods: {
        addProduct() {
            const formData = new FormData();
            Object.keys(this.newProduct).forEach((key) => {
                formData.append(key, this.newProduct[key]);
            });
            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }
            console.log(formData);  // Add this to see the data being sent
            // Log the FormData contents
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            this.$inertia.post('/admin/products', formData, {
                onSuccess: () => {
                    // Clear the form on success
                    this.newProduct = {
                        name: '',
                        price: '',
                        category: '',
                        description: '',
                    };
                    this.imageFile = null;
                    // Fetch updated products
                    // this.fetchProducts();
                },
                onError: (errors) => {
                    console.error('Failed to add product:', errors);
                },
            });
        },

        handleFileUpload(event) {
            this.imageFile = event.target.files[0];
        },
        // Sort products based on the selected order
        fetchOrders() {
            fetch('/admin/orders')
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    this.orders = data; // Assign the fetched data to the 'products' property
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
        fetchProducts() {
            fetch('/admin/products')
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    this.products = data; // Assign the fetched data to the 'products' property
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
    },
};
</script>

<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
table, th, td {
    border: 1px solid black;
}
th, td {
    padding: 8px;
    text-align: left;
}
form div {
    margin-bottom: 10px;
}
</style>
