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

use Dotclear\Module\MyPlugin;

class My extends MyPlugin
{
    /** @var    string  This module settings ID */
    public const NS_SETTING_ID = 'handlers';
}
