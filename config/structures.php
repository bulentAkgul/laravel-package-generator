<?php

return [
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
