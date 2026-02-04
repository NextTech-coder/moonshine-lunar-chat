<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use MoonShine\UI\Components\MoonShineComponent;

final class LunarChat extends MoonShineComponent
{
    public ?string $action = null;

    protected string $view = 'moonshine-chat::components.lunar-chat';

    public function __construct(
        public array $messages = [],
    ) {
        parent::__construct();
    }

    public function action(string $name): LunarChat
    {
        $this->action = $name;

        return $this;
    }

    protected function viewData(): array
    {
        return [
            'messages' => $this->messages,
        ];
    }
}
