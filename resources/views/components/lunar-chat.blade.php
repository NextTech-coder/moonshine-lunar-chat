@props([
    'messages' => [],
])

<x-moonshine::card x-data="lunarChat()">

    <div class="text-2xl font-bold mb-6 text-white flex items-center gap-3">
        <x-moonshine::icon icon='chat-bubble-left-right' />
        <x-moonshine::title>
            Чат
        </x-moonshine::title>
    </div>

    <div x-ref="messagesContainer" style="max-height:500px; overflow-y:auto;" class="space-y-4 mb-6 pr-2">
        <template x-for="(message, index) in messages" :key="index">
            <div>
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
            placeholder="Введите сообщение..."
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
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('lunarChat', () => ({
            sending: false,
            clientMessage: '',
            messages: {!! @json_encode($messages) !!},

            init() {
                this.$nextTick(() => {
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

                    const data = await response.json();
                    this.messages = data.messages;
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
