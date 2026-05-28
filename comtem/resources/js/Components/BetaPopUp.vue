<script>
import { useTranslation } from '../Composables/useTranslation';
export default {
    name: "BetaModal",
    setup() {
        const { t } = useTranslation();
        return { t };
    },
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
        <div v-if="visible" class="beta-overlay" @click.self="close">
            <div class="beta-card">
                <div class="beta-icon">⚠️</div>
                <h2>{{t('beta_title')}}</h2>
                <p>{{t('beta_body')}}</p>
                <button @click="close">{{t('beta_btn')}}</button>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.beta-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 5, 30, 0.7);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    padding: 16px;
    box-sizing: border-box;
}

.beta-card {
    background: #fff;
    border-radius: 20px;
    text-align: center;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 24px 64px rgba(66, 13, 101, 0.22);
    animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    overflow: hidden;
}

.beta-card::before {
    content: '';
    display: block;
    height: 5px;
    background: linear-gradient(to right, #420d65, #a34faf);
}

.beta-icon {
    font-size: 2.4rem;
    margin: 28px 0 10px;
    display: block;
}

.beta-card h2 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d0845;
    margin: 0 0 10px;
    padding: 0 24px;
}

.beta-card p {
    color: #6b5a7a;
    font-size: 0.92rem;
    line-height: 1.6;
    margin: 0 0 24px;
    padding: 0 24px;
}

.beta-card button {
    display: block;
    width: calc(100% - 48px);
    margin: 0 24px 24px;
    padding: 13px;
    background: #420d65;
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(66, 13, 101, 0.28);
    box-sizing: border-box;
}

.beta-card button:hover {
    background: #6a1fa0;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(66, 13, 101, 0.36);
}

.beta-card button:active {
    transform: translateY(0);
}

/* Transition */
.fade-enter-active { transition: opacity 0.25s ease; }
.fade-leave-active { transition: opacity 0.2s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }

@keyframes scaleIn {
    from { opacity: 0; transform: scale(0.88) translateY(12px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

/* Mobile */
@media (max-width: 480px) {
    .beta-card {
        border-radius: 16px;
    }

    .beta-icon { font-size: 2rem; margin-top: 24px; }

    .beta-card h2 { font-size: 1.15rem; }

    .beta-card p { font-size: 0.88rem; }

    .beta-card button {
        padding: 14px;
        font-size: 1rem;
    }
}
</style>
