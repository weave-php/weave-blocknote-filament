<?php

return [

    'uploads' => [
        'enabled' => env('WEAVE_BLOCKNOTE_UPLOADS_ENABLED', true),

        'disk' => env('WEAVE_BLOCKNOTE_UPLOADS_DISK', 'public'),

        'directory' => env('WEAVE_BLOCKNOTE_UPLOADS_DIRECTORY', 'blocknote'),

        'visibility' => env('WEAVE_BLOCKNOTE_UPLOADS_VISIBILITY', 'public'),

        'max_size_kb' => (int) env('WEAVE_BLOCKNOTE_UPLOADS_MAX_KB', 12_288),

        'middleware' => ['web', 'auth'],

        'throttle' => env('WEAVE_BLOCKNOTE_UPLOADS_THROTTLE', '60,1'),

        'authorize' => null,

        'response_url_key' => 'url',

        'input_name' => 'file',
    ],

];
