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

if (!defined('DC_RC_PATH')) { return; }

$this->registerModule(
    'My URL handlers',              // Name
    'Change Dotclear URL handlers', // Description
    'Alex Pirine and contributors', // Author
    '2022.08',                      // Version
    [
        'permissions' => 'contentadmin',
        'priority'    => 150000,
        'type'        => 'plugin',
        'dc_min'      => '2.7',
        'support'     => 'http://forum.dotclear.org/viewforum.php?id=16',
        'details'     => 'http://plugins.dotaddict.org/dc2/details/myUrlHandlers'
    ]
);
