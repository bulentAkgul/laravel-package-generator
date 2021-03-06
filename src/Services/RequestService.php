<?php

namespace Bakgul\PackageGenerator\Services;

use Bakgul\Kernel\Helpers\Package;
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
            'registrar' => $r = $this->setRegistrar($p),
            'root_namespace' => $rn = GenerateNamespace::_($attr),
            'composer_namespace' => str_replace('\\', '\\\\', $rn),
            'apps' => Settings::folders('apps'),
            'package_views' => $this->setPackageViews($r),
            'auth' => Text::append(Settings::main('auth'), ':')
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

         return implode('\\', [
             $request['map']['root_namespace'],
             Path::toNamespace(str_replace([$request['attr']['path'], DIRECTORY_SEPARATOR . 'src'], '', $path))
         ]);
    }
    
    protected function makePath(array $request): string
    {
        return Text::replaceByMap($request['map'], $request['attr']['path'], true, DIRECTORY_SEPARATOR);
    }
    
    private function setPackageViews($registrar)
    {
        $sp = Settings::standalone('package');
        $view = Settings::folders('view');

        $views = [];

        foreach (Settings::apps(callback: fn ($x) => $x['medium'] == 'browser') as $app) {
            $views[] = $sp
                ? '__DIR__.' . "'/../resources/{$app['folder']}/{$view}' => resource_path('views/{$registrar}'),"
                : '$this->loadViewsFrom(__DIR__.' . "'/../resources" . Text::wrap(Settings::folders('apps'), '/') . "{$app['folder']}/{$view}', '$registrar');";
        }

        $views = array_map(fn ($x) => str_repeat(' ', $sp ? 12 : 8) . $x, $views);

        if ($sp) {
            array_splice($views, 0, 0, '$this->publishes([');
            $views[] = str_repeat(' ', 8) . ']);';
        } else {
            $views[0] = trim($views[0]);
        }

        return implode(PHP_EOL, $views);
    }
}
