<?php

namespace Bakgul\PackageGenerator\Services\RequestServices;

use Bakgul\PackageGenerator\Services\RequestService;

class VueStoreRequestService extends RequestService
{
    public function handle(array $request): array
    {
        $request['attr']['path'] = $this->makePath($request);
        $request['attr']['file'] = "stores.js";
        $request['attr']['stub'] = "js.vue.export.stub";

        $request['map']['brackets'] = "{}";

        return $request;
    }
}