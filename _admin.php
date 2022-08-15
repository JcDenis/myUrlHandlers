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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

$_menu['Blog']->addItem(__('URL handlers'),'plugin.php?p=myUrlHandlers',
    'index.php?pf=myUrlHandlers/icon.png',
    preg_match('/plugin.php\?p=myUrlHandlers$/',$_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check('contentadmin',dcCore::app()->blog->id));

dcCore::app()->addBehavior('adminDashboardFavorites','myUrlHandlersDashboardFavorites');

function myUrlHandlersDashboardFavorites($core,$favs)
{
    $favs->register('myUrlHandlers', array(
        'title' => __('URL handlers'),
        'url' => 'plugin.php?p=myUrlHandlers',
        'small-icon' => 'index.php?pf=myUrlHandlers/icon.png',
        'large-icon' => 'index.php?pf=myUrlHandlers/icon-big.png',
        'permissions' => 'contentadmin'
    ));
}
