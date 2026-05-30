<script setup>
import { ref } from 'vue';
import { useUser } from '../Composables/useUser';
import axios from 'axios';
import { useRouter } from 'vue-router';

import { useTranslation } from '../Composables/useTranslation';
const { locale, t, toggleLocale } = useTranslation();

const { isLoggedIn, user } = useUser();
const isMenuActive = ref(false);
const router = useRouter();

const toggleNav = () => {
    isMenuActive.value = !isMenuActive.value;
    document.body.style.overflow = isMenuActive.value ? 'hidden' : 'auto';
};

const logout = async () => {
    try {
        await axios.post('/logout');
        isMenuActive.value = false;
        document.body.style.overflow = 'auto';
        if (isLoggedIn?.value !== undefined) isLoggedIn.value = false;
    } catch (error) {
        console.error('Error logging out:', error);
    }
};

const goToUserPage = () => {
    window.location.href = "/user";
};
</script>

<template>
    <nav>
        <div class="logo">
            <h1><a href="/">COMTEM</a></h1>
            <img class="imglogo" src="/m.png"/>
        </div>
        <ul class="desktop-nav">
            <li><a href="/">{{ t('home') }}</a></li>
            <li><a href="/about">{{ t('about') }}</a></li>
            <li><a href="/contacts">{{ t('contacts') }}</a></li>
            <li><a href="/tutor">{{ t('blog') }}</a></li>
            <li><a href="/market">{{ t('market') }}</a></li>
            <li><a href="/login">{{ t('login') }}</a></li>
            <li v-if="isLoggedIn">
                <i class="fa fa-user icon" :style="{color: 'white', cursor: 'pointer'}" :title="user?.name || 'User'" @click="goToUserPage"></i>
            </li>
            <li v-else>
                <i class="fa fa-user-circle icon" :style="{ color: 'white' }"></i>
            </li>
            <li v-if="isLoggedIn" @click="logout" style="cursor: pointer;">
                    <i class="fa fa-sign-out icon" style="color: white;" title="Logout"></i>
            </li>
            <li>
                <a href="/cart" style="position: relative;">
                    <i class="fa fa-shopping-cart icon" style="color: white;"></i>
                    <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
                </a>
            </li>
            <li>
                <button @click="toggleLocale" style="background:none;border:1px solid rgba(255,255,255,0.4);color:#fff;padding:3px 10px;border-radius:6px;cursor:pointer;font-size:0.85rem;font-weight:600;">
                    {{ locale === 'en' ? 'LV' : locale === 'lv' ? 'LT' : 'EN' }}
                </button>
            </li>
        </ul>

        <!-- Hamburger -->
        <button class="hamburger" :class="{ 'is-active': isMenuActive }" @click="toggleNav" aria-label="Toggle menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </nav>

    <!-- Mobile Drawer -->
    <Transition name="drawer">
        <div v-if="isMenuActive" class="menubar">
            <div class="menubar-header">
                <span class="menubar-brand">COMTEM</span>
                <button class="close-btn" @click="toggleNav" aria-label="Close menu">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <ul class="menubar-nav">
                <li><a href="/" @click="toggleNav"><i class="fa fa-home nav-icon"></i>{{ t('home') }}</a></li>
                <li><a href="/about" @click="toggleNav"><i class="fa fa-info-circle nav-icon"></i>{{ t('about') }}</a></li>
                <li><a href="/contacts" @click="toggleNav"><i class="fa fa-envelope nav-icon"></i>{{ t('contacts') }}</a></li>
                <li><a href="/tutor" @click="toggleNav"><i class="fa fa-book nav-icon"></i>{{ t('blog') }}</a></li>
                <li><a href="/market" @click="toggleNav"><i class="fa fa-store nav-icon"></i>{{ t('market') }}</a></li>
                <li><a href="/login" @click="toggleNav"><i class="fa fa-lock nav-icon"></i>{{ t('login') }}</a></li>
            </ul>

            <div class="menubar-footer">
                <a href="/cart" class="footer-action">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Cart</span>
                    <span v-if="cartCount > 0" class="cart-badge-mobile">{{ cartCount }}</span>
                </a>

                <button v-if="isLoggedIn" class="footer-action" @click="goToUserPage">
                    <i class="fa fa-user"></i>
                    <span>{{ user?.name || 'Profile' }}</span>
                </button>

                <button v-if="isLoggedIn" class="footer-action logout-btn" @click="logout">
                    <i class="fa fa-sign-out"></i>
                    <span>Logout</span>
                </button>
                <button class="footer-action" @click="toggleLocale">
                    <i class="fa fa-language nav-icon"></i>
                    <span>{{ locale === 'en' ? 'Latviešu' : locale === 'lv' ? 'Lietuvių' : 'English' }}</span>
                </button>
            </div>
        </div>
    </Transition>

    <Transition name="fade">
        <div v-if="isMenuActive" class="overlay" @click="toggleNav"></div>
    </Transition>
</template>

