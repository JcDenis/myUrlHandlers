<?php

declare(strict_types=1);

namespace Dotclear\Plugin\myUrlHandlers;

use Dotclear\App;
use Dotclear\Core\PostType;

/**
 * @brief       myUrlHandlers main class.
 * @ingroup     myUrlHandlers
 *
 * @author      Alex Pirine (author)
 * @author      Jean-Christian Denis (latest)
 * @copyright   GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
class MyUrlHandlers
{
    /**
     * The URLs stack.
     *
     * @var     UrlStack    $stack
     */
    private static UrlStack $stack;

    /**
     * The posts types URLs.
     *
     * @var     array<string, string>   $pt_public2type
     */
    private static array $pt_public2type = [];

    /**
     * The posts types admin URLs.
     *
     * @var     array<string, string>   $pt_type2admin
     */
    private static array $pt_type2admin = [];

    /**
     * Initialize handlers list.
     */
    public static function init(): void
    {
        self::$stack = new UrlStack();

        # Set defaults
        foreach (App::url()->getTypes() as $k => $v) {
            if (empty($v['url'])) {
                continue;
            }

            $v['representation'] = str_replace('%', '%%', $v['representation']);
            $v['representation'] = preg_replace('/' . preg_quote($v['url'], '/') . '/', '%s', $v['representation'], 1, $c);

            if ($c && is_string($v['representation'])) {
                self::$stack->set(new UrlDescriptor(
                    $k,
                    $v['url'],
                    $v['representation'],
                    $v['handler'] ?? null
                ));
            }
        }

        foreach (App::postTypes()->dump() as $pt) {
            self::$pt_public2type[$pt->public_url] = $pt->type;
            self::$pt_type2admin[$pt->type]        = $pt->admin_url;
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
        $desc = self::$stack->get($name);
        if (is_null($desc->handler())) {
            return;
        }

        App::url()->register(
            $name,
            $url,
            sprintf($desc->representation, $url),
            $desc->handler()
        );

        $type = self::$pt_public2type[$desc->url . '/%s'] ?? '';
        if (!$type) {
            return;
        }

        App::postTypes()->set(new PostType(
            $type,
            self::$pt_type2admin[$type],
            App::url()->getBase($name) . '/%s'
        ));
    }

    /**
     * Get default URLs handlers.
     *
     * @return  array<string, string>   The default URLs handlers
     */
    public static function getDefaults(): array
    {
        $res = [];
        foreach (self::$stack->dump() as $v) {
            $res[$v->id] = $v->url;
        }

        return $res;
    }

    /**
     * Get custom blog URLs handlers.
     *
     * @return  array<string, string>   The blog URLs handlers
     */
    public static function getBlogHandlers(): array
    {
        $handlers = json_decode((string) My::settings()->get(My::NS_SETTING_ID), true);

        return is_array($handlers) ? $handlers : [];
    }

    /**
     * Save custom URLs handlers.
     *
     * @param   array<string, string>   $handlers   The custom URLs handlers
     */
    public static function saveBlogHandlers(array $handlers): void
    {
        My::settings()->put(My::NS_SETTING_ID, json_encode($handlers));
        App::blog()->triggerBlog();
    }
}
