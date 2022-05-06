<?php

namespace Bakgul\PackageGenerator\Tests\Feature;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\TestCase;

class StructureFlexibilityTest extends TestCase
{
    /** @test */
    public function when_new_folder_added_to_structure_it_will_be_created()
    {
        Settings::set('structures.package.src.Models', []);

        $this->artisan('create:package users core');

        $this->assertFileExists(Path::base([Settings::folders('packages'), 'core', 'users', 'src', 'Models']));
    }
    
    /** @test */
    public function when_new_deeply_nested_folder_added_to_structure_it_will_be_created()
    {
        Settings::set('structures.package.src.Models', ['Sub1' => ['Sub2' => ['Sub3' => ['Folders' => []]]]]);

        $this->artisan('create:package users core');

        $this->assertFileExists(Path::base([Settings::folders('packages'), 'core', 'users', 'src', 'Models', 'Sub1', 'Sub2', 'Sub3', 'Folders']));
    }

    /** @test */
    public function when_a_new_file_added_to_structure_it_will_be_created()
    {
        Settings::set('structures.package.src.Models.FILES', ['model' => 'Post.php']);

        $this->artisan('create:package users core');

        $path = Path::base([Settings::folders('packages'), 'core', 'users', 'src', 'Models', 'Post.php']);
        
        $this->assertFileExists($path);

        $content = file($path);
        
        $this->assertEquals(
            trim($content[2]), 'namespace Core\Users\Models;'
        );
    }
}