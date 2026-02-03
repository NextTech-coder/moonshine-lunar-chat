"![Lunar Chat](https://banners.beyondco.de/Lunar%20Chat.png?theme=dark&packageManager=&packageName=&pattern=kiwi&style=style_1&description=Chat+UI+components+for+MoonShine+4&md=1&showWatermark=0&fontSize=100px&images=moon)

## Message structure

```php
array<int, ChatMessage>
```

| Field  |          Type           | Required | Description                      |
|--------|:-----------------------:|----------|----------------------------------|
| sent   |          bool           |   yes    | Message was sent by current user |
| author |         string          |   yes    | Message author name              |
| time   |         string          |   yes    | Message time (e.g. 14:32)        |
| blocks | array<int, ChatMessage> |   yes    | Content blocks                   |
| avatar |         string          |  no      |  Author avatar URL               |

```
[
    [
        'sent' => true,
        'author' => 'Дональд Трамп',
        'time' => now()->format('H:i'),
        'blocks' => [
            [
                'type' => 'paragraph',
                'contents' => [
                    'Привет, Владимир! Как дела сегодня?',
                ],
            ],
        ],
        'avatar' => 'https://i.pravatar.cc/150?img=5',
    ],
    [
        'sent' => false,
        'author' => 'Владимир Путин',
        'time' => now()->addMinute()->format('H:i'),
        'blocks' => [
            [
                'type' => 'paragraph',
                'contents' => [
                    'Привет, Дональд. Всё в порядке, спасибо. А у тебя?',
                ],
            ],
        ],
        'avatar' => 'https://i.pravatar.cc/150?img=6',
    ],
];
```

Multiple blocks per message

One message may contain multiple blocks rendered in order.

```
[
    'sent' => true,
    'author' => 'Дональд Трамп',
    'time' => now()->format('H:i'),
    'blocks' => [
        [
            'type' => 'paragraph',
            'contents' => ['У меня тоже всё отлично.'],
        ],
        [
            'type' => 'paragraph',
            'contents' => ['Сегодня куча встреч.'],
        ],
    ],
]
```
