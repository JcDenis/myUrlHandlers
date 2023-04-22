<?php
/**
 * @brief myUrlHandlers, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugin
 *
 * @author Alex Pirine and contributors
 *
 * @copyright Jean-Christian Denis
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_RC_PATH')) {
    return null;
}

$this->registerModule(
    'URL handlers',
    'Change Dotclear URL handlers',
    'Alex Pirine and contributors',
    '2023.04.22',
    [
        'requires'    => [['core', '2.26']],
        'permissions' => dcCore::app()->auth->makePermissions([
            dcAuth::PERMISSION_CONTENT_ADMIN,
        ]),
        'priority'   => 150000,
        'type'       => 'plugin',
        'support'    => 'https://github.com/JcDenis/myUrlHandlers',
        'details'    => 'http://plugins.dotaddict.org/dc2/details/myUrlHandlers',
        'repository' => 'https://raw.githubusercontent.com/JcDenis/myUrlHandlers/master/dcstore.xml',
    ]
);
