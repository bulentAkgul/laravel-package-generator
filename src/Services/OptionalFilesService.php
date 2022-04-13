<?php

namespace Bakgul\PackageGenerator\Services;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileContent\Functions\MakeFile;
use Bakgul\PackageGenerator\Tasks\GetBladeRequest;
use Bakgul\PackageGenerator\Tasks\GetCssRequest;
use Bakgul\PackageGenerator\Tasks\GetJsRequest;
use Illuminate\Filesystem\Filesystem;

class OptionalFilesService
{
    public static function create(array $request): void
    {
        self::createResources($request);

        self::copy($request['attr']['base_path']);
    }

    private static function createResources(array $request): void
    {
        if (Settings::resources() == null) return;

        $request['attr']['path'] .= DIRECTORY_SEPARATOR . 'resources'; 

        foreach (Settings::apps() as $app) {
            self::createJs($request, $app);
            self::createCss($request, $app);
            self::createView($request, $app);
        }
    }

    private static function createJs($request, $app)
    {
        foreach (['route', 'store'] as $option) {
            $fileRequest = (new GetJsRequest)($request, $app, $option);

            if ($fileRequest) MakeFile::_($fileRequest);
        }
    }

    private static function createCss(array $request, array $app)
    {
        $fileRequest = (new GetCssRequest)($request, $app);

        if ($fileRequest) MakeFile::_($fileRequest);
    }

    private static function createView(array $request, array $app)
    {
        if ($app['type'] != 'blade') return;

        $fileRequest = (new GetBladeRequest)($request, $app);

        if ($fileRequest) MakeFile::_($fileRequest);
    }

    private static function copy(string $path): void
    {
        if (Settings::standalone('package')) {
            (new Filesystem)->copyDirectory(Path::glue([__DIR__, '..', '..', 'files']), $path);
        }
    }
}
