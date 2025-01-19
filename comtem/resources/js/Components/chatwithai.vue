<template>
    <div class="chat-container">
        <h2>Chat with AI</h2>
        <div class="chat-box" ref="chatBox">
            <div v-for="(message, index) in chatMessages" :key="index" :class="['chat-message', message.sender]">
                <p>{{ message.text }}</p>
            </div>
            <p v-if="isLoading" class="loading-message">AI is typing...</p>
        </div>
        <form @submit.prevent="sendMessage" class="chat-input">
            <input
                type="text"
                v-model="userInput"
                placeholder="Ask about PC components..."
                required
                aria-label="Chat input"
            />
            <button type="submit" :disabled="isLoading" aria-label="Send message">Send</button>
        </form>
    </div>
</template>

<script>
export default {
    name: "ChatWithAI",
    data() {
        return {
            userInput: "",
            chatMessages: [
                { sender: "ai", text: "Hello! Ask me anything about PC components." },
            ],
            isLoading: false,
        };
    },
    methods: {
        async sendMessage() {
            if (!this.userInput.trim()) return;

            // Add user message to chat
            this.chatMessages.push({ sender: "user", text: this.userInput });

            // Store the input to send to the backend
            const input = this.userInput;
            this.userInput = ""; // Clear input field

            // Scroll chat box to the bottom
            this.scrollToBottom();

            // Set loading state
            this.isLoading = true;

            // Retrieve CSRF token from the meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

            // Call backend API for AI response
            try {
                const response = await fetch("/chatai", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({ message: input }),
                });

                // Check if the response is JSON
                const contentType = response.headers.get('Content-Type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Expected JSON response, but got ' + contentType);
                }

                const data = await response.json();
                this.chatMessages.push({
                    sender: "ai",
                    text: data.reply || "I couldn't process your request. Try again.",
                });
            } catch (error) {
                console.error("Error fetching AI response:", error);
                this.chatMessages.push({
                    sender: "ai",
                    text: "Sorry, something went wrong. Please try again later.",
                });
            } finally {
                // Remove loading state
                this.isLoading = false;

                // Scroll chat box to the bottom
                this.scrollToBottom();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const chatBox = this.$refs.chatBox;
                if (chatBox) {
                    chatBox.scrollTop = chatBox.scrollHeight;
                }
            });
        },
    },
};
</script>
<style scoped>
.chat-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f9f9f9;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    color: #333;
}

.chat-box {
    max-height: 300px;
    overflow-y: auto;
    margin-bottom: 1rem;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #fff;
}

.chat-message {
    margin-bottom: 0.5rem;
    padding: 0.5rem;
    border-radius: 5px;
}

.chat-message.user {
    background: #d1e7ff;
    text-align: right;
}

.chat-message.ai {
    background: #e9ecef;
    text-align: left;
}

.chat-input {
    display: flex;
    gap: 0.5rem;
}

.chat-input input {
    flex-grow: 1;
    padding: 0.5rem;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.chat-input button {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 5px;
    background: #007bff;
    color: white;
    cursor: pointer;
    transition: background 0.3s ease;
}

.chat-input button:hover {
    background: #0056b3;
}
</style>
