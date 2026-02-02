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
                <x-moonshine-chat::lunar-chat-message :avatar="'https://i.pravatar.cc/150?img=5'" author="Jane" text="doing fine, how r you?"
                    time="4 minutes ago" />

                <x-moonshine-chat::lunar-chat-message :mine="true" :avatar="'https://i.pravatar.cc/150?img=12'" author="me"
                    text="hey, how are you?" time="7 minutes ago" />

                <x-moonshine-chat::lunar-chat-message :avatar="'https://i.pravatar.cc/150?img=5'" author="Jane"
                    text="I've been working on this new project with MoonShine and Tailwind. The combination is really powerful! 🚀"
                    time="2 minutes ago" />

                <x-moonshine-chat::lunar-chat-message :mine="true" :avatar="'https://i.pravatar.cc/150?img=12'" author="me"
                    text="That sounds amazing! I'd love to see what you've built. Could you share some screenshots?"
                    time="just now" />
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
