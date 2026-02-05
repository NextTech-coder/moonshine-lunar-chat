<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use MoonShine\UI\Components\MoonShineComponent;

final class LunarChat extends MoonShineComponent
{
    protected string $title = '';
    protected string $placeholder = '';
    protected ?string $action = null;
    protected ?string $channel = null;
    public bool $isPrivate = false;

    protected string $view = 'moonshine-chat::components.lunar-chat';

    public function __construct(
        public array $messages = [],
    ) {
        parent::__construct();
    }

    public function action(string $name): self
    {
        $this->action = $name;

        return $this;
    }

    public function channel(string $name): self
    {
        $this->channel = $name;

        return $this;
    }

    public function private(): self
    {
        $this->isPrivate = true;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    protected function viewData(): array
    {
        return [
            'messages' => $this->messages,
            'channel' => $this->channel,
            'isPrivate' => $this->isPrivate,
            'action' => $this->action,
            'title' => $this->title,
            'placeholder' => $this->placeholder,
        ];
    }
}
