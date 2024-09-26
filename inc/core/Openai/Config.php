<?php
return [
    'id' => 'openai',
    'folder' => 'core',
    'name' => 'OpenAI',
    'author' => 'Stackcode',
    'author_uri' => 'https://stackposts.com',
    'desc' => 'Customize system interface',
    'icon' => 'bi bi-android',
    'color' => '#ff5c35',
    'menu' => [
        'tab' =>10,
        'type' => false,
        'position' => 1020,
        'name' => 'OpenAI',
        'icon' => 'icon icon-openai',
    ],
    'js' => [
        "Assets/js/openai.js"
    ],
];