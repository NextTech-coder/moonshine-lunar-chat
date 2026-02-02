<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use MoonShine\Contracts\UI\ComponentAttributesBagContract;
use MoonShine\UI\Components\MoonShineComponent;

final class LunarChat extends MoonShineComponent
{
    protected string $view = 'moonshine-chat::components.lunar-chat';

    public function __construct(
        public array $messages,
    ) {
        parent::__construct();
    }

    public function getAttributes(): ComponentAttributesBagContract
    {
        return parent::getAttributes();
    }
}
