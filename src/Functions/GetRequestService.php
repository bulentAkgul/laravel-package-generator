<?php

namespace Bakgul\PackageGenerator\Functions;

use Bakgul\Kernel\Helpers\Convention;

class GetRequestService
{
    public static function _(string $namespace, string $name, bool $newable = true): ?object
    {
        $namespace = SetNamespace::_(Convention::class($name) . 'RequestService', $namespace);

        return class_exists($namespace) ? ($newable ? new $namespace : $namespace) : null;
    }
}