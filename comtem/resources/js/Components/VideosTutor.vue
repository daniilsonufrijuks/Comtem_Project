<template>

    <div class="video-section">
        <h2>Watch Our Videos</h2>
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


export default {
    name: 'VideosTutor',
    props: {
        routes: Object
    },
    data() {
        return {
            videos: [
                { id: 'b85h_ujZ_vg?si=TxTPv8ABd8wJOF4t', title: 'Video 1' },
                { id: 'beW8pCezrdI?si=_6FoX2LEQJmPHtQC', title: 'Video 2' },
                { id: 'WpnLehvOM6E?si=kWKwhcBmqfGb-j0O', title: 'Video 3' }
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
</style>
