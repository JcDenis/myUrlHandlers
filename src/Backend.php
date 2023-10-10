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

use Dotclear\App;
use Dotclear\Core\Process;
use Dotclear\Core\Backend\Favorites;

/**
 * @brief   myUrlHandlers backend class.
 * @ingroup myUrlHandlers
 *
 * @author      Alex Pirine and contributors
 * @author      Jean-Christian Denis
 * @copyright   Alex Pirine
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Backend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::BACKEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        My::addBackendMenuItem();

        // register user backend dashboard icon
        App::behavior()->addBehavior('adminDashboardFavoritesV2', function (Favorites $favs): void {
            $favs->register(My::id(), [
                'title'       => My::name(),
                'url'         => My::manageUrl(),
                'small-icon'  => My::icons(),
                'large-icon'  => My::icons(),
                'permissions' => App::auth()->makePermissions([App::auth()::PERMISSION_CONTENT_ADMIN]),
            ]);
        });

        return true;
    }
}
