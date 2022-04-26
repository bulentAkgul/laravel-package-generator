<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\Kernel\Helpers\Settings;

class BuildPackageStructure
{
    public static function _(array $request)
    {
        CreateFiles::_(self::initialStructure(), $request);
    }

    private static function initialStructure()
    {
        return Settings::standalone('laravel')
            ? ['resources' => Settings::structures('package.resources')]
            : Settings::structures('package');
    }
}