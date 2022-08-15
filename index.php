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

$page_title = __('URL handlers');

try
{
    # Read default handlers
    $handlers = myUrlHandlers::getDefaults();

    # Overwrite with user settings
    $settings = @unserialize(dcCore::app()->blog->settings->myurlhandlers->url_handlers);
    if (is_array($settings)) {
        foreach ($settings as $name=>$url)
        {
            if (isset($handlers[$name])) {
                $handlers[$name] = $url;
            }
        }
    }
    unset($settings);

    if (!empty($_POST['handlers']) && is_array($_POST['handlers']))
    {
        foreach ($_POST['handlers'] as $name=>$url)
        {
            $url = text::tidyURL($url);

            if (empty($handlers[$name])) {
                throw new Exception(sprintf(
                __('Handler "%s" doesn\'t exist.'),html::escapeHTML($name)));
            }

            if (empty($url)) {
                throw new Exception(sprintf(
                __('Invalid URL for handler "%s".'),html::escapeHTML($name)));
            }

            $handlers[$name] = $url;
        }

        # Get duplicates
        $w = array_unique(array_diff_key($handlers,array_unique($handlers)));

        /**
         *
         * Error on the line
         * array_walk($w,create_function('&$v,$k,$h','$v = array_keys($h,$v);'),$handlers);
         *
         * Begin fix
         */

        $v = function (&$v,$k,$h) {
            return array_keys($h,$v);
        };

        array_walk($w,$v,$handlers);

        /**
         * End fix
         */

        $w = call_user_func_array('array_merge',$w);

        if (!empty($w)) {
            throw new Exception(sprintf(
                __('Duplicate URL in handlers "%s".'),implode('", "',$w)));
        }
    }


    if (isset($_POST['act_save']))
    {
        dcCore::app()->blog->settings->myurlhandlers->put('url_handlers',serialize($handlers));
        dcCore::app()->blog->triggerBlog();
        $msg = __('URL handlers have been successfully updated.');
    }
    elseif (isset($_POST['act_restore']))
    {
        dcCore::app()->blog->settings->myurlhandlers->put('url_handlers',serialize(array()));
        dcCore::app()->blog->triggerBlog();
        $handlers = myUrlHandlers::getDefaults();
        $msg = __('URL handlers have been successfully restored.');
    }
}
catch (Exception $e)
{
    dcCore::app()->error->add($e->getMessage());
}

/* DISPLAY
--------------------------------------------------- */

?>
<html><head>
<title><?php echo $page_title; ?></title>
</head><body>
<?php

    echo dcPage::breadcrumb(
        array(
            html::escapeHTML(dcCore::app()->blog->name) => '',
            '<span class="page-title">'.$page_title.'</span>' => ''
        )
    );

if (!empty($msg)) {
    dcPage::success($msg);
}
?>

<?php if (empty($handlers)): ?>
<p class="message"><?php echo __('No URL handler to configure.'); ?></p>
<?php else: ?>
<p><?php echo __('You can write your own URL for each handler of this list.'); ?></p>
<form action="<?php echo $p_url; ?>" method="post">
<table>
  <thead>
    <tr><th>Type</th><th>URL</th></tr>
  </thead>
  <tbody>
<?php
foreach ($handlers as $name=>$url)
{
    echo
    '<tr><td>'.html::escapeHTML($name).'</td><td>'.
    form::field(array('handlers['.$name.']'),20,255,html::escapeHTML($url)).
    '</td></tr>'."\n";
}
?>
</tbody>
</table>
<p><input type="submit" name="act_save" value="<?php echo __('Save'); ?>" />
    <input type="submit" name="act_restore" value="<?php echo __('Reset'); ?>" />
    <?php echo dcCore::app()->formNonce(); ?></p>
</form>
<?php endif; ?>
</body></html>
