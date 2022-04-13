<?php

namespace Bakgul\PackageGenerator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\PackageGenerator\Services\RequestService;

class SassRequestService extends RequestService
{
    public function handle(array $request): array
    {
        $request['attr']['path'] = $this->makePath($request);
        $request['attr']['file'] = "_index." . Settings::resources('sass.extension');
        $request['attr']['stub'] = "css.stub";

        foreach (['forwards', 'uses', 'class'] as $placeholder) {
            $request['map'][$placeholder] = '';
        }

        return $request;
    }
}