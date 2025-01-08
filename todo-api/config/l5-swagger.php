<?php

return [
    'default' => 'default',
    'documentations' => [
        'default' => [
            'info' => [
                'title' => 'API de Gerenciamento de Tarefas',
                'description' => 'Documentação interativa para a API.',
                'version' => '1.0.0',
            ],
            'routes' => [
                'api' => 'api/documentation',
            ],
            'paths' => [
                'docs' => storage_path('api-docs'),
                'base' => null,
                'routes' => base_path('routes/api.php'),
                'annotations' => [
                    base_path('app/Http/Controllers'),
                ],
                'excludes' => [],
            ],
        ],
    ],
    'paths' => [
        'docs' => storage_path('api-docs'),
        'base' => null,
        'routes' => base_path('routes/api.php'),
        'annotations' => base_path('app/Http/Controllers'),
        'excludes' => [],
    ],

];


