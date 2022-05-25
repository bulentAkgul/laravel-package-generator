<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\Kernel\Helpers\Settings;

class SetVueJsRequest
{
    public static function _($request, $name, $role)
    {
        return [
            "command" => "create:resource",
            "name" => $name,
            "type" => "js:client:{$role}",
            "package" => $request['attr']['package'],
            "app" => $request['attr']['folder'],
            "parent" => null,
            "class" => false,
            "taskless" => false,
            "force" => false,
            "queue" => self::queue($request, $name, $role),
            "pipeline" => [...Settings::resources('vue'), 'type' => 'vue'],
        ];
    }

    private static function queue($request, $name, $role)
    {
        return [[
            'type' => 'js',
            'status' => 'pair',
            'name' => $name,
            'variation' => 'client',
            'subs' => '',
            'package' => $request['attr']['package'],
            'task' => '',
            'order' => 'pair',
            'wrapper' => '',
            'role' => $role,
        ]];
    }
}
