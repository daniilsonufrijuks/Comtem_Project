<script setup>
import { useTranslation } from '../Composables/useTranslation';
const { t } = useTranslation();
</script>

<template>
    <section class="contact-section">
        <h1 class="aboutus">{{ t('contacts_title') }}</h1>
        <div class="contact-container">
            <div class="contact-box">
                <img src="/images/front/online_store_icon.png" alt="Online Store Icon">
                <h3>{{ t('contacts_online_store') }}</h3>
                <p><a href="">www.comtem.com</a></p>
                <p>Phone: <a href="tel:+37166778899">+371 66778899</a></p>
                <p>Email: <a href="mailto:info@comtem.com">info@comtem.com</a></p>
                <p>{{ t('contacts_working_days') }}: 09:00 – 18:00</p>
                <p>{{ t('contacts_saturday') }}: 09:00 – 16:00</p>
                <p>{{ t('contacts_sunday') }}: 09:00 – 16:00</p>
            </div>
            <div class="contact-box">
                <img src="/images/front/customer_service_icon.png" alt="Customer Service Icon">
                <h3>{{ t('contacts_customer_service') }}</h3>
                <p>{{ t('contacts_support') }}</p>
                <p>Phone: <a href="tel:+37166165555">+371 66165555</a></p>
                <p>Email: <a href="mailto:clientservis@comtem.com">clientservis@comtem.com</a></p>
                <p>{{ t('contacts_working_days') }}: 10:00 – 17:00</p>
                <p>{{ t('contacts_saturday') }}: {{ t('contacts_closed') }}</p>
                <p>{{ t('contacts_sunday') }}: {{ t('contacts_closed') }}</p>
            </div>
            <div class="contact-box">
                <img src="/images/front/company_icon.png" alt="Company Icon">
                <h3>{{ t('contacts_company') }}</h3>
                <p>{{ t('contacts_reg_info') }}</p>
                <p>{{ t('contacts_office') }}</p>
                <p>{{ t('contacts_reg_no') }}</p>
                <p>{{ t('contacts_vat') }}</p>
            </div>
            <div class="contact-box">
                <img src="/images/front/procurement_icon.png" alt="Procurement Icon">
                <h3>{{ t('contacts_procurement') }}</h3>
                <p>{{ t('contacts_procurement_desc') }}</p>
                <p>Email: <a href="mailto:comtem@gmail.com">comtem@gmail.com</a></p>
            </div>
        </div>
    </section>
</template>




<style scoped>
.contact-section {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 16px;
    text-align: center;
}

.contact-section h1 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 30px;
    color: #222;
}

/* GRID */
.contact-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
}

/* CARD */
.contact-box {
    background: #ffffff;
    padding: 24px 20px;
    border-radius: 16px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    text-align: left;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

/* HOVER EFFECT */
.contact-box:hover {
    transform: translateY(-6px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

/* ICON */
.contact-box img {
    width: 55px;
    margin-bottom: 12px;
}

/* TITLE */
.contact-box h3 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: #333;
}

/* TEXT */
.contact-box p {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 6px;
    line-height: 1.4;
}

/* LINKS */
.contact-box a {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 500;
}

.contact-box a:hover {
    text-decoration: underline;
}

/* BADGE STYLE (optional for labels) */
.contact-box strong {
    color: #111;
}

/*  TABLET */
@media (max-width: 992px) {
    .contact-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

/*  MOBILE */
@media (max-width: 576px) {
    .contact-container {
        grid-template-columns: 1fr;
    }

    .contact-section h1 {
        font-size: 1.6rem;
    }

    .contact-box {
        padding: 20px;
    }
}
</style>
