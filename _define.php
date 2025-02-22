<?php
/**
 * @file
 * @brief       The plugin myUrlHandlers definition
 * @ingroup     myUrlHandlers
 *
 * @defgroup    myUrlHandlers Plugin myUrlHandlers.
 *
 * Change Dotclear URL handlers.
 *
 * @author      Alex Pirine and contributors
 * @author      Jean-Christian Denis
 * @copyright   Alex Pirine
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

$this->registerModule(
    'URL handlers',
    'Change Dotclear URL handlers',
    'Alex Pirine and contributors',
    '2025.02.16',
    [
        'requires'    => [['core', '2.33']],
        'permissions' => 'My',
        'priority'    => 150000,
        'type'        => 'plugin',
        'support'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/issues',
        'details'     => 'https://github.com/JcDenis/' . basename(__DIR__) . '/src/branch/master/README.md',
        'repository'  => 'https://github.com/JcDenis/' . basename(__DIR__) . '/raw/branch/master/dcstore.xml',
        'date'        => '2025-02-16T20:44:44+00:00',
    ]
);
