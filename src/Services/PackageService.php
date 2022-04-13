<?php

namespace Bakgul\PackageGenerator\Services;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileContent\Functions\MakeFile;
use Bakgul\FileContent\Tasks\CompleteFolders;
use Bakgul\PackageGenerator\Tasks\RegisterToComposer;
use Illuminate\Support\Str;

class PackageService
{
    private $requestService;
    private $request;

    public function handle(array $request)
    {
        $this->requestService = new RequestService;
        $this->request = $this->requestService->handle($request);

        CompleteFolders::_($this->request['attr']['path']);

        $this->create($this->initialStructure(), $this->request['attr']['path']);

        OptionalFilesService::create($this->request);

        RegisterToComposer::_($this->request);
    }

    private function initialStructure()
    {
        return Settings::standalone('laravel')
            ? ['resources' => Settings::structures('package.resources')]
            : Settings::structures('package');
    }

    public function create($structure, $path)
    {
        foreach ($structure as $key => $value) {
            $key == 'FILES'
                ? $this->createFiles($value, $path)
                : $this->createFolder($key, $path, $value);
        }
    }

    private function createFiles($files, $path)
    {
        foreach ($files as $stub => $file) {
            MakeFile::_($this->requestService->modify(
                $this->request, $stub, $this->setItems($file)[0], $path
            ));
        }
    }

    private function createFolder($folder, $path, $structure)
    {
        $folders = $this->setItems($folder);

        foreach ($folders as $folder) {
            CompleteFolders::_(Path::glue([$path, $folder]));

            $this->create($structure, Path::glue([$path, $folder]));
        }
    }

    private function setItems(string $name): array
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
            ?? Arry::get($this->request['map'], $key)
            ?? $key) . $suffix . Text::append($ext, '.')];
    }
}
