<?php

/*
 * Plugin Name: Sophware Analytics
 * Description: A plugin that allows you to embed your Sophware Analytics survey and collect responses on your WordPress website. After entering your campaign information on the settings. You can then display the survey itself by using the shortcode: [survey] or our widget.
 * Version: 1.1
 * License: GPLv2
 * Author: Sophware Enterprises
 * Author URI: https://analytics.sophware.com/
 */

//security
if(!defined('ABSPATH')) {
	exit;
}

//includes
require_once('survey_widget.php');

//creats admin page
function sophware_create_settings_page() {
	add_menu_page('Survey Settings', 'Sophware', 'manage_options', 'sophware-settings', 'sophware_get_settings_content', '', 200);
}
add_action('admin_menu', 'sophware_create_settings_page');

//creates settings page
function sophware_get_settings_content() {
	include 'settings_page.php';
}

//registers survey taking widget
function sophware_register_widget() {
	register_widget('SA_Survey_Widget');
}
add_action('widgets_init', 'sophware_register_widget');

//creates shortcode for surveys
function sophware_get_survey_shortcode() {
	$json = array();
	$campaign_id = get_option('sophware_campaign_id', '');
	$json['color'] = get_option('sophware_font_color', '#000000');
	$json['background'] = get_option('sophware_background_color', '#ffffff');
	$json['shadow'] = filter_var(get_option('sophware_box_shadow', 'false'), FILTER_VALIDATE_BOOLEAN);
	$json['mutedColor'] = get_option('sophware_muted_color', '#000000');
	$json['fontSize'] = get_option('sophware_font_size', '16') . "px";

	$html = "<div class='sophware-survey' data-uid='";
	$html .= $campaign_id;
	$html .= "' data-style='" . json_encode($json) . "'>";
	$html .= "</div>";
	return $html;
}
add_shortcode('survey', 'sophware_get_survey_shortcode');

//adds dashboard summary
function sophware_create_dashboard_summary_widget() {
	wp_add_dashboard_widget('summary-widget', 'Your Survey Summary', 'sophware_get_dashboard_summary_content');
}
add_action('wp_dashboard_setup', 'sophware_create_dashboard_summary_widget');

//adds iframe script
function sophware_add_iframe() {
	wp_enqueue_script('sophware_main_script', plugins_url('load_iframe.js', __FILE__));
}
add_action('wp_enqueue_scripts', 'sophware_add_iframe');
