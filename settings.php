<?php
/**
 * @package SMF JQuery Loader
 * @author digger http://mysmf.ru
 * @copyright 2017
 * @license MIT http://opensource.org/licenses/mit-license.php
 * @version 1.0
 */

if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')) {
    require_once(dirname(__FILE__) . '/SSI.php');
} elseif (!defined('SMF')) {
    die('<b>Error:</b> Cannot install - please verify that you put this file in the same place as SMF\'s index.php and SSI.php files.');
}

if ((SMF == 'SSI') && !$user_info['is_admin']) {
    die('Admin privileges required.');
}

// List settings here in the format: setting_key => default_value.  Escape any "s. (" => \")
$mod_settings = array(
    // Settings
    'jquery_loader_jq_enabled' => 0,
    'jquery_loader_jq_version' => '1.12.4',
    'jquery_loader_jq_target' => 'google',
    'jquery_loader_ui_enabled' => 0,
    'jquery_loader_ui_target' => 'google',
    'jquery_loader_ui_theme' => 'base',
);

// Update mod settings if applicable
foreach ($mod_settings as $new_setting => $new_value) {
    if (!isset($modSettings[$new_setting])) {
        updateSettings(array($new_setting => $new_value));
    }
}
