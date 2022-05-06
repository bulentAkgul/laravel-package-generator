<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;

class RegisterToComposer
{
    static private $composer;
    static private $request;

    public static function _(array $request)
    {
        self::$request = $request;

        self::get();
        self::add();
        self::order();
        self::setComposer();
    }
    
    private static function get()
    {
        self::$composer = json_decode(file_get_contents(base_path('composer.json'), "w+"), true);
    }

    private static function add()
    {
        self::setRepository();
        self::setRequirement();
    }

    private static function setRepository()
    {
        self::$composer['repositories'][self::$request['attr']['package']] = [
            'type' => 'path',
            'url' => self::url(),
            "options" => ['symlink' => true]
        ];
    }

    private static function url()
    {
        return Path::glue([Settings::folders('packages'), self::$request['attr']['root'], self::$request['attr']['package']]);
    }

    private static function setRequirement()
    {
        self::$composer['require' . (self::$request['attr']['dev'] ? '-dev' : '')][self::$request['map']['vendor'] . '/' . self::$request['attr']['package']] = "@dev";
    }

    private static function order()
    {
        ksort(self::$composer['repositories']);
        ksort(self::$composer['require']);
        ksort(self::$composer['require-dev']);
    }

    private static function setComposer()
    {
        file_put_contents(
            base_path('composer.json'),
            str_replace('\/', '/', json_encode(self::$composer, JSON_PRETTY_PRINT))
        );
    }
}