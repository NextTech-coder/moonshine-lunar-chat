document.addEventListener('alpine:init', () => {
    Alpine.data('lunarChat', () => ({
        init() {
            this.scrollToBottom();
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    }));
})