<style scoped>
/* ── Base nav ── */
nav {
    background-color: #420d65;
    padding: 0 2%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 12px rgba(0,0,0,0.35);
    z-index: 100;
    height: 58px;
    position: relative;
}

nav .logo {
    display: flex;
    align-items: center;
    gap: 8px;
}
nav .logo img {
    height: 26px;
    width: auto;
}
nav .logo h1 {
    font-size: 1.4rem;
    margin: 0;
    background: linear-gradient(to right, #c593dc 0%, #e0cae4 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: 0.04em;
}
nav .logo h1 a {
    color: inherit;
    text-decoration: none;
    -webkit-text-fill-color: transparent;
    background: linear-gradient(to right, #c593dc 0%, #e0cae4 100%);
    -webkit-background-clip: text;
    background-clip: text;
}

/* ── Desktop nav ── */
.desktop-nav {
    list-style: none;
    display: flex;
    align-items: center;
    gap: 2px;
    margin: 0;
    padding: 0;
}
.desktop-nav li a {
    text-decoration: none;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 400;
    padding: 5px 10px;
    border-radius: 6px;
    transition: background 0.2s;
}
.desktop-nav li a:hover {
    background-color: #a34faf;
}
.icon { font-size: 1.1rem; }

/* ── Hamburger ── */
.hamburger {
    display: none;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    border-radius: 8px;
    transition: background 0.2s;
}
.hamburger:hover { background: rgba(255,255,255,0.1); }
.hamburger .bar {
    display: block;
    width: 24px;
    height: 2px;
    background: #fff;
    border-radius: 2px;
    transition: transform 0.3s ease, opacity 0.3s ease;
}
/* X state */
.hamburger.is-active .bar:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.hamburger.is-active .bar:nth-child(2) { opacity: 0; transform: scaleX(0); }
.hamburger.is-active .bar:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── Mobile Drawer ── */
.menubar {
    position: fixed;
    top: 0;
    left: 0;
    width: min(320px, 85vw);
    height: 100dvh;
    background: #fff;
    z-index: 200;
    display: flex;
    flex-direction: column;
    box-shadow: 4px 0 24px rgba(66,13,101,0.18);
    overflow-y: auto;
}

.menubar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 20px;
    height: 58px;
    background: #420d65;
    flex-shrink: 0;
}
.menubar-brand {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    background: linear-gradient(to right, #c593dc, #e0cae4);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
.close-btn {
    background: rgba(255,255,255,0.12);
    border: none;
    color: #fff;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}
.close-btn:hover { background: rgba(255,255,255,0.25); }

/* Nav links */
.menubar-nav {
    list-style: none;
    margin: 0;
    padding: 16px 0;
    flex: 1;
}
.menubar-nav li a {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 24px;
    text-decoration: none;
    color: #2d0845;
    font-size: 1rem;
    font-weight: 500;
    border-left: 3px solid transparent;
    transition: background 0.18s, border-color 0.18s, color 0.18s;
}
.menubar-nav li a:hover,
.menubar-nav li a:active {
    background: #f5edf9;
    border-left-color: #8a2caf;
    color: #420d65;
}
.nav-icon {
    width: 20px;
    text-align: center;
    font-size: 1rem;
    color: #a050c0;
    flex-shrink: 0;
}

/* Footer actions */
.menubar-footer {
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 12px 16px 28px;
    border-top: 1px solid #ede0f5;
}
.footer-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 14px;
    border-radius: 10px;
    background: none;
    border: none;
    cursor: pointer;
    color: #2d0845;
    font-size: 0.95rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.18s;
    width: 100%;
    text-align: left;
}
.footer-action:hover { background: #f5edf9; }
.footer-action i { font-size: 1rem; color: #a050c0; width: 20px; text-align: center; }

.logout-btn { color: #b0254a; }
.logout-btn i { color: #b0254a; }
.logout-btn:hover { background: #fdeef2; }

.cart-badge-mobile {
    margin-left: auto;
    background: #420d65;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    border-radius: 99px;
    padding: 1px 7px;
    min-width: 20px;
    text-align: center;
}

/* Desktop badge */
.cart-badge {
    position: absolute;
    top: -6px;
    right: -8px;
    background: #e040a0;
    color: #fff;
    font-size: 0.65rem;
    font-weight: 700;
    border-radius: 99px;
    padding: 1px 5px;
    min-width: 16px;
    text-align: center;
}

/* ── Overlay ── */
.overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.45);
    backdrop-filter: blur(2px);
    z-index: 199;
}

/* ── Transitions ── */
.drawer-enter-active { transition: transform 0.32s cubic-bezier(0.22,1,0.36,1); }
.drawer-leave-active { transition: transform 0.26s cubic-bezier(0.55,0,1,0.45); }
.drawer-enter-from, .drawer-leave-to { transform: translateX(-100%); }

.fade-enter-active { transition: opacity 0.28s ease; }
.fade-leave-active { transition: opacity 0.22s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

/* ── Responsive ── */
@media screen and (max-width: 790px) {
    .desktop-nav { display: none; }
    .hamburger { display: flex; }
    nav { padding: 0 16px; }
}
</style>
