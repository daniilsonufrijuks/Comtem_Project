<!-- resources/js/Pages/Auth/Registration.vue -->
<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import { ref } from 'vue';

const accountType = ref('standalone'); // 'standalone', 'create_family', 'join_family'

const form = useForm({
    email: '',
    password: '',
    password_confirmation: '',
    address: '',
    family_name: '',
    invitation_code: '',
});

const submit = () => {
    if (accountType.value === 'join_family') {
        form.invitation_code = form.invitation_code.toUpperCase();
    }

    // Remove family fields if not needed
    if (accountType.value === 'standalone') {
        form.family_name = '';
        form.invitation_code = '';
    } else if (accountType.value === 'create_family') {
        form.invitation_code = '';
    } else if (accountType.value === 'join_family') {
        form.family_name = '';
    }

    form.post(route('registration'), {
        onError: (errors) => {
            console.log('Registration errors:', errors);
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <section class="container forms">
        <Link :href="route('home')" class="back-button">
            {{ 'Back Home' }}
        </Link>
        <div class="form login">
            <div class="form-content">
                <header>Registration</header>

                <!-- Account Type Selection -->
                <div class="account-type-selector">
                    <div class="account-type-option"
                         @click="accountType = 'standalone'"
                         :class="{ active: accountType === 'standalone' }">
                        <div class="option-icon">👤</div>
                        <div class="option-text">
                            <div class="option-title">Standalone Account</div>
                            <div class="option-description">Create a personal account</div>
                        </div>
                    </div>

                    <div class="account-type-option"
                         @click="accountType = 'create_family'"
                         :class="{ active: accountType === 'create_family' }">
                        <div class="option-icon">👨‍👩‍👧‍👦</div>
                        <div class="option-text">
                            <div class="option-title">Create Family</div>
                            <div class="option-description">Start a new family as parent</div>
                        </div>
                    </div>

                    <div class="account-type-option"
                         @click="accountType = 'join_family'"
                         :class="{ active: accountType === 'join_family' }">
                        <div class="option-icon">➕</div>
                        <div class="option-text">
                            <div class="option-title">Join Family</div>
                            <div class="option-description">Join existing family as child</div>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit">
                    <div class="field input-field">
                        <input type="email" v-model="form.email" placeholder="Email" class="input" required>
                    </div>

                    <div class="field input-field">
                        <input type="text" v-model="form.address" placeholder="Address" class="input" required>
                    </div>

                    <!-- Family Name (only for create_family) -->
                    <div class="field input-field" v-if="accountType === 'create_family'">
                        <input
                            type="text"
                            v-model="form.family_name"
                            placeholder="Family Name"
                            class="input"
                            :required="accountType === 'create_family'"
                        >
                        <small style="display: block; margin-top: 5px; color: #666;">
                            Choose a name for your family
                        </small>
                    </div>

                    <!-- Invitation Code (only for join_family) -->
                    <div class="field input-field" v-if="accountType === 'join_family'">
                        <input
                            type="text"
                            v-model="form.invitation_code"
                            placeholder="Family Invitation Code"
                            class="input"
                            :required="accountType === 'join_family'"
                        >
                        <small style="display: block; margin-top: 5px; color: #666;">
                            Get this code from your family admin
                        </small>
                    </div>

                    <div class="field input-field">
                        <input type="password" v-model="form.password" placeholder="Password" class="password" required>
                    </div>

                    <div class="field input-field">
                        <input type="password" v-model="form.password_confirmation" placeholder="Repeat password" class="password" required>
                    </div>

                    <div class="field button-field">
                        <button type="submit" :disabled="form.processing">
                            {{
                                accountType === 'standalone' ? 'Create Account' :
                                    accountType === 'create_family' ? 'Create Family Account' :
                                        'Join Family'
                            }}
                        </button>
                    </div>
                </form>

                <div class="form-link">
                    <span>Already have an account? <a href="/login" class="link signup-link">Login</a></span>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.container {
    height: 100vh;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #420d65;
    column-gap: 30px;
}

.form {
    position: absolute;
    max-width: 430px;
    width: 100%;
    padding: 30px;
    border-radius: 6px;
    background: #FFF;
}

.form.signup {
    opacity: 0;
    pointer-events: none;
}

.forms.show-signup .form.signup {
    opacity: 1;
    pointer-events: auto;
}

.forms.show-signup .form.login {
    opacity: 0;
    pointer-events: none;
}

header {
    font-size: 28px;
    font-weight: 600;
    color: #232836;
    text-align: center;
}

form {
    margin-top: 30px;
}

.form .field {
    position: relative;
    height: 50px;
    width: 100%;
    margin-top: 20px;
    border-radius: 6px;
}

.field input,
.field button {
    height: 100%;
    width: 100%;
    border: none;
    font-size: 16px;
    font-weight: 400;
    border-radius: 6px;
}

.field input {
    outline: none;
    padding: 0 15px;
    border: 1px solid #CACACA;
}

.field input:focus {
    border-bottom-width: 2px;
}

.eye-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    font-size: 18px;
    color: #8b8b8b;
    cursor: pointer;
    padding: 5px;
}

.field button {
    color: #fff;
    background-color: #420d65;
    transition: all 0.3s ease;
    cursor: pointer;
}

.field button:hover {
    background-color: #420d65;
}

.form-link {
    text-align: center;
    margin-top: 10px;
}

.form-link span,
.form-link a {
    font-size: 14px;
    font-weight: 400;
    color: #232836;
}

.form a {
    color: #420d65;
    text-decoration: none;
}

.form-content a:hover {
    text-decoration: underline;
}

.line {
    position: relative;
    height: 1px;
    width: 100%;
    margin: 36px 0;
    background-color: #d4d4d4;
}

.line::before {
    content: 'Or';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #FFF;
    color: #8b8b8b;
    padding: 0 15px;
}

.media-options a {
    display: flex;
    align-items: center;
    justify-content: center;
}

a.facebook {
    color: #fff;
    background-color: #420d65;
}

a.facebook .facebook-icon {
    height: 28px;
    width: 28px;
    color: #420d65;
    font-size: 20px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #fff;
}

.facebook-icon,
img.google-img {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
}

img.google-img {
    height: 20px;
    width: 20px;
    object-fit: cover;
}

a.google {
    border: 1px solid #CACACA;
}

a.google span {
    font-weight: 500;
    opacity: 0.6;
    color: #232836;
}

/* ========== Back Button ========== */
.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background-color: #fff;
    color: #a741e4;
    padding: 8px 16px;
    border-radius: 6px;
    border: 2px solid #a741e4;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.3s ease;
    z-index: 10;
}

.back-button:hover {
    background-color: #b07cf1;
    color: #fff;
    cursor: pointer;
}

/* ========== Account Type Selector ========== */
.account-type-selector {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-bottom: 20px;
}

.account-type-option {
    display: flex;
    align-items: center;
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f9f9f9;
}

.account-type-option:hover {
    border-color: #b07cf1;
    background: #f5f0ff;
}

.account-type-option.active {
    border-color: #420d65;
    background: #f0e6ff;
}

.option-icon {
    font-size: 24px;
    margin-right: 15px;
    width: 40px;
    text-align: center;
}

.option-text {
    flex: 1;
}

.option-title {
    font-weight: 600;
    color: #333;
    margin-bottom: 3px;
}

.option-description {
    font-size: 12px;
    color: #666;
}

@media screen and (max-width: 400px) {
    .form {
        padding: 20px 10px;
    }

    .account-type-option {
        padding: 12px;
    }

    .option-icon {
        font-size: 20px;
        margin-right: 10px;
        width: 30px;
    }
}
</style>
