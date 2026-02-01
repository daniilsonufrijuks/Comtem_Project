<template>
    <div class="admin-dashboard">
        <div class="admin-header">
            <button @click="$inertia.visit('/')" class="back-button">Back to Home</button>
            <h1 class="title">Admin Dashboard</h1>
        </div>

        <!-- Navigation Tabs -->
        <div class="navigation-tabs">
            <button @click="activeTab = 'analytics'" :class="['tab-button', { active: activeTab === 'analytics' }]">
                📊 Analytics
            </button>
            <button @click="activeTab = 'orders'" :class="['tab-button', { active: activeTab === 'orders' }]">
                📦 Orders
            </button>
            <button @click="activeTab = 'orderDetails'" :class="['tab-button', { active: activeTab === 'orderDetails' }]">
                📋 Order Details
            </button>
            <button @click="activeTab = 'orderItems'" :class="['tab-button', { active: activeTab === 'orderItems' }]">
                📝 Order Items
            </button>
            <button @click="activeTab = 'products'" :class="['tab-button', { active: activeTab === 'products' }]">
                🛍️ Products
            </button>
            <button @click="activeTab = 'users'" :class="['tab-button', { active: activeTab === 'users' }]">
                👥 Users
            </button>
            <button @click="activeTab = 'families'" :class="['tab-button', { active: activeTab === 'families' }]">
                👨‍👩‍👧‍👦 Families
            </button>
            <button @click="activeTab = 'addProduct'" :class="['tab-button', { active: activeTab === 'addProduct' }]">
                ➕ Add Product
            </button>
            <button @click="activeTab = 'addUser'" :class="['tab-button', { active: activeTab === 'addUser' }]">
                👤 Add User
            </button>
            <button @click="activeTab = 'addFamily'" :class="['tab-button', { active: activeTab === 'addFamily' }]">
                🏠 Add Family
            </button>
        </div>

        <!-- Analytics Tab -->
        <section v-if="activeTab === 'analytics'" class="section analytics-section">
            <h2 class="section-title">📊 Dashboard Analytics</h2>

            <!-- Loading state -->
            <div v-if="loadingAnalytics" class="loading">
                Loading analytics...
            </div>

            <div v-else>
                <!-- Summary Cards -->
                <div class="summary-cards">
                    <div class="summary-card">
                        <div class="card-icon">📦</div>
                        <div class="card-content">
                            <h3>Total Orders</h3>
                            <p class="stat">{{ analyticsData.totalOrders || 0 }}</p>
                            <small>{{ analyticsData.recentOrders || 0 }} in last 30 days</small>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-icon">💰</div>
                        <div class="card-content">
                            <h3>Total Revenue</h3>
                            <p class="stat">${{ formatNumber(analyticsData.totalRevenue) || '0' }}</p>
                            <small>${{ formatNumber(analyticsData.recentRevenue) || '0' }} recent</small>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-icon">🛍️</div>
                        <div class="card-content">
                            <h3>Products</h3>
                            <p class="stat">{{ analyticsData.totalProducts || 0 }}</p>
                            <small>Active listings</small>
                        </div>
                    </div>

                    <div class="summary-card">
                        <div class="card-icon">👥</div>
                        <div class="card-content">
                            <h3>Users</h3>
                            <p class="stat">{{ analyticsData.totalUsers || 0 }}</p>
                            <small>{{ analyticsData.totalFamilies || 0 }} families</small>
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="charts-grid">
                    <div class="chart-container">
                        <h3>📈 Orders Trend (30 Days)</h3>
                        <canvas ref="ordersChart" width="400" height="200"></canvas>
                    </div>

                    <div class="chart-container">
                        <h3>💰 Revenue Trend (30 Days)</h3>
                        <canvas ref="revenueChart" width="400" height="200"></canvas>
                    </div>

                    <div class="chart-container">
                        <h3>📊 Order Status</h3>
                        <canvas ref="statusChart" width="400" height="200"></canvas>
                    </div>

                    <div class="chart-container">
                        <h3>🏷️ Category Performance</h3>
                        <canvas ref="categoryChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Top Tables -->
                <div class="tables-grid">
                    <!-- Top Products -->
                    <div class="table-container" v-if="analyticsData.topProducts && analyticsData.topProducts.length > 0">
                        <h3>🔥 Top Products</h3>
                        <div class="scrollable-table">
                            <table class="data-table">
                                <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Sold</th>
                                    <th>Revenue</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="product in analyticsData.topProducts" :key="product.name">
                                    <td>{{ product.name }}</td>
                                    <td>{{ product.category }}</td>
                                    <td>{{ product.quantity_sold }}</td>
                                    <td>${{ formatNumber(product.revenue) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Customers -->
                    <div class="table-container" v-if="analyticsData.topCustomers && analyticsData.topCustomers.length > 0">
                        <h3>⭐ Top Customers</h3>
                        <div class="scrollable-table">
                            <table class="data-table">
                                <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Orders</th>
                                    <th>Spent</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="customer in analyticsData.topCustomers" :key="customer.id">
                                    <td>{{ customer.name }}</td>
                                    <td>{{ customer.email }}</td>
                                    <td>{{ customer.order_count }}</td>
                                    <td>${{ formatNumber(customer.total_spent) }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Orders Tab -->
        <section v-if="activeTab === 'orders'" class="section">
            <h2 class="section-title">📦 Orders Management</h2>
            <div class="table-controls">
                <input v-model="orderSearch" type="text" placeholder="Search orders..." class="search-input" />
                <select v-model="orderStatusFilter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="order-card" v-for="order in filteredOrders" :key="order.id">
                        <template v-if="editOrder && editOrder.id === order.id">
                            <div class="edit-form">
                                <select v-model="editOrder.status" class="form-input">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                <input v-model="editOrder.total" type="number" step="0.01" placeholder="Total" class="form-input" />
                                <div class="edit-actions">
                                    <button @click="updateOrder" class="save-btn">💾 Save</button>
                                    <button @click="cancelEditOrder" class="cancel-btn">❌ Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="order-header">
                                <div class="order-id">Order #{{ order.id }}</div>
                                <div class="order-status" :class="order.status">{{ order.status }}</div>
                            </div>
                            <div class="order-details">
                                <p><strong>Customer:</strong> {{ order.user ? order.user.name : 'N/A' }}</p>
                                <p><strong>Total:</strong> ${{ order.total }}</p>
                                <p><strong>Date:</strong> {{ formatDate(order.created_at) }}</p>
                                <p><strong>Payment:</strong> {{ order.payment_method || 'N/A' }}</p>
                                <p><strong>Items:</strong> {{ order.order_goods ? order.order_goods.length : 0 }}</p>
                                <p v-if="order.shipping_address"><strong>Shipping:</strong> {{ order.shipping_address }}</p>
                            </div>
                            <div class="actions">
                                <button @click="startEditOrder(order)" class="edit-btn">✏️ Edit</button>
                                <button @click="deleteOrder(order.id)" class="delete-btn">🗑️ Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
                <div v-if="filteredOrders.length === 0" class="empty-state">
                    No orders found
                </div>
            </div>
        </section>

        <!-- Order Details Tab -->
        <section v-if="activeTab === 'orderDetails'" class="section">
            <h2 class="section-title">📋 Order Details</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="order-detail-card" v-for="order in ordersj" :key="order.order_id">
                        <div class="order-header">
                            <div class="order-id">Order #{{ order.order_id }}</div>
                            <div class="order-status" :class="order.order_status">{{ order.order_status }}</div>
                        </div>
                        <div class="order-details">
                            <p><strong>Customer:</strong> {{ order.customer_name }} ({{ order.customer_email }})</p>
                            <p><strong>Item:</strong> {{ order.item_name }}</p>
                            <p><strong>Price:</strong> ${{ order.item_price }}</p>
                            <p><strong>Category:</strong> {{ order.category }}</p>
                            <p><strong>Total:</strong> ${{ order.total_price }}</p>
                            <p><strong>Date:</strong> {{ formatDate(order.created_at) }}</p>
                        </div>
                        <div class="actions">
                            <button @click="deleteOrder(order.order_id)" class="delete-btn">🗑️ Delete</button>
                        </div>
                    </div>
                </div>
                <div v-if="ordersj.length === 0" class="empty-state">
                    No order details found
                </div>
            </div>
        </section>

        <!-- Order Items Tab -->
        <section v-if="activeTab === 'orderItems'" class="section">
            <h2 class="section-title">📝 Order Items</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="order-item-card" v-for="item in orderItems" :key="item.id">
                        <div class="item-header">
                            <div class="item-id">Item #{{ item.id }}</div>
                            <div class="item-status" :class="item.status">{{ item.status }}</div>
                        </div>
                        <div class="item-details">
                            <p><strong>Order ID:</strong> {{ item.order_id }}</p>
                            <p><strong>Product:</strong> {{ item.name }}</p>
                            <p><strong>Price:</strong> ${{ item.price }}</p>
                            <p><strong>Category:</strong> {{ item.category }}</p>
                            <p><strong>Total:</strong> ${{ item.total_price }}</p>
                            <p><strong>Date:</strong> {{ formatDate(item.created_at) }}</p>
                        </div>
                        <div class="actions">
                            <button @click="deleteOrderItem(item.id)" class="delete-btn">🗑️ Delete</button>
                        </div>
                    </div>
                </div>
                <div v-if="orderItems.length === 0" class="empty-state">
                    No order items found
                </div>
            </div>
        </section>

        <!-- Products Tab -->
        <section v-if="activeTab === 'products'" class="section">
            <h2 class="section-title">🛍️ Product Management</h2>
            <div class="table-controls">
                <input v-model="productSearch" type="text" placeholder="Search products..." class="search-input" />
                <select v-model="productCategoryFilter" class="filter-select">
                    <option value="">All Categories</option>
                    <option v-for="category in productCategories" :key="category">{{ category }}</option>
                </select>
            </div>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="product-card" v-for="product in filteredProducts" :key="product.id">
                        <template v-if="editProduct && editProduct.id === product.id">
                            <div class="edit-form">
                                <input v-model="editProduct.name" placeholder="Name" class="form-input" />
                                <input v-model="editProduct.price" type="number" step="0.01" placeholder="Price" class="form-input" />
                                <input v-model="editProduct.category" placeholder="Category" class="form-input" />
                                <textarea v-model="editProduct.description" placeholder="Description" class="form-input"></textarea>

                                <div v-if="product.image && !editImageFile" class="current-image">
                                    <p><strong>Current Image:</strong></p>
                                    <img :src="'/' + product.image" alt="Current Product Image" />
                                </div>

                                <div v-if="editImageFile" class="new-image">
                                    <p><strong>New Image:</strong></p>
                                    <img :src="editImagePreview" alt="New Product Image" />
                                </div>

                                <div class="file-upload">
                                    <label>Update Image (optional):</label>
                                    <input type="file" @change="handleEditImageUpload" accept="image/*" />
                                </div>

                                <div class="edit-actions">
                                    <button @click="updateProduct" class="save-btn">💾 Save</button>
                                    <button @click="cancelEdit" class="cancel-btn">❌ Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="product-image-container">
                                <img v-if="product.image" :src="'/' + product.image" alt="Product Image" class="product-image" />
                                <div v-else class="no-image">No Image</div>
                            </div>
                            <div class="product-info">
                                <h4>{{ product.name }}</h4>
                                <p class="price">${{ product.price }}</p>
                                <p class="category">{{ product.category }}</p>
                                <p class="description">{{ product.description }}</p>
                                <p class="created">Added: {{ formatDate(product.created_at) }}</p>
                            </div>
                            <div class="actions">
                                <button @click="startEdit(product)" class="edit-btn">✏️ Edit</button>
                                <button @click="deleteProduct(product.id)" class="delete-btn">🗑️ Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
                <div v-if="filteredProducts.length === 0" class="empty-state">
                    No products found
                </div>
            </div>
        </section>

        <!-- Users Tab (Keep existing) -->
        <section v-if="activeTab === 'users'" class="section">
            <h2 class="section-title">👥 User Management</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="user-card" v-for="user in users" :key="user.id">
                        <template v-if="editUser && editUser.id === user.id">
                            <div class="edit-form">
                                <input v-model="editUser.name" placeholder="Name" class="form-input">
                                <input v-model="editUser.email" type="email" placeholder="Email" class="form-input">
                                <select v-model="editUser.role" class="form-input">
                                    <option value="user">User</option>
                                    <option value="parent">Parent</option>
                                    <option value="child">Child</option>
                                    <option value="admin">Admin</option>
                                </select>
                                <input v-model="editUser.awards" type="number" placeholder="Awards" class="form-input">
                                <input v-model="editUser.password" type="password" placeholder="New Password (leave empty to keep current)" class="form-input">
                                <div class="edit-actions">
                                    <button @click="updateUser" class="save-btn">💾 Save</button>
                                    <button @click="cancelEditUser" class="cancel-btn">❌ Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="user-header">
                                <div class="user-avatar">{{ user.name.charAt(0) }}</div>
                                <div class="user-info">
                                    <h4>{{ user.name }}</h4>
                                    <p class="user-email">{{ user.email }}</p>
                                </div>
                            </div>
                            <div class="user-details">
                                <p><strong>Role:</strong> <span class="role-badge" :class="user.role">{{ user.role }}</span></p>
                                <p><strong>Awards:</strong> {{ user.awards || 0 }}</p>
                                <p><strong>Orders:</strong> {{ user.orders_count || 0 }}</p>
                                <p><strong>Joined:</strong> {{ formatDate(user.created_at) }}</p>
                                <p v-if="user.family"><strong>Family:</strong> {{ user.family.family_name }}</p>
                            </div>
                            <div class="actions">
                                <button @click="startEditUser(user)" class="edit-btn">✏️ Edit</button>
                                <button @click="deleteUser(user.id)" class="delete-btn">🗑️ Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add User Tab -->
        <section v-if="activeTab === 'addUser'" class="section">
            <h2 class="section-title">👤 Add New User</h2>
            <div class="scrollable-container">
                <form @submit.prevent="addUser" class="form">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input v-model="newUser.name" type="text" placeholder="John Doe" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address *</label>
                        <input v-model="newUser.email" type="email" placeholder="john@example.com" required>
                    </div>

                    <div class="form-group">
                        <label>Password *</label>
                        <input v-model="newUser.password" type="password" placeholder="Minimum 8 characters" required>
                    </div>

                    <div class="form-group">
                        <label>Role *</label>
                        <select v-model="newUser.role" required>
                            <option value="user">User</option>
                            <option value="parent">Parent</option>
                            <option value="child">Child</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Awards Points</label>
                        <input v-model="newUser.awards" type="number" placeholder="0" min="0">
                    </div>

                    <div class="form-group">
                        <label>Assign to Family (Optional)</label>
                        <select v-model="newUser.family_id">
                            <option value="">No Family</option>
                            <option v-for="family in families" :key="family.id" :value="family.id">
                                {{ family.family_name }} (Parent: {{ family.parent?.name || 'N/A' }})
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="submit-btn" :disabled="isAddingUser">
                        {{ isAddingUser ? 'Adding User...' : '➕ Add User' }}
                    </button>
                </form>
            </div>
        </section>

        <!-- Families Tab -->
        <section v-if="activeTab === 'families'" class="section">
            <h2 class="section-title">👨‍👩‍👧‍👦 Family Management</h2>
            <div class="scrollable-container">
                <div class="card-grid">
                    <div class="family-card" v-for="family in families" :key="family.id">
                        <template v-if="editFamily && editFamily.id === family.id">
                            <div class="edit-form">
                                <input v-model="editFamily.family_name" placeholder="Family Name" class="form-input">
                                <select v-model="editFamily.parent_id" class="form-input">
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }} ({{ user.email }})
                                    </option>
                                </select>
                                <div class="edit-actions">
                                    <button @click="updateFamily" class="save-btn">💾 Save</button>
                                    <button @click="cancelEditFamily" class="cancel-btn">❌ Cancel</button>
                                </div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="family-header">
                                <div class="family-icon">🏠</div>
                                <div class="family-info">
                                    <h4>{{ family.family_name }}</h4>
                                    <p class="invitation-code">Invite Code: <strong>{{ family.invitation_code }}</strong></p>
                                </div>
                            </div>
                            <div class="family-details">
                                <p><strong>Parent:</strong> {{ family.parent?.name || 'Not set' }}</p>
                                <p><strong>Members:</strong> {{ family.users_count || 0 }}</p>
                                <p><strong>Created:</strong> {{ formatDate(family.created_at) }}</p>
                            </div>
                            <div class="actions">
                                <button @click="startEditFamily(family)" class="edit-btn">✏️ Edit</button>
                                <button @click="deleteRecord('family', family.id)" class="delete-btn">🗑️ Delete</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Add Family Tab -->
        <section v-if="activeTab === 'addFamily'" class="section">
            <h2 class="section-title">🏠 Create New Family</h2>
            <div class="scrollable-container">
                <form @submit.prevent="addFamily" class="form">
                    <div class="form-group">
                        <label>Family Name *</label>
                        <input v-model="newFamily.family_name" type="text" placeholder="Smith Family" required>
                    </div>

                    <div class="form-group">
                        <label>Parent *</label>
                        <select v-model="newFamily.parent_id" required>
                            <option value="">Select Parent User</option>
                            <option v-for="user in users.filter(u => u.role !== 'child')" :key="user.id" :value="user.id">
                                {{ user.name }} ({{ user.email }}) - {{ user.role }}
                            </option>
                        </select>
                    </div>

                    <button type="submit" class="submit-btn" :disabled="isAddingFamily">
                        {{ isAddingFamily ? 'Creating Family...' : '🏠 Create Family' }}
                    </button>
                </form>
            </div>
        </section>

        <!-- Add Product Tab (Keep existing) -->
        <section v-if="activeTab === 'addProduct'" class="section">
            <h2 class="section-title">🛍️ Add New Product</h2>
            <div class="scrollable-container">
                <form @submit.prevent="addProduct" class="form">
                    <div class="form-group">
                        <label>Product Name *</label>
                        <input v-model="newProduct.name" type="text" placeholder="Product Name" required>
                    </div>

                    <div class="form-group">
                        <label>Price *</label>
                        <input v-model="newProduct.price" type="number" step="0.01" placeholder="Price" required>
                    </div>

                    <div class="form-group">
                        <label>Category *</label>
                        <input v-model="newProduct.category" type="text" placeholder="Category" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea v-model="newProduct.description" placeholder="Description"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Product Image *</label>
                        <input type="file" @change="handleFileUpload" accept="image/*" required>
                        <small>Supported formats: JPEG, PNG, JPG, GIF (Max: 2MB)</small>
                    </div>

                    <div v-if="imageFile" class="image-preview">
                        <p><strong>Image Preview:</strong></p>
                        <img :src="imagePreview" alt="Product Image Preview">
                        <p class="image-name">{{ imageFile.name }}</p>
                    </div>

                    <button type="submit" class="submit-btn" :disabled="isAdding">
                        {{ isAdding ? 'Adding Product...' : '➕ Add Product' }}
                    </button>
                </form>
            </div>
        </section>

        <!-- Other Tabs (Orders, Products, OrderDetails - Keep existing) -->
        <!-- ... existing tabs code ... -->

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
        orders: { type: Array, default: () => [] },
        products: { type: Array, default: () => [] },
        ordersj: { type: Array, default: () => [] },
        users: { type: Array, default: () => [] },
        families: { type: Array, default: () => [] },
    },
    data() {
        return {
            activeTab: 'analytics',
            newUser: {
                name: '',
                email: '',
                password: '',
                role: 'user',
                awards: 0,
                family_id: null
            },
            newFamily: {
                family_name: '',
                parent_id: null
            },
            newProduct: {
                name: '',
                price: '',
                category: '',
                description: ''
            },
            imageFile: null,
            imagePreview: null,
            editUser: null,
            editFamily: null,
            editProduct: null,
            editOrder: null,
            isAdding: false,
            isAddingUser: false,
            isAddingFamily: false,
            notification: {
                show: false,
                message: '',
                type: 'success'
            },
            analyticsData: {},
            ordersChart: null,
            revenueChart: null,
            statusChart: null,
            categoryChart: null,
            monthlyChart: null,
            userGrowthChart: null,
            exporting: false,
            exportStartDate: '',
            exportEndDate: ''
        };
    },
    mounted() {
        if (this.activeTab === 'analytics') {
            this.fetchAnalyticsData();
        }
        this.fetchOrderItems();
    },
    watch: {
        activeTab(newTab) {
            if (newTab === 'analytics') {
                this.fetchAnalyticsData();
            }
        }
    },
    computed: {
        filteredOrders() {
            let filtered = this.orders;

            if (this.orderSearch) {
                const search = this.orderSearch.toLowerCase();
                filtered = filtered.filter(order =>
                    (order.user && order.user.name.toLowerCase().includes(search)) ||
                    order.id.toString().includes(search) ||
                    order.total.toString().includes(search)
                );
            }

            if (this.orderStatusFilter) {
                filtered = filtered.filter(order =>
                    order.status === this.orderStatusFilter
                );
            }

            return filtered;
        },

        productCategories() {
            const categories = new Set();
            this.products.forEach(product => {
                if (product.category) {
                    categories.add(product.category);
                }
            });
            return Array.from(categories);
        },

        filteredProducts() {
            let filtered = this.products;

            if (this.productSearch) {
                const search = this.productSearch.toLowerCase();
                filtered = filtered.filter(product =>
                    product.name.toLowerCase().includes(search) ||
                    product.description.toLowerCase().includes(search) ||
                    product.category.toLowerCase().includes(search)
                );
            }

            if (this.productCategoryFilter) {
                filtered = filtered.filter(product =>
                    product.category === this.productCategoryFilter
                );
            }

            return filtered;
        }
    },
    methods: {
        async fetchOrderItems() {
            this.loadingOrderItems = true;
            try {
                const response = await axios.get('/admin/order-items');
                this.orderItems = response.data;
            } catch (error) {
                console.error('Error fetching order items:', error);
                this.showNotification('Failed to load order items', 'error');
            } finally {
                this.loadingOrderItems = false;
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

        resetProductForm() {
            this.newProduct = {
                name: '',
                price: '',
                category: '',
                description: ''
            };
            this.imageFile = null;
            this.imagePreview = null;
            // Clear file input
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) fileInput.value = '';
        },

        async addProduct() {
            if (!this.newProduct.name || !this.newProduct.price || !this.newProduct.category || !this.imageFile) {
                this.showNotification('Please fill all required fields and select an image', 'error');
                return;
            }

            this.isAdding = true;

            const formData = new FormData();
            formData.append('name', this.newProduct.name);
            formData.append('price', this.newProduct.price);
            formData.append('category', this.newProduct.category);
            formData.append('description', this.newProduct.description || '');
            formData.append('image', this.imageFile);

            try {
                await this.$inertia.post('/admin/products', formData, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Product added successfully!', 'success');
                        this.resetProductForm();
                        this.activeTab = 'products';
                    },
                    onError: (errors) => {
                        const errorMessage = Object.values(errors).flat().join(', ');
                        this.showNotification('Failed to add product: ' + errorMessage, 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error adding product: ' + error.message, 'error');
            } finally {
                this.isAdding = false;
            }
        },

        formatNumber(num) {
            if (!num) return '0';
            return parseFloat(num).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        },



        async fetchAnalyticsData() {
            try {
                const response = await axios.get('/admin/analytics');
                this.analyticsData = response.data;
                this.initCharts();
            } catch (error) {
                console.error('Error fetching analytics:', error);
            }
        },

        initCharts() {
            this.destroyCharts();
            this.createOrdersChart();
            this.createRevenueChart();
            this.createStatusChart();
            this.createCategoryChart();
            this.createMonthlyChart();
            this.createUserGrowthChart();
        },

        destroyCharts() {
            const charts = [
                this.ordersChart,
                this.revenueChart,
                this.statusChart,
                this.categoryChart,
                this.monthlyChart,
                this.userGrowthChart
            ];

            charts.forEach(chart => {
                if (chart) chart.destroy();
            });
        },

        createOrdersChart() {
            const ctx = this.$refs.ordersChart?.getContext('2d');
            if (!ctx) return;

            const labels = this.analyticsData.ordersPerDay?.map(item =>
                new Date(item.date).toLocaleDateString('en-US', { weekday: 'short' })
            ) || [];

            const data = this.analyticsData.ordersPerDay?.map(item => item.count) || [];

            this.ordersChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Orders',
                        data: data,
                        borderColor: '#420d65',
                        backgroundColor: 'rgba(66, 13, 101, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        },

        createRevenueChart() {
            const ctx = this.$refs.revenueChart?.getContext('2d');
            if (!ctx) return;

            const labels = this.analyticsData.revenuePerDay?.map(item =>
                new Date(item.date).toLocaleDateString('en-US', { weekday: 'short' })
            ) || [];

            const data = this.analyticsData.revenuePerDay?.map(item => item.revenue) || [];

            this.revenueChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue ($)',
                        data: data,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },

        createStatusChart() {
            const ctx = this.$refs.statusChart?.getContext('2d');
            if (!ctx) return;

            const labels = this.analyticsData.statusDistribution?.map(item => item.status) || [];
            const data = this.analyticsData.statusDistribution?.map(item => item.count) || [];

            this.statusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#FF6384', // pending
                            '#36A2EB', // processing
                            '#4BC0C0', // completed
                            '#FFCE56', // cancelled
                            '#9966FF'  // other
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        },

        createCategoryChart() {
            const ctx = this.$refs.categoryChart?.getContext('2d');
            if (!ctx) return;

            const labels = this.analyticsData.categoryPerformance?.map(item => item.category) || [];
            const data = this.analyticsData.categoryPerformance?.map(item => item.revenue) || [];

            this.categoryChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue by Category',
                        data: data,
                        backgroundColor: 'rgba(153, 102, 255, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    indexAxis: 'y',
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        },

        createMonthlyChart() {
            const ctx = this.$refs.monthlyChart?.getContext('2d');
            if (!ctx) return;

            const labels = this.analyticsData.monthlyTrends?.map(item => item.month) || [];
            const revenue = this.analyticsData.monthlyTrends?.map(item => item.revenue) || [];
            const orders = this.analyticsData.monthlyTrends?.map(item => item.order_count) || [];

            this.monthlyChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: revenue,
                            borderColor: '#420d65',
                            backgroundColor: 'rgba(66, 13, 101, 0.1)',
                            yAxisID: 'y'
                        },
                        {
                            label: 'Orders',
                            data: orders,
                            borderColor: '#4CAF50',
                            backgroundColor: 'rgba(76, 175, 80, 0.1)',
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            type: 'linear',
                            display: true,
                            position: 'left',
                            title: {
                                display: true,
                                text: 'Revenue ($)'
                            }
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            title: {
                                display: true,
                                text: 'Orders'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        },

        createUserGrowthChart() {
            const ctx = this.$refs.userGrowthChart?.getContext('2d');
            if (!ctx) return;

            const labels = this.analyticsData.userGrowth?.map(item =>
                new Date(item.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
            ) || [];

            const data = this.analyticsData.userGrowth?.map(item => item.new_users) || [];

            this.userGrowthChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'New Users',
                        data: data,
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        },

        async addUser() {
            this.isAddingUser = true;
            try {
                await this.$inertia.post('/admin/users', this.newUser, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('User added successfully!', 'success');
                        this.newUser = {
                            name: '',
                            email: '',
                            password: '',
                            role: 'user',
                            awards: 0,
                            family_id: null
                        };
                        this.activeTab = 'users';
                    },
                    onError: (errors) => {
                        const errorMessage = Object.values(errors).flat().join(', ');
                        this.showNotification('Failed to add user: ' + errorMessage, 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error adding user: ' + error.message, 'error');
            } finally {
                this.isAddingUser = false;
            }
        },

        async addFamily() {
            this.isAddingFamily = true;
            try {
                await this.$inertia.post('/admin/families', this.newFamily, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Family created successfully!', 'success');
                        this.newFamily = {family_name: '', parent_id: null};
                        this.activeTab = 'families';
                    },
                    onError: (errors) => {
                        const errorMessage = Object.values(errors).flat().join(', ');
                        this.showNotification('Failed to create family: ' + errorMessage, 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error creating family: ' + error.message, 'error');
            } finally {
                this.isAddingFamily = false;
            }
        },

        async updateOrder() {
            if (!this.editOrder) return;

            try {
                await this.$inertia.put(`/admin/orders/${this.editOrder.id}`, {
                    status: this.editOrder.status,
                    total: this.editOrder.total
                }, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Order updated successfully!', 'success');
                        this.editOrder = null;
                    },
                    onError: (errors) => {
                        const errorMessage = Object.values(errors).flat().join(', ');
                        this.showNotification('Failed to update order: ' + errorMessage, 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating order: ' + error.message, 'error');
            }
        },

        async updateProduct() {
            if (!this.editProduct) return;

            const formData = new FormData();
            formData.append('name', this.editProduct.name || '');
            formData.append('price', this.editProduct.price || 0);
            formData.append('category', this.editProduct.category || '');
            formData.append('description', this.editProduct.description || '');
            formData.append('_method', 'PUT');

            if (this.editImageFile) {
                formData.append('image', this.editImageFile);
            }

            try {
                await this.$inertia.post(`/admin/products/${this.editProduct.id}`, formData, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Product updated successfully!', 'success');
                        this.editProduct = null;
                        this.editImageFile = null;
                        this.editImagePreview = null;
                    },
                    onError: (errors) => {
                        const errorMessage = Object.values(errors).flat().join(', ');
                        this.showNotification('Failed to update product: ' + errorMessage, 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating product: ' + error.message, 'error');
            }
        },


        startEditUser(user) {
            this.editUser = { ...user, password: '' };
        },

        startEditFamily(family) {
            this.editFamily = { ...family };
        },

        cancelEditUser() {
            this.editUser = null;
        },

        cancelEditFamily() {
            this.editFamily = null;
        },

        async updateUser() {
            if (!this.editUser) return;

            try {
                await this.$inertia.put(`/admin/users/${this.editUser.id}`, {
                    name: this.editUser.name,
                    email: this.editUser.email,
                    role: this.editUser.role,
                    awards: this.editUser.awards,
                    password: this.editUser.password || undefined,
                    family_id: this.editUser.family_id
                }, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('User updated successfully!', 'success');
                        this.editUser = null;
                    },
                    onError: (errors) => {
                        const errorMessage = Object.values(errors).flat().join(', ');
                        this.showNotification('Failed to update user: ' + errorMessage, 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating user: ' + error.message, 'error');
            }
        },

        async updateFamily() {
            if (!this.editFamily) return;

            try {
                await this.$inertia.put(`/admin/families/${this.editFamily.id}`, {
                    family_name: this.editFamily.family_name,
                    parent_id: this.editFamily.parent_id
                }, {
                    onSuccess: () => {
                        this.showNotification('Family updated successfully!', 'success');
                        this.editFamily = null;
                    },
                    onError: (errors) => {
                        this.showNotification('Error updating family: ' + JSON.stringify(errors), 'error');
                    }
                });
            } catch (error) {
                this.showNotification('Error updating family: ' + error.message, 'error');
            }
        },

        async exportOrders() {
            this.exporting = true;
            try {
                const params = {};
                if (this.exportStartDate) params.start_date = this.exportStartDate;
                if (this.exportEndDate) params.end_date = this.exportEndDate;

                const response = await axios.get('/admin/export-orders', { params });

                if (response.data.success) {
                    // Convert to CSV
                    const csv = this.convertToCSV(response.data.data);
                    this.downloadCSV(csv, `orders-export-${new Date().toISOString().split('T')[0]}.csv`);
                    this.showNotification(`Exported ${response.data.count} orders successfully!`, 'success');
                }
            } catch (error) {
                this.showNotification('Error exporting orders: ' + error.message, 'error');
            } finally {
                this.exporting = false;
            }
        },

        convertToCSV(data) {
            if (!data.length) return '';

            const headers = Object.keys(data[0]);
            const rows = data.map(row =>
                headers.map(header => JSON.stringify(row[header] || '')).join(',')
            );

            return [headers.join(','), ...rows].join('\n');
        },

        downloadCSV(csv, filename) {
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
        },

        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        },

        showNotification(message, type = 'success') {
            this.notification = { show: true, message, type };
            setTimeout(() => {
                this.notification.show = false;
            }, 3000);
        },

        async deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                await this.$inertia.delete(`/admin/users/${id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('User deleted successfully!', 'success');
                    },
                    onError: () => {
                        this.showNotification('Failed to delete user', 'error');
                    }
                });
            }
        },

        async deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                await this.$inertia.delete(`/admin/products/${id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Product deleted successfully!', 'success');
                    },
                    onError: () => {
                        this.showNotification('Failed to delete product', 'error');
                    }
                });
            }
        },

        async deleteOrder(id) {
            if (confirm('Are you sure you want to delete this order?')) {
                await this.$inertia.delete(`/admin/orders/${id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Order deleted successfully!', 'success');
                    },
                    onError: () => {
                        this.showNotification('Failed to delete order', 'error');
                    }
                });
            }
        },

        async deleteOrderItem(id) {
            if (confirm('Are you sure you want to delete this order item?')) {
                await this.$inertia.delete(`/admin/orderitems/${id}`, {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.showNotification('Order item deleted successfully!', 'success');
                    },
                    onError: () => {
                        this.showNotification('Failed to delete order item', 'error');
                    }
                });
            }
        },

        startEditOrder(order) {
            this.editOrder = { ...order };
        },

        startEdit(product) {
            this.editProduct = { ...product };
            this.editImageFile = null;
            this.editImagePreview = null;
        },


        cancelEditOrder() {
            this.editOrder = null;
        },

        cancelEdit() {
            this.editProduct = null;
            this.editImageFile = null;
            this.editImagePreview = null;
        },

    }
};
</script>

<style scoped>
/* Enhanced Admin Styles */
.admin-dashboard {
    padding: 20px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    max-width: 1600px;
    margin: 0 auto;
}

.admin-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 30px;
}

.title {
    color: #420d65;
    margin: 0;
    font-size: 32px;
}

.back-button {
    background: #420d65;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.3s;
}

.back-button:hover {
    background: #330a4f;
}

.navigation-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 30px;
    flex-wrap: wrap;
    background: #f8f9fa;
    padding: 15px;
    border-radius: 12px;
}

.tab-button {
    padding: 12px 24px;
    border: 2px solid #e0e0e0;
    background: white;
    color: #666;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-button:hover {
    border-color: #420d65;
    color: #420d65;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(66, 13, 101, 0.1);
}

.tab-button.active {
    background: #420d65;
    color: white;
    border-color: #420d65;
}

.section {
    background: white;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.section-title {
    color: #333;
    margin: 0 0 24px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
    font-size: 24px;
}

/* Summary Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 24px;
    color: white;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: transform 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-4px);
}

.summary-card:nth-child(2) {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.summary-card:nth-child(3) {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.summary-card:nth-child(4) {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.card-icon {
    font-size: 32px;
    opacity: 0.9;
}

.card-content h3 {
    margin: 0 0 8px 0;
    font-size: 14px;
    font-weight: 500;
    opacity: 0.9;
}

.card-content .stat {
    margin: 0 0 4px 0;
    font-size: 28px;
    font-weight: 700;
}

.card-content small {
    font-size: 12px;
    opacity: 0.8;
}

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 30px;
}

.chart-container {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.chart-container h3 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 16px;
    font-weight: 600;
}

/* Tables Grid */
.tables-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-bottom: 30px;
}

.table-container {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    padding: 20px;
}

.table-container h3 {
    margin: 0 0 16px 0;
    color: #333;
    font-size: 16px;
    font-weight: 600;
}

.scrollable-table {
    max-height: 300px;
    overflow-y: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th {
    background: #f8f9fa;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    color: #555;
    border-bottom: 2px solid #eaeaea;
    position: sticky;
    top: 0;
}

.data-table td {
    padding: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.data-table tr:hover {
    background: #f9f9f9;
}

/* Cards */
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.user-card, .family-card {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.user-card:hover, .family-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.user-header, .family-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.user-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    font-weight: 600;
}

.family-icon {
    font-size: 32px;
    color: #420d65;
}

.user-info h4, .family-info h4 {
    margin: 0 0 4px 0;
    color: #333;
}

.user-email {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.invitation-code {
    margin: 4px 0 0 0;
    color: #666;
    font-size: 13px;
}

.user-details, .family-details {
    margin: 16px 0;
}

.user-details p, .family-details p {
    margin: 8px 0;
    color: #555;
    font-size: 14px;
}

.role-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.role-badge.user { background: #6c757d; }
.role-badge.parent { background: #198754; }
.role-badge.child { background: #0d6efd; }
.role-badge.admin { background: #dc3545; }

.category-badge {
    display: inline-block;
    padding: 2px 8px;
    background: #e9ecef;
    border-radius: 12px;
    font-size: 12px;
    color: #495057;
}

/* Export Section */
.export-section {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 12px;
    padding: 20px;
}

.export-section h3 {
    margin: 0 0 16px 0;
    color: #333;
}

.export-controls {
    display: flex;
    align-items: center;
    gap: 16px;
}

.date-range {
    display: flex;
    align-items: center;
    gap: 8px;
}

.date-range input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.date-range span {
    color: #666;
}

.export-btn {
    background: #420d65;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 600;
    transition: background 0.3s;
}

.export-btn:hover:not(:disabled) {
    background: #330a4f;
}

.export-btn:disabled {
    background: #cccccc;
    cursor: not-allowed;
}

/* Forms */
.form {
    max-width: 500px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    transition: border 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #420d65;
    box-shadow: 0 0 0 3px rgba(66, 13, 101, 0.1);
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.submit-btn {
    background: #420d65;
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.submit-btn:hover:not(:disabled) {
    background: #330a4f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(66, 13, 101, 0.2);
}

.submit-btn:disabled {
    background: #cccccc;
    cursor: not-allowed;
    transform: none;
}

/* Notification */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 16px 24px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1000;
    animation: slideIn 0.3s ease;
}

.notification.success {
    background: #198754;
}

.notification.error {
    background: #dc3545;
}

/* Table Controls */
.table-controls {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.search-input, .filter-select {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.search-input {
    flex: 1;
}

.filter-select {
    min-width: 150px;
}

/* Scrollable Container */
.scrollable-container {
    max-height: 600px;
    overflow-y: auto;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

/* Card Grid */
.card-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

/* Order Card */
.order-card, .order-detail-card, .order-item-card {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s ease;
}

.order-card:hover, .order-detail-card:hover, .order-item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.order-id {
    font-weight: bold;
    color: #420d65;
    font-size: 18px;
}

.order-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    text-transform: uppercase;
}

.order-status.pending {
    background: #FFA726;
    color: white;
}

.order-status.processing {
    background: #29B6F6;
    color: white;
}

.order-status.completed {
    background: #66BB6A;
    color: white;
}

.order-status.cancelled {
    background: #EF5350;
    color: white;
}

.order-details p {
    margin: 8px 0;
    color: #555;
    font-size: 14px;
}

.order-details strong {
    color: #333;
    margin-right: 5px;
}

/* Product Card */
.product-card {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.product-image-container {
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 8px;
    margin-bottom: 15px;
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #666;
}

.product-info h4 {
    margin: 0 0 10px 0;
    color: #333;
    font-size: 18px;
}

.price {
    font-size: 20px;
    font-weight: bold;
    color: #420d65;
    margin: 10px 0;
}

.category {
    display: inline-block;
    background: #e9ecef;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    color: #495057;
    margin: 5px 0;
}

.description {
    color: #666;
    font-size: 14px;
    margin: 10px 0;
    line-height: 1.4;
}

.created {
    color: #999;
    font-size: 12px;
    margin: 10px 0 0 0;
}

/* Edit Forms */
.edit-form {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-input {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.form-input:focus {
    outline: none;
    border-color: #420d65;
}

.edit-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.save-btn, .cancel-btn, .edit-btn, .delete-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.save-btn {
    background: #4CAF50;
    color: white;
}

.save-btn:hover {
    background: #45a049;
}

.cancel-btn {
    background: #757575;
    color: white;
}

.cancel-btn:hover {
    background: #666;
}

.edit-btn {
    background: #2196F3;
    color: white;
}

.edit-btn:hover {
    background: #1976D2;
}

.delete-btn {
    background: #e63946;
    color: white;
}

.delete-btn:hover {
    background: #d32f2f;
}

.actions {
    display: flex;
    gap: 10px;
    margin-top: 15px;
}

/* Image Previews */
.current-image, .new-image {
    margin: 10px 0;
}

.current-image img, .new-image img {
    max-width: 100px;
    max-height: 100px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 5px;
}

.file-upload {
    margin: 10px 0;
}

.file-upload label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #333;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px;
    color: #666;
    font-style: italic;
    background: white;
    border: 1px dashed #ddd;
    border-radius: 8px;
}

/* Loading */
.loading {
    text-align: center;
    padding: 40px;
    color: #666;
    font-size: 16px;
}

/* Family Card specific styles */
.family-card {
    background: white;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 20px;
    transition: all 0.3s ease;
}

.family-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.family-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.family-icon {
    font-size: 24px;
    color: #420d65;
}

.family-info h4 {
    margin: 0 0 5px 0;
    color: #333;
}

.invitation-code {
    margin: 0;
    color: #666;
    font-size: 13px;
}

.family-details p {
    margin: 8px 0;
    color: #555;
    font-size: 14px;
}

.family-details strong {
    color: #333;
    margin-right: 5px;
}

/* Form Styles */
.form {
    background: white;
    padding: 30px;
    border-radius: 8px;
    border: 1px solid #eaeaea;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #420d65;
    box-shadow: 0 0 0 3px rgba(66, 13, 101, 0.1);
}

.form-group textarea {
    min-height: 100px;
    resize: vertical;
}

.submit-btn {
    background: #420d65;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s;
}

.submit-btn:hover:not(:disabled) {
    background: #330a4f;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(66, 13, 101, 0.2);
}

.submit-btn:disabled {
    background: #cccccc;
    cursor: not-allowed;
    transform: none;
}

.image-preview {
    margin: 15px 0;
}

.image-preview img {
    max-width: 200px;
    max-height: 200px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 10px;
}

.image-name {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 12px;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .charts-grid,
    .tables-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .admin-dashboard {
        padding: 10px;
    }

    .navigation-tabs {
        flex-direction: column;
    }

    .tab-button {
        width: 100%;
        justify-content: center;
    }

    .summary-cards {
        grid-template-columns: 1fr;
    }

    .card-grid {
        grid-template-columns: 1fr;
    }

    .export-controls {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
