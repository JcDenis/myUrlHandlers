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
class myUrlHandlers
{
    private static $defaults     = [];
    private static $url2post     = [];
    private static $post_adm_url = [];

    public static function init()
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
        $handlers = (array) @unserialize(dcCore::app()->blog->settings->myurlhandlers->url_handlers);
        foreach ($handlers as $name => $url) {
            self::overrideHandler($name, $url);
        }
    }

    public static function overrideHandler($name, $url)
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

    public static function getDefaults()
    {
        $res = [];
        foreach (self::$defaults as $k => $v) {
            $res[$k] = $v['url'];
        }

        return $res;
    }
}
