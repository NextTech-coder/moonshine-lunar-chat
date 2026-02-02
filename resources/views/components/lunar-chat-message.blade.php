@props([
    'sent' => false,
    'avatar' => null,
    'author' => null,
    'blocks' => [],
    'time' => null,
])

<div class="flex items-end gap-3 mb-4 {{ $sent ? 'justify-end' : '' }}">
    @if (!$sent && $avatar)
        <x-moonshine-chat::lunar-avatar :avatar="$avatar" :alt="$author" />
    @endif

    <div class="flex flex-col {{ $sent ? 'items-end' : 'items-start' }} max-w-[70%]">
        @if ($author)
            <div class="text-xs text-gray-500 mb-1 px-3">
                {{ $author }}
            </div>
        @endif

        @foreach ($blocks as $block)
            @switch($block['type'])
                @case('paragraph')
                    @foreach ($block['contents'] as $text)
                        <div class="chat-bubble {{ $sent ? 'chat-bubble--sent' : 'chat-bubble--other' }}">
                            {{ $text }}
                        </div>
                    @endforeach
                @break
            @endswitch
        @endforeach

        @if ($time)
            <div class="text-xs text-gray-400 mt-1 px-3">
                {{ $time }}
            </div>
        @endif
    </div>

    @if ($sent && $avatar)
        <x-moonshine-chat::lunar-avatar :avatar="$avatar" :alt="$author" />
    @endif
</div>

@once
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
@endonce
