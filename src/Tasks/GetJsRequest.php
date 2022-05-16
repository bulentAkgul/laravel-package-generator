<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Prevented;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\PackageGenerator\Functions\ExtendRequest;
use Bakgul\PackageGenerator\Functions\GetRequestService;

class GetJsRequest
{
    public function __invoke(array $request, array $app, string $option): ?array
    {
        if ($this->isNotRequired($app, $option)) return null;

        return $this->service($app, $option)?->handle(
            ExtendRequest::_($request, $app, 'js', $option)
        );
    }

    private function isNotRequired($app, $option): bool
    {
        if ($option == 'route') return Prevented::route($app['router']);
        if ($option == 'store') return Prevented::store($app['type']);

        return true;
    }

    private function service(array $app, string $option = ''): ?object
    {
        foreach ($this->types($app['type']) as $type) {
            $class = GetRequestService::_(
                __NAMESPACE__,
                implode('-', array_filter([$type, $option]))
            );

            if ($class) return $class;
        }

        return null;
    }

    private function types(string $type): array
    {
        return [$type, Arry::get(Settings::resources($type) ?? [], 'framework') ?? '', ''];
    }
}
