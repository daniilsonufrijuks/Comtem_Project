<template>
    <!-- Footer -->
    <footer class="footer">
        <div class="waves">
            <div class="wave" id="wave1"></div>
            <div class="wave" id="wave2"></div>
            <div class="wave" id="wave3"></div>
            <div class="wave" id="wave4"></div>
        </div>
        <ul class="menu">
            <li class="menu__item"><a class="menu__link" href="/">Home</a></li>
            <li class="menu__item"><a class="menu__link" href="/about">About</a></li>
            <li class="menu__item"><a class="menu__link" href="https://github.com/daniilsonufrijuks">DAN</a></li>
            <li class="menu__item"><a class="menu__link" href="#">Team</a></li>
            <li class="menu__item"><a class="menu__link" href="/contact">Contact</a></li>
        </ul>
        <p>&copy;2025 COMTEM | MADE BY DAN | All Rights Reserved</p>
    </footer>
    <!-- Footer -->
</template>


<style scoped>
.footer {
    background: #420d65;
    padding: 20px 50px;
    text-align: center;
    position: relative; /* Fix the footer to the bottom of the page */
    bottom: 0; /* Position the footer at the bottom */
    left: 0;
    width: 100%; /* Make sure it spans the entire width */
    z-index: 1000; /* Keep it above other content */
    margin: 0;
}

.social-icon,
.menu {
    display: flex;
    justify-content: center;
    margin: 10px 0;
    flex-wrap: wrap;
    justify-content: center;
}

.menu__link {
    font-size: 1.2rem;
    color: #fff;
    margin: 0 10px;
    text-decoration: none;
    opacity: 0.75;
    transition: 0.5s;
}

.menu__link:hover {
    opacity: 1;
}

.footer p {
    color: #fff;
    margin: 15px 0 10px;
    font-size: 1rem;
}

@media (max-width: 500px) {
    .menu__item {
        display: none;
    }
}

/* waves */
.wave {
    position: absolute;
    top: -100px;
    left: 0;
    width: 100%;
    height: 100px;
    background: url("/images/front/wave.png");
    background-size: 1000px 100px;
}

.wave#wave1 {
    z-index: 1000;
    opacity: 1;
    bottom: 0;
    animation: animateWaves 4s linear infinite;
}

.wave#wave2 {
    z-index: 999;
    opacity: 0.5;
    bottom: 10px;
    animation: animate 4s linear infinite !important;
}

.wave#wave3 {
    z-index: 1000;
    opacity: 0.2;
    bottom: 15px;
    animation: animateWaves 3s linear infinite;
}

.wave#wave4 {
    z-index: 999;
    opacity: 0.7;
    bottom: 20px;
    animation: animate 3s linear infinite;
}

/* animations */
@keyframes animateWaves {
    0% {
        background-position-x: 1000px;
    }
    100% {
        background-positon-x: 0px;
    }
}

@keyframes animate {
    0% {
        background-position-x: -1000px;
    }
    100% {
        background-positon-x: 0px;
    }
}
</style>
