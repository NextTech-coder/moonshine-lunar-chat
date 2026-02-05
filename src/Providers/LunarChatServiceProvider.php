<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\AssetManager\Js;
use MoonShine\Contracts\AssetManager\AssetManagerContract;

final class LunarChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'moonshine-chat');

        $this->publishes([
            __DIR__.'/../../resources/js/lunar-chat.js' => public_path('vendor/moonshine-chat/assets/js/lunar-chat.js'),
        ], 'moonshine-chat-assets');

        moonshineAssets()->add([
            Js::make('https://cdn.jsdelivr.net/npm/pusher-js@8.4.0/dist/web/pusher.min.js'),
            Js::make('https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js'),
            Js::make(asset('vendor/moonshine-chat/assets/js/lunar-chat.js')),
        ]);
    }
}
