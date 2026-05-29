<template>
    <div v-if="showBanner" class="banner">
        <div class="banner-content">
            <p>{{t('banner')}}</p>
            <button @click="closeBanner">X</button>
        </div>
    </div>
</template>

<script>
import { useTranslation } from '../Composables/useTranslation';
export default {
    data() {
        return {
            showBanner: true
        };
    },
    setup() {
        const { t, locale } = useTranslation();
        return { t, locale };
    },
    methods: {
        closeBanner() {
            this.showBanner = false;
            localStorage.setItem("bannerDismissed", "true");
        }
    },
};
</script>

<style scoped>
.banner {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background: linear-gradient(135deg, #ed5f7f, #9a1b06);
    color: white;
    text-align: center;
    padding: 10px;
    font-size: 18px;
    font-weight: bold;
    z-index: 1000;
}
.banner-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 90%;
    margin: auto;
}
button {
    background: white;
    color: red;
    border: none;
    padding: 5px 10px;
    font-size: 16px;
    cursor: pointer;
}

@media screen and (max-width: 480px) {

    .banner {
        padding: 6px 8px;
        font-size: 13px;
    }

    .banner-content {
        max-width: 95%;
        gap: 8px;
    }

    .banner p {
        margin: 0;
        line-height: 1.2;
    }

    button {
        padding: 3px 6px;
        font-size: 12px;
        border-radius: 4px;
    }
}
</style>
