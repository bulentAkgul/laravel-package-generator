<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\Kernel\Helpers\Requirement;
use Bakgul\PackageGenerator\Functions\ExtendRequest;
use Bakgul\PackageGenerator\Services\RequestServices\BladeRequestService;

class GetBladeRequest
{
    public function __invoke(array $request, array $app): ?array
    {
        return Requirement::view('blade')
            ? (new BladeRequestService)->handle(
                ExtendRequest::_($request, $app, 'view')
            ) : null;
    }
}
