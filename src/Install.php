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

use dcCore;
use dcNamespace;
use dcNsProcess;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && dcCore::app()->newVersion(My::id(), dcCore::app()->plugins->moduleInfo(My::id(), 'version'));

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init || is_null(dcCore::app()->blog)) {
            return false;
        }

        self::growUp();

        dcCore::app()->blog->settings->get(My::id())->put(
            My::NS_SETTING_ID,
            json_encode([]),
            'string',
            'Personalized URL handlers',
            false
        );

        return true;
    }

    private static function growUp(): void
    {
        $current = dcCore::app()->getVersion(My::id());

        // Update settings id, ns, value
        if ($current && version_compare($current, '2023.03.11', '<')) {
            $record = dcCore::app()->con->select(
                'SELECT * FROM ' . dcCore::app()->prefix . dcNamespace::NS_TABLE_NAME . ' ' .
                "WHERE setting_ns = 'myurlhandlers' AND setting_id = 'url_handlers' "
            );

            while ($record->fetch()) {
                $value = @unserialize($record->f('setting_value'));
                $cur   = dcCore::app()->con->openCursor(dcCore::app()->prefix . dcNamespace::NS_TABLE_NAME);
                $cur->setField('setting_id', My::NS_SETTING_ID);
                $cur->setField('setting_ns', My::id());
                $cur->setField('setting_value', json_encode(is_array($value) ? $value : []));
                $cur->update(
                    "WHERE setting_id = '" . $record->f('setting_id') . "' and setting_ns = '" . $record->f('setting_ns') . "' " .
                    'AND blog_id ' . (null === $record->f('blog_id') ? 'IS NULL ' : ("= '" . dcCore::app()->con->escapeStr((string) $record->f('blog_id')) . "' "))
                );
            }
        }
    }
}
