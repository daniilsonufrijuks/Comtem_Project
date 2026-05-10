<template>
    <section class="chat-section">
        <div class="chat-card">

            <!-- Header -->
            <div class="chat-header">
                <div class="header-left">
                    <div class="ai-dot"></div>
                    <h2>COMTEM AI</h2>
                </div>
                <span class="status-badge">Online</span>
            </div>

            <!-- Messages -->
            <div class="chat-box" ref="chatBox">
                <div class="welcome" v-if="chatMessages.length === 0">
                    <i class="fa fa-robot"></i>
                    <p>Hi! Ask me anything about COMTEM.</p>
                </div>

                <div
                    v-for="(message, index) in chatMessages"
                    :key="index"
                    :class="['message-row', message.sender]"
                >
                    <div class="bubble">
                        <div v-if="message.sender === 'ai'" v-html="formatMessage(message.text)" class="message-text"></div>
                        <p v-else>{{ message.text }}</p>
                    </div>
                </div>

                <div v-if="isLoading" class="message-row ai">
                    <div class="bubble typing">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            </div>

            <!-- Input -->
            <form @submit.prevent="sendMessage" class="chat-input">
                <input
                    type="text"
                    placeholder="Ask me anything…"
                    v-model="userInput"
                    :disabled="isLoading"
                    aria-label="Chat input"
                />
                <button type="submit" :disabled="isLoading || !userInput.trim()" aria-label="Send message">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>

        </div>
    </section>
</template>

<script>
import { marked } from "marked";

export default {
    name: "ChatWithAI",
    data() {
        return {
            userInput: "",
            chatMessages: [],
            isLoading: false,
        };
    },
    methods: {
        async sendMessage() {
            if (!this.userInput.trim()) return;
            if (this.userInput.length > 500) {
                alert("Message is too long. Please limit to 500 characters.");
                return;
            }

            this.chatMessages.push({ sender: "user", text: this.userInput });
            const input = this.userInput;
            this.userInput = "";
            this.scrollToBottom();
            this.isLoading = true;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            try {
                const response = await fetch("/chatai", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                    },
                    body: JSON.stringify({ message: input }),
                });

                const contentType = response.headers.get("content-type");
                let data;
                if (contentType && contentType.includes("application/json")) {
                    data = await response.json();
                } else {
                    const text = await response.text();
                    console.error("Non-JSON response:", text);
                    throw new Error("Server returned invalid format. Please try again.");
                }

                if (!response.ok) throw new Error(data.reply || data.message || `Server error: ${response.status}`);

                this.chatMessages.push({
                    sender: "ai",
                    text: data.reply || "I couldn't process your request. Try again.",
                });
            } catch (error) {
                console.error("Error:", error);
                this.chatMessages.push({
                    sender: "ai",
                    text: error.message || "Sorry, I'm having trouble connecting. Please try again later.",
                });
            } finally {
                this.isLoading = false;
                this.scrollToBottom();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const box = this.$refs.chatBox;
                if (box) box.scrollTop = box.scrollHeight;
            });
        },
        formatMessage(text) {
            return text ? marked.parse(text) : "";
        },
    },
};
</script>

<style scoped>
.chat-section {
    padding: 60px 16px;
    width: 100%;
    box-sizing: border-box;
}

/* Card */
.chat-card {
    max-width: 1100px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(66, 13, 101, 0.10);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 600px; /* fixed total height — only chat-box scrolls */
}

/* Header */
.chat-header {
    background: #420d65;
    padding: 18px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.ai-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #6dff9a;
    box-shadow: 0 0 6px #6dff9a;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50%       { opacity: 0.4; }
}

.chat-header h2 {
    margin: 0;
    font-size: 1.15rem;
    font-weight: 700;
    color: #fff;
    letter-spacing: 0.02em;
}

.status-badge {
    font-size: 0.72rem;
    font-weight: 700;
    color: #6dff9a;
    background: rgba(109, 255, 154, 0.12);
    border: 1px solid rgba(109, 255, 154, 0.3);
    padding: 3px 10px;
    border-radius: 99px;
    letter-spacing: 0.04em;
}

/* Chat box */
.chat-box {
    flex: 1;
    min-height: 0; /* critical — lets flexbox shrink it so it scrolls internally */
    overflow-y: auto;
    padding: 24px 24px 12px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    background: #faf8fc;
    scroll-behavior: smooth;
}

