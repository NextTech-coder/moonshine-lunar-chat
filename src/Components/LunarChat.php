<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use MoonShine\AssetManager\Css;
use MoonShine\AssetManager\Js;
use MoonShine\UI\Components\MoonShineComponent;

final class LunarChat extends MoonShineComponent
{
    protected string $title = '';
    protected string $placeholder = '';
    protected ?string $action = null;
    protected ?string $channel = null;
    public bool $isPrivate = false;
    protected ?int $userId = null;

    protected string $view = 'moonshine-chat::components.lunar-chat';

    public function __construct(
        public array $messages = [],
    ) {
        $this->userId = auth()->id();

        parent::__construct();
    }

    public function assets(): array
    {
        return [
            Css::make('/vendor/moonshine-lunar-chat/css/lunar-chat.css'),
            Js::make('/vendor/moonshine-lunar-chat/js/lunar-chat.js'),

            Js::make('https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js'),
            Js::make('https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js'),
        ];
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

    public function user(int $id): self
    {
        $this->userId = $id;

        return $this;
    }

    protected function viewData(): array
    {
        return [
            'action' => $this->action,
            'channel' => $this->channel,
            'isPrivate' => $this->isPrivate,
            'messages' => $this->messages,
            'placeholder' => $this->placeholder,
            'title' => $this->title,
            'userId' => $this->userId,
        ];
    }
}
