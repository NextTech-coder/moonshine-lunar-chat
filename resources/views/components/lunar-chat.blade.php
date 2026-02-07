@props([
    'channel' => null,
    'isPrivate' => false,
    'messages' => [],
    'placeholder' => '',
    'title' => '',
    'userId' => null,
])

<x-moonshine::card
    x-data="lunarChat($el.dataset)"
    data-action="{{ $action }}"
    data-channel="{{ $channel }}"
    data-messages='{{ json_encode($messages) }}'
    data-private="{{ $isPrivate }}"
    data-user-id="{{ $userId }}"
>

<div class="text-2xl font-bold mb-6 text-white flex items-center gap-3">
        <x-moonshine::icon icon='chat-bubble-left-right' />
        <x-moonshine::title>
            {{ $title }}
        </x-moonshine::title>
    </div>

    <div x-ref="messagesContainer" class="lunar-chat-container space-y-4 mb-6 pr-2">
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
                                <div class="lunar-chat-bubble"
                                     :class="[
                                                 message.sent ? 'lunar-chat-bubble--sent' : 'lunar-chat-bubble--other',
                                                 blockIndex === message.blocks.length - 1 ? 'lunar-chat-bubble--last' : ''
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
