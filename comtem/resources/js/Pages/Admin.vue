<template>
    <div class="admin-dashboard">
        <button @click="$inertia.visit('/')" class="back-button">Back to user page</button>
        <h1 class="title">Admin Dashboard</h1>

        <!-- Navigation Tabs -->
        <div class="navigation-tabs">
            <button
                @click="activeTab = 'orders'"
                :class="['tab-button', { active: activeTab === 'orders' }]"
            >
                Orders
            </button>
            <button
                @click="activeTab = 'orderItems'"
                :class="['tab-button', { active: activeTab === 'orderItems' }]"
            >
                Order Items
            </button>
            <button
                @click="activeTab = 'orderDetails'"
                :class="['tab-button', { active: activeTab === 'orderDetails' }]"
            >
                Order Details
            </button>
            <button
                @click="activeTab = 'users'"
                :class="['tab-button', { active: activeTab === 'users' }]"
            >
                Users
            </button>
            <button
                @click="activeTab = 'products'"
                :class="['tab-button', { active: activeTab === 'products' }]"
            >
                Products
            </button>
<!--            <button-->
<!--                @click="activeTab = 'brands'"-->
<!--                :class="['tab-button', { active: activeTab === 'brands' }]"-->
<!--            >-->
<!--                Brands-->
<!--            </button>-->
<!--            <button-->
<!--                @click="activeTab = 'categories'"-->
<!--                :class="['tab-button', { active: activeTab === 'categories' }]"-->
<!--            >-->
<!--                Categories-->
<!--            </button>-->
            <button
                @click="activeTab = 'addProduct'"
                :class="['tab-button', { active: activeTab === 'addProduct' }]"
            >
                Add Product
            </button>
            <button
                @click="activeTab = 'analytics'"
                :class="['tab-button', { active: activeTab === 'analytics' }]"
            >
                Analytics
            </button>
        </div>

        <!-- Orders Tab -->
        <section v-if="activeTab === 'orders'" class="section">
            <h2 class="section-title">Orders</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="order in orders" :key="order.id">
                        <template v-if="editOrder && editOrder.id === order.id">
                            <div class="edit-form">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <select v-model="editOrder.status" class="form-input">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Total ($):</label>
                                    <input v-model="editOrder.total" type="number" step="0.01" class="form-input" />
                                </div>
                                <div class="edit-actions">
                                    <button @click="updateOrder" class="save-btn">Save Changes</button>
                                    <button @click="cancelEditOrder" class="cancel-btn">Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <p><strong>ID:</strong> {{ order.id }}</p>
                            <p><strong>Customer ID:</strong> {{ order.user_id }}</p>
                            <p><strong>Customer:</strong> {{ order.user ? order.user.name : 'N/A' }}</p>
                            <p><strong>Status:</strong>
                                <span :class="`status-badge status-${order.status.toLowerCase()}`">
                                    {{ order.status }}
                                </span>
                            </p>
                            <p><strong>Total:</strong> ${{ order.total }}</p>
                            <p><strong>Ordered At:</strong> {{ formatDate(order.ordered_at || order.created_at) }}</p>
                            <p><strong>Items Count:</strong> {{ order.orderGoods ? order.orderGoods.length : 0 }}</p>
                            <div class="actions">
                                <button @click="startEditOrder(order)" class="edit-btn">Edit</button>
                                <button @click="deleteRecord('order', order.id)" class="delete-btn">Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>


        <!-- Order Items Tab -->
        <section v-if="activeTab === 'orderItems'" class="section">
            <h2 class="section-title">Order Items</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="item in orderItems" :key="item.id">
                        <p><strong>ID:</strong> {{ item.id }}</p>
                        <p><strong>Order ID:</strong> {{ item.order_id }}</p>
                        <p><strong>Product Name:</strong> {{ item.name }}</p>
                        <p><strong>Price:</strong> ${{ item.price }}</p>
                        <p><strong>Status:</strong> {{ item.status }}</p>
                        <p><strong>Category:</strong> {{ item.category }}</p>
                        <p><strong>Total Price:</strong> ${{ item.total_price }}</p>
                        <div class="actions">
                            <button @click="deleteRecord('orderitem', item.id)" class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Order Details Tab -->
        <section v-if="activeTab === 'orderDetails'" class="section">
            <h2 class="section-title">Order Details</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="order in ordersj" :key="order.order_id">
                        <p><strong>Order ID:</strong> {{ order.order_id }}</p>
                        <p><strong>Customer:</strong> {{ order.customer_name }} ({{ order.customer_email }})</p>
                        <p><strong>Item:</strong> {{ order.item_name }}</p>
                        <p><strong>Status:</strong> {{ order.order_status }}</p>
                        <p><strong>Total Price:</strong> ${{ order.total_price }}</p>
                        <p><strong>Category:</strong> {{ order.category }}</p>
                        <p><strong>Order Date:</strong> {{ formatDate(order.created_at) }}</p>
                        <div class="actions">
                            <button @click="deleteRecord('orderj', order.order_id)" class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Users Tab -->
        <section v-if="activeTab === 'users'" class="section">
            <h2 class="section-title">Users</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="user in users" :key="user.id">
                        <template v-if="editUser && editUser.id === user.id">
                            <div class="edit-form">
                                <div class="form-group">
                                    <label>Name:</label>
                                    <input v-model="editUser.name" type="text" class="form-input" />
                                </div>
                                <div class="form-group">
                                    <label>Email:</label>
                                    <input v-model="editUser.email" type="email" class="form-input" />
                                </div>
                                <div class="form-group">
                                    <label>Password (leave empty to keep current):</label>
                                    <input v-model="editUser.password" type="password" class="form-input" placeholder="New password" />
                                </div>
                                <div class="edit-actions">
                                    <button @click="updateUser" class="save-btn">Save Changes</button>
                                    <button @click="cancelEditUser" class="cancel-btn">Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <p><strong>ID:</strong> {{ user.id }}</p>
                            <p><strong>Name:</strong> {{ user.name }}</p>
                            <p><strong>Email:</strong> {{ user.email }}</p>
                            <p><strong>Registered:</strong> {{ formatDate(user.created_at) }}</p>
                            <p><strong>Orders Count:</strong> {{ user.orders_count || 0 }}</p>
                            <div class="actions">
                                <button @click="startEditUser(user)" class="edit-btn">Edit</button>
                                <button @click="deleteRecord('user', user.id)" class="delete-btn">Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Tab -->
        <section v-if="activeTab === 'products'" class="section">
            <h2 class="section-title">Products</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="product in products" :key="product.id">
                        <template v-if="editProduct && editProduct.id === product.id">
                            <div class="edit-form">
                                <input v-model="editProduct.name" placeholder="Name" class="form-input" />
                                <input v-model="editProduct.price" type="number" step="0.01" placeholder="Price" class="form-input" />
                                <input v-model="editProduct.category" placeholder="Category" class="form-input" />
                                <textarea v-model="editProduct.description" placeholder="Description" class="form-input"></textarea>

                                <!-- Current Image Preview -->
                                <div v-if="product.image && !editImageFile" class="current-image">
                                    <p><strong>Current Image:</strong></p>
                                    <img :src="'/' + product.image" alt="Current Product Image" />
                                    <p class="image-path">{{ product.image }}</p>
                                </div>

                                <!-- New Image Preview -->
                                <div v-if="editImageFile" class="new-image">
                                    <p><strong>New Image:</strong></p>
                                    <img :src="editImagePreview" alt="New Product Image" />
                                    <p class="image-name">{{ editImageFile.name }}</p>
                                </div>

                                <!-- File Upload -->
                                <div class="file-upload">
                                    <label>Update Image (optional):</label>
                                    <input type="file" @change="handleEditImageUpload" accept="image/*" />
                                    <small>Leave empty to keep current image</small>
                                </div>

                                <div class="edit-actions">
                                    <button @click="updateProduct" class="save-btn">Save Changes</button>
                                    <button @click="cancelEdit" class="cancel-btn">Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <p><strong>Name:</strong> {{ product.name }}</p>
                            <p><strong>Price:</strong> ${{ product.price }}</p>
                            <p><strong>Category:</strong> {{ product.category }}</p>
                            <p><strong>Description:</strong> {{ product.description }}</p>
                            <div v-if="product.image" class="product-image">
                                <img :src="'/' + product.image" alt="Product Image" />
                            </div>
                            <div class="actions">
                                <button @click="startEdit(product)" class="edit-btn">Edit</button>
                                <button @click="deleteRecord('product', product.id)" class="delete-btn">Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Brands Tab -->
        <section v-if="activeTab === 'brands'" class="section">
            <h2 class="section-title">Brands</h2>
            <!-- Add Brand Form -->
            <div class="add-brand-form">
                <h3>Add New Brand</h3>
                <form @submit.prevent="addBrand" class="form">
                    <div class="form-group">
                        <label>Brand Name *</label>
                        <input v-model="newBrand.name" type="text" placeholder="Brand Name" required />
                    </div>
                    <button type="submit" class="submit-btn" :disabled="isAddingBrand">
                        {{ isAddingBrand ? 'Adding Brand...' : 'Add Brand' }}
                    </button>
                </form>
            </div>

            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="brand in brands" :key="brand.id">
                        <p><strong>ID:</strong> {{ brand.id }}</p>
                        <p><strong>Name:</strong> {{ brand.name }}</p>
                        <p><strong>Created:</strong> {{ formatDate(brand.created_at) }}</p>
                        <div class="actions">
                            <button @click="deleteRecord('brand', brand.id)" class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Categories Tab -->
        <section v-if="activeTab === 'categories'" class="section">
            <h2 class="section-title">Categories</h2>
            <!-- Add Category Form -->
            <div class="add-category-form">
                <h3>Add New Category</h3>
                <form @submit.prevent="addCategory" class="form">
                    <div class="form-group">
                        <label>Category Name *</label>
                        <input v-model="newCategory.name" type="text" placeholder="Category Name" required />
                    </div>
                    <button type="submit" class="submit-btn" :disabled="isAddingCategory">
                        {{ isAddingCategory ? 'Adding Category...' : 'Add Category' }}
                    </button>
                </form>
            </div>

            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="card" v-for="category in categories" :key="category.id">
                        <p><strong>ID:</strong> {{ category.id }}</p>
                        <p><strong>Name:</strong> {{ category.name }}</p>
                        <p><strong>Created:</strong> {{ formatDate(category.created_at) }}</p>
                        <div class="actions">
                            <button @click="deleteRecord('category', category.id)" class="delete-btn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add Product Tab -->
        <section v-if="activeTab === 'addProduct'" class="section">
            <h2 class="section-title">Add Product</h2>
            <div class="scrollable-container">
                <form @submit.prevent="addProduct" class="form">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input v-model="newProduct.name" type="text" placeholder="Product Name" required />
                    </div>

                    <div class="form-group">
                        <label>Price *</label>
                        <input v-model="newProduct.price" type="number" step="0.01" placeholder="Price" required />
                    </div>

                    <div class="form-group">
                        <label>Category *</label>
                        <input v-model="newProduct.category" type="text" placeholder="Category" required />
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea v-model="newProduct.description" placeholder="Description"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Product Image *</label>
                        <input type="file" @change="handleFileUpload" accept="image/*" required />
                        <small>Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                    </div>

                    <div v-if="imageFile" class="image-preview">
                        <p><strong>Image Preview:</strong></p>
                        <img :src="imagePreview" alt="Product Image Preview" />
                        <p class="image-name">{{ imageFile.name }}</p>
                    </div>

                    <button type="submit" class="submit-btn" :disabled="isAdding">
                        {{ isAdding ? 'Adding Product...' : 'Add Product' }}
                    </button>
                </form>
            </div>
        </section>

        <!-- Analytics Tab -->
        <section v-if="activeTab === 'analytics'" class="section">
            <h2 class="section-title">Analytics Dashboard</h2>
            <div class="analytics-grid">
                <!-- Summary Cards -->
                <div class="summary-cards">
                    <div class="summary-card">
                        <h3>Total Orders</h3>
                        <p class="stat">{{ totalOrders }}</p>
                    </div>
                    <div class="summary-card">
                        <h3>Total Revenue</h3>
                        <p class="stat">${{ totalRevenue }}</p>
                    </div>
                    <div class="summary-card">
                        <h3>Total Products</h3>
                        <p class="stat">{{ totalProducts }}</p>
                    </div>
                    <div class="summary-card">
                        <h3>Total Users</h3>
                        <p class="stat">{{ totalUsers }}</p>
                    </div>
                </div>

                <!-- Orders Chart -->
                <div class="chart-container">
                    <h3>Orders Per Day (Last 7 Days)</h3>
                    <canvas ref="ordersChart" width="400" height="200"></canvas>
                </div>

                <!-- Revenue Chart -->
                <div class="chart-container">
                    <h3>Revenue Per Day (Last 7 Days)</h3>
                    <canvas ref="revenueChart" width="400" height="200"></canvas>
                </div>

                <!-- Top Products -->
                <div class="top-products">
                    <h3>Top Selling Products</h3>
                    <table class="data-table">
                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in topProducts" :key="product.id">
                            <td>{{ product.name }}</td>
                            <td>{{ product.quantity_sold }}</td>
                            <td>${{ product.revenue }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Order Status Distribution -->
                <div class="status-distribution">
                    <h3>Order Status Distribution</h3>
                    <canvas ref="statusChart" width="400" height="200"></canvas>
                </div>
            </div>
        </section>

        <!-- Notification -->
        <div v-if="notification.show" :class="['notification', notification.type]">
            {{ notification.message }}
        </div>
    </div>
</template>

<script>
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
export default {
    props: {
        orders: {
            type: Array,
            default: () => []
        },
        products: {
            type: Array,
            default: () => []
        },
        ordersj: {
            type: Array,
            default: () => []
        },
        users: {
            type: Array,
            default: () => []
        },
        brands: {
            type: Array,
            default: () => []
        },
        categories: {
            type: Array,
            default: () => []
        },
        analytics: {
            type: Object,
            default: () => ({})
        }
    },
    data() {
        return {
            activeTab: 'orders',
            newProduct: {
                name: '',
                price: '',
                category: '',
                description: ''
            },
            newBrand: {
                name: ''
            },
            newCategory: {
                name: ''
            },
            imageFile: null,
            imagePreview: null,
            editProduct: null,
            editUser: null,
            editOrder: null,
            editImageFile: null,
            editImagePreview: null,
            isAdding: false,
            isAddingBrand: false,
            isAddingCategory: false,
            notification: {
                show: false,
                message: '',
                type: 'success'
            },
            orderItems: [],
            totalOrders: 0,
            totalRevenue: 0,
            totalProducts: 0,
            totalUsers: 0,
            topProducts: [],
            ordersChart: null,
            revenueChart: null,
            statusChart: null
        };
    },
    mounted() {
        this.fetchOrderItems();
        this.calculateAnalytics();
        if (this.activeTab === 'analytics') {
            this.$nextTick(() => {
                this.initCharts();
            });
        }
    },
    watch: {
        activeTab(newTab) {
            if (newTab === 'analytics') {
                this.$nextTick(() => {
                    this.initCharts();
                });
            }
        }
    },
    methods: {
        startEdit(product) {
            this.editProduct = { ...product };
            this.editImageFile = null;
            this.editImagePreview = null;
        },

        startEditUser(user) {
            this.editUser = { ...user, password: '' };
        },

        startEditOrder(order) {
            this.editOrder = { ...order };
        },

        cancelEdit() {
            this.editProduct = null;
            this.editImageFile = null;
            this.editImagePreview = null;
        },

        cancelEditUser() {
            this.editUser = null;
        },

        cancelEditOrder() {
            this.editOrder = null;
        },

        async updateProduct() {
            if (!this.editProduct) return;

            const formData = new FormData();
            Object.keys(this.editProduct).forEach(key => {
                if (this.editProduct[key] !== null && this.editProduct[key] !== undefined) {
                    formData.append(key, this.editProduct[key]);
                }
            });

            formData.append('_method', 'PUT');

            if (this.editImageFile) {
                formData.append('image', this.editImageFile);
            }

            try {
                await this.$inertia.post(`/admin/products/${this.editProduct.id}`, formData, {
                    onSuccess: () => {
                        this.showNotification('Product updated successfully!', 'success');
                        this.editProduct = null;
                        this.editImageFile = null;
                        this.editImagePreview = null;
                    },
                    onError: (errors) => {
                        this.showNotification('Error updating product: ' + JSON.stringify(errors), 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating product: ' + error.message, 'error');
            }
        },

        async updateUser() {
            if (!this.editUser) return;

            try {
                await this.$inertia.put(`/admin/users/${this.editUser.id}`, {
                    name: this.editUser.name,
                    email: this.editUser.email,
                    password: this.editUser.password || undefined
                }, {
                    onSuccess: () => {
                        this.showNotification('User updated successfully!', 'success');
                        this.editUser = null;
                    },
                    onError: (errors) => {
                        this.showNotification('Error updating user: ' + JSON.stringify(errors), 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating user: ' + error.message, 'error');
            }
        },

        async updateOrder() {
            if (!this.editOrder) return;

            try {
                await this.$inertia.put(`/admin/orders/${this.editOrder.id}`, {
                    status: this.editOrder.status,
                    total: this.editOrder.total
                }, {
                    onSuccess: () => {
                        this.showNotification('Order updated successfully!', 'success');
                        this.editOrder = null;
                    },
                    onError: (errors) => {
                        this.showNotification('Error updating order: ' + JSON.stringify(errors), 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating order: ' + error.message, 'error');
            }
        },

        async addProduct() {
            if (!this.newProduct.name || !this.newProduct.price || !this.newProduct.category || !this.imageFile) {
                this.showNotification('Please fill all required fields and select an image', 'error');
                return;
            }

            this.isAdding = true;

            const formData = new FormData();
            Object.keys(this.newProduct).forEach((key) => {
                if (this.newProduct[key] !== null && this.newProduct[key] !== undefined && this.newProduct[key] !== '') {
                    formData.append(key, this.newProduct[key]);
                }
            });

            if (this.imageFile) {
                formData.append('image', this.imageFile);
            }

            try {
                await this.$inertia.post('/admin/products', formData, {
                    onSuccess: () => {
                        this.showNotification('Product added successfully!', 'success');
                        this.resetForm();
                        this.isAdding = false;
                        this.activeTab = 'products';
                    },
                    onError: (errors) => {
                        this.showNotification('Failed to add product: ' + JSON.stringify(errors), 'error');
                        this.isAdding = false;
                    }
                });
            } catch (error) {
                this.showNotification('Error adding product: ' + error.message, 'error');
                this.isAdding = false;
            }
        },

        async addBrand() {
            if (!this.newBrand.name) {
                this.showNotification('Please enter a brand name', 'error');
                return;
            }
            this.isAddingBrand = true;
            try {
                await this.$inertia.post('/admin/brands', {
                    name: this.newBrand.name
                }, {
                    onSuccess: () => {
                        this.showNotification('Brand added successfully!', 'success');
                        this.newBrand.name = '';
                    },
                    onError: (errors) => {
                        this.showNotification('Failed to add brand: ' + JSON.stringify(errors), 'error');
                    },
                    onFinish: () => {
                        this.isAddingBrand = false;
                    },
                });
            } catch (error) {
                this.showNotification('Error adding brand: ' + error.message, 'error');
                this.isAddingBrand = false;
            }
        },

        async addCategory() {
            if (!this.newCategory.name) {
                this.showNotification('Please enter a category name', 'error');
                return;
            }
            this.isAddingCategory = true;
            try {
                await this.$inertia.post('/admin/categories', {
                    name: this.newCategory.name
                }, {
                    onSuccess: () => {
                        this.showNotification('Category added successfully!', 'success');
                        this.newCategory.name = '';
                    },
                    onError: (errors) => {
                        this.showNotification('Failed to add category: ' + JSON.stringify(errors), 'error');
                    },
                    onFinish: () => {
                        this.isAddingCategory = false;
                    },
                });
            } catch (error) {
                this.showNotification('Error adding category: ' + error.message, 'error');
                this.isAddingCategory = false;
            }
        },

        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.imageFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.imageFile = null;
                this.imagePreview = null;
            }
        },

        handleEditImageUpload(event) {
            const file = event.target.files[0];
            if (file) {
                this.editImageFile = file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.editImagePreview = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.editImageFile = null;
                this.editImagePreview = null;
            }
        },

        deleteRecord(type, id) {
            if (confirm(`Are you sure you want to delete this ${type}?`)) {
                const pluralForms = {
                    'order': 'orders',
                    'orderj': 'orders',
                    'orderitem': 'orderitems',
                    'product': 'products',
                    'user': 'users',
                    'brand': 'brands',
                    'category': 'categories'
                };

                const pluralType = pluralForms[type] || `${type}s`;
                this.$inertia.delete(`/admin/${pluralType}/${id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification(`${type} deleted successfully!`, 'success');
                    },
                    onError: (err) => {
                        let errorMessage = `Error deleting ${type}`;
                        if (err && typeof err === 'object') {
                            errorMessage = err.error || errorMessage;
                        } else if (err) {
                            errorMessage = err;
                        }
                        this.showNotification(errorMessage, 'error');
                    },
                });
            }
        },

        updateOrderStatus(order) {
            const newStatus = prompt('Enter new status (pending, processing, completed, cancelled):', order.status);
            if (newStatus && newStatus !== order.status) {
                this.$inertia.put(`/admin/orders/${order.id}`, {
                    status: newStatus
                }, {
                    onSuccess: () => {
                        this.showNotification('Order status updated successfully!', 'success');
                    },
                    onError: (err) => {
                        this.showNotification('Error updating order status: ' + err, 'error');
                    }
                });
            }
        },

        resetForm() {
            this.newProduct = {
                name: '',
                price: '',
                category: '',
                description: ''
            };
            this.imageFile = null;
            this.imagePreview = null;
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = '';
        },

        showNotification(message, type = 'success') {
            this.notification = {
                show: true,
                message,
                type
            };
            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        },

        async fetchOrderItems() {
            try {
                const response = await axios.get('/admin/order-items');
                this.orderItems = response.data;
            } catch (error) {
                console.error('Error fetching order items:', error);
            }
        },

        calculateAnalytics() {
            this.totalOrders = this.orders.length;
            this.totalProducts = this.products.length;
            this.totalUsers = this.users.length;

            // Calculate total revenue from orders
            this.totalRevenue = this.orders.reduce((sum, order) => {
                return sum + (parseFloat(order.total) || 0);
            }, 0).toFixed(2);

            // Calculate top products (simplified - you might want to get this from backend)
            const productSales = {};
            this.ordersj.forEach(order => {
                if (!productSales[order.item_name]) {
                    productSales[order.item_name] = {
                        name: order.item_name,
                        quantity_sold: 0,
                        revenue: 0
                    };
                }
                productSales[order.item_name].quantity_sold += 1;
                productSales[order.item_name].revenue += parseFloat(order.total_price) || 0;
            });

            this.topProducts = Object.values(productSales)
                .sort((a, b) => b.revenue - a.revenue)
                .slice(0, 5);
        },

        initCharts() {
            // Destroy existing charts
            if (this.ordersChart) this.ordersChart.destroy();
            if (this.revenueChart) this.revenueChart.destroy();
            if (this.statusChart) this.statusChart.destroy();

            // Orders per day chart
            const ordersCtx = this.$refs.ordersChart?.getContext('2d');
            if (ordersCtx) {
                // Generate sample data for last 7 days
                const labels = [];
                const ordersData = [];
                for (let i = 6; i >= 0; i--) {
                    const date = new Date();
                    date.setDate(date.getDate() - i);
                    labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
                    // Random data for demo - replace with actual data
                    ordersData.push(Math.floor(Math.random() * 20) + 5);
                }

                this.ordersChart = new Chart(ordersCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Orders',
                            data: ordersData,
                            borderColor: '#420d65',
                            backgroundColor: 'rgba(66, 13, 101, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 5
                                }
                            }
                        }
                    }
                });
            }

            // Revenue chart
            const revenueCtx = this.$refs.revenueChart?.getContext('2d');
            if (revenueCtx) {
                const labels = [];
                const revenueData = [];
                for (let i = 6; i >= 0; i--) {
                    const date = new Date();
                    date.setDate(date.getDate() - i);
                    labels.push(date.toLocaleDateString('en-US', { weekday: 'short' }));
                    // Random data for demo
                    revenueData.push((Math.random() * 1000 + 500).toFixed(2));
                }

                this.revenueChart = new Chart(revenueCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Revenue ($)',
                            data: revenueData,
                            backgroundColor: 'rgba(75, 192, 192, 0.6)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: true
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return '$' + value;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Status distribution chart
            const statusCtx = this.$refs.statusChart?.getContext('2d');
            if (statusCtx) {
                const statusCounts = {};
                this.orders.forEach(order => {
                    statusCounts[order.status] = (statusCounts[order.status] || 0) + 1;
                });

                const statusLabels = Object.keys(statusCounts);
                const statusData = Object.values(statusCounts);
                const backgroundColors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

                this.statusChart = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: statusLabels,
                        datasets: [{
                            data: statusData,
                            backgroundColor: backgroundColors,
                            hoverBackgroundColor: backgroundColors.map(color => color + 'CC')
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }
        }
    }
};
</script>

<style scoped>
/* Add all the CSS from the second admin template here */
/* Copy the entire <style scoped> section from the second admin template */

.admin-dashboard {
    padding: 20px;
    font-family: Arial, sans-serif;
    max-width: 1400px;
    margin: 0 auto;
}

.title {
    text-align: center;
    color: #420d65;
    margin-bottom: 30px;
}

.navigation-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    justify-content: center;
}

.tab-button {
    padding: 12px 24px;
    border: 2px solid #420d65;
    background-color: white;
    color: #420d65;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: all 0.3s ease;
}

.tab-button:hover {
    background-color: #f0e6f7;
}

.tab-button.active {
    background-color: #420d65;
    color: white;
}

.section {
    margin: 20px 0;
    padding: 20px;
    border: 1px solid #e0e0e0;
    border-radius: 10px;
    background-color: #f9f9f9;
}

.section-title {
    margin-bottom: 20px;
    color: #333;
    border-bottom: 2px solid #420d65;
    padding-bottom: 10px;
}

.scrollable-container {
    max-height: 70vh;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: white;
    padding: 15px;
}

.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.actions {
    margin-top: 15px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.edit-btn {
    background-color: #4CAF50;
    color: white;
}

.edit-btn:hover {
    background-color: #45a049;
}

.delete-btn {
    background-color: #e63946;
    color: white;
}

.delete-btn:hover {
    background-color: #d62828;
}

.status-btn {
    background-color: #2196F3;
    color: white;
}

.status-btn:hover {
    background-color: #1976D2;
}

.save-btn {
    background-color: #2196F3;
    color: white;
}

.cancel-btn {
    background-color: #757575;
    color: white;
}

.form {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.form-group label {
    font-weight: bold;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 14px;
}

.form-group textarea {
    min-height: 80px;
    resize: vertical;
}

.submit-btn {
    background-color: #420d65;
    color: white;
    cursor: pointer;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 6px;
}

.submit-btn:hover:not(:disabled) {
    background-color: #330a4f;
}

.submit-btn:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}

.back-button {
    background-color: #420d65;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 20px;
    font-size: 14px;
}

.back-button:hover {
    background-color: #330a4f;
}

.product-image img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.edit-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-input {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.current-image, .new-image, .image-preview {
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
}

.current-image img, .new-image img, .image-preview img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 5px;
    color: white;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.notification.success {
    background-color: #4CAF50;
}

.notification.error {
    background-color: #e63946;
}

/* Analytics Styles */
.analytics-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.summary-cards {
    grid-column: 1 / -1;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.summary-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.summary-card h3 {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 14px;
}

.summary-card .stat {
    font-size: 32px;
    font-weight: bold;
    color: #420d65;
    margin: 0;
}

.chart-container, .top-products, .status-distribution {
    background: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.chart-container h3, .top-products h3, .status-distribution h3 {
    margin: 0 0 20px 0;
    color: #333;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th, .data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.data-table th {
    background-color: #f5f5f5;
    font-weight: bold;
    color: #333;
}

.data-table tr:hover {
    background-color: #f9f9f9;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: bold;
    color: white;
}

.status-pending {
    background-color: #FFA726;
}

.status-processing {
    background-color: #29B6F6;
}

.status-completed {
    background-color: #66BB6A;
}

.status-cancelled {
    background-color: #EF5350;
}

@media (max-width: 1024px) {
    .analytics-grid {
        grid-template-columns: 1fr;
    }

    .summary-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .card-grid {
        grid-template-columns: 1fr;
    }

    .summary-cards {
        grid-template-columns: 1fr;
    }

    .navigation-tabs {
        flex-direction: column;
        align-items: center;
    }

    .tab-button {
        width: 100%;
        max-width: 300px;
    }

    .scrollable-container {
        max-height: 60vh;
    }
}
</style>
