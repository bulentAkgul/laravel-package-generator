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
    protected $description = '';

    public function __construct()
    {
        $this->setEvaluator(PackageCommandEvaluationService::class);

        parent::__construct();
    }

    public function handle()
    {
        $this->prepareRequest();

        if (Settings::evaluator('evaluate_commands')) {
            $this->evaluate();
            if ($this->stop()) return $this->terminate();
        }

        $this->logFile();

        (new PackageService)->handle($this->makePackageRequest());
    }
}
