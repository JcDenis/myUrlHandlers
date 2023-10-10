<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use Dotclear\App;
use Dotclear\Module\MyPlugin;

/**
 * @brief   myUrlHandlers My helper.
 * @ingroup myUrlHandlers
 *
 * @author      Alex Pirine and contributors
 * @author      Jean-Christian Denis
 * @copyright   Alex Pirine
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class My extends MyPlugin
{
    /**
     * This module settings ID.
     *
     * @var     string  NS_SETTING_ID
     */
    public const NS_SETTING_ID = 'handlers';

    public static function checkCustomContext(int $context): ?bool
    {
        return match ($context) {
            // Whole module: Limit backend to registered user and pages user
            self::MODULE => !App::task()->checkContext('BACKEND')
                || (
                    App::blog()->isDefined()
                    && App::auth()->check(App::auth()->makePermissions([
                        App::auth()::PERMISSION_CONTENT_ADMIN,
                    ]), App::blog()->id())
                ),

            default => null,
        };
    }
}
