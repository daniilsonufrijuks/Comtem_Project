<template>
    <div class="bids-history-container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">My Bids History</h5>
            </div>
            <div class="card-body">
                <div v-if="loading" class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div v-else-if="error" class="alert alert-danger">
                    {{ error }}
                </div>

                <div v-else-if="bids.length === 0" class="alert alert-info">
                    You haven't placed any bids yet.
                </div>

                <div v-else class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Item</th>
                            <th>Bid Amount</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="bid in bids" :key="bid.id">
                            <td>
                                <div class="d-flex align-items-center">
<!--                                    <img v-if="bid.product.img" :src="'/storage/' + bid.product.img" alt="Item" class="bid-item-img me-2">-->
                                    <div>
                                        <strong>{{ bid.product.name }}</strong>
                                        <br>
                                        <small class="text-muted">Starting bid: ${{ bid.product.starting_bid }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-bold text-success">${{ bid.bid_amount }}</td>
                            <td>{{ formatDateTime(bid.created_at) }}</td>
                            <td>
                                    <span class="badge" :class="getBidStatusClass(bid)">
                                        {{ getBidStatus(bid) }}
                                    </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'BidsHistory',
    data() {
        return {
            bids: [],
            loading: true,
            error: null,
        };
    },
    mounted() {
        this.fetchBids();
    },
    methods: {
        async fetchBids() {
            this.loading = true;
            this.error = null;
            try {
                const response = await axios.get('/user/bids');
                this.bids = response.data.data || response.data;
            } catch (err) {
                this.error = err.response?.data?.message || 'Failed to load bids history.';
                console.error('Bids fetch error:', err);
            } finally {
                this.loading = false;
            }
        },
        formatDateTime(dateString) {
            if (!dateString) return '—';
            const date = new Date(dateString);
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
            });
        },
        getBidStatus(bid) {
            if (bid.is_winning === true) return 'Winning';
            if (bid.is_winning === false) return 'Outbid';
            return 'Placed';
        },
        getBidStatusClass(bid) {
            const status = this.getBidStatus(bid);
            if (status === 'Winning') return 'bg-success';
            if (status === 'Outbid') return 'bg-danger';
            return 'bg-secondary';
        },
    },
};
</script>

<style scoped>
.bids-history-container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 16px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.bid-item-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
}

.table th, .table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.7rem;
}
</style>
