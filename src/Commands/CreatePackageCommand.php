<?php

namespace Bakgul\PackageGenerator\Commands;

use Bakgul\Kernel\Concerns\HasPreparation;
use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Concerns\Sharable;
use Bakgul\Evaluator\Concerns\ShouldBeEvaluated;
use Bakgul\Evaluator\Services\PackageCommandEvaluationService;
use Bakgul\FileHistory\Concerns\HasHistory;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\PackageGenerator\Services\PackageService;
use Illuminate\Console\Command;

class CreatePackageCommand extends Command
{
    use HasHistory, HasPreparation, HasRequest, Sharable, ShouldBeEvaluated;

    protected $signature = 'create:package {package?} {root?} {--D|dev}';

    protected $description = 'This command will generate a package if you work on a Packagefied Laravel repository.';

    protected $arguments = [
        'package' => [
            "Require",
            "The name of the package must be unique throughout the repository."
        ],
        'root' => [
            "Require",
            "It must be one of the defined roots in the 'roots' array on *config/packagify.php*"
        ],
    ];
    protected $options = [
        'dev' => [
            "If you create a dev-dependency, add '-d' or '--dev' to the command. "
        ]
    ];

    protected $examples = [];


    public function __construct()
    {
        $this->setEvaluator(PackageCommandEvaluationService::class);

        parent::__construct();
    }

    public function handle()
    {
        if (Settings::standalone('laravel')) $this->write('Laravel');
        
        if (Settings::standalone('package') && $this->argument('name')) $this->write('Package');

        $this->prepareRequest();

        if (Settings::evaluator('evaluate_commands')) {
            $this->evaluate();
            if ($this->stop()) return $this->terminate();
        }

        $this->logFile();

        (new PackageService)->handle($this->makePackageRequest());
    }

    private function write($type)
    {
        $this->error("This command can't be executed when the repository is s Standalone {$type}");
    }
}
