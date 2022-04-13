<?php

namespace Bakgul\PackageGenerator\Functions;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;

class SetNamespace
{
    public static function _(string $class, string $namespace): string
    {
        return Path::glue([
            '',
            Text::dropTail($namespace, '\\'),
            'Services',
            'RequestServices',
            $class
        ], '\\');
    }
}