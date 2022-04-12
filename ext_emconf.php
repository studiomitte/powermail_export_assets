<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Export assets in powermail',
    'description' => '',
    'category' => 'backend',
    'author' => 'Georg Ringer',
    'author_email' => 'gr@studiomitte.com',
    'state' => 'alpha',
    'clearCacheOnLoad' => true,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.0-11.5.99',
            'powermail' => '7.2.0-9.9.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
