<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Components;

use MoonShine\AssetManager\Css;
use MoonShine\AssetManager\Js;
use MoonShine\UI\Components\MoonShineComponent;
use NextTech\MoonShineLunarChat\Collections\ChatMessageCollection;

final class LunarChat extends MoonShineComponent
{
    protected string $view = 'moonshine-chat::components.lunar-chat';
    public bool $isPrivate = false;
    protected ?string $action = null;
    protected ?array $websocket = null;
    protected ?int $userId = null;

    protected ChatMessageCollection $messages;

    public function __construct(
        public string $title = '',
        public string $placeholder = ''
    ) {
        $this->userId = auth()->id();
        $this->messages = new ChatMessageCollection();

        parent::__construct();
    }

    /**
     * @param \NextTech\MoonShineLunarChat\Collections\ChatMessageCollection $messages
     *
     * @return \NextTech\MoonShineLunarChat\Components\LunarChat
     */
    public function messages(ChatMessageCollection $messages): self
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * @return array|\MoonShine\Contracts\AssetManager\AssetElementContract[]
     */
    public function assets(): array
    {
        return [
            Css::make('/vendor/moonshine-lunar-chat/css/lunar-chat.css'),
            Js::make('/vendor/moonshine-lunar-chat/js/lunar-chat.js'),
        ];
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function action(string $name): self
    {
        $this->action = $name;

        return $this;
    }

    /**
     * @param string $channel
     * @param string $listen
     *
     * @return $this
     */
    public function websocket(string $channel, string $listen): self
    {
        $this->websocket = [
            'channel' => $channel,
            'listen' => $listen,
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function private(): self
    {
        $this->isPrivate = true;

        return $this;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function user(int $id): self
    {
        $this->userId = $id;

        return $this;
    }

    /**
     * @return array
     */
    protected function viewData(): array
    {
        return [
            'action' => $this->action,
            'websocket' => $this->websocket,
            'isPrivate' => $this->isPrivate,
            'messages' => $this->messages->toArray(),
            'placeholder' => $this->placeholder,
            'title' => $this->title,
            'userId' => $this->userId,
        ];
    }
}
