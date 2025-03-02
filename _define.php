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
        'support'     => 'https://github.com/JcDenis/' . $this->id . '/issues',
        'details'     => 'https://github.com/JcDenis/' . $this->id . '/',
        'repository'  => 'https://raw.githubusercontent.com/JcDenis/' . $this->id . '/master/dcstore.xml',
        'date'        => '2025-02-24T23:31:12+00:00',
    ]
);
