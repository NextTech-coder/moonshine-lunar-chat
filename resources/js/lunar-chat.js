document.addEventListener('alpine:init', () => {
    Alpine.data('lunarChat', (messages, config) => ({
        sending: false,
        clientMessage: '',
        messages: messages,
        channel: config.channel,
        isPrivate: config.isPrivate,
        userId: config.userId,
        echoInstance: null,
        lastMessageId: null,

        init() {
        this.setupEcho();
        this.$nextTick(() => {
            this.scrollToBottom();
        });
    },

    setupEcho() {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: 'vubkg9ccrgssnkjt7w6d',
            wsHost: '127.0.0.1',
            wsPort: 8080 ?? 80,
            wssPort: 8080 ?? 443,
            forceTLS: ('http' ?? 'https') === 'https',
            enabledTransports: ['ws', 'wss'],
        });

        if (typeof window.Echo !== 'undefined') {
            this.subscribe()
        }
    },

    subscribe() {
        const channelName = this.isPrivate ? `private-${this.channel}` : this.channel;

        this.echoInstance = this.isPrivate ? window.Echo.private(channelName) : window.Echo.channel(channelName);

        this.echoInstance.listen('.chat.message.received', (message) => {
            if (message.sent && message.author_id === this.userId) {
                return;
            }

            this.handleIncomingMessage(message);
        });
    },

    handleIncomingMessage(message) {
        message.id = Date.now() + Math.random();
        this.messages.push(message);

        this.$nextTick(() => {
            const lastMsg = this.$el.querySelector(`[data-message-id="${message.id}"]`);
            this.scrollToBottom();

            if (lastMsg) {
                lastMsg.classList.add('chat-new-message');
            }
        });
    },

    scrollToBottom() {
        this.$nextTick(() => {
            this.$refs.messagesContainer.scrollTop = this.$refs.messagesContainer.scrollHeight;
        });
    },

    async sendMessage() {
        if (!this.clientMessage.trim() || this.sending) {
            return
        }

        this.sending = true

        try {
            const response = await fetch('{{ $action }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: this.clientMessage }),
            })

            if (!response.ok) {
                throw new Error('Request failed')
            }

            const message = await response.json();

            this.messages.push(message);
            this.clientMessage = '';

            this.scrollToBottom();
        } catch (e) {
            console.error(e)
        } finally {
            this.sending = false
        }
    },
}));
});
