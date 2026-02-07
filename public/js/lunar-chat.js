document.addEventListener('alpine:init', () => {
    Alpine.data('lunarChat', (dataset) => ({
        sending: false,
        clientMessage: '',
        messages: JSON.parse(dataset.messages),
        channel: dataset.channel,
        isPrivate: dataset.private,
        userId: dataset.userId ? Number(dataset.userId) : null,
        echoInstance: null,

        init() {
        this.setupEcho();
        this.$nextTick(() => {
            this.scrollToBottom();
        });
    },

    setupEcho() {
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: 'b18j2owqken8yl38jz7d',
            wsHost: '127.0.0.1',
            wsPort: 6001 ?? 80,
            wssPort: 6001 ?? 443,
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

        this.echoInstance.listen('.message', (message) => {
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
