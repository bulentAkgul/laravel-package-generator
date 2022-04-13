<?php

namespace Bakgul\PackageGenerator\Commands;

use Bakgul\Kernel\Concerns\HasPreparation;
use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Concerns\Sharable;
use Bakgul\Evaluator\Concerns\ShouldBeEvaluated;
use Bakgul\Evaluator\Services\PackageCommandEvaluationService;
use Bakgul\PackageGenerator\Services\PackageService;
use Illuminate\Console\Command;

class CreatePackageCommand extends Command
{
    use HasPreparation, HasRequest, Sharable, ShouldBeEvaluated;

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

        $this->evaluate();

        if ($this->stop()) return $this->terminate();

        (new PackageService)->handle($this->makePackageRequest());
    }
}
