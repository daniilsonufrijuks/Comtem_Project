<template>

    <div class="video-section">
        <h2 class="font-bold">{{t('blog_head')}}</h2>
        <div class="videos">
            <div v-for="video in videos" :key="video.id" class="video">
                <iframe
                    width="560"
                    height="315"
                    :src="`https://www.youtube.com/embed/${video.id}`"
                    :title="video.title"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
<!--                "`https://www.youtube-nocookie.com/embed/${video.id}`"-->
            </div>
        </div>
    </div>
</template>

<script>

import { useTranslation } from '../Composables/useTranslation';
export default {
    name: 'VideosTutor',
    props: {
        routes: Object
    },
    setup() {
        const { t, locale } = useTranslation();
        return { t, locale };
    },
    data() {
        return {
            videos: [
                // https://youtu.be/XEEcxYzNOfI?si=HOyKec5_kCK5p_Sm
                { id: 'b85h_ujZ_vg?si=TxTPv8ABd8wJOF4t', title: 'Video 1' },
                { id: 'beW8pCezrdI?si=_6FoX2LEQJmPHtQC', title: 'Video 2' },
                { id: 'WpnLehvOM6E?si=kWKwhcBmqfGb-j0O', title: 'Video 3' },
                { id: 'XEEcxYzNOfI?si=HOyKec5_kCK5p_Sm', title: 'Video 4' }
            ]
        };
    }
};
</script>

<style scoped>
.video-section {
    text-align: center;
    margin: 50px 0;
}

.video-section h2 {
    font-size: 24px;
    margin-bottom: 20px;
}

.videos {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

iframe {
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Responsive styles for smaller screens */
@media (max-width: 768px) {
    .video-section h2 {
        font-size: 20px;
    }

    .videos {
        gap: 10px;
    }

    iframe {
        width: 100%;
        max-width: 100%;
        height: 180px;
    }
}
</style>
