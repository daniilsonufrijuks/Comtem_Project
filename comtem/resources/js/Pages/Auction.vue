<template>
    <Navbar/>
    <Search />
    <Slider />

    <div class="main-container">
        <Visitit />
        <div class="auction-page">
<!--            <h1>Auction Listings</h1>-->
            <!-- Add Auction Button -->
            <div class="add-auction-container">
                <button @click="goToAddAuction" class="add-auction-btn">Add new auction</button>
            </div>

            <!-- Auction items list -->
            <div class="auction-list">
                <AuctionCardDB v-for="item in auctionItems" :key="item.id" :item="item"/>
            </div>
        </div>
        <Contact />
    </div>
    <Footer/>
</template>

<script>
import ProductCardDB from "@/Components/ProductCardDB.vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import Slider from "@/Components/Slider.vue";
import Search from "@/Components/Search.vue";
import Visitit from "@/Components/Visitit.vue";
import Contact from "@/Components/Contact.vue";
import AuctionCardDB from "@/Components/AuctionCardDB.vue";

export default {
    components: {AuctionCardDB, Contact, Visitit, Search, Slider, Footer, Navbar, ProductCardDB},
    data() {
        return {
            auctionItems: [],
        };
    },
    mounted() {
        this.fetchAuctionItems();
    },
    methods: {
        fetchAuctionItems() {
            fetch(`/auction/items`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Fetched products:", data);
                    this.auctionItems = data;
                })
                .catch((error) => {
                    console.error("Error fetching products:", error);
                });
        },
        goToAddAuction() {
            window.location.href = `/auction/add`; // Redirect to the add auction page
        },
        placeBid(itemId) {
            console.log(`Placing a bid on item ${itemId}`);
        }
    },
};
</script>

<style scoped>
.main-container {
    display: flex;
    flex-direction: column;
    gap: 70px;
    padding-bottom: 80px;
}

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

.auction-item img {
    width: 100px;
    height: 100px;
}

.add-auction-container {
    display: flex;
    justify-content: center;
    margin-bottom: 30px;
}

.add-auction-btn {
    background-color: #7a3a7b;
    color: #fff;
    border: none;
    padding: 12px 24px;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 40px;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    font-family: Roboto, sans-serif;
}

.add-auction-btn:hover {
    background-color: #8e4b8f;
    transform: scale(1.02);
}

</style>
