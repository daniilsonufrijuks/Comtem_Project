<template>
    <div class="fixed bottom-14 right-4 z-50">
        <div class="w-24 h-24 animate-wave">
            <img src="/images/front/robot.png" alt="Robot Assistant" class="w-full h-full" />
        </div>
    </div>
</template>

<script>
export default {
    name: "RobotAssistant",
};
</script>

<style scoped>
@keyframes wave {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(10deg); }
    50% { transform: rotate(-10deg); }
    75% { transform: rotate(10deg); }
}
.animate-wave {
    animation: wave 2s infinite ease-in-out;
    transform-origin: bottom right;
}
</style>
