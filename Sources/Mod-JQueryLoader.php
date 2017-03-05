<?php
/**
 * @package SMF jQuery Loader
 * @author digger http://mysmf.ru
 * @copyright 2017
 * @license The MIT License (MIT)
 * @version 1.0
 */

if (!defined('SMF')) {
    die('Hacking attempt...');
}

const JQuery1 = '1.12.4';
const JQuery2 = '2.2.4';
const JQuery3 = '3.1.1';
const JQueryUI = '1.12.1';


/**
 * Load all needed hooks
 */
function loadJQueryLoaderHooks()
{
    add_integration_function('integrate_load_theme', 'loadJQueryLoaderAssets', false);
    add_integration_function('integrate_menu_buttons', 'addJQueryLoaderCopyright', false);
    add_integration_function('integrate_admin_areas', 'addJQueryLoaderAdminArea', false);
    add_integration_function('integrate_modify_modifications', 'addJQueryLoaderAdminAction', false);
}


/**
 * Adds mod copyright to the forum credit's page
 */
function addJQueryLoaderCopyright()
{
    global $context;

    if ($context['current_action'] == 'credits') {
        $context['copyrights']['mods'][] = '<a href="http://mysmf.ru/mods/jquery-loader" target="_blank">jQuery Loader</a> &copy; 2017, digger';
    }
}


/**
 * Add admin area
 * @param $admin_areas
 */
function addJQueryLoaderAdminArea(&$admin_areas)
{
    global $txt;
    loadLanguage('JQueryLoader/JQueryLoader');

    $admin_areas['config']['areas']['modsettings']['subsections']['jquery_loader'] = array($txt['jquery_loader_title_menu']);
}


/**
 * Add admin area action
 * @param $subActions
 */
function addJQueryLoaderAdminAction(&$subActions)
{
    $subActions['jquery_loader'] = 'addJQueryLoaderAdminSettings';
}


/**
 * @param bool $return_config
 * @return array config vars
 */
function addJQueryLoaderAdminSettings($return_config = false)
{
    global $txt, $scripturl, $context;
    loadLanguage('JQueryLoader/JQueryLoader');

    $context['page_title'] = $txt['jquery_loader_title_menu'];
    $context['post_url'] = $scripturl . '?action=admin;area=modsettings;save;sa=jquery_loader';

    $config_vars = array(
        array('title', 'jquery_loader_jq_title'),
        array('check', 'jquery_loader_jq_enabled'),
        array(
            'select',
            'jquery_loader_jq_version',
            array(
                JQuery1 => JQuery1,
                JQuery2 => JQuery2,
                JQuery3 => JQuery3,
            ),
        ),
        array(
            'select',
            'jquery_loader_jq_target',
            array(
                'local' => $txt['jquery_loader_source_local'],
                'jquery' => $txt['jquery_loader_source_jquery'],
                'google' => $txt['jquery_loader_source_google'],
                'custom' => $txt['jquery_loader_source_custom'],
            ),
        ),
        array('text', 'jquery_loader_jq_custom'),

        array('title', 'jquery_loader_ui_title'),
        array('check', 'jquery_loader_ui_enabled'),
        array(
            'select',
            'jquery_loader_ui_theme',
            array(
                'base' => 'base',
                'black-tie' => 'black-tie',
                'blitzer' => 'blitzer',
                'cupertino' => 'cupertino',
                'dark-hive' => 'dark-hive',
                'dot-luv' => 'dot-luv',
                'eggplant' => 'eggplant',
                'excite-bike' => 'excite-bike',
                'flick' => 'flick',
                'hot-sneaks' => 'hot-sneaks',
                'humanity' => 'humanity',
                'le-frog' => 'le-frog',
                'mint-choc' => 'mint-choc',
                'overcast' => 'overcast',
                'pepper-grinder' => 'pepper-grinder',
                'redmond' => 'redmond',
                'smoothness' => 'smoothness',
                'south-street' => 'south-street',
                'start' => 'start',
                'sunny' => 'sunny',
                'swanky-purse' => 'swanky-purse',
                'trontastic' => 'trontastic',
                'ui-darkness' => 'ui-darkness',
                'ui-lightness' => 'ui-lightness',
                'vader' => 'vader',
            ),
        ),
        array(
            'select',
            'jquery_loader_ui_target',
            array(
                //'local' => $txt['jquery_loader_source_local'],
                'jquery' => $txt['jquery_loader_source_jquery'],
                'google' => $txt['jquery_loader_source_google'],
                'custom' => $txt['jquery_loader_source_custom'],
            ),
        ),
        array('text', 'jquery_loader_ui_custom_js'),
        array('text', 'jquery_loader_ui_custom_css'),
    );

    if ($return_config) {
        return $config_vars;
    }

    if (isset($_GET['save'])) {
        checkSession();
        saveDBSettings($config_vars);
        redirectexit('action=admin;area=modsettings;sa=jquery_loader');
    }

    prepareDBSettingContext($config_vars);
}


