<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of My URL handlers, a plugin for Dotclear.
# 
# Copyright (c) 2007-2015 Alex Pirine
# <alex pirine.fr>
# 
# Licensed under the GPL version 2.0 license.
# A copy is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

class myUrlHandlers
{
    private $sets;
    private $handlers = array();

    private static $defaults = array();
    private static $url2post = array();
    private static $post_adm_url = array();

    public static function init($core)
    {
        # Set defaults
        foreach (dcCore::app()->url->getTypes() as $k=>$v)
        {
            if (empty($v['url'])) {
                continue;
            }

            $p = '/'.preg_quote($v['url'],'/').'/';
            $v['representation'] = str_replace('%','%%',$v['representation']);
            $v['representation'] = preg_replace($p,'%s',$v['representation'],1,$c);

            if ($c) {
                self::$defaults[$k] = $v;
            }
        }

        foreach (dcCore::app()->getPostTypes() as $k=>$v)
        {
            self::$url2post[$v['public_url']] = $k;
            self::$post_adm_url[$k] = $v['admin_url'];
        }

        # Read user settings
        $handlers = (array) @unserialize(dcCore::app()->blog->settings->myurlhandlers->url_handlers);
        foreach ($handlers as $name => $url)
        {
            self::overrideHandler($name,$url);
        }
    }

    public static function overrideHandler($name,$url)
    {
        if (!isset(self::$defaults[$name])) {
            return;
        }

        dcCore::app()->url->register($name,$url,
            sprintf(self::$defaults[$name]['representation'],$url),
            self::$defaults[$name]['handler']);

        $k = isset(self::$url2post[self::$defaults[$name]['url'].'/%s'])
            ? self::$url2post[self::$defaults[$name]['url'].'/%s'] : '';

        if ($k) {
            dcCore::app()->setPostType($k,self::$post_adm_url[$k],dcCore::app()->url->getBase($name).'/%s');
        }
    }

    public static function getDefaults()
    {
        $res = array();
        foreach (self::$defaults as $k=>$v)
        {
            $res[$k] = $v['url'];
        }
        return $res;
    }
}
