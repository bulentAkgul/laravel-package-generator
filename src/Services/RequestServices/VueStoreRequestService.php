<?php

namespace Bakgul\PackageGenerator\Services\RequestServices;

use Bakgul\PackageGenerator\Services\RequestService;
use Bakgul\PackageGenerator\Tasks\SetVueJsRequest;

class VueStoreRequestService extends RequestService
{
    public function handle(array $request): array
    {
        return SetVueJsRequest::_($request, 'stores', 'store');
    }
}