<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-br from-indigo-200 to-pink-100 p-6">
        <h1 class="text-4xl font-bold text-indigo-800 mb-8 drop-shadow-md">
            🎉 Spin the Fortune Wheel 🎉
        </h1>

        <Roulette
            ref="wheel"
            :items="items"
            :size="320"
            :duration="5"
            :display-indicator="true"
            :display-border="true"
            :display-shadow="true"
            easing="bounce"
            class="drop-shadow-xl"
            @wheel-end="onEnd"
        >
            <template #baseContent>
                <div class="text-center text-white font-bold text-lg">
                    🌀 Good Luck!
                </div>
            </template>
        </Roulette>

        <button
            @click="launchWheel"
            class="mt-6 bg-indigo-600 text-white px-6 py-3 rounded-full hover:bg-indigo-700 active:scale-95 transition-all duration-300 shadow-lg"
        >
            Spin the Wheel
        </button>

        <div
            v-if="result !== null"
            class="mt-8 bg-white rounded-xl shadow-md px-6 py-4 text-center text-lg font-semibold text-indigo-700 border-t-4 border-indigo-500 animate-fade-in"
        >
            🎊 You won: <span class="text-pink-600">+{{ result }}</span> Apples
        </div>
    </div>
</template>

<script setup>
import {ref} from 'vue'
import {Roulette} from 'vue3-roulette'
import axios from 'axios'

const wheel = ref(null)
const result = ref(null)

// IMPORTANT: value = numeric award
const items = [
    {id: 1, name: "Apple", htmlContent: "🍎 Apple x0", value: 0},
    {id: 2, name: "Apple", htmlContent: "🍎 Apple x1", value: 1},
    {id: 3, name: "Apple", htmlContent: "🍎 Apple x2", value: 2},
    {id: 4, name: "Apple", htmlContent: "🍎 Apple x5", value: 5},
    {id: 5, name: "Apple", htmlContent: "🍎 Apple x10", value: 10},
]

function launchWheel() {
    result.value = null
    wheel.value.launchWheel()
}

async function onEnd(item) {
    // item.value is the numeric award
    const response = await axios.post('/spin', {
        award: item.value,
    })

    result.value = response.data.won
}
</script>

<style scoped>
@keyframes fade-in {
    0% {
        opacity: 0;
        transform: translateY(10px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.6s ease-out;
}
</style>
