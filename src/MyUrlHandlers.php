<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use Dotclear\App;
use Dotclear\Core\PostType;

/**
 * @brief   myUrlHandlers main class.
 * @ingroup myUrlHandlers
 *
 * @author      Alex Pirine and contributors
 * @author      Jean-Christian Denis
 * @copyright   Alex Pirine
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class MyUrlHandlers
{
    /**
     * The default URLs handlers.
     *
     * @var     array<string,array<string,string>>  $defaults
     */
    private static array $defaults = [];

    /**
     * The posts types URLs.
     *
     * @var     array<string,string>    $url2post
     */
    private static array $url2post = [];

    /**
     * The posts types admin URLs.
     *
     * @var     array<string,string>    $post_adm_url
     */
    private static array $post_adm_url = [];

    /**
     * Initialize handlers list.
     */
    public static function init(): void
    {
        # Set defaults
        foreach (App::url()->getTypes() as $k => $v) {
            if (empty($v['url'])) {
                continue;
            }

            $p                   = '/' . preg_quote($v['url'], '/') . '/';
            $v['representation'] = str_replace('%', '%%', $v['representation']);
            $v['representation'] = preg_replace($p, '%s', $v['representation'], 1, $c);

            if ($c) {
                self::$defaults[$k] = $v;
            }
        }

        foreach (App::postTypes()->dump() as $pt) {
            self::$url2post[$pt->public_url] = $pt->type;
            self::$post_adm_url[$pt->type]   = $pt->admin_url;
        }

        # Read user settings
        foreach (self::getBlogHandlers() as $name => $url) {
            self::overrideHandler($name, $url);
        }
    }

    /**
     * Override handler.
     *
     * @param   string  $name   The handler name
     * @param   string  $url    The new url
     */
    public static function overrideHandler(string $name, string $url): void
    {
        if (!isset(self::$defaults[$name])) {
            return;
        }

        App::url()->register(
            $name,
            $url,
            sprintf(self::$defaults[$name]['representation'], $url),
            self::$defaults[$name]['handler']
        );

        $k = self::$url2post[self::$defaults[$name]['url'] . '/%s'] ?? '';

        if ($k) {
            App::postTypes()->set(new PostType(
                $k,
                self::$post_adm_url[$k],
                App::url()->getBase($name) . '/%s'
            ));
        }
    }

    /**
     * Get default URLs handlers.
     *
     * @return  array<string,string>    The default URLs handlers
     */
    public static function getDefaults(): array
    {
        $res = [];
        foreach (self::$defaults as $k => $v) {
            $res[$k] = $v['url'];
        }

        return $res;
    }

    /**
     * Get custom blog URLs handlers.
     *
     * @return  array<string,string>    The blog URLs handlers
     */
    public static function getBlogHandlers(): array
    {
        $handlers = json_decode((string) My::settings()->get(My::NS_SETTING_ID), true);

        return is_array($handlers) ? $handlers : [];
    }

    /**
     * Save custom URLs handlers.
     *
     * @param   array<string,string>    $handlers   The custom URLs handlers
     */
    public static function saveBlogHandlers(array $handlers): void
    {
        My::settings()->put(My::NS_SETTING_ID, json_encode($handlers));
        App::blog()->triggerBlog();
    }
}
