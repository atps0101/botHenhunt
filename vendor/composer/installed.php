<?php return array(
    'root' => array(
        'name' => 'admin/bot',
        'pretty_version' => '1.0.0+no-version-set',
        'version' => '1.0.0.0',
        'reference' => NULL,
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        'admin/bot' => array(
            'pretty_version' => '1.0.0+no-version-set',
            'version' => '1.0.0.0',
            'reference' => NULL,
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'telegram-bot/api' => array(
            'pretty_version' => 'v2.5.0',
            'version' => '2.5.0.0',
            'reference' => 'eaae3526223db49a1bad76a2dfa501dc287979cf',
            'type' => 'library',
            'install_path' => __DIR__ . '/../telegram-bot/api',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
    ),
);