/**
 * Load js and css assets
 */
function loadJQueryLoaderAssets()
{
    global $modSettings, $context;

    if (!empty($modSettings['jquery_loader_jq_enabled'])) {
        $context['insert_after_template'] .= '
                <script src="' . getJQueryLoaderSource($modSettings['jquery_loader_jq_target'], 'jq',
                $modSettings['jquery_loader_jq_target']) . '" type="text/javascript"></script>
                ';
    }

    if (!empty($modSettings['jquery_loader_ui_enabled'])) {
        $context['insert_after_template'] .= '
                <script src="' . getJQueryLoaderSource($modSettings['jquery_loader_ui_target'],
                'ui') . '" type="text/javascript"></script>';
        $context['html_headers'] .= '
                <link rel="stylesheet" type="text/css" href="' . getJQueryLoaderSource($modSettings['jquery_loader_ui_target'],
                'ui-css') . '" />
                ';
    }

}


/**
 * Get link source
 * @param string $source
 * @param string $type
 * @param string $version
 * @return bool
 */
function getJQueryLoaderSource($source = 'google', $type = 'jq', $version = JQuery1)
{
    global $modSettings, $settings;

    switch ($source) {

        case 'local';
            if ($type == 'jq') {
                return $settings['default_theme_url'] . '/scripts/JQueryLoader/jquery-' . $modSettings['jquery_loader_jq_version'] . '.min.js';
            } elseif ($type == 'ui') {
                return $settings['default_theme_url'] . '/scripts/JQueryLoader/jquery-ui.min.js';
            } elseif ($type == 'ui-css') {
                return $settings['default_theme_url'] . '/css/JQueryLoader/jquery-ui.css';
            }
            break;

        case
            'jquery';
            if ($type == 'jq') {
                return '//code.jquery.com/jquery-' . $modSettings['jquery_loader_jq_version'] . '.min.js';
            } elseif ($type == 'ui') {
                return '//code.jquery.com/ui/' . JQueryUI . '/jquery-ui.min.js';
            } elseif ($type == 'ui-css') {
                return '//code.jquery.com/ui/' . JQueryUI . '/themes/' . $modSettings['jquery_loader_ui_theme'] . '/jquery-ui.css';
            }
            break;

        case 'google';
            if ($type == 'jq') {
                return '//ajax.googleapis.com/ajax/libs/jquery/' . $modSettings['jquery_loader_jq_version'] . '/jquery.min.js';
            } elseif ($type == 'ui') {
                return '//ajax.googleapis.com/ajax/libs/jqueryui/' . JQueryUI . '/jquery-ui.min.js';
            } elseif ($type == 'ui-css') {
                return '//ajax.googleapis.com/ajax/libs/jqueryui/' . JQueryUI . '/themes/' . $modSettings['jquery_loader_ui_theme'] . '/jquery-ui.css';
            }
            break;

        case 'custom';
            if ($type == 'jq') {
                return $modSettings['jquery_loader_jq_custom'];
            } elseif ($type == 'ui') {
                return $modSettings['jquery_loader_ui_custom_js'];
            } elseif ($type == 'ui-css') {
                return $modSettings['jquery_loader_ui_custom_css'];
            }
            break;
    }

    return true;
}
