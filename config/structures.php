<?php

return [
    /*
    |--------------------------------------------------------------------------
    | The Skeleton To Generate Packages
    |--------------------------------------------------------------------------
    |
    | The following structure will be used when a package is being generated.
    | 
    | FILES: The list of files that will be created in that root.
    |        The keys of that arrays are the stub names, and values 
    |        are the file names.
    | 
    | Other keys are the folders' names. When their values are empty
    | array, we'll have empty directories.
    | 
    | {{ ... }} are placeholders whose values come from the settings
    | or code.
    | 
    | When you create a standalone package, some files will be
    | copied from the vendor folder.
    | 
    | You can change this structure as you need.
    | 
    */
    'package' => [
        'FILES' => ['composer' => 'composer.json'],
        'config' => ['FILES' => ['config' => '{{ registrar }}.php']],
        'database' => ['factories' => [], 'migrations' => [], 'seeders' => []],
        'resources' => [
            '{{ apps }}' => [
                '{{ app }}' => [
                    '{{ view }}' => [], '{{ css }}' => [], '{{ js }}' => []
                ]
            ]
        ],
        'routes' => ['FILES' => ['route.web' => 'web.php', 'route.api' => 'api.php']],
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
    'resources' => [
        'sass' => [
            'abstractions' => ['properties', 'variables', 'functions', 'mixins'],
            'utilities' => ['box-model', 'color', 'decorator', 'typrography', 'item', '_index'],
            'components' => ['_index'],
        ]
    ]
];
