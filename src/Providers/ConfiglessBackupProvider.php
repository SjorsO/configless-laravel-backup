<?php

namespace SjorsO\ConfiglessLaravelBackup\Providers;

use Illuminate\Support\ServiceProvider;
use SjorsO\ConfiglessLaravelBackup\Commands\ConfiglessBackup;

class ConfiglessBackupProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->commands([
            ConfiglessBackup::class,
        ]);
    }
}
