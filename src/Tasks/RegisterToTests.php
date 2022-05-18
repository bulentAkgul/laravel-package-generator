<?php

namespace Bakgul\PackageGenerator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;

class RegisterToTests
{
    private static $content;
    private static $lines;

    public static function _(array $attr)
    {
        match(Settings::get('tests.php')) {
            'phpunit' => self::registerToPhpunit($attr),
            default => null
        };
    }

    private static function registerToPhpunit($attr)
    {
        self::getContent();

        foreach (['Unit', 'Feature'] as $suite) {
            [$start, $end] = self::findLimits($suite);

            self::getLines($suite, $attr, $start, $end);

            self::replaceLines($start, $end);
        }

        Content::write(base_path('phpunit.xml'), self::$content);
    }

    private static function getContent()
    {
        self::$content = array_map(
            fn ($x) => trim($x, PHP_EOL),
            file(base_path('phpunit.xml'))
        );
    }

    public static function findLimits($suite)
    {
        $start = Arry::containsAt('<testsuite name="' . $suite . '">', self::$content) + 1;
        $end = 0;

        foreach (self::$content as $i => $line) {
            if (str_contains($line, '</testsuite>') && $i > $start) {
                $end = $i - 1;
                break;
            }
        }

        return [$start, $end];
    }

    private static function getLines($suite, $attr, $start, $end)
    {
        $lines = array_slice(self::$content, $start, $end - $start + 1);

        self::$lines = [...$lines, self::setLine($attr, $suite)];

        sort(self::$lines);
    }

    private static function setLine($attr, $suite)
    {
        return str_repeat(' ', 12)
            .'<directory suffix="Test.php">./'
            . Settings::folders('packages')
            . "/{$attr['root']}/{$attr['package']}/{$suite}</directory>";
    }

    private static function replaceLines($start, $end)
    {
        array_splice(self::$content, $start, $end - $start + 1, self::$lines);
    }
}