
<script setup>
import { useTranslation } from '../Composables/useTranslation';
const { t } = useTranslation();
</script>

<template>
    <section class="testimonial-section">
        <div class="parent-container">
            <blockquote class="responsive-quote">
                <p class="headline">
                    {{ t('about_headline') }}
                </p>

                <p>{{ t('about_p1') }}</p>

                <p>{{ t('about_p2') }}</p>

                <ul class="values">
                    <li>{{ t('about_v1') }}</li>
                    <li>{{ t('about_v2') }}</li>
                    <li>{{ t('about_v3') }}</li>
                    <li>{{ t('about_v4') }}</li>
                </ul>

                <p class="closing">{{ t('about_closing') }}</p>

                <cite>{{ t('testimonial_cite') }}</cite>
            </blockquote>
        </div>
    </section>
</template>


<style scoped>
.testimonial-section {
    background: linear-gradient(135deg, #3b0a57, #4b1170);
    padding: 80px 0;
}

.parent-container {
    width: 90%;
    max-width: 850px;
    margin: 0 auto;
}

.responsive-quote {
    position: relative;
    padding: 50px;
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    line-height: 1.7;
    color: #333;
}

/* Quote icons */
.responsive-quote::before,
.responsive-quote::after {
    content: "❝";
    position: absolute;
    font-size: 120px;
    color: #7a3a7b;
    opacity: 0.08;
    font-family: serif;
}

.responsive-quote::before {
    top: -30px;
    left: 20px;
}

.responsive-quote::after {
    content: "❞";
    bottom: -40px;
    right: 20px;
}

/* Text */
.responsive-quote p {
    font-size: 16.5px;
    margin-bottom: 18px;
}

.headline {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 16px;
    color: #7a3a7b;
}

/* Values list */
.values {
    list-style: none;
    padding: 0;
    margin: 25px 0;
}

.values li {
    font-size: 15.5px;
    margin-bottom: 10px;
    padding-left: 4px;
}

/* Closing text */
.closing {
    font-weight: 500;
}

/* Cite */
.responsive-quote cite {
    display: block;
    margin-top: 25px;
    font-size: 14.5px;
    font-weight: 600;
    color: #7a3a7b;
    text-align: right;
}

/* Mobile */
@media (max-width: 600px) {
    .responsive-quote {
        padding: 28px;
    }

    .headline {
        font-size: 22px;
    }

    .responsive-quote p {
        font-size: 15px;
    }
}

</style>
