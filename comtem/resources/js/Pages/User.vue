<template>
    <Navbar :routes="routes"/>
    <Search />
    <div class="main-container">
        <div class="col-xl-6 col-md-12">
            <div class="card user-card-full">
                <div class="row m-l-0 m-r-0">
                    <div class="col-sm-4 bg-c-lite-green user-profile">
                        <div class="card-block text-center text-white">
                            <div class="m-b-25">
                                <img src="https://img.icons8.com/bubbles/100/000000/user.png" class="img-radius" alt="User-Profile-Image">
                            </div>
                            <h6 class="f-w-600">{{ user.name }}</h6>
                            <i class="fas fa-edit edit-icon m-t-10 f-16" @click="toggleEditMode"></i>
                            <p class="m-t-10">{{ isEditing ? 'Editing Mode' : 'Click pencil to edit' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="card-block">
                            <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>

                            <!-- Success/Error Messages -->
                            <div v-if="successMessage" class="alert alert-success">
                                <i class="fas fa-check-circle"></i> {{ successMessage }}
                            </div>
                            <div v-if="errorMessage" class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i> {{ errorMessage }}
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <p class="m-b-10 f-w-600">Email</p>
                                    <div v-if="!isEditing">
                                        <h6 class="text-muted f-w-400">{{ user.email }}</h6>
                                    </div>
                                    <div v-else>
                                        <input type="email" v-model="editForm.email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <p class="m-b-10 f-w-600">Name</p>
                                    <div v-if="!isEditing">
                                        <h6 class="text-muted f-w-400">{{ user.name }}</h6>
                                    </div>
                                    <div v-else>
                                        <input type="text" v-model="editForm.name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <p class="m-b-10 f-w-600">Address</p>
                                    <div v-if="!isEditing">
                                        <h6 class="text-muted f-w-400">{{ user.address }}</h6>
                                    </div>
                                    <div v-else>
                                        <input type="text" v-model="editForm.address" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Edit buttons -->
                            <div class="row m-t-20" v-if="isEditing">
                                <div class="col-sm-12">
                                    <button class="btn btn-primary btn-sm" @click="updateProfile" :disabled="loading">
                                        <span v-if="loading">Updating...</span>
                                        <span v-else>Save Changes</span>
                                    </button>
                                    <button class="btn btn-secondary btn-sm m-l-10" @click="cancelEdit">Cancel</button>
                                </div>
                            </div>
                            <!-- Delete Account Section -->
                            <div class="row m-t-30">
                                <div class="col-sm-12">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600 text-danger">Danger Zone</h6>
                                    <p class="text-muted m-b-15">Once you delete your account, there is no going back. Please be certain.</p>
                                    <button class="btn btn-danger btn-sm" @click="confirmDelete" :disabled="deleting">
                                        <span v-if="deleting">Deleting...</span>
                                        <span v-else>Delete Account</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <div v-if="showDeleteModal" class="modal-backdrop">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Confirm Account Deletion</h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
                                        <div class="form-group">
                                            <label for="password">Enter your password to confirm:</label>
                                            <input type="password" v-model="deletePassword" class="form-control" id="password" placeholder="Your password">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" @click="cancelDelete">Cancel</button>
                                        <button type="button" class="btn btn-danger" @click="deleteAccount" :disabled="!deletePassword || deleting">
                                            <span v-if="deleting">Deleting...</span>
                                            <span v-else>Delete Account</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Family Payment Section -->
        <div v-if="userFamilyData" class="col-xl-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Family Payment Management</h5>
                </div>
                <div class="card-body">
                    <!-- Family Info -->
                    <div class="family-info mb-4">
                        <h6>{{ userFamilyData.family_name }}</h6>
                        <p>Invitation Code: <strong>{{ userFamilyData.invitation_code }}</strong></p>
                        <p>Your Role: <span class="badge" :class="user.role === 'parent' ? 'bg-primary' : 'bg-secondary'">
                            {{ user.role }}
                        </span></p>
                    </div>

                    <!-- Payment Methods Section -->
                    <div class="payment-methods-section">
                        <h6>Family Payment Methods</h6>

                        <!-- Add Card (Parent Only) -->
                        <div v-if="isFamilyAdmin" class="add-card-section mb-3">
                            <div id="card-element" class="card-input mb-2"></div>
                            <button @click="addFamilyCard" :disabled="processingCard" class="btn btn-sm btn-primary">
                                {{ processingCard ? 'Adding...' : 'Add Family Card' }}
                            </button>
                        </div>

                        <!-- Cards List -->
                        <div v-if="paymentMethods.length > 0" class="cards-list">
                            <div v-for="method in paymentMethods" :key="method.id" class="card-item mb-2 p-2 border rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i :class="getCardIcon(method.card_brand)" class="me-2"></i>
                                        {{ method.card_brand }} ****{{ method.card_last_four }}
                                        <span v-if="method.is_default" class="badge bg-success ms-2">Default</span>
                                    </div>
                                    <div v-if="isFamilyAdmin">
                                        <button
                                            v-if="!method.is_default"
                                            @click="setDefaultCard(method.stripe_payment_method_id)"
                                            class="btn btn-sm btn-outline-primary me-1"
                                        >
                                            Set Default
                                        </button>
                                        <button
                                            @click="removeCard(method.stripe_payment_method_id)"
                                            class="btn btn-sm btn-outline-danger"
                                        >
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="alert alert-info">
                            No payment methods added yet.
                        </div>

                        <!-- Card Usage Permission -->
                        <div v-if="isFamilyAdmin" class="permission-section mt-4">
                            <h6>Manage Family Members</h6>
                            <div v-if="familyMembers.length > 0" class="members-list">
                                <div v-for="member in familyMembers" :key="member.id" class="member-item d-flex justify-content-between align-items-center p-2 border-bottom">
                                    <div>
                                        <span>{{ member.name }}</span>
                                        <small class="text-muted ms-2">{{ member.email }}</small>
                                    </div>
                                    <div>
                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                :checked="member.can_use_family_card"
                                                @change="togglePermission(member.id)"
                                                :disabled="member.id === user.id"
                                            >
                                            <label class="form-check-label">
                                                Can use family card
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="alert alert-info">
                                No other family members.
                            </div>
                        </div>

                        <!-- Transaction History -->
                        <div class="transactions-section mt-4">
                            <h6>Recent Transactions</h6>
                            <div v-if="transactions.length > 0">
                                <div v-for="transaction in transactions" :key="transaction.id" class="transaction-item p-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <strong>${{ transaction.amount }}</strong>
                                            <br>
                                            <small>{{ transaction.description }}</small>
                                            <br>
                                            <small class="text-muted">By: {{ transaction.user.name }}</small>
                                        </div>
                                        <div>
                                            <span class="badge" :class="{
                                                'bg-success': transaction.status === 'succeeded',
                                                'bg-warning': transaction.status === 'pending',
                                                'bg-danger': transaction.status === 'failed'
                                            }">
                                                {{ transaction.status }}
                                            </span>
                                            <br>
                                            <small class="text-muted">{{ formatDate(transaction.created_at) }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="alert alert-info">
                                No transactions yet.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Visitit />
        <Roulette />
        <OrdersHistory />
        <div class="products">
            <ProductCardDB v-for="product in products" :key="product.id" :product="product" />
        </div>
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
import ProductCardDB from "@/Components/ProductCardDB.vue";
import Roulette from "@/Components/Roulette.vue";
import OrdersHistory from "@/Components/OrdersHistory.vue";
import { loadStripe } from '@stripe/stripe-js';

export default {
    name: 'Home',
    components: {
        OrdersHistory,
        Roulette,
        ProductCardDB,
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
        user: Object
    },
    data() {
        return {
            localUser: { ...this.user },
            products: [],
            filters: {
                price_min: 0,
                price_max: 100000,
            },
            isEditing: false,
            editForm: {
                name: '',
                email: '',
                address: '',
            },
            successMessage: '',
            errorMessage: '',
            loading: false,
            deleting: false,
            showDeleteModal: false,
            deletePassword: '',
            paymentMethods: [],
            familyMembers: [],
            transactions: [],
            processingCard: false,
            stripe: null,
            cardElement: null,
            userFamilyData: null,
        };
    },
    computed: {
        isFamilyAdmin() {
            return this.user.role === 'parent' ||
                this.user.is_family_admin === true ||
                (this.userFamilyData && this.userFamilyData.is_family_admin === true);
        }
    },
    async mounted() {
        this.fetchProducts();
        this.editForm.name = this.user?.name || '';
        this.editForm.email = this.user?.email || '';
        this.editForm.address = this.user?.address || '';

        // Load user's family data
        await this.loadUserFamily();

        // If user has family, load family payment data
        if (this.userFamilyData) {
            await this.loadFamilyPaymentData();

            // Initialize Stripe for adding cards if user is family admin
            if (this.isFamilyAdmin) {
                await this.initializeStripe();
            }
        }
    },
    methods: {
        fetchProducts() {
            const params = new URLSearchParams({
                price_min: this.filters.price_min ?? 0,
                price_max: this.filters.price_max ?? 100000,
            }).toString();

            fetch(`/products/laptops?${params}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Fetched products:", data);
                    this.products = data;
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },

        // Profile editing methods
        toggleEditMode() {
            this.isEditing = !this.isEditing;
            if (this.isEditing) {
                // Initialize edit form with current values
                this.editForm.name = this.user.name;
                this.editForm.email = this.user.email;
                this.editForm.address = this.user.address || '';
            }
        },

        async updateProfile() {
            this.loading = true;
            this.successMessage = '';
            this.errorMessage = '';

            try {
                const response = await axios.put('/user/profile', this.editForm);
                this.successMessage = 'Profile updated successfully!';
                this.user = { ...this.user, ...response.data.user };
                this.isEditing = false;
            } catch (error) {
                this.errorMessage = error.response?.data?.message || 'Failed to update profile';
            } finally {
                this.loading = false;
            }
        },

        cancelEdit() {
            this.isEditing = false;
            this.editForm.name = this.user.name;
            this.editForm.email = this.user.email;
            this.editForm.address = this.user.address || '';
        },

        confirmDelete() {
            this.showDeleteModal = true;
        },

        cancelDelete() {
            this.showDeleteModal = false;
            this.deletePassword = '';
        },

        async deleteAccount() {
            this.deleting = true;
            try {
                await axios.delete('/user/account', {
                    data: { password: this.deletePassword }
                });
                window.location.href = '/';
            } catch (error) {
                alert(error.response?.data?.message || 'Failed to delete account');
                this.deleting = false;
                this.showDeleteModal = false;
                this.deletePassword = '';
            }
        },

        async loadUserFamily() {
            try {
                const response = await axios.get('/user/family');
                console.log('Family API response:', response.data);

                if (response.data && response.data.family) {
                    this.userFamilyData = response.data.family;
                    console.log('Family loaded:', this.userFamilyData);
                } else {
                    console.log('No family found for user');
                }
            } catch (error) {
                console.error('Error loading user family:', error);

                // Fallback to user.family_id if available
                if (this.user.family_id) {
                    try {
                        const familyResponse = await axios.get(`/api/families/${this.user.family_id}`);
                        this.userFamilyData = familyResponse.data;
                    } catch (familyError) {
                        console.error('Error fetching family details:', familyError);
                    }
                }
            }
        },

        async initializeStripe() {
            try {
                const stripeKey = import.meta.env.VITE_STRIPE_KEY;
                if (!stripeKey) {
                    console.error('Stripe key not found');
                    return;
                }

                this.stripe = await loadStripe(stripeKey);

                if (this.stripe) {
                    const elements = this.stripe.elements();
                    this.cardElement = elements.create('card', {
                        style: {
                            base: {
                                fontSize: '16px',
                                color: '#32325d',
                            },
                        },
                    });

                    // Check if element exists before mounting
                    const cardElementDiv = document.getElementById('card-element');
                    if (cardElementDiv) {
                        this.cardElement.mount('#card-element');
                    }
                }
            } catch (error) {
                console.error('Error initializing Stripe:', error);
            }
        },

        async loadFamilyPaymentData() {
            try {
                // Load payment methods
                const paymentMethodsResponse = await axios.get('/family/payment-methods');
                this.paymentMethods = paymentMethodsResponse.data;

                // Load family members
                const membersResponse = await axios.get('/family/members');
                this.familyMembers = membersResponse.data;

                // Load transactions
                const transactionsResponse = await axios.get('/family/transactions');
                this.transactions = transactionsResponse.data.data || transactionsResponse.data;
            } catch (error) {
                console.error('Error loading family payment data:', error);
            }
        },

        async addFamilyCard() {
            if (!this.stripe || !this.cardElement) {
                alert('Stripe not initialized');
                return;
            }

            this.processingCard = true;

            try {
                const {paymentMethod, error} = await this.stripe.createPaymentMethod({
                    type: 'card',
                    card: this.cardElement,
                });

                if (error) {
                    throw new Error(error.message);
                }

                const response = await axios.post('/family/payment-method', {
                    payment_method_id: paymentMethod.id,
                });

                if (response.data.success) {
                    alert('Family card added successfully!');
                    // Clear the card element
                    this.cardElement.clear();
                    await this.loadFamilyPaymentData();
                }
            } catch (error) {
                alert(error.response?.data?.error || 'Failed to add card');
            } finally {
                this.processingCard = false;
            }
        },

        async setDefaultCard(paymentMethodId) {
            try {
                await axios.post(`/family/payment-methods/${paymentMethodId}/default`);
                await this.loadFamilyPaymentData();
            } catch (error) {
                alert('Failed to set default card');
            }
        },

        async removeCard(paymentMethodId) {
            if (!confirm('Are you sure you want to remove this card?')) return;

            try {
                await axios.delete(`/family/payment-methods/${paymentMethodId}`);
                await this.loadFamilyPaymentData();
            } catch (error) {
                alert('Failed to remove card');
            }
        },

        async togglePermission(memberId) {
            try {
                const response = await axios.post(`/family/child/${memberId}/toggle-card-permission`);

                // Update local state
                const memberIndex = this.familyMembers.findIndex(m => m.id === memberId);
                if (memberIndex !== -1) {
                    this.familyMembers[memberIndex].can_use_family_card = response.data.can_use_family_card;
                }
            } catch (error) {
                alert('Failed to update permission');
            }
        },

        getCardIcon(brand) {
            const icons = {
                'visa': 'fab fa-cc-visa',
                'mastercard': 'fab fa-cc-mastercard',
                'amex': 'fab fa-cc-amex',
                'discover': 'fab fa-cc-discover',
                'jcb': 'fab fa-cc-jcb',
                'diners': 'fab fa-cc-diners-club',
            };
            return icons[brand?.toLowerCase()] || 'far fa-credit-card';
        },

        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }
    }
}
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 70px;
}

.user-card-full {
    overflow: hidden;
}

.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
    box-shadow: 0 1px 20px 0 rgba(69, 90, 100, 0.08);
    border: none;
    margin-bottom: 30px;
}

.m-r-0 {
    margin-right: 0px;
}

.m-l-0 {
    margin-left: 0px;
}

.user-card-full .user-profile {
    border-radius: 5px 0 0 5px;
}

.bg-c-lite-green {
    background: #A34FAFFF;
}

.user-profile {
    padding: 20px 0;
}

.card-block {
    padding: 1.25rem;
}

.m-b-25 {
    margin-bottom: 25px;
    justify-self: center;
}

.img-radius {
    border-radius: 5px;
}

h6 {
    font-size: 14px;
}

.card .card-block p {
    line-height: 25px;
}

@media only screen and (min-width: 1400px) {
    p {
        font-size: 14px;
    }
}

.card-block {
    padding: 1.25rem;
}

.b-b-default {
    border-bottom: 1px solid #e0e0e0;
}

.m-b-20 {
    margin-bottom: 20px;
}

.p-b-5 {
    padding-bottom: 5px !important;
}

.card .card-block p {
    line-height: 25px;
}

.m-b-10 {
    margin-bottom: 10px;
}

.text-muted {
    color: #919aa3 !important;
}

.b-b-default {
    border-bottom: 1px solid #e0e0e0;
}

.f-w-600 {
    font-weight: 600;
}

.m-b-20 {
    margin-bottom: 20px;
}

.m-t-40 {
    margin-top: 20px;
}

.p-b-5 {
    padding-bottom: 5px !important;
}

.m-b-10 {
    margin-bottom: 10px;
}

.m-t-40 {
    margin-top: 20px;
}

.user-card-full .social-link li {
    display: inline-block;
}

.user-card-full .social-link li a {
    font-size: 20px;
    margin: 0 10px 0 0;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.form-control {
    display: block;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    color: #495057;
    background-color: #fff;
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: 0.25rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    color: #fff;
    background-color: #0069d9;
    border-color: #0062cc;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
}

.m-l-10 {
    margin-left: 10px;
}

.m-t-20 {
    margin-top: 20px;
}

.alert {
    position: relative;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.25rem;
}

.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}

.alert-error {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}

.edit-icon {
    cursor: pointer;
    transition: color 0.3s;
}

.edit-icon:hover {
    color: #ffc107;
}

.card-input {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    background: white;
}

.badge {
    font-size: 0.75em;
}

/* Modal styles */
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.modal-content {
    background-color: white;
    border-radius: 0.3rem;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
}

.modal-header {
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.modal-body {
    padding: 1rem;
}

.modal-footer {
    padding: 1rem;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
}

/* Family payment section styles */
.family-info {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}

.cards-list {
    max-height: 200px;
    overflow-y: auto;
}

.member-item {
    transition: background-color 0.2s;
}

.member-item:hover {
    background-color: #f8f9fa;
}

.transaction-item {
    transition: background-color 0.2s;
}

.transaction-item:hover {
    background-color: #f8f9fa;
}
</style>
