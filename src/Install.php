<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use Dotclear\App;
use Dotclear\Helper\Process\TraitProcess;

/**
 * @brief       myUrlHandlers installation class.
 * @ingroup     myUrlHandlers
 *
 * @author      Alex Pirine (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class Install
{
    use TraitProcess;

    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        self::growUp();

        My::settings()->put(
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
        $current = App::version()->getVersion(My::id());

        // Update settings id, ns, value
        if ($current && version_compare($current, '2023.03.11', '<')) {
            $record = App::db()->con()->select(
                'SELECT * FROM ' . App::db()->con()->prefix() . App::blogWorkspace()::NS_TABLE_NAME . ' ' .
                "WHERE setting_ns = 'myurlhandlers' AND setting_id = 'url_handlers' "
            );

            while ($record->fetch()) {
                $value = @unserialize($record->f('setting_value'));
                $cur   = App::blogWorkspace()->openBlogWorkspaceCursor();
                $cur->setField('setting_id', My::NS_SETTING_ID);
                $cur->setField('setting_ns', My::id());
                $cur->setField('setting_value', json_encode(is_array($value) ? $value : []));
                $cur->update(
                    "WHERE setting_id = '" . $record->f('setting_id') . "' and setting_ns = '" . $record->f('setting_ns') . "' " .
                    'AND blog_id ' . (null === $record->f('blog_id') ? 'IS NULL ' : ("= '" . App::db()->con()->escapeStr((string) $record->f('blog_id')) . "' "))
                );
            }
        }
    }
}
