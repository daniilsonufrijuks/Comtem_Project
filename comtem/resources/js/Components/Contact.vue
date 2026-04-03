
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import {route} from "ziggy-js";
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    body: '',
});

const submit = () => {
    form.post(route('contact'), {

    });
};
</script>


<template>

    <!-- contact section -->
    <section class="contact_section ">
        <div class="container container-bg">
            <div class="row">
                <div class="col-lg-7 col-md-6 px-0">
                    <div class="map_container">
                        <div class="map-responsive">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2175.752002814705!2d24.101903377117267!3d56.95305007355204!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46eecf7bac058ce9%3A0x96a8a0e931b27448!2z0KDQuNC20YHQutC40Lkg0LPQvtGB0YPQtNCw0YDRgdGC0LLQtdC90L3Ri9C5INGC0LXRhdC90LjQutGD0Lw!5e0!3m2!1sru!2slv!4v1725731560133!5m2!1sen!2slv"  width="600" height="300" frameborder="0" style="border:0; width: 100%; height:100%" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-5 px-0">
                    <form @submit.prevent="submit">
                        <div>
                            <input v-model="form.name" type="text" name="name" placeholder="Name" required />
                        </div>
                        <div>
                            <input v-model="form.email" type="email" name="email" placeholder="Email" required />
                        </div>
<!--                        <div>-->
<!--                            <input v-model="form.subject" type="text" name="subject" placeholder="Phone" required />-->
<!--                        </div>-->
                        <div>
                            <textarea v-model="form.body" name="message" class="message-box" placeholder="Message" required ></textarea>
                        </div>
                        <div class="d-flex">
                            <button type="submit">SEND</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.contact_section {
    padding: 80px 20px;
    width: 100%;
}

/* Container */
.contact_section .container-bg {
    max-width: 1200px; /* ⬅️ bigger, fixes "too small" */
    width: 100%;
    margin: 0 auto; /* center it */
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

/* Row fix (important) */
.contact_section .row {
    display: flex;
    flex-wrap: wrap;
    margin: 0;
}

/* Columns */
.contact_section .col-lg-6 {
    flex: 0 0 50%;
    max-width: 50%;
}

/* MAP FIX */
.contact_section .map_container {
    width: 100%;
    height: 100%;
    min-height: 400px; /* ⬅️ prevents tiny map */
}

.map-responsive {
    width: 100%;
    height: 100%;
}

.map-responsive iframe {
    width: 100%;
    height: 100%;
    border: 0;
}

/* FORM */
.contact_section form {
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* INPUTS */
.contact_section input,
.contact_section textarea {
    width: 100%;
    border: 1px solid #ddd;
    height: 50px;
    margin-bottom: 18px;
    padding: 0 15px;
    border-radius: 8px;
    background-color: #f9f9f9;
    outline: none;
    transition: 0.2s;
}

.contact_section textarea {
    height: 130px;
    padding-top: 10px;
}

.contact_section input:focus,
.contact_section textarea:focus {
    border-color: #420d65;
    background: #fff;
}

/* BUTTON */
.contact_section button {
    padding: 14px;
    background: #420d65;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.contact_section button:hover {
    background: #5c1a85;
}

/* MOBILE */
@media (max-width: 992px) {
    .contact_section .col-lg-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }

    .contact_section .map_container {
        min-height: 300px;
    }

    .contact_section form {
        padding: 25px;
    }
}

@media (max-width: 576px) {
    .contact_section {
        padding: 40px 10px;
    }

    .contact_section .container-bg {
        border-radius: 8px;
    }

    .contact_section input,
    .contact_section textarea {
        font-size: 14px;
    }
}
</style>
