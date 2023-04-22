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

class MyUrlHandlers
{
    /** @var    array   $defaults   The default URLs handlers */
    private static array $defaults = [];

    /** @var    array   $url2post   The posts types URLs */
    private static array $url2post = [];

    /** @var    array   $post_adm_url   The posts types admin URLs */
    private static array $post_adm_url = [];

    /**
     * Initialize handlers list.
     */
    public static function init(): void
    {
        # Set defaults
        foreach (dcCore::app()->url->getTypes() as $k => $v) {
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

        foreach (dcCore::app()->getPostTypes() as $k => $v) {
            self::$url2post[$v['public_url']] = $k;
            self::$post_adm_url[$k]           = $v['admin_url'];
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

        dcCore::app()->url->register(
            $name,
            $url,
            sprintf(self::$defaults[$name]['representation'], $url),
            self::$defaults[$name]['handler']
        );

        $k = self::$url2post[self::$defaults[$name]['url'] . '/%s'] ?? '';

        if ($k) {
            dcCore::app()->setPostType($k, self::$post_adm_url[$k], dcCore::app()->url->getBase($name) . '/%s');
        }
    }

    /**
     * Get default URLs handlers
     *
     * @return  array   The default URLs handlers
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
     * @return  array   The blog URLs handlers
     */
    public static function getBlogHandlers(): array
    {
        if (is_null(dcCore::app()->blog)) {
            return [];
        }
        $handlers = json_decode((string) dcCore::app()->blog->settings->get(My::id())->get(My::NS_SETTING_ID), true);

        return is_array($handlers) ? $handlers : [];
    }

    /**
     * Save custom URLs handlers
     *
     * @param   array   $handlers   The custom URLs handlers
     */
    public static function saveBlogHandlers(array $handlers): void
    {
        if (is_null(dcCore::app()->blog)) {
            return;
        }
        dcCore::app()->blog->settings->get(My::id())->put(My::NS_SETTING_ID, json_encode($handlers));
        dcCore::app()->blog->triggerBlog();
    }
}
