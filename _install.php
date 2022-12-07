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

try {
    if (version_compare(
        dcCore::app()->getVersion(basename(__DIR__)),
        dcCore::app()->plugins->moduleInfo(basename(__DIR__), 'version'),
        '>='
    )) {
        return null;
    }

    dcCore::app()->blog->settings->addNamespace('myurlhandlers');
    dcCore::app()->blog->settings->myurlhandlers->put(
        'url_handlers',
        '',
        'string',
        'Personalized URL handlers',
        false
    );

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
