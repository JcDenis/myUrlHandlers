<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use Dotclear\App;
use Dotclear\Module\MyPlugin;

/**
 * @brief       myUrlHandlers My helper.
 * @ingroup     myUrlHandlers
 *
 * @author      Alex Pirine (author)
 * @author      Jean-Christian Denis (latest)
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

    // Use default permissions
}
