@props([
    'messages' => [],
])

<x-moonshine::layout>
    <x-moonshine::layout.content>
        <x-moonshine::card>

            <div class="text-2xl font-bold mb-6 text-white flex items-center gap-3">
                <x-moonshine::icon icon='chat-bubble-left-right' />
                <x-moonshine::title>
                    Чат
                </x-moonshine::title>
            </div>

            <div class="space-y-4 mb-6 max-h-[600px] overflow-y-auto pr-2">

                @if ($messages)
                    @foreach ($messages as $index => $message)
                        <x-moonshine-chat::lunar-chat-message :sent="$message['sent']" :avatar="$message['avatar']" :author="$message['author']"
                            :blocks="$message['blocks']" :time="$message['time']" :is-last="$index === count($messages) - 1" />
                    @endforeach
                @else
                    Начините новый чат!!!!
                @endif


            </div>

            <form method="POST" class="w-full flex gap-2 items-center">
                @csrf
                <x-moonshine::form.textarea name="message" rows="1" placeholder="Введите сообщение..." autosize
                    class="flex-1" />

                <x-moonshine::form.button>
                    <x-moonshine::icon icon='paper-airplane' />
                </x-moonshine::form.button>
            </form>

        </x-moonshine::card>
    </x-moonshine::layout.content>
</x-moonshine::layout>
