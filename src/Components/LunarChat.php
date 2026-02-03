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
    ) {
        parent::__construct();
    }

    protected function viewData(): array
    {
        $messages = collect($this->messages)->map(function ($message) {
            $blocks = collect($message['blocks'])->map(function ($block) {
                return [
                    'type' => $block['type'],
                    'slot' => new ComponentSlot($block['contents']),
                ];
            });

            return [
                'sent' => $message['sent'],
                'author' => $message['author'],
                'avatar' => $message['avatar'] ?? null,
                'time' => $message['time'] ?? null,
                'blocks' => $blocks->all(),
            ];
        });

        return [
            'messages' => $messages->all(),
        ];
    }

    public function getAttributes(): ComponentAttributesBagContract
    {
        return parent::getAttributes();
    }
}
