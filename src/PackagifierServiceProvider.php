<?php

namespace Bakgul\PackageGenerator;

use Bakgul\Kernel\Concerns\HasConfig;
use Illuminate\Support\ServiceProvider;

class PackagifierServiceProvider extends ServiceProvider
{
    use HasConfig;
    
    public function boot()
    {
        $this->commands([
            \Bakgul\PackageGenerator\Commands\CreatePackageCommand::class,
        ]);
    }

    public function register()
    {
        $this->registerConfigs(__DIR__ . DIRECTORY_SEPARATOR . '..');
    }
}
