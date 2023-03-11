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
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

dcCore::app()->menu[dcAdmin::MENU_PLUGINS]->addItem(
    __('URL handlers'),
    dcCore::app()->adminurl->get('admin.plugin.myUrlHandlers'),
    dcPage::getPF('myUrlHandlers/icon.png'),
    preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.myUrlHandlers')) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]), dcCore::app()->blog->id)
);

dcCore::app()->addBehavior('adminDashboardFavoritesV2', function ($favs) {
    $favs->register('myUrlHandlers', [
        'title'       => __('URL handlers'),
        'url'         => dcCore::app()->adminurl->get('admin.plugin.myUrlHandlers'),
        'small-icon'  => dcPage::getPF('myUrlHandlers/icon.png'),
        'large-icon'  => dcPage::getPF('myUrlHandlers/icon-big.png'),
        'permissions' => dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]),
    ]);
});
