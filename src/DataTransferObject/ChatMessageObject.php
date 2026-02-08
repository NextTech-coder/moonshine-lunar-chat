<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\DataTransferObject;

final readonly class ChatMessageObject
{
    /**
     * @param int $id
     * @param int $authorId
     * @param bool $sent
     * @param array $blocks
     * @param string|null $time
     * @param string|null $author
     * @param string|null $avatar
     * @param string|null $label
     */
    public function __construct(
        public int    $id,
        public int    $authorId,
        public bool   $sent,
        public array  $blocks,
        public ?string $time = null,
        public ? string $author = null,
        public ?string $avatar = null,
        public ?string $label = null,
    ) {
        // ..
    }
}
