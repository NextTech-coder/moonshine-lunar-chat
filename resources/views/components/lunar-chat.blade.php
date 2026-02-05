@props([
    'messages' => [],
    'isPrivate' => false,
    'channel' => null,
    'title' => '',
    'placeholder' => '',
])

<x-moonshine::card x-data="lunarChat()">

<div class="text-2xl font-bold mb-6 text-white flex items-center gap-3">
        <x-moonshine::icon icon='chat-bubble-left-right' />
        <x-moonshine::title>
            {{ $title }}
        </x-moonshine::title>
    </div>

    <div x-ref="messagesContainer" style="height:100vh; overflow-y:auto;" class="space-y-4 mb-6 pr-2">
        <template x-for="(message, index) in messages" :key="index">
            <div :data-message-id="message.id">
                <template x-if="message.label">
                    <x-moonshine::title :h="4" class="text-center" x-text="message.label"></x-moonshine::title>
                </template>
                <template x-if="!message.label">
                    <div class="flex items-end gap-3 mb-4" :class="message.sent ? 'justify-end' : ''">

                        <template x-if="!message.sent && message.avatar">
                            <img :src="message.avatar" :alt="message.author" class="w-10 h-10 rounded-full">
                        </template>

                        <div class="flex flex-col max-w-[70%]" :class="message.sent ? 'items-end' : 'items-start'">

                            <template x-if="message.author">
                                <div class="text-xs text-gray-500 mb-2 px-3" x-text="message.author"></div>
                            </template>

                            <template x-for="(block, blockIndex) in message.blocks" :key="blockIndex">
                                <div class="chat-bubble"
                                     :class="[
                                                 message.sent ? 'chat-bubble--sent' : 'chat-bubble--other',
                                                 blockIndex === message.blocks.length - 1 ? 'chat-bubble--last' : ''
                                             ]">
                                    <template x-for="(content, contentIndex) in block.contents" :key="contentIndex">
                                        <p x-text="content"></p>
                                    </template>
                                </div>
                            </template>

                            <template x-if="message.time">
                                <div class="text-xs text-gray-400 mt-1 px-3" x-text="message.time"></div>
                            </template>
                        </div>

                        <template x-if="message.sent && message.avatar">
                            <img :src="message.avatar" :alt="message.author" class="w-10 h-10 rounded-full">
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>



    <form
        x-on:submit.prevent="sendMessage"
        class="w-full flex gap-2 items-center"
    >
        @csrf
        <x-moonshine::form.textarea
            x-model="clientMessage"
            name="clientMessage"
            rows="1"
            placeholder="{{ $placeholder }}"
            autosize
            class="flex-1"
        />

        <x-moonshine::form.button
            type="submit"
            x-bind:disabled="sending || !clientMessage.trim()"
        >
            <x-moonshine::icon icon="paper-airplane" />
        </x-moonshine::form.button>

    </form>

</x-moonshine::card>

<style>
    .chat-bubble {
        position: relative;
        padding: 0.75rem 1rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        line-height: 1.5;
        max-width: 100%;
        margin-bottom: 5px;

        background: var(--color-base-100);
        color: var(--color-base-text);
        border: 1px solid var(--color-base-stroke);
    }

    .chat-bubble--sent {
        background: var(--color-primary);
        color: var(--color-primary-text);
        border-bottom-right-radius: 0.35rem;
    }

    .chat-bubble--sent.chat-bubble--last::after {
        content: '';
        position: absolute;
        right: -6px;
        bottom: 10px;
        border-left: 6px solid var(--color-primary);
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
    }

    .chat-bubble--other {
        background: var(--color-base);
    }

    .chat-bubble--other.chat-bubble--last::before {
        content: '';
        position: absolute;
        left: -6px;
        bottom: 10px;
        border-right: 6px solid var(--color-base-stroke);
        border-top: 6px solid transparent;
        border-bottom: 6px solid transparent;
    }

    .chat-new-message {
        animation: pulse 1.5s infinite;
    }

    .message-enter {
        opacity: 0;
        transform: translateY(10px);
    }

    .message-enter-active {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .chat-new-message {
        animation: messagePulse 1.5s ease-in-out;
    }

    @keyframes messagePulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(59, 130, 246, 0.15);
        }
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('lunarChat', () => ({
            sending: false,
            clientMessage: '',
            messages: {!! @json_encode($messages) !!},
            channel: '{{ $channel ?? "" }}',
            isPrivate: {{ !empty($isPrivate) ? 'true' : 'false' }},
            userId: {{ auth()->id() ?? 'null' }},
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

                    if (lastMsg) {
                        lastMsg.classList.add('message-enter');

                        setTimeout(() => {
                            lastMsg.classList.add('message-enter-active');
                        }, 10);

                        setTimeout(() => {
                            lastMsg.classList.remove('message-enter', 'message-enter-active');
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

</script>
