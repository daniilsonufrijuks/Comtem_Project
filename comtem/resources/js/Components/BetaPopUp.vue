
<script>
export default {
    name: "BetaModal",
    data() {
        return {
            visible: false,
        };
    },
    mounted() {
        // if (!localStorage.getItem("betaShown")) {
        this.visible = true;
        // }
    },
    methods: {
        close() {
            this.visible = false;
            localStorage.setItem("betaShown", "true");
        },
    },
};
</script>

<template>
    <transition name="fade">
        <div v-if="visible" class="beta-overlay">
            <div class="beta-card">
                <div class="beta-icon">⚠</div>

                <h2>Beta Version</h2>

                <p>
                    This website is currently in beta testing. Some features may not work as expected.
                </p>

                <button @click="close">Got it</button>
            </div>
        </div>
    </transition>
</template>



<style scoped>
/* Overlay */
.beta-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(6px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

/* Card */
.beta-card {
    background: #ffffff;
    padding: 30px 25px;
    border-radius: 16px;
    text-align: center;
    max-width: 420px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    animation: scaleIn 0.3s ease;
}

/* Icon */
.beta-icon {
    font-size: 32px;
    margin-bottom: 10px;
}

/* Title */
.beta-card h2 {
    margin-bottom: 10px;
    font-size: 22px;
    font-weight: 600;
}

/* Text */
.beta-card p {
    color: #555;
    font-size: 15px;
    margin-bottom: 20px;
}

/* Button */
.beta-card button {
    background: #4f46e5;
    color: white;
    border: none;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.beta-card button:hover {
    background: #4338ca;
    transform: translateY(-1px);
}

</style>
