<template>
    <section class="comments-section">
        <div class="comments-card">

            <!-- Header -->
            <div class="card-header">
                <h2>Comments <span v-if="comments.length" class="badge">{{ comments.length }}</span></h2>
            </div>

            <div class="card-body">
                <!-- Compose -->
                <div v-if="user" class="compose">
                    <textarea
                        v-model="newComment"
                        placeholder="Write a comment…"
                        rows="3"
                        @keydown.ctrl.enter="submitComment"
                    />
                    <div class="compose-actions">
                        <span class="hint">Ctrl + Enter to send</span>
                        <button @click="submitComment" :disabled="!newComment.trim()">
                            <i class="fa fa-paper-plane"></i> Post
                        </button>
                    </div>
                </div>

                <div v-else class="login-nudge">
                    <i class="fa fa-lock"></i>
                    Please <a href="/login">log in</a> to comment.
                </div>

                <!-- List -->
                <div class="comments-list" v-if="comments.length">
                    <div v-for="comment in comments" :key="comment.id" class="comment-item">
                        <div class="comment-avatar">{{ (comment.user?.name || 'G')[0].toUpperCase() }}</div>
                        <div class="comment-content">
                            <div class="comment-meta">
                                <strong>{{ comment.user?.name || 'Guest' }}</strong>
                                <span v-if="comment.created_at">{{ formatTime(comment.created_at) }}</span>
                            </div>
                            <p>{{ comment.body }}</p>
                        </div>
                    </div>
                </div>

                <div v-else class="empty-state">
                    <i class="fa fa-comments"></i>
                    <p>No comments yet. Be the first!</p>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            comments: [],
            newComment: '',
            user: null
        };
    },
    mounted() {
        this.fetchComments();
        this.fetchUser();
    },
    methods: {
        fetchComments() {
            axios.get('/comments').then(res => this.comments = res.data);
        },
        fetchUser() {
            axios.get('/user').then(res => this.user = res.data).catch(() => this.user = null);
        },
        submitComment() {
            if (!this.newComment.trim()) return;
            axios.post('/comments', { body: this.newComment }).then(res => {
                this.comments.unshift(res.data);
                this.newComment = '';
            });
        },
        formatTime(dateStr) {
            const diff = Math.floor((new Date() - new Date(dateStr)) / 1000);
            if (diff < 60) return 'just now';
            if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            return new Date(dateStr).toLocaleDateString('en', { month: 'short', day: 'numeric' });
        }
    }
};
</script>

<style scoped>
.comments-section {
    padding: 60px 16px;
    width: 100%;
    box-sizing: border-box;
}

.comments-card {
    max-width: 780px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(66, 13, 101, 0.10);
    overflow: hidden;
}

/* Header */
.card-header {
    background: #420d65;
    padding: 20px 32px;
}

.card-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 10px;
}

.badge {
    background: rgba(255,255,255,0.2);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 99px;
}

/* Body */
.card-body {
    padding: 28px 32px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

/* Compose */
.compose textarea {
    width: 100%;
    border: 1.5px solid #e2d5ee;
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 0.95rem;
    font-family: inherit;
    color: #2d0845;
    background: #faf8fc;
    outline: none;
    resize: none;
    line-height: 1.55;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    -webkit-appearance: none;
}

.compose textarea:focus {
    border-color: #7c28a8;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(124, 40, 168, 0.10);
}

.compose textarea::placeholder { color: #b09ec0; }

.compose-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
}

.hint {
    font-size: 0.75rem;
    color: #b09ec0;
}

.compose-actions button {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 10px 22px;
    background: #420d65;
    color: #fff;
    border: none;
    border-radius: 9px;
    font-size: 0.88rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 3px 10px rgba(66,13,101,0.22);
}

.compose-actions button:hover:not(:disabled) {
    background: #6a1fa0;
    transform: translateY(-1px);
}

.compose-actions button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Login nudge */
.login-nudge {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f5edf9;
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 0.92rem;
    color: #5a3a72;
}

.login-nudge .fa { color: #8a2caf; }

.login-nudge a {
    color: #420d65;
    font-weight: 700;
    text-decoration: underline;
}

/* Comments list */
.comments-list {
    display: flex;
    flex-direction: column;
    max-height: 480px;
    overflow-y: auto;
    border-top: 1px solid #ede0f5;
    padding-top: 4px;
}

.comments-list::-webkit-scrollbar { width: 4px; }
.comments-list::-webkit-scrollbar-track { background: transparent; }
.comments-list::-webkit-scrollbar-thumb { background: #d8c0ec; border-radius: 4px; }

.comment-item {
    display: flex;
    gap: 13px;
    padding: 16px 0;
    border-bottom: 1px solid #f0e8f8;
}

.comment-item:last-child { border-bottom: none; }

.comment-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6a1fa0, #420d65);
    color: #fff;
    font-size: 0.9rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.comment-content { flex: 1; min-width: 0; }

.comment-meta {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-bottom: 4px;
}

.comment-meta strong {
    font-size: 0.9rem;
    color: #2d0845;
}

.comment-meta span {
    font-size: 0.75rem;
    color: #b09ec0;
}

.comment-content p {
    margin: 0;
    font-size: 0.93rem;
    color: #3d2455;
    line-height: 1.6;
}

/* Empty state */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #c0a8d8;
    border-top: 1px solid #ede0f5;
}

.empty-state .fa { font-size: 2rem; display: block; margin-bottom: 10px; }
.empty-state p { margin: 0; font-size: 0.9rem; }

/* Mobile */
@media (max-width: 540px) {
    .comments-section { padding: 24px 12px 40px; }
    .card-header { padding: 16px 20px; }
    .card-header h2 { font-size: 1.1rem; }
    .card-body { padding: 20px 16px; gap: 18px; }
    .compose textarea { font-size: 1rem; }
    .hint { display: none; }
    .compose-actions button { padding: 10px 16px; }
    .comments-list { max-height: 340px; }
    .comment-avatar { width: 32px; height: 32px; font-size: 0.8rem; }
}
</style>
