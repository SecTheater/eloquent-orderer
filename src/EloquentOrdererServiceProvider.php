<?php

namespace Eloquent\Orderer;

use Illuminate\Support\ServiceProvider;

class EloquentOrdererServiceProvider extends ServiceProvider
{
    public function register()
    {
        if ($this->app->runningInConsole()) {

            $this->registerConsoleCommands();
        }
    }
    protected function registerConsoleCommands()
    {
        $this->commands(\Eloquent\Orderer\Commands\RefreshOrdersCommand::class);
    }
}
