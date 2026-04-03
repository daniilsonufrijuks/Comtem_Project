<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Roulette } from 'vue3-roulette'
import axios from 'axios'

const wheel = ref(null)
const result = ref(null)
const spinning = ref(false)

/* Dynamic wheel size */
const wheelSize = ref(300)

function updateWheelSize() {
    const width = window.innerWidth

    if (width < 380) wheelSize.value = 200
    else if (width < 500) wheelSize.value = 240
    else if (width < 768) wheelSize.value = 280
    else wheelSize.value = 300
}

onMounted(() => {
    updateWheelSize()
    window.addEventListener('resize', updateWheelSize)
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateWheelSize)
})

const items = [
    { id: 1, htmlContent: "🍎 x0", value: 0 },
    { id: 2, htmlContent: "🍎 x1", value: 1 },
    { id: 3, htmlContent: "🍎 x2", value: 2 },
    { id: 4, htmlContent: "🍎 x5", value: 5 },
    { id: 5, htmlContent: "🍎 x10", value: 10 },
]

function launchWheel() {
    if (!wheel.value || spinning.value) return
    spinning.value = true
    result.value = null
    wheel.value.launchWheel()
}

async function onEnd(item) {
    try {
        const response = await axios.post('/spin', {
            award: item.value,
        })

        result.value = response.data.won
    } catch (e) {
        console.error(e)
    } finally {
        spinning.value = false
    }
}
</script>

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
                    :size="wheelSize"
                    :duration="5"
                    :display-indicator="true"
                    :display-border="true"
                    :display-shadow="true"
                    easing="bounce"
                    @wheel-end="onEnd"
                />
            </div>

            <!-- BUTTON -->
            <button
                @click="launchWheel"
                :disabled="spinning"
                class="spin-btn"
            >
                {{ spinning ? 'Spinning...' : 'Spin the Wheel' }}
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

<style scoped>
/* CONTAINER */
.roulette-container {
    width: 100%;
    display: flex;
    justify-content: center;
    padding: 40px 16px;
    overflow-x: hidden;
}

/* PANEL */
.roulette-panel {
    background: white;
    border-radius: 20px;
    padding: 28px 24px;
    width: 100%;
    max-width: 900px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    text-align: center;
    overflow: hidden;
}

/* TITLE */
.title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #4338ca;
    margin-bottom: 20px;
}

/* WHEEL WRAPPER (responsive) */
.wheel-wrapper {
    width: 100%;
    max-width: 300px;
    aspect-ratio: 1 / 1;
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
    font-size: 1rem;
    cursor: pointer;
    border: none;
}

.spin-btn:hover {
    background: #4338ca;
}

.spin-btn:active {
    transform: scale(0.96);
}

.spin-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
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
    font-size: 1rem;
}

.result-value {
    color: #db2777;
    margin: 0 4px;
    font-size: 1.2rem;
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

/* TABLET */
@media (max-width: 768px) {
    .roulette-panel {
        padding: 22px 18px;
    }

    .title {
        font-size: 1.4rem;
    }

    .wheel-wrapper {
        max-width: 280px;
    }
}

/* MOBILE */
@media (max-width: 500px) {
    .roulette-container {
        padding: 20px 12px;
    }

    .roulette-panel {
        padding: 18px 14px;
        border-radius: 16px;
    }

    .title {
        font-size: 1.3rem;
    }

    .wheel-wrapper {
        max-width: 240px;
    }

    .spin-btn {
        padding: 12px;
        font-size: 0.95rem;
    }

    .result-box {
        font-size: 0.95rem;
    }
}

/*  SMALL DEVICES */
@media (max-width: 380px) {
    .wheel-wrapper {
        max-width: 200px;
    }

    .title {
        font-size: 1.2rem;
    }
}
</style>
