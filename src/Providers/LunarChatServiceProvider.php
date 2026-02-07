<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\AssetManager\Css;
use MoonShine\AssetManager\Js;

final class LunarChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'moonshine-chat');


        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/moonshine-lunar-chat'),
        ], ['moonshine-lunar-chat-assets']);
    }
}
