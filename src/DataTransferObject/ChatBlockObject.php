<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\DataTransferObject;

final readonly class ChatBlock
{
    /**
     * @param list<string> $contents
     */
    public function __construct(
        public string $type,
        public array  $contents,
    ) {
        // ..
    }
}
