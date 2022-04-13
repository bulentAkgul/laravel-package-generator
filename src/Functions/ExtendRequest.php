<?php

namespace Bakgul\PackageGenerator\Functions;

use Bakgul\Kernel\Helpers\Settings;

class ExtendRequest
{
    public static function _(array $request, array $app, string $type, string $role = ''): array
    {
        return [
            'attr' => array_merge($request['attr'], $app, [
                'role' => $role,
                'path' => $request['attr']['path'] . Settings::files("{$type}.path_schema")
            ]),
            'map' => array_merge($request['map'], [
                'apps' => Settings::folders('apps'),
                'app' => Settings::folders($app['folder']),
                'container' => Settings::folders($type),
                'role' => '',
                'variation' => '',
                'folder' => '',
                'subs' => '',
            ])
        ];
    }
}