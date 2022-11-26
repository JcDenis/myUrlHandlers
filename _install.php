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
if (!defined('DC_RC_PATH')) {
    return null;
}

$label       = basename(__DIR__);
$new_version = dcCore::app()->plugins->moduleInfo($label, 'version');

if (version_compare(dcCore::app()->getVersion($label), $new_version, '>=')) {
    return;
}

try {
    dcCore::app()->blog->settings->addNamespace('myurlhandlers');
    dcCore::app()->blog->settings->myurlhandlers->put(
        'url_handlers',
        '',
        'string',
        'Personalized URL handlers',
        false
    );
    dcCore::app()->setVersion($label, $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
