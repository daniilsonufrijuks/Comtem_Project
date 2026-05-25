<template>
    <div
        class="slider"
        ref="sliderEl"
        @mouseenter="stopAutoplay"
        @mouseleave="startAutoplay"
        @touchstart.passive="onTouchStart"
        @touchend.passive="onTouchEnd"
    >
        <div class="slider-wrapper" :style="wrapperStyle">
            <div
                v-for="(photo, index) in photos"
                :key="index"
                class="slider-item"
                :style="{ width: itemWidth + 'px' }"
            >
                <a href="/market" class="slider-link">
                    <img :src="photo" alt="Slider Image" draggable="false" />
                </a>
            </div>
        </div>

        <!-- Arrows -->
        <button class="arrow arrow-prev" @click="prevSlide" aria-label="Previous slide">
            <i class="fa fa-chevron-left"></i>
        </button>
        <button class="arrow arrow-next" @click="nextSlide" aria-label="Next slide">
            <i class="fa fa-chevron-right"></i>
        </button>

        <!-- Dots -->
        <div class="dots">
            <span
                v-for="(photo, index) in photos"
                :key="'dot-' + index"
                :class="{ active: index === currentIndex }"
                @click="goToSlide(index)"
            ></span>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const photos = [
    "../images/front/slbg1.png",
    "../images/front/slbg2.png",
    "../images/front/slbg3.png",
    "../images/front/slbg4.jpg",
    "../images/front/slbg5.jpg",
    "../images/front/slbg6.jpg",
    "../images/front/slbg7.jpg",
    "../images/front/slbg8.jpg",
];

const sliderEl = ref(null);
const itemWidth = ref(0);
const currentIndex = ref(0);
const touchStartX = ref(0);
const SWIPE_THRESHOLD = 40;
let autoplayInterval = null;
let resizeObserver = null;

// Transform is now pixel-based using the measured width — no % ambiguity
const wrapperStyle = computed(() => ({
    transform: `translateX(-${currentIndex.value * itemWidth.value}px)`,
    transition: 'transform 0.45s cubic-bezier(0.4, 0, 0.2, 1)',
}));

const measure = () => {
    if (sliderEl.value) {
        itemWidth.value = sliderEl.value.offsetWidth;
    }
};

const nextSlide = () => {
    currentIndex.value = (currentIndex.value + 1) % photos.length;
};

const prevSlide = () => {
    currentIndex.value = (currentIndex.value - 1 + photos.length) % photos.length;
};

const goToSlide = (index) => {
    currentIndex.value = index;
};

const onTouchStart = (e) => {
    touchStartX.value = e.touches[0].clientX;
    stopAutoplay();
};

const onTouchEnd = (e) => {
    const delta = e.changedTouches[0].clientX - touchStartX.value;
    if      (delta < -SWIPE_THRESHOLD) nextSlide();
    else if (delta >  SWIPE_THRESHOLD) prevSlide();
    startAutoplay();
};

const startAutoplay = () => {
    stopAutoplay();
    autoplayInterval = setInterval(nextSlide, 3000);
};

const stopAutoplay = () => clearInterval(autoplayInterval);

onMounted(() => {
    measure();
    // Re-measure if slider resizes (orientation change, window resize)
    resizeObserver = new ResizeObserver(measure);
    resizeObserver.observe(sliderEl.value);
    startAutoplay();
});

onUnmounted(() => {
    stopAutoplay();
    resizeObserver?.disconnect();
});
</script>

<style scoped>
.slider {
    position: relative;
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    overflow: hidden;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(66, 13, 101, 0.15);
    background: #1a0028;
    aspect-ratio: 16 / 7;
    user-select: none;
    -webkit-user-select: none;
}

@media (max-width: 640px) {
    .slider {
        aspect-ratio: 4 / 3;
        border-radius: 12px;
    }
}

.slider-wrapper {
    display: flex;
    height: 100%;
    will-change: transform;
}

/* width set inline via JS — always exactly the slider's offsetWidth */
.slider-item {
    flex-shrink: 0;
    height: 100%;
    overflow: hidden;
}

.slider-item img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    object-position: center;
    pointer-events: none;
    -webkit-user-drag: none;
}

/* Arrows */
.arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #fff;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.9rem;
    transition: background 0.2s, transform 0.2s;
    z-index: 2;
}

.arrow:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-50%) scale(1.08);
}

.arrow-prev { left: 14px; }
.arrow-next { right: 14px; }

@media (max-width: 480px) {
    .arrow { width: 34px; height: 34px; font-size: 0.78rem; }
    .arrow-prev { left: 10px; }
    .arrow-next { right: 10px; }
}

/* Dots */
.dots {
    position: absolute;
    bottom: 14px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 7px;
    z-index: 2;
}

.dots span {
    display: block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    cursor: pointer;
    transition: background 0.25s, width 0.25s;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.dots span.active {
    background: #fff;
    width: 22px;
    border-radius: 99px;
}

@media (max-width: 480px) {
    .dots { bottom: 10px; gap: 5px; }
    .dots span { width: 6px; height: 6px; }
    .dots span.active { width: 16px; }
}
</style>
