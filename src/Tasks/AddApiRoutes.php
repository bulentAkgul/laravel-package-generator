<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class AddApiRoutes
{
    private static $skeleton = [
        'Route::prefix(' . "'api{{ app }}'" . ')->group(function () {',
        '    Route::middleware([' . "'api', 'auth{{ auth }}'" . '])->group(function () {',
        '        Route::apiResources([]);',
        '    });',
        '});'
    ];

    public static function _(array $request)
    {
        $path = "{$request['attr']['path']}/routes/api.php";

        $content = file($path);

        foreach (Settings::apps() as $app) {

            $request['map']['app'] = "/{$app['folder']}";

            foreach (self::$skeleton as $line) {
                $content[] = Text::replaceByMap($request['map'], $line);
            }
            $content[] = '';
        }

        Content::write($path, $content);
    }
}
