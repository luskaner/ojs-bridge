<?php
/*
Plugin Name: OJS Bridge Test Plugin
Plugin URI:
Description: Test of OJS Bridge
Author: David Fernández Aldana
Version: 1.0
Author URI: https://www.linkedin.com/in/david-fern%C3%A1ndez-aldana/
*/

register_activation_hook(__FILE__, 'o_j_s_bridge_test_plugin_activate');
register_deactivation_hook(__FILE__, 'o_j_s_bridge_test_plugin_deactivate');

function o_j_s_bridge_test_plugin_activate() {
    global $wp_rewrite;
    require_once dirname(__FILE__).'/o_j_s_bridge_test_plugin_loader.php';
    $loader = new OJSBridgeTestPluginLoader();
    $loader->activate();
    $wp_rewrite->flush_rules( true );
}

function o_j_s_bridge_test_plugin_deactivate() {
    global $wp_rewrite;
    require_once dirname(__FILE__).'/o_j_s_bridge_test_plugin_loader.php';
    $loader = new OJSBridgeTestPluginLoader();
    $loader->deactivate();
    $wp_rewrite->flush_rules( true );
}
