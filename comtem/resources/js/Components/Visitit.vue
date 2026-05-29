<script setup>
import { useTranslation } from '../Composables/useTranslation';
const { locale, t, toggleLocale } = useTranslation();
</script>

<template>
    <!-- visit this -->
    <h1 class="aboutus">{{t('partners_title')}}</h1>
    <div class="product-cards-top">
        <a class="product-card-top" href="https://www.asus.com/" target="_blank">
            <img src="/images/front/prod1.png" alt="Product 1">
            <p>{{t('partners_p1')}}</p>
            <span class="go-btn">Go</span>
        </a>

        <a class="product-card-top" href="https://www.bequiet.com/en" target="_blank">
            <img src="/images/front/prod2.png" alt="Product 2">
            <p>{{t('partners_p2')}}</p>
            <span class="go-btn">Go</span>
        </a>

        <a class="product-card-top" href="https://www.apple.com/" target="_blank">
            <img src="/images/front/prod3.png" alt="Product 3">
            <p>{{t('partners_p3')}}</p>
            <span class="go-btn">Go</span>
        </a>

        <a class="product-card-top" href="https://www.msi.com/index.php" target="_blank">
            <img src="/images/front/prod4.png" alt="Product 4">
            <p>{{t('partners_p4')}}</p>
            <span class="go-btn">Go</span>
        </a>
    </div>
    <!-- visit this -->
</template>


<style scoped>
/* Cards near top*/
.product-cards-top {
    display: flex;
    justify-content: space-between;
    margin: 20px auto;
    max-width: 1200px;
    gap: 20px;
    align-items: stretch;
}

/* .product-card-top {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 23%;
    text-align: center;
    padding: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
} */

.product-card-top {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 23%;
    text-align: center;
    padding: 15px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    height: 100%;
    min-height: 300px;
}

.product-card-top:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.product-card-top img {
    max-width: 100%;
    border-radius: 10px;
}


.product-card-top p {
    font-size: 0.9em;
    color: #666;
    margin: 10px 0;
}

.product-card-top button {
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.product-card-top button:hover {
    background-color: #0056b3;
}

.go-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: #fff;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.product-card-top:hover .go-btn {
    background-color: #0056b3;
}

/* Media query for phones */
@media (max-width: 768px) {
    .product-cards-top {
        flex-direction: column;
        align-items: center;
    }

    .product-card-top {
        width: 80%;
        margin-bottom: 20px;
    }
}
/* Cards near top */

h1 {
    max-width: 1200px;
    margin: 40px auto 0;
    padding: 0 16px;
    text-align: center;
    font-size: 2.2rem;
    font-weight: 700;
    color: #222;
}

/*  MOBILE */
@media (max-width: 576px) {
    h1 {
        font-size: 1.6rem;
    }

}


@media (max-width: 768px) {

    .product-cards-top {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        padding: 0 10px;
    }

    .product-card-top {
        width: 100%;
        min-height: auto;
        padding: 10px;
        border-radius: 8px;
    }

    .product-card-top img {
        max-height: 120px;
        object-fit: contain;
    }

    .product-card-top p {
        font-size: 12px;
        margin: 8px 0;
    }

    .product-card-top button {
        padding: 6px 12px;
        font-size: 13px;
        border-radius: 4px;
    }
}
</style>

