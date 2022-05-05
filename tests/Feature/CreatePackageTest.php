<?php

namespace Bakgul\PackageGenerator\Tests\Feature;

use Bakgul\Kernel\Tests\Concerns\HasTestMethods;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\Kernel\Tests\TestCase;
use Illuminate\Support\Str;

class CreatePackageTest extends TestCase
{
    use HasTestMethods;

    /** @test */
    public function package_will_not_be_created_when_standalone_laravel_is_true()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('sl'));

        $this->artisan('create:package users core');

        $this->assertFileDoesNotExist(base_path('src'));
        $this->assertFileDoesNotExist(Path::base([Settings::main('packages_root'), 'core', 'users', 'src']));
    }

    /** @test */
    public function package_will_be_created_on_the_base_when_standalone_package_is_true()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('sp'));
        
        Settings::set('identity.package', 'my test package');
        Settings::set('identity.registrar', 'register-with-me');

        Folder::refresh(base_path(Settings::main('package_root')));

        $this->artisan('create:package users core');

        $this->assertFileDoesNotExist(base_path(Settings::main('packages_root')));
        $this->assertFileExists(Path::base(['src', 'MyTestPackage.php']));

        $content = file(Path::base(['src', 'MyTestPackageServiceProvider.php']));
        
        $this->assertEquals(
            trim($content[2]), 'namespace ' . Settings::identity('namespace') . ';'
        );
        $this->assertTrue(str_contains(
            $content[52], Path::glue(['', '..', 'config', "register-with-me.php'"]) . ", 'register-with-me'"
        ));
    }

    /** @test */
    public function package_will_be_created_on_the_root_when_standalone_is_false()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('pl'), true);

        $this->artisan("create:package {$this->testPackage['name']} {$this->testPackage['folder']}");

        $src = Path::base([Settings::main('packages_root'), $this->testPackage['folder'], $this->testPackage['name'], 'src']);
        
        $name = ucfirst(Str::singular($this->testPackage['name']));

        $this->assertFileExists(Path::glue([$src, "{$name}.php"]));
        
        $content = file(Path::glue([$src, "{$name}ServiceProvider.php"]));

        $this->assertEquals(
            trim($content[2]), "namespace {$this->testPackage['namespace']}\\{$name};"
        );
        
        $this->assertTrue(str_contains(
            $content[52], Path::glue(['', '..', 'config', "{$this->testPackage['name']}.php"]) . "', '{$this->testPackage['name']}'"
        ));
    }
}