<template>
    <section id="productdetails" v-if="item">
        <div class="single-pro-image">
            <img :src="item.img || ''" id="MainImg" alt="Auction item image" class="clickable-img" @click="openModal = true" />
        </div>

        <div class="single-pro-details">
            <h6 class="category">{{ item.category }}</h6>
            <h4 class="product-name">{{ item.name }}</h4>
            <h2 class="product-price">${{ item.starting_bid }}</h2>

            <div class="quantity-add">
                <h2>{{ t('auction_place_bid') }}</h2>
                <input type="number" v-model="bidAmount" :min="minBid" step="0.1" @input="validateBidAmount" :disabled="!isAuctionActive" />
                <button class="add-btn" @click="placeBid(item.id)" :disabled="isBidding || !isAuctionActive">
                    {{ isBidding ? t('auction_placing') : t('auction_place_btn') }}
                </button>
            </div>

            <div v-if="!isAuctionActive" class="auction-closed-banner">
                {{ getAuctionStatus === t('auction_status_not_started') ? t('auction_not_started') : t('auction_ended') }}
            </div>

            <div class="auction-info">
                <p><strong>{{ t('auction_starting_bid') }}</strong> ${{ item.starting_bid }}</p>
                <p><strong>{{ t('auction_start_date') }}</strong> {{ formattedStartDate }}</p>
                <p><strong>{{ t('auction_end_date') }}</strong> {{ formattedEndDate }}</p>
                <p><strong>{{ t('auction_min_bid') }}</strong> ${{ minBid }}</p>
            </div>

            <h4>{{ t('auction_product_details') }}</h4>
            <p class="description">{{ item.description }}</p>

            <h4 class="section-title">{{ t('auction_specifications') }}</h4>
            <ul class="specs">
                <li><strong>{{ t('auction_spec_category') }}</strong> {{ item.category }}</li>
                <li><strong>{{ t('auction_spec_starting') }}</strong> ${{ item.starting_bid }}</li>
                <li><strong>{{ t('auction_spec_status') }}</strong> {{ getAuctionStatus }}</li>
                <li><strong>{{ t('auction_spec_id') }}</strong> {{ item.id }}</li>
            </ul>
        </div>

        <div v-if="openModal" class="modal" @click="openModal = false">
            <img :src="item.image" alt="Large product view" class="modal-img" />
        </div>
    </section>

    <p v-else>{{ t('auction_loading') }}</p>

    <transition name="slide">
        <div v-if="showNotification" class="notification">
            ✅ {{ notificationMessage }}
        </div>
    </transition>
</template>


<script>
import axios from 'axios';
import {computed, ref} from "vue";
import { useTranslation } from '../Composables/useTranslation';
export default {
    props: {
        item: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const startingBid = props.item?.starting_bid ?? 0;

        const { t } = useTranslation();
        const bidAmount = ref(startingBid + 1);
        const showNotification = ref(false);
        const notificationMessage = ref('');
        const openModal = ref(false);
        const isBidding = ref(false);

        const minBid = computed(() => (props.item?.starting_bid ?? 0) + 1);

        const formattedStartDate = computed(() => {
            if (!props.item?.start_time) return 'Not specified';
            return new Date(props.item.start_time).toLocaleString();
        });

        const formattedEndDate = computed(() => {
            if (!props.item?.end_time) return 'Not specified';
            return new Date(props.item.end_time).toLocaleString();
        });

        // const validateBidAmount = () => {
        //     let raw = bidAmount.value;
        //     let numericValue = parseFloat(raw);
        //     if (isNaN(numericValue)) numericValue = minBid.value;
        //     if (numericValue < minBid.value) numericValue = minBid.value;
        //     bidAmount.value = numericValue;
        // };

        const validateBidAmount = () => {
            let raw = bidAmount.value;
            bidAmount.value = parseFloat(raw);
        };

        const getAuctionStatus = computed(() => {
            if (!props.item?.start_time || !props.item?.end_time) return 'Unknown';
            const now = new Date();
            const start = new Date(props.item.start_time);
            const end = new Date(props.item.end_time);
            if (now < start) return 'Not started yet';
            if (now > end) return 'Auction ended';
            return 'Active - Bidding open';
        });

        const isAuctionActive = computed(() => {
            if (!props.item?.start_time || !props.item?.end_time) return false;
            const now = new Date();
            return now >= new Date(props.item.start_time) && now <= new Date(props.item.end_time);
        });

        const placeBid = async (itemId) => {
            if (!props.item) {
                notificationMessage.value = '❌ Auction item not loaded';
                showNotification.value = true;
                setTimeout(() => (showNotification.value = false), 3000);
                return;
            }

            if (!isAuctionActive.value) {
                notificationMessage.value = '❌ This auction is not currently accepting bids';
                showNotification.value = true;
                setTimeout(() => (showNotification.value = false), 3000);
                return;
            }

            if (bidAmount.value < minBid.value) {
                notificationMessage.value = `❌ Bid must be at least $${minBid.value}`;
                showNotification.value = true;
                setTimeout(() => (showNotification.value = false), 3000);
                return;
            }

            isBidding.value = true;
            try {
                const response = await axios.post(`/place-bid/${itemId}`, {
                    bid_amount: bidAmount.value,
                    itemId: itemId,
                });

                notificationMessage.value = `Bid of $${bidAmount.value} placed successfully!`;
                showNotification.value = true;
                setTimeout(() => (showNotification.value = false), 3000);
                console.log('Bid Response:', response.data);
            } catch (error) {
                if (error.response && error.response.status === 401) {
                    notificationMessage.value = 'You must be logged in to place a bid';
                } else if (error.response && error.response.status === 400) {
                    notificationMessage.value = error.response.data.message || 'Invalid bid amount';
                } else {
                    notificationMessage.value = 'Error placing bid. Please try again.';
                }
                showNotification.value = true;
                setTimeout(() => (showNotification.value = false), 3000);
            } finally {
                isBidding.value = false;
            }
        };

        return {
            bidAmount,
            showNotification,
            notificationMessage,
            openModal,
            isBidding,
            minBid,
            validateBidAmount,
            formattedStartDate,
            formattedEndDate,
            getAuctionStatus,
            isAuctionActive,
            placeBid,
            t,
        };
    },
};
</script>

