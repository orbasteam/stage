<?php

return [
    'modelNamespace' => 'App\\',
    'field' => [
        'storage' => env('STAGE_FIELD_STORAGE', 'file'),
        'path'    => env('STAGE_FIELD_FILE_PATH', 'app/fields/')
    ],
    'route' => 'stage-setup' 
];