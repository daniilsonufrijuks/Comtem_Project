<template>
    <div class="roulette-container">
        <div class="roulette-panel">
            <h1 class="title">
                Spin the Fortune Wheel
            </h1>

            <!-- WHEEL -->
            <div class="wheel-wrapper">
                <Roulette
                    ref="wheel"
                    :items="items"
                    :size="300"
                    :duration="5"
                    :display-indicator="true"
                    :display-border="true"
                    :display-shadow="true"
                    easing="bounce"
                    @wheel-end="onEnd"
                >
                </Roulette>
            </div>

            <!-- BUTTON -->
            <button
                @click="launchWheel"
                class="spin-btn"
            >
                Spin the Wheel
            </button>

            <!-- RESULT -->
            <div
                v-if="result !== null"
                class="result-box"
            >
                🎊 You won
                <span class="result-value">+{{ result }}</span>
                Apples
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Roulette } from 'vue3-roulette'
import axios from 'axios'

const wheel = ref(null)
const result = ref(null)

const items = [
    { id: 1, htmlContent: "🍎 x0", value: 0 },
    { id: 2, htmlContent: "🍎 x1", value: 1 },
    { id: 3, htmlContent: "🍎 x2", value: 2 },
    { id: 4, htmlContent: "🍎 x5", value: 5 },
    { id: 5, htmlContent: "🍎 x10", value: 10 },
]

function launchWheel() {
    if (!wheel.value) return
    result.value = null
    wheel.value.launchWheel()
}

async function onEnd(item) {
    const response = await axios.post('/spin', {
        award: item.value,
    })

    result.value = response.data.won
}
</script>

<style scoped>
/* CONTAINER – NO BACKGROUND */
.roulette-container {
    display: flex;
    justify-content: center;
    padding: 40px 16px;
}

/* PANEL */
.roulette-panel {
    background: white;
    border-radius: 20px;
    padding: 28px 32px;
    width: 380px;
    max-width: 100%;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    text-align: center;
}

/* TITLE */
.title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #4338ca;
    margin-bottom: 20px;
}

/* FIX WHEEL SHAPE */
.wheel-wrapper {
    width: 300px;
    height: 300px;
    margin: 0 auto 22px;
    display: flex;
    align-items: center;
    justify-content: center;
}


/* BUTTON */
.spin-btn {
    width: 100%;
    padding: 14px;
    background: #4f46e5;
    color: white;
    font-weight: 700;
    border-radius: 999px;
    transition: all 0.25s ease;
}

.spin-btn:hover {
    background: #4338ca;
}

.spin-btn:active {
    transform: scale(0.96);
}

/* RESULT */
.result-box {
    margin-top: 18px;
    padding: 12px;
    border-radius: 12px;
    background: #f8fafc;
    color: #334155;
    font-weight: 700;
    animation: fade-in 0.5s ease-out;
}

.result-value {
    color: #db2777;
    margin: 0 4px;
}

/* ANIMATION */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(6px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
