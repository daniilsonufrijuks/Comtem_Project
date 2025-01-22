<template>
    <div class="auction-page">
        <h1>Auction Listings</h1>

        <!-- Auction items list -->
        <div class="auction-list">
            <div v-for="item in auctionItems" :key="item.id" class="auction-item">
                <img :src="`/images/auction/${item.img}`" alt="auction item" />
                <h3>{{ item.name }}</h3>
                <p>{{ item.description }}</p>
                <p>Starting Bid: ${{ item.starting_bid }}</p>
                <button @click="placeBid(item.id)">Place Bid</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            auctionItems: [], // This will hold the auction items
        };
    },
    mounted() {
        this.fetchAuctionItems();
    },
    methods: {
        async fetchAuctionItems() {
            try {
                const response = await axios.get('/auction-items');
                this.auctionItems = response.data;
            } catch (error) {
                console.error('Error fetching auction items:', error);
            }
        },
        placeBid(itemId) {
            // Handle the placing bid logic (You may want to handle this with a modal or a form)
            console.log(`Placing a bid on item ${itemId}`);
        }
    },
};
</script>

<style scoped>
.auction-page {
    padding: 20px;
}

.auction-item {
    border: 1px solid #ccc;
    margin-bottom: 20px;
    padding: 10px;
}

.auction-item img {
    width: 100px;
    height: 100px;
}

.auction-item button {
    margin-top: 10px;
}
</style>
