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
declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use dcAdmin;
use dcCore;
use dcFavorites;
use dcPage;
use dcNsProcess;

class Backend extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN');

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init || is_null(dcCore::app()->auth) || is_null(dcCore::app()->blog) || is_null(dcCore::app()->adminurl)) {
            return false;
        }

        // add backend sidebar menu icon
        dcCore::app()->menu[dcAdmin::MENU_PLUGINS]->addItem(
            My::name(),
            dcCore::app()->adminurl->get('admin.plugin.' . My::id()),
            dcPage::getPF(My::id() . '/icon.svg'),
            preg_match('/' . preg_quote(dcCore::app()->adminurl->get('admin.plugin.' . My::id())) . '(&.*)?$/', $_SERVER['REQUEST_URI']),
            dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([dcCore::app()->auth::PERMISSION_CONTENT_ADMIN]), dcCore::app()->blog->id)
        );

        // register user backend dashboard icon
        dcCore::app()->addBehavior('adminDashboardFavoritesV2', function (dcFavorites $favs): void {
            if (is_null(dcCore::app()->auth) || is_null(dcCore::app()->adminurl)) {
                return;
            }
            $favs->register(My::id(), [
                'title'       => My::name(),
                'url'         => dcCore::app()->adminurl->get('admin.plugin.' . My::id()),
                'small-icon'  => dcPage::getPF(My::id() . '/icon.svg'),
                'large-icon'  => dcPage::getPF(My::id() . '/icon.svg'),
                'permissions' => dcCore::app()->auth->makePermissions([dcCore::app()->auth::PERMISSION_CONTENT_ADMIN]),
            ]);
        });

        return true;
    }
}
