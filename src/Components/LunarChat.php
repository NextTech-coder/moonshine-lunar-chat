<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use MoonShine\Contracts\UI\ComponentAttributesBagContract;
use MoonShine\UI\Components\MoonShineComponent;

final class LunarChat extends MoonShineComponent
{
    protected string $view = 'moonshine-chat::components.lunar-chat';

    public function __construct(
        public string $text,
        public bool $mine = false,
        public ?string $author = null,
        public ?string $time = null,
        public ?string $avatar = null,
    ) {
        parent::__construct();
    }

    public function getAttributes(): ComponentAttributesBagContract
    {
        return parent::getAttributes();
    }
}
