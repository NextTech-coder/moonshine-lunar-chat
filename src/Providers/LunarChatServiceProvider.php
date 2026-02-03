<?php

declare(strict_types=1);

namespace NextTech\MoonShineLunarChat\Providers;

use Illuminate\Support\ServiceProvider;
use MoonShine\AssetManager\Js;
use MoonShine\Contracts\AssetManager\AssetManagerContract;

final class LunarChatServiceProvider extends ServiceProvider
{
    public function boot(AssetManagerContract $asset): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'moonshine-chat');
    }
}
