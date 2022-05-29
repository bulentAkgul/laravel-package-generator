<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class AddApiRoutes
{
    public static function _(array $request)
    {
        $path = "{$request['attr']['path']}/routes/api.php";

        $content = file($path);

        foreach (Settings::apps() as $type => $app) {
            $request = self::extendRequest($request, [...$app, 'type' => $type]);

            foreach (self::$skeleton as $line) {
                $content[] = Text::replaceByMap($request['map'], $line);
            }

            $content[] = '';
        }

        Content::write($path, $content);
    }

    private static function extendRequest($request, $app)
    {
        $request['map']['app'] = "/{$app['folder']}";
        $request['map']['admin_mv'] = $app['type'] == 'admin' ? Text::wrap('admin', 'sq') . ', ' : '';

        return $request;
    }

    private static $skeleton = [
        'Route::prefix(' . "'api{{ app }}'" . ')->group(function () {',
        '    Route::middleware([' . "{{admin_mv}}'api', 'auth{{ auth }}'" . '])->group(function () {',
        '        Route::apiResources([]);',
        '    });',
        '});'
    ];
}
