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
if (!defined('DC_CONTEXT_ADMIN')) {
    return null;
}

dcPage::check(dcCore::app()->auth->makePermissions([dcAuth::PERMISSION_CONTENT_ADMIN]));

$page_title = __('URL handlers');

try {
    # Read default handlers
    $handlers = myUrlHandlers::getDefaults();

    # Overwrite with user settings
    $settings = @unserialize(dcCore::app()->blog->settings->myurlhandlers->url_handlers);
    if (is_array($settings)) {
        foreach ($settings as $name => $url) {
            if (isset($handlers[$name])) {
                $handlers[$name] = $url;
            }
        }
    }
    unset($settings);

    if (!empty($_POST['handlers']) && is_array($_POST['handlers'])) {
        foreach ($_POST['handlers'] as $name => $url) {
            $url = text::tidyURL($url);

            if (empty($handlers[$name])) {
                throw new Exception(sprintf(
                    __('Handler "%s" doesn\'t exist.'),
                    html::escapeHTML($name)
                ));
            }

            if (empty($url)) {
                throw new Exception(sprintf(
                    __('Invalid URL for handler "%s".'),
                    html::escapeHTML($name)
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
        dcCore::app()->blog->settings->myurlhandlers->put('url_handlers', serialize($handlers));
        dcCore::app()->blog->triggerBlog();
        dcAdminNotices::addSuccessNotice(__('URL handlers have been successfully updated.'));
    } elseif (isset($_POST['act_restore'])) {
        dcCore::app()->blog->settings->myurlhandlers->put('url_handlers', serialize([]));
        dcCore::app()->blog->triggerBlog();
        $handlers = myUrlHandlers::getDefaults();
        dcAdminNotices::addSuccessNotice(__('URL handlers have been successfully restored.'));
    }
} catch (Exception $e) {
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
        [
            html::escapeHTML(dcCore::app()->blog->name)           => '',
            '<span class="page-title">' . $page_title . '</span>' => '',
        ]
    ) .
    dcPage::notices();

?>

<?php if (empty($handlers)): ?>
<p class="message"><?php echo __('No URL handler to configure.'); ?></p>
<?php else: ?>
<p><?php echo __('You can write your own URL for each handler of this list.'); ?></p>
<form action="<?php echo dcCore::app()->admin->getPageURL(); ?>" method="post">
<table>
  <thead>
    <tr><th>Type</th><th>URL</th></tr>
  </thead>
  <tbody>
<?php
foreach ($handlers as $name => $url) {
    echo
    '<tr><td>' . html::escapeHTML($name) . '</td><td>' .
    form::field(['handlers[' . $name . ']'], 20, 255, html::escapeHTML($url)) .
    '</td></tr>' . "\n";
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
