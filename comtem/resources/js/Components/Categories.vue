<template>
    <!-- Categories -->
    <div class="product-cards">
        <a class="product-card" href="/laptops">
            <img src="/images/front/asuslaptop.png" alt="Laptops">
            <p>LAPTOPS</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/pcs">
            <img src="/images/front/pc.png" alt="PC">
            <p>PC</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/components">
            <img src="/images/front/inteli5.png" alt="Components">
            <p>COMPONENTS</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/auction">
            <img src="/images/front/auction.png" alt="Auction">
            <p>AUCTION</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/phones">
            <img src="/images/front/phones.png" alt="Phones">
            <p>PHONES</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/peripherals">
            <img src="/images/front/keyboard.png" alt="Peripherals">
            <p>PERIPHERALS</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/furniture">
            <img src="/images/front/table.png" alt="Furniture">
            <p>FURNITURE</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/cables">
            <img src="/images/front/cables.png" alt="Cables">
            <p>CABLES</p>
            <span class="visit-btn">VISIT</span>
        </a>

        <a class="product-card" href="/games">
            <img src="/images/front/games.png" alt="Game Zone">
            <p>GAME ZONE</p>
            <span class="visit-btn">VISIT</span>
        </a>
    </div>
    <!-- Categories -->
</template>


<style scoped>
.product-cards {
    display: flex;
    justify-content: space-between;
    margin: 20px auto;
    max-width: 1200px;
    gap: 20px;
    flex-wrap: wrap;
    position: relative;
}

.product-card{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 15px;
    width: 23%;
    text-align: center;
    padding: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    flex: 1 1 calc(50% - 10px);
    margin-bottom: 50px;
}

.product-card:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.product-card img {
    max-width: 100%;
    height: fit-content;
    border-radius: 10px;
}

.product-card p {
    font-size: 0.9em;
    color: #666;
    margin: 10px 0;
}

.product-card button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.product-card button:hover {
    background-color: #0056b3;
}

.visit-btn {
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border-radius: 5px;
    display: inline-block;
}

.product-card:hover .visit-btn {
    background-color: #0056b3;
}

/* Media query for screens 500px or less */
@media (max-width: 500px) {
    .product-card {
        flex: 1 1 calc(50% - 5px);
        margin-bottom: 15px;
    }
}
</style>
