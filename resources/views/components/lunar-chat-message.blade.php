@props([
    'mine' => false,
    'avatar' => null,
    'author' => null,
    'text' => '',
    'time' => null,
])

<div class="flex items-end gap-3 {{ $mine ? 'justify-end' : '' }} mb-4 animate-fade-in">
    @if (!$mine && $avatar)
        <img src="{{ $avatar }}" alt="{{ $author }}"
            class="w-10 h-10 rounded-full ring-2 flex-shrink-0 shadow-md"
            style="ring-color: var(--primary-rgb, rgba(120, 67, 230, 0.2));">
    @endif

    <div class="flex flex-col {{ $mine ? 'items-end' : 'items-start' }} max-w-[70%]">
        @if ($author)
            <span class="text-xs mb-1 {{ $mine ? 'mr-3' : 'ml-3' }} font-medium"
                style="color: var(--secondary-color, rgba(156, 163, 175, 1));">
                {{ $author }}
            </span>
        @endif

        <div class="relative group">
            <div class="chat-message-bubble {{ $mine ? 'chat-message-mine' : 'chat-message-other' }}">
                <p class="text-sm leading-relaxed break-words">{{ $text }}</p>
            </div>

            @if ($time)
                <span class="text-xs mt-1 {{ $mine ? 'mr-3' : 'ml-3' }} block"
                    style="color: var(--secondary-color, rgba(107, 114, 128, 1));">
                    {{ $time }}
                </span>
            @endif
        </div>
    </div>

    @if ($mine && $avatar)
        <img src="{{ $avatar }}" alt="{{ $author }}"
            class="w-10 h-10 rounded-full ring-2 flex-shrink-0 shadow-md"
            style="ring-color: var(--primary-rgb, rgba(120, 67, 230, 0.2));">
    @endif
</div>

@once
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .chat-message-bubble {
            color: white;
            border-radius: 1rem;
            padding: 0.75rem 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            backdrop-filter: blur(4px);
        }

        .chat-message-mine {
            background: var(--primary-color, linear-gradient(135deg, #7843e6 0%, #6d28d9 100%));
            border-bottom-right-radius: 0.25rem;
            box-shadow: 0 4px 6px -1px var(--primary-rgb, rgba(120, 67, 230, 0.2));
        }

        .chat-message-other {
            background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);
            border-bottom-left-radius: 0.25rem;
            box-shadow: 0 4px 6px -1px rgba(147, 51, 234, 0.3);
        }

        .chat-message-bubble:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }
    </style>
@endonce