<style scoped>
/* ---------- LAYOUT ---------- */
#productdetails {
    display: flex;
    flex-wrap: wrap;
    gap: 40px;
    margin: 100px auto;
    width: 90%;
    max-width: 1200px;
    background: #fff;
    border-radius: 16px;
    padding: 40px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
}

/* ---------- IMAGE ---------- */
.single-pro-image {
    flex: 1 1 40%;
    display: flex;
    justify-content: center;
    align-items: center;
    max-height: 420px;
}

.clickable-img {
    max-width: 100%;
    max-height: 420px;
    width: auto;
    height: auto;
    object-fit: contain;
    border-radius: 12px;
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.clickable-img:hover {
    transform: scale(1.03);
}

/* ---------- DETAILS ---------- */
.single-pro-details {
    flex: 1 1 50%;
}

.category {
    text-transform: uppercase;
    color: #777;
    font-size: 13px;
    letter-spacing: 1px;
}

.product-name {
    font-size: 28px;
    font-weight: 700;
    margin: 10px 0;
}

.product-price {
    font-size: 24px;
    color: #7a3a7b;
    font-weight: 800;
    margin-bottom: 20px;
}

.section-title {
    margin-top: 28px;
    margin-bottom: 10px;
    font-size: 18px;
    font-weight: 700;
}

.description {
    line-height: 1.8;
    color: #333;
    font-size: 15.5px;
}

.specs {
    font-size: 14.5px;
    color: #444;
}

.specs li {
    margin-bottom: 6px;
}

.auction-info {
    background: #f9f5fc;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    border: 1px solid #e9d9eb;
}

.auction-info p {
    margin: 8px 0;
    font-size: 15px;
}

.quantity-add {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.quantity-add h2 {
    font-size: 18px;
    margin: 0;
    color: #333;
}

.quantity-add input {
    width: 100px;
    height: 45px;
    border-radius: 6px;
    border: 1px solid #ccc;
    padding-left: 10px;
    font-size: 16px;
}

.add-btn {
    background: #7a3a7b;
    color: #fff;
    border: none;
    padding: 10px 22px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    font-size: 16px;
    transition: background 0.2s ease;
}

.add-btn:hover:not(:disabled) {
    background: #8e4b8f;
}

.add-btn:disabled {
    background: #b08bb1;
    cursor: not-allowed;
    opacity: 0.7;
}

.modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.9);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2000;
}

.modal-img {
    width: 90vw;
    height: 90vh;
    object-fit: contain;
    border-radius: 12px;
    animation: zoomIn 0.25s ease;
}

.notification {
    position: fixed;
    bottom: 100px;
    right: 20px;
    background: #7a3a7b;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    z-index: 1000;
    font-weight: 500;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.slide-enter-active,
.slide-leave-active {
    transition: all 0.5s ease;
}

.slide-enter-from,
.slide-leave-to {
    transform: translateX(120%);
    opacity: 0;
}

@keyframes zoomIn {
    from {
        transform: scale(0.85);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@media (max-width: 768px) {
    #productdetails {
        flex-direction: column;
        padding: 25px;
    }

    .quantity-add {
        flex-direction: column;
        align-items: flex-start;
    }

    .quantity-add input {
        width: 100%;
    }
}

.auction-closed-banner {
    background: #fff3cd;
    color: #856404;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 15px;
}

.add-btn:disabled {
    background: #b08bb1;
    cursor: not-allowed;
    opacity: 0.6;
}
</style>
