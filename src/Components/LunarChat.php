<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use Illuminate\View\ComponentSlot;
use MoonShine\Contracts\UI\ComponentAttributesBagContract;
use MoonShine\UI\Components\MoonShineComponent;

final class LunarChat extends MoonShineComponent
{
    protected string $view = 'moonshine-chat::components.lunar-chat';

    public function __construct(
        public array $messages,
    )
    {
        parent::__construct();
    }

    protected function viewData(): array
    {
        return [
            'messages' => $this->messages,
        ];
    }
}
