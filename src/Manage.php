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

use dcAuth;
use dcCore;
use dcNsProcess;
use dcPage;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Text;
use Exception;

/**
 * Manage contributions list
 */
class Manage extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && dcCore::app()->auth->check(
                dcCore::app()->auth->makePermissions([
                    dcAuth::PERMISSION_CONTENT_ADMIN,
                ]),
                dcCore::app()->blog->id
            );

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        try {
            $handlers = self::getHandlers();

            if (!empty($_POST['handlers']) && is_array($_POST['handlers'])) {
                foreach ($_POST['handlers'] as $name => $url) {
                    $url = Text::tidyURL($url);

                    if (empty($handlers[$name])) {
                        throw new Exception(sprintf(
                            __('Uknown handler "%s".'),
                            Html::escapeHTML($name)
                        ));
                    }

                    if (empty($url)) {
                        throw new Exception(sprintf(
                            __('Invalid URL for handler "%s".'),
                            Html::escapeHTML($name)
                        ));
                    }

                    $handlers[$name] = $url;
                }

                # Get duplicates
                $w = array_unique(array_diff_key($handlers, array_unique($handlers)));

                /**
                 * Error on the line
                 * array_walk($w,create_function('&$v,$k,$h','$v = array_keys($h,$v);'),$handlers);
                 *
                 * Begin fix
                 */
                $v = function (&$v, $k, $h) {
                    return array_keys($h, $v);
                };

                array_walk($w, $v, $handlers);

                /**
                 * End fix
                 */
                $w = call_user_func_array('array_merge', $w);

                if (!empty($w)) {
                    throw new Exception(sprintf(
                        __('Duplicate URL in handlers "%s".'),
                        implode('", "', $w)
                    ));
                }
            }

            if (isset($_POST['act_save'])) {
                MyUrlHandlers::saveBlogHandlers($handlers);
                dcPage::addSuccessNotice(__('URL handlers have been successfully updated.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            } elseif (isset($_POST['act_restore'])) {
                MyUrlHandlers::saveBlogHandlers([]);
                dcPage::addSuccessNotice(__('URL handlers have been successfully restored.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            }
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }

    public static function render(): void
    {
        if (!static::$init) {
            return;
        }

        $handlers = self::getHandlers();

        dcPage::openModule(My::name());

        echo
        dcPage::breadcrumb(
            [
                Html::escapeHTML(dcCore::app()->blog->name) => '',
                My::name()                                  => '',
            ]
        ) .
        dcPage::notices();

        if (empty($handlers)) {
            echo
            '<p class="message">' . __('No URL handler to configure.') . '</p>';
        } else {
            echo
            '<form action="' . dcCore::app()->admin->getPageURL() . '" method="post">' .
            '<div class="table-outer">' .
            '<table>' .
            '<caption>' . __('URL handlers list') . '</caption>' .
            '<thead>' .
            '<tr>' .
            '<th class="nowrap" scope="col">' . __('Type') . '</th>' .
            '<th class="nowrap" scope="col">' . __('URL') . '</th>' .
            '</tr>' .
            '</thead>' .
            '<tbody>';

            foreach ($handlers as $name => $url) {
                echo
                '<tr class="line">' .
                '<td class="nowrap minimal">' . Html::escapeHTML($name) . '</td>' .
                '<td>' .
                    (new Input(['handlers[' . $name . ']']))->size(20)->maxlenght(255)->value(Html::escapeHTML($url))->render() .
                '</td>' .
                '</tr>';
            }

            echo
            '</tbody></table></div>' .
            '<p class="form-note">' . __('You can write your own URL for each handler of this list.') . '</p>' .
            '<p>' .
            '<input type="submit" name="act_save" value="' . __('Save') . '" /> ' .
            '<input class="delete" type="submit" name="act_restore" value="' . __('Reset') . '" />' .
            dcCore::app()->formNonce() . '</p>' .
            '</form>';
        }

        dcPage::closeModule();
    }

    private static function getHandlers(): array
    {
        # Read default handlers
        $handlers = MyUrlHandlers::getDefaults();

        # Overwrite with user settings
        foreach (MyUrlHandlers::getBlogHandlers() as $name => $url) {
            if (isset($handlers[$name])) {
                $handlers[$name] = $url;
            }
        }

        return $handlers;
    }
}
