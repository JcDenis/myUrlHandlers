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

$label = basename(dirname(__FILE__));
$m_version = dcCore::app()->plugins->moduleInfo($label,'version');
$i_version = dcCore::app()->getVersion($label);

if (version_compare($i_version,$m_version,'>=')) {
    return;
}

dcCore::app()->blog->settings->addNamespace('myurlhandlers');
$s = &dcCore::app()->blog->settings->myurlhandlers;
$s->put('url_handlers','','string','Personalized URL handlers',false);

dcCore::app()->setVersion($label,$m_version);
return true;
