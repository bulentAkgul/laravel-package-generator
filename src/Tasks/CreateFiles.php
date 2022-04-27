<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\FileContent\Functions\MakeFile;
use Bakgul\Kernel\Tasks\CompleteFolders;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\PackageGenerator\Services\RequestService;
use Illuminate\Support\Str;

class CreateFiles
{
    private static $request;
    private static $requestService;

    public static function _(array $structure, array $request)
    {
        self::$request = $request;
        self::$requestService = new RequestService;

        self::create($structure, $request['attr']['path']);
    }
    
    public static function create($structure, $path)
    {
        foreach ($structure as $key => $value) {
            $key == 'FILES'
                ? self::createFiles($value, $path)
                : self::createFolder($key, $path, $value);
        }
    }

    private static function createFiles($files, $path)
    {
        foreach ($files as $stub => $file) {
            MakeFile::_(self::$requestService->modify(
                self::$request,
                $stub,
                self::setItems($file)[0],
                $path
            ));
        }
    }

    private static function createFolder($folder, $path, $structure)
    {
        $folders = self::setItems($folder);

        foreach ($folders as $folder) {
            CompleteFolders::_(Path::glue([$path, $folder]));

            self::create($structure, Path::glue([$path, $folder]));
        }
    }

    private static function setItems(string $name): array
    {
        if ($name == '{{ app }}') return array_map(
            fn ($x) => $x['folder'],
            array_values(Settings::apps())
        );

        if (!str_contains($name, '{{')) return [$name];

        $key = trim(Str::between($name, '{{', '}}'));
        $ext = Arry::get(explode('.', $name), 1) ?? '';
        $suffix = str_replace(['{', '}', '.', ' ', $ext, $key], '', $name);

        return [(Settings::folders($key, nullable: true)
            ?? Arry::get(self::$request['map'], $key)
            ?? $key) . $suffix . Text::append($ext, '.')];
    }
}