<?php

namespace Bakgul\PackageGenerator\Services;

use Bakgul\FileContent\Tasks\CompleteFolders;
use Bakgul\PackageGenerator\Tasks\BuildPackageStructure;
use Bakgul\PackageGenerator\Tasks\RegisterToComposer;

class PackageService
{
    public function handle(array $request)
    {
        $request = (new RequestService)->handle($request);

        CompleteFolders::_($request['attr']['path']);

        BuildPackageStructure::_($request);

        OptionalFilesService::create($request);

        RegisterToComposer::_($request);
    }
}
