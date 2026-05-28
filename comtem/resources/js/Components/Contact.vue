<script setup>
import { ref } from 'vue';
import axios from 'axios';
import {route} from "ziggy-js";
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useTranslation } from '../Composables/useTranslation';
const { locale, t, toggleLocale } = useTranslation();


const form = useForm({
    name: '',
    email: '',
    body: '',
});

const submit = () => {
    form.post(route('contact'), {});
};
</script>

<template>
    <section class="contact_section">
        <div class="contact-card">
            <div class="map-side">
                <div class="map-wrapper">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2175.752002814705!2d24.101903377117267!3d56.95305007355204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46eecf7bac058ce9%3A0x96a8a0e931b27448!2z0KDQuNC20YHQutC40Lkg0LPQvtGB0YPQtNCw0YDRgdGC0LLQtdC90L3Ri9C5INGC0LXRhdC90LjQutGD0Lc!5e0!3m2!1sru!2slv!4v1725731560133!5m2!1sen!2slv"
                        frameborder="0"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>

            <div class="form-side">
                <div class="form-header">
                    <h2>{{ t('contact_form_title') }}</h2>
                    <p>{{ t('contact_form_subtitle') }}</p>
                </div>

                <form @submit.prevent="submit">
                    <div class="field">
                        <label for="name">{{ t('contact_form_name') }}</label>
                        <input
                            id="name"
                            v-model="form.name"
                            type="text"
                            :placeholder="t('contact_form_name_placeholder')"
                            required
                        />
                    </div>
                    <div class="field">
                        <label for="email">{{ t('contact_form_email') }}</label>
                        <input
                            id="email"
                            v-model="form.email"
                            type="email"
                            :placeholder="t('contact_form_email_placeholder')"
                            required
                        />
                    </div>
                    <div class="field">
                        <label for="message">{{ t('contact_form_message') }}</label>
                        <textarea
                            id="message"
                            v-model="form.body"
                            :placeholder="t('contact_form_message_placeholder')"
                            required
                        ></textarea>
                    </div>
                    <button type="submit" :disabled="form.processing">
                        <span v-if="form.processing">{{ t('contact_form_sending') }}</span>
                        <span v-else>{{ t('contact_form_send') }} <i class="fa fa-paper-plane"></i></span>
                    </button>
                    <p v-if="form.recentlySuccessful" class="success-msg">
                        <i class="fa fa-check-circle"></i> {{ t('contact_form_success') }}
                    </p>
                </form>
            </div>
        </div>
    </section>
</template>

<style scoped>
.contact_section {
    padding: 60px 16px;
    width: 100%;
    box-sizing: border-box;
}

/* Card wrapper — side by side on desktop, stacked on mobile */
.contact-card {
    max-width: 1100px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(66, 13, 101, 0.10);
    overflow: hidden;
    display: flex;
    flex-direction: row;
}

/* ── Map ── */
.map-side {
    flex: 1 1 45%;
    min-height: 300px;
}

.map-wrapper {
    width: 100%;
    height: 100%;
    min-height: 300px;
}

.map-wrapper iframe {
    width: 100%;
    height: 100%;
    min-height: 300px;
    display: block;
    border: 0;
}

/* ── Form ── */
.form-side {
    flex: 1 1 55%;
    padding: 44px 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    box-sizing: border-box;
}

.form-header {
    margin-bottom: 28px;
}

.form-header h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #2d0845;
    margin: 0 0 6px;
    letter-spacing: -0.01em;
}

.form-header p {
    color: #7a6a8a;
    font-size: 0.92rem;
    margin: 0;
}

/* Fields */
.field {
    margin-bottom: 16px;
}

.field label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: #4a3060;
    margin-bottom: 6px;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

.field input,
.field textarea {
    width: 100%;
    border: 1.5px solid #e2d5ee;
    padding: 12px 14px;
    border-radius: 10px;
    background: #faf8fc;
    font-size: 0.95rem;
    color: #2d0845;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    box-sizing: border-box;
    -webkit-appearance: none;
    appearance: none;
}

.field textarea {
    height: 120px;
    resize: vertical;
    line-height: 1.5;
}

.field input:focus,
.field textarea:focus {
    border-color: #7c28a8;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(124, 40, 168, 0.12);
}

/* Button */
button[type="submit"] {
    width: 100%;
    padding: 14px;
    background: #420d65;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    cursor: pointer;
    transition: background 0.25s, transform 0.15s, box-shadow 0.2s;
    margin-top: 4px;
    box-shadow: 0 4px 14px rgba(66,13,101,0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

button[type="submit"]:hover:not(:disabled) {
    background: #6a1fa0;
    box-shadow: 0 6px 20px rgba(66,13,101,0.32);
    transform: translateY(-1px);
}

button[type="submit"]:active:not(:disabled) {
    transform: translateY(0);
}

button[type="submit"]:disabled {
    opacity: 0.65;
    cursor: not-allowed;
}

.success-msg {
    margin-top: 12px;
    color: #2a8a4a;
    font-size: 0.88rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* ── Tablet ── */
@media (max-width: 900px) {
    .contact-card {
        flex-direction: column;
    }

    .map-side {
        flex: none;
        height: 240px;
        min-height: 0;
    }

    .map-wrapper,
    .map-wrapper iframe {
        min-height: 240px;
        height: 240px;
    }

    .form-side {
        padding: 32px 28px;
    }
}

/* ── Mobile ── */
@media (max-width: 480px) {
    .contact_section {
        padding: 24px 12px 40px;
    }

    .contact-card {
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(66,13,101,0.12);
    }

    .map-side {
        height: 200px;
    }

    .map-wrapper,
    .map-wrapper iframe {
        min-height: 200px;
        height: 200px;
    }

    .form-side {
        padding: 24px 18px 28px;
    }

    .form-header h2 {
        font-size: 1.3rem;
    }

    .field input,
    .field textarea {
        font-size: 1rem; /* prevents iOS zoom on focus */
        padding: 13px 14px;
    }

    .field textarea {
        height: 110px;
    }

    button[type="submit"] {
        padding: 15px;
        font-size: 1rem;
    }
}
</style>
