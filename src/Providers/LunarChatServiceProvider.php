<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Providers;

use Illuminate\Support\ServiceProvider;

final class LunarChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'moonshine-chat');

        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/moonshine-chat'),
        ], 'moonshine-chat-assets');
    }
}
