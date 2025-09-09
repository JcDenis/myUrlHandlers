<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use Dotclear\Helper\Process\TraitProcess;

/**
 * @brief       myUrlHandlers prepend class.
 * @ingroup     myUrlHandlers
 *
 * @author      Alex Pirine (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Prepend
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        MyUrlHandlers::init();

        return true;
    }
}