.chat-box::-webkit-scrollbar { width: 4px; }
.chat-box::-webkit-scrollbar-track { background: transparent; }
.chat-box::-webkit-scrollbar-thumb { background: #d8c0ec; border-radius: 4px; }

/* Welcome */
.welcome {
    margin: auto;
    text-align: center;
    color: #c0a8d8;
    padding: 40px 20px;
}
.welcome .fa { font-size: 2.4rem; display: block; margin-bottom: 12px; }
.welcome p { font-size: 0.92rem; margin: 0; }

/* Message rows */
.message-row {
    display: flex;
    align-items: flex-end;
}

.message-row.user { justify-content: flex-end; }
.message-row.ai   { justify-content: flex-start; }

/* Bubbles */
.bubble {
    max-width: 72%;
    padding: 11px 16px;
    border-radius: 16px;
    font-size: 0.92rem;
    line-height: 1.6;
    word-break: break-word;
}

.bubble p { margin: 0; }

.message-row.user .bubble {
    background: #420d65;
    color: #fff;
    border-bottom-right-radius: 4px;
}

.message-row.ai .bubble {
    background: #fff;
    color: #2d0845;
    border: 1px solid #ede0f5;
    border-bottom-left-radius: 4px;
    box-shadow: 0 1px 4px rgba(66,13,101,0.07);
}

/* Typing indicator */
.typing {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 14px 18px;
}

.typing span {
    display: block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    background: #b09ec0;
    animation: bounce 1.2s infinite;
}
.typing span:nth-child(2) { animation-delay: 0.2s; }
.typing span:nth-child(3) { animation-delay: 0.4s; }

@keyframes bounce {
    0%, 80%, 100% { transform: translateY(0); }
    40%           { transform: translateY(-6px); }
}

/* Markdown content inside AI bubbles */
.message-text { white-space: normal; }
.message-text h3 { margin: 0.5rem 0; font-size: 1rem; color: #2d0845; }
.message-text strong { font-weight: 700; }
.message-text em { font-style: italic; }
.message-text ul, .message-text ol { padding-left: 1.4rem; margin: 0.4rem 0; }
.message-text p { margin: 0.3rem 0; }
.message-text p:first-child { margin-top: 0; }
.message-text p:last-child  { margin-bottom: 0; }
.message-text code {
    background: #f0e8f8;
    border-radius: 4px;
    padding: 1px 5px;
    font-family: monospace;
    font-size: 0.85em;
    color: #5c1a85;
}
.message-text pre {
    background: #f0e8f8;
    border-radius: 8px;
    padding: 10px 14px;
    overflow-x: auto;
    margin: 0.5rem 0;
}

/* Input bar */
.chat-input {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 20px;
    border-top: 1px solid #ede0f5;
    background: #fff;
    flex-shrink: 0;
}

.chat-input input {
    flex: 1;
    border: 1.5px solid #e2d5ee;
    border-radius: 10px;
    padding: 11px 16px;
    font-size: 0.95rem;
    font-family: inherit;
    color: #2d0845;
    background: #faf8fc;
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    -webkit-appearance: none;
    min-width: 0;
}

.chat-input input:focus {
    border-color: #7c28a8;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(124, 40, 168, 0.10);
}

.chat-input input::placeholder { color: #b09ec0; }

.chat-input input:disabled { opacity: 0.6; }

.chat-input button {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #420d65;
    color: #fff;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 3px 10px rgba(66,13,101,0.22);
}

.chat-input button:hover:not(:disabled) {
    background: #6a1fa0;
    transform: translateY(-1px);
}

.chat-input button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Mobile */
@media (max-width: 540px) {
    .chat-section { padding: 24px 12px 40px; }

    .chat-header { padding: 14px 18px; }
    .chat-header h2 { font-size: 1rem; }

    .chat-card {
        height: 520px;
    }

    .chat-box {
        padding: 16px 14px 10px;
    }

    .bubble { max-width: 88%; font-size: 0.9rem; }

    .chat-input { padding: 12px 14px; gap: 8px; }

    .chat-input input { font-size: 1rem; padding: 10px 13px; } /* prevent iOS zoom */

    .chat-input button { width: 40px; height: 40px; font-size: 0.9rem; }
}
</style>
