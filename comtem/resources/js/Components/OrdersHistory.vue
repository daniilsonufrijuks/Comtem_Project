<script setup>
import { Link } from "@inertiajs/vue3";
import { ref, onMounted, computed } from "vue";
import { useTranslation } from '../Composables/useTranslation';

const orders = ref([]);
const loading = ref(true);
const error = ref(null);
const selectedDate = ref("");
const { t } = useTranslation();

const fetchOrders = async () => {
    try {
        const response = await fetch("/orders/user", {
            credentials: "include",
            headers: { Accept: "application/json" },
        });

        if (!response.ok) {
            if (response.status === 401) throw new Error("Please log in to view your orders.");
            throw new Error("Failed to fetch orders.");
        }

        const data = await response.json();
        orders.value = Array.isArray(data) ? data : [];
    } catch (err) {
        error.value = err.message;
    } finally {
        loading.value = false;
    }
};

onMounted(fetchOrders);

const formatDate = (dateStr) => {
    return new Date(dateStr).toLocaleString("en-US", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

const filteredOrders = computed(() => {
    if (!selectedDate.value) return orders.value;

    return orders.value.filter((order) => {
        const orderDate = new Date(order.ordered_at).toISOString().split("T")[0];
        return orderDate === selectedDate.value;
    });
});

const clearFilter = () => {
    selectedDate.value = "";
};
</script>

<template>
    <div class="order-history">
        <h2 class="title">{{ t('orders_title') }}</h2>

        <div class="filter-bar">
            <label for="dateFilter">{{ t('orders_filter_label') }}:</label>
            <input id="dateFilter" type="date" v-model="selectedDate" class="date-input" />
            <button v-if="selectedDate" @click="clearFilter" class="clear-btn">{{ t('orders_clear') }}</button>
        </div>

        <div v-if="loading" class="loading">{{ t('orders_loading') }}</div>
        <div v-else-if="error" class="error"><p>{{ error }}</p></div>
        <div v-else-if="filteredOrders.length === 0" class="empty">
            <p>{{ selectedDate ? t('orders_empty_date') : t('orders_empty') }}</p>
        </div>

        <div v-else>
            <table class="order-table">
                <thead>
                <tr>
                    <th>#</th>
                    <th>{{ t('orders_col_date') }}</th>
                    <th>{{ t('orders_col_name') }}</th>
                    <th>{{ t('orders_col_total') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(order, index) in filteredOrders" :key="order.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ formatDate(order.created_at) }}</td>
                    <td>{{ order.item_name }}</td>
                    <td>{{ Number(order.order_total).toFixed(2) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>


<style scoped>
.order-history {
    width: 100%;
    max-width: 950px;
    margin: 2rem auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 1.5rem;
}


.title {
    text-align: center;
    color: #333;
    margin-bottom: 1.5rem;
}


.filter-bar {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.date-input {
    padding: 0.4rem 0.6rem;
    border: 1px solid #ccc;
    border-radius: 6px;
}

.clear-btn {
    background: #dc3545;
    color: white;
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s;
}

.clear-btn:hover {
    background: #c82333;
}

.order-table {
    width: 100%;
    border-collapse: collapse;
}

.order-table th,
.order-table td {
    border: 1px solid #eee;
    padding: 0.75rem 1rem;
    text-align: left;
}

.order-table th {
    background-color: #f8f9fa;
    color: #333;
}

.view-btn {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
}

.view-btn:hover {
    text-decoration: underline;
}

.loading,
.empty,
.error {
    text-align: center;
    padding: 1.5rem;
    color: #555;
}

.error p {
    color: #d9534f;
}

@media (max-width: 600px) {
    .order-table thead {
        display: none;
    }

    .order-table,
    .order-table tbody,
    .order-table tr,
    .order-table td {
        display: block;
        width: 100%;
    }

    .order-table tr {
        margin-bottom: 1rem;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 0.5rem;
    }

    .order-table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
    }

    .order-table td::before {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-weight: bold;
        text-align: left;
    }

    .order-table td:nth-child(1)::before { content: "#"; }
    .order-table td:nth-child(2)::before { content: "Date"; }
    .order-table td:nth-child(3)::before { content: "Name"; }
    .order-table td:nth-child(4)::before { content: "Total"; }
}
</style>
