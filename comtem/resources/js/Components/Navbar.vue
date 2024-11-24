<script setup>
import { ref } from 'vue';
import { useUser } from '../Composables/useUser';
import axios from 'axios';
import { useRouter } from 'vue-router';  // For navigation after logout

const { isLoggedIn, user } = useUser(); // Use your custom composable to check login status
const isMenuActive = ref(false);
const router = useRouter();  // For redirecting after logout

console.log('User Data:', user.value);
// Toggle navigation menu state
const toggleNav = () => {
    isMenuActive.value = !isMenuActive.value;
};

// Logout function
const logout = async () => {
    try {
        // Send a POST request to the Laravel logout route
        await axios.post('/logout');

        // After successful logout, update the login state
        if (isLoggedIn?.value !== undefined) isLoggedIn.value = false;
        //user.value = null; // Clear user data

        // Optionally redirect to the login page or homepage
        //await router.push('/login');  // Or use '/home' or another route
    } catch (error) {
        console.error('Error logging out:', error);
    }
};


</script>


<template>
    <nav>
        <div class="logo">
            <h1>COMTEM</h1>
        </div>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contacts</a></li>
            <li><a href="/market">Market</a></li>
            <li><a href="/login">Login</a></li>
            <li v-if="isLoggedIn">
                <!-- User is logged in: show user avatar -->
                <i class="fa fa-user icon"
                   :title="user?.name || 'User'" ></i>
            </li>
            <li v-else>
                <!-- User is not logged in: show login icon -->
                <i class="fa fa-user-circle icon"></i> <!-- FontAwesome icon -->
            </li>
            <li @click="logout" style="color:white">Logout</li>
        </ul>
        <!-- Hamburger Menu -->
        <div class="hamburger" @click="toggleNav">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </nav>
    <!-- Menubar for Mobile -->
    <div class="menubar" :class="{ active: isMenuActive }">
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">About</a></li>
            <li><a href="/contact">Contacts</a></li>
            <li><a href="/market">Market</a></li>
            <li><a href="/login">Login</a></li>
<!--            <li><a :href="routes.login">Login</a></li>-->
        </ul>
    </div>
</template>



<style scoped>
nav {
    background-color: #420d65;
    padding: 5px 5%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px,
    rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    z-index: 1;
    height: 55px;
}
nav .logo {
    display: flex;
    align-items: center;
}
nav .logo img {
    height: 25px;
    width: auto;
    margin-right: 10px;
}
nav .logo h1 {
    font-size: 1.1rem;
    background: linear-gradient(to right, #b927fc 0%, #2c64fc 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}

nav ul {
    list-style: none;
    display: flex;
}
nav ul li {
    margin-left: 1.5rem;
}
nav ul li a {
    text-decoration: none;
    color: #ffffff;
    font-size: 95%;
    font-weight: 400;
    padding: 4px 8px;
    border-radius: 5px;
}

nav ul li a:hover {
    background-color: #a34faf;
}

.hamburger {
    display: none;
    cursor: pointer;
}

.hamburger .line {
    width: 25px;
    height: 2px;
    background-color: #ffffff;
    display: block;
    margin: 7px auto;
    transition: all 0.3s ease-in-out;
}

.hamburger-active {
    transition: all 0.3s ease-in-out;
    transition-delay: 0.6s;
    transform: rotate(45deg);
}

.hamburger-active .line:nth-child(2) {
    width: 0px;
}

.hamburger-active .line:nth-child(1),
.hamburger-active .line:nth-child(3) {
    transition-delay: 0.3s;
}

.hamburger-active .line:nth-child(1) {
    transform: translateY(12px);
}

.hamburger-active .line:nth-child(3) {
    transform: translateY(-5px) rotate(90deg);
}

.menubar {
    position: absolute;
    top: 0;
    left: -60%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    width: 60%;
    height: 100vh;
    padding: 20% 0;
    background: rgba(255, 255, 255);
    transition: all 0.5s ease-in;
    z-index: 2;
}
.menubar.active {
    left: 0;
    box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
}

.menubar ul {
    padding: 0;
    list-style: none;
}
.menubar ul li {
    margin-bottom: 32px;
}

.menubar ul li a {
    text-decoration: none;
    color: #000;
    font-size: 95%;
    font-weight: 400;
    padding: 5px 10px;
    border-radius: 5px;
}

.menubar ul li a:hover {
    background-color: #ac60bf;
}

/* Media Query for Mobile */
@media screen and (max-width: 790px) {
    .hamburger {
        display: block;
    }
    nav ul {
        display: none;
    }
}
</style>
