<?php

return [
    /*
    |--------------------------------------------------------------------------
    | The Skeleton To Generate Packages
    |--------------------------------------------------------------------------
    |
    | The following structure will be used when a package is being generated.
    | 
    | FILE : The list of files that will be created in that root. The keys of
    |        FILE arrays are the stub names, and values are the file names.
    | Other keys are the folders' names. When their values are empty array,
    | we'll have empty directories.
    |
    | {{ ... }} are placeholders whose values come from the settings or code.
    |
    | When you create a standalone package, some files will be copied from
    | the vendor folder.
    |
    | You can change this structure as you need.
    |
    */
    'package' => [
        'FILES' => ['composer' => 'composer.json', 'readme' => 'README.md'],
        'config' => ['FILES' => ['config' => '{{ registrar }}.php']],
        'database' => ['factories' => [], 'migrations' => [], 'seeder' => [
            'FILES' => ['seeder' => 'PackageSeeder.php']
        ]],
        'resources' => [
            '{{ apps }}' => [
                '{{ app }}' => [
                    '{{ view }}' => [], '{{ css }}' => [], '{{ js }}' => []
                ]
            ]
        ],
        'src' => ['FILES' => [
            'class' => '{{ Package }}.php',
            'facade' => '{{ Package }}Facade.php',
            'provider' => '{{ Package }}ServiceProvider.php'
        ]],
        'tests' => [
            'FILES' => ['test' => 'TestCase.php'],
            'Feature' => [],
            'Unit' => [],
        ]
    ],
];
