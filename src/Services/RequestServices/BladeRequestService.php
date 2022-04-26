<?php

namespace Bakgul\PackageGenerator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\PackageGenerator\Services\RequestService;

class BladeRequestService extends RequestService
{
    public function handle(array $request): array
    {
        $request['attr']['path'] = $this->makePath($request);
        $request['attr']['file'] = "page.blade.php";
        $request['attr']['stub'] = "blade.stub";

        $request['map']['extends'] = Settings::standalone('package') ? '' : "layouts.{$request['attr']['folder']}";

        return $request;
    }
}
