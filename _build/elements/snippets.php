<?php

return [
    'autoD' => [
        'file' => 'autoD',
        'description' => '',
        'properties' => [
            'tpl' => [
                'type' => 'textfield',
                'value' => 'tpl.autoD',
            ],
            'frontend_js' => [
                'type' => 'textfield',
                'value' => '[[+assetsUrl]]js/web/default.js',
            ],
        ],
    ],
];