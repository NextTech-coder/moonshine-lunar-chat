<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Collections;


use Illuminate\Support\Collection;
use NextTech\MoonShineLunarChat\DataTransferObject\ChatBlockObject;
use NextTech\MoonShineLunarChat\DataTransferObject\ChatMessageObject;

/**
 * @extends \Illuminate\Support\Collection<int, \NextTech\MoonShineLunarChat\DataTransferObject\ChatMessageObject>
 */
final class ChatMessageCollection extends Collection
{
    /**
     * @param \NextTech\MoonShineLunarChat\DataTransferObject\ChatMessageObject $message
     *
     * @return $this
     */
    public function addMessage(ChatMessageObject $message): self
    {
        $this->items[] = $message;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $collection = new self();

        foreach ($data as $item) {

            if (isset($item['id'], $item['author_id'], $item['blocks'], $item['author'], $item['time'])) {
                $blocks = array_map(fn($b) => new ChatBlockObject($b['type'], $b['contents']), $item['blocks']);

                $collection->addMessage(new ChatMessageObject(
                    id: $item['id'],
                    authorId: $item['author_id'],
                    sent: $item['sent'] ?? false,
                    blocks: $blocks,
                    time: $item['time'],
                    author: $item['author'],
                    avatar: $item['avatar'] ?? null,
                ));

                continue;
            }

            if (isset($item['label'])) {
                $collection->addMessage(new ChatMessageObject(
                    id: 0,
                    authorId: 0,
                    sent: false,
                    blocks: [],
                    time: now()->format('H:i'),
                    author: '',
                    avatar: null,
                    label: $item['label'],
                ));
            }
        }

        return $collection;
    }

    /**
     * @return \NextTech\MoonShineLunarChat\DataTransferObject\ChatMessageObject[]
     */
    public function all(): array
    {
        return parent::all();
    }
}
