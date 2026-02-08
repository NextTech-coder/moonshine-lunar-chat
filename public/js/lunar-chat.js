document.addEventListener('alpine:init', () => {
    Alpine.data('lunarChat', (dataset) => ({
        sending: false,
        clientMessage: '',
        messages: JSON.parse(dataset.messages),
        websocket: JSON.parse(dataset.websocket),
        isPrivate: dataset.private,
        userId: dataset.userId ? Number(dataset.userId) : null,
        echoInstance: null,
        action: dataset.action,

        init() {
            if (this.websocket && this.websocket?.channel && this.websocket?.listen) {
                this.setupEcho();
            }

            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },

        setupEcho() {
            this.subscribe();
        },

        subscribe() {
            try {
                const channelName = this.isPrivate
                    ? `private-${this.websocket.channel}`
                    : this.websocket.channel;

                this.echoInstance = this.isPrivate
                    ? window.Echo.private(channelName)
                    : window.Echo.channel(channelName);

                this.echoInstance.listen(`.${this.websocket.listen}`, (message) => {
                    if (message.sent && message.author_id === this.userId) {
                        return;
                    }

                    this.handleIncomingMessage(message);
                });
            } catch (error) {
                console.error('Subscribe error:', error);

                this.showToast('Failed to subscribe to channel', 'error');
            }
        },

        handleIncomingMessage(message) {
            this.messages.push(message);

            this.$nextTick(() => {
                const lastMsg = this.$el.querySelector(`[data-message-id="${message.id}"]`);

                if (lastMsg) {
                    lastMsg.classList.add('lunar-chat-message-enter');

                    setTimeout(() => {
                        lastMsg.classList.add('lunar-chat-message-enter-active');
                    }, 10);

                    setTimeout(() => {
                        lastMsg.classList.remove('lunar-chat-message-enter', 'lunar-chat-message-enter-active');
                    }, 400);
                }

                this.scrollToBottom();
            });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
            });
        },

        async sendMessage() {
            if (!this.action) {
                this.showToast('Action URL is not configured', 'error');

                return;
            }

            if (!this.clientMessage.trim() || this.sending) {
                return;
            }

            this.sending = true;

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message: this.clientMessage }),
                })

                if (!response.ok) {
                    this.showToast(`Server error: ${response.status}`, 'error');

                    return;
                }

                const message = await response.json();

                this.messages.push(message);
                this.clientMessage = '';

                this.scrollToBottom();
            } catch (error) {
                console.error('Send message error:', error);

                this.showToast(error.message || 'Failed to send message', 'error');
            } finally {
                this.sending = false
            }
        },

        showToast(message, type = 'info') {
            this.$dispatch('toast', {
                type: type,
                text: message,
            });
        }
    }));
});
