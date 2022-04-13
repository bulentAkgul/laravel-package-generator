<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\Kernel\Helpers\Requirement;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\PackageGenerator\Functions\ExtendRequest;
use Bakgul\PackageGenerator\Functions\GetRequestService;

class GetCssRequest
{
    public function __invoke(array $request, array $app): ?array
    {
        return Requirement::css()
            ? GetRequestService::_(__NAMESPACE__, Settings::resourceOptions('css'))?->handle(
                ExtendRequest::_($request, $app, 'css')
            ) : null;
    }
}
