<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_menu', 'atec_wpdb_menu');
function atec_wpdb_menu() 
{ 
  $pluginDir=plugin_dir_path(__DIR__);
  include_once($pluginDir.'includes/atec-base64-svg.php');
  $base64=atec_base64_svg($pluginDir.'assets/img/atec_wpdb_icon.svg'); 
  
  $menu_slug = 'atec_wpdb';
  add_menu_page( 'atec DDoS block - Dashboard', 'atec DDoS block', 'manage_options', $menu_slug, $menu_slug, 
    'data:image/svg+xml;base64,'.esc_html($base64) );
}

function atec_wpdb() { include_once(plugin_dir_path(__DIR__).'/includes/atec-ddos-block-results.php'); }

require_once(__DIR__.'/atec-tools.php');
function atec_wpdb_styles()
{
  $pluginsUrl=plugin_dir_url(__DIR__);
  atec_reg_style('atec_style',$pluginsUrl,'atec-style.min.css','1.0.0');
}

function atec_wpdb_init()
{
  if (atec_get_slug()=='atec_wpdb')
  {
    if (wp_rand(0,7)==3 && !isset($_COOKIE[atec_get_slug().'_donate'])) add_action('admin_notices','atec_donate_notice');
    add_action( 'admin_enqueue_scripts', 'atec_wpdb_styles' );
  }
}
add_action( 'init', 'atec_wpdb_init' );
?>
