<?php

namespace Bakgul\PackageGenerator\Services;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tasks\GenerateNamespace;

class RequestService
{
    public function handle(array $request): array
    {
        $this->request = $request;
        $this->map = Settings::identity();

        return [
            'attr' => $a = [...$request, ...$this->setAttr()],
            'map' => [...$this->map, ...$this->setMap($a)]
        ];
    }

    private function setAttr()
    {
        return [
            'package' => $p = $this->setPackage($this->map['package']),
            'base_path' => $b = Path::head($p ?? ''),
            'root' => $r = $this->setRoot(),
            'path' => $this->setPath($b, $r, $p)
        ];
    }

    private function setPackage($package)
    {
        return Settings::standalone('package') ? $package : $this->request['package'];
    }

    private function setRoot()
    {
        return Settings::standalone('package') ? '' : $this->request['root'];
    }

    private function setPath($base, $root, $package)
    {
        return Settings::standalone('package')
            ? $base
            : Path::glue(array_filter(
                [$base, Settings::folders('packages'), $root, $package]
            ));
    }

    private function setMap($attr)
    {
        return [
            'package' => $p = ConvertCase::kebab($attr['package']),
            'Package' => ConvertCase::pascal($attr['package']),
            'identity' => $this->serIdentity(),
            'registrar' => $this->setRegistrar($p),
            'root_namespace' => $rn = GenerateNamespace::_($attr),
            'composer_namespace' => str_replace('\\', '\\\\', $rn)
        ];
    }

    private function serIdentity()
    {
        return Settings::standalone() ? '' : Settings::identity('registrar') . '.';
    }
    
    private function setRegistrar($package)
    {
        return Settings::standalone() ? $this->map['registrar'] : $package;
    }

    public function modify($request, $stub, $file, $path)
    {
        return [
            'attr' => array_merge($request['attr'], [
                'stub' => $this->setStub($stub),
                'file' => $file,
                'path' => $path,
            ]),
            'map' => array_merge($request['map'], [
                'class' => explode('.', $file)[0],
                'namespace' => $this->setNamespace($request, $file, $path),
                'apps' => Settings::folders('apps')
            ])
        ];
    }

    private function setStub($stub)
    {
        return $stub . (
            $stub == 'test' && Settings::standalone('package') ? '.standalone' : ''
        ) . ".stub";
    }

    private function setNamespace($request, $file, $path)
    {
        if ($request['attr']['base_path'] == $path || array_reverse(explode('.', $file))[0] != 'php') return '';

        return $request['map']['root_namespace']
            . Text::append(Path::toNamespace(trim(
                str_replace([$request['attr']['base_path'], DIRECTORY_SEPARATOR . 'src'], '', $path),
                DIRECTORY_SEPARATOR
            )), '\\');
    }

    protected function makePath(array $request): string
    {
        return Text::replaceByMap($request['map'], $request['attr']['path'], true, DIRECTORY_SEPARATOR);
    }
}
