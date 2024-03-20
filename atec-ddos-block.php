<?php
if ( !defined('ABSPATH') ) { die; }
  /**
  * Plugin Name:  atec DDoS block
  * Plugin URI: https://atec-systems.com/
  * Description: atec DDoS block
  * Version: 1.1.4
  * Author: Chris Ahrweiler
  * Author URI: https://atec-systems.com
  * License: GPL2
  * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
  * Text Domain:  atec-ddos-block
  */
 

function atec_WPDB_table() { global $wpdb; return $wpdb->base_prefix.'atec_ddos_block'; }
function atec_wpdb_contains($str, array $arr) { foreach($arr as $a) { if (stripos($str,$a) !== false) return true; } return false; }

if (is_admin())
{ 
  wp_cache_set('atec_WPDB_version','1.1.4');
  require_once(__DIR__.'/includes/wpdb-install.php');
  
  function atec_ddos_cleanup() 
  {
    global $wpdb;
    $table=atec_WPDB_table();
    // @codingStandardsIgnoreStart
    $wpdb->query("DELETE FROM {$table} where NOW()-ts > 3600"); // 1hour
    $wpdb->query("DELETE FROM {$table}_ed where NOW()-ts > 86400"); // 24 hours
    // @codingStandardsIgnoreEnd
  }
  if ( ! wp_next_scheduled( 'atec_ddos_cleanup' ) ) { wp_schedule_event( time(), 'hourly', 'atec_ddos_cleanup' ); }
  
  function atec_wpdb_activate() 
  {
    atec_wpdb_deactivate();
    global $wpdb;
    $table=atec_WPDB_table();
    // @codingStandardsIgnoreStart
      $sql=' (`id` INT NOT NULL AUTO_INCREMENT, `count` INT NOT NULL DEFAULT 1, `ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `ip` VARCHAR(15) NOT NULL , `url` VARCHAR(2048) NOT NULL , PRIMARY KEY (`id`), INDEX `ipi` (`ip`)) ENGINE = InnoDB CHARSET=utf8mb4 COLLATE  utf8mb4_unicode_520_ci';
      $wpdb->query("CREATE TABLE {$table} ".$sql);
      $wpdb->query("CREATE TABLE {$table}_ed ".$sql);
    // }
    // @codingStandardsIgnoreEnd
  }    
  register_activation_hook( __FILE__, 'atec_wpdb_activate' );

  function atec_wpdb_deactivate() 
  {
    global $wpdb;
    $table=atec_WPDB_table();
    // @codingStandardsIgnoreStart
    $wpdb->query("DROP TABLE IF EXISTS {$table}");
    $wpdb->query("DROP TABLE IF EXISTS {$table}_ed");
    // @codingStandardsIgnoreEnd
    wp_clear_scheduled_hook( 'atec_ddos_cleanup' );
  }
  register_deactivation_hook( __FILE__, 'atec_wpdb_deactivate' );
  
  // UPDATE
  
  include_once(__DIR__.'/includes/updater.php');
  
      $config = array(
        'slug' => plugin_basename( __FILE__ ),
        'proper_folder_name' => 'atec-ddos-block',
        'api_url' => 'https://api.github.com/repos/docjojo/atec-ddos-block',
        'raw_url' => 'https://raw.githubusercontent.com/docjojo/atec-ddos-block/master',
        'github_url' => 'https://github.com/docjojo/atec-ddos-block',
        'zip_url' => 'https://github.com/docjojo/atec-ddos-block/archive/refs/heads/main.zip',
        'sslverify' => true,
        'requires' => '5.2',
        'tested' => '6.5',
        'readme' => 'readme.txt',
        'access_token' => '',
      ); 
      new WP_GitHub_Updater( $config );
  
  // UPDATE
}
else
{
  if ( ! function_exists( 'atec_ddos_check' ) ) 
  {
    function atec_ddos_check() 
    {
      $ip=trim($_SERVER['REMOTE_ADDR']);

      error_log('lastIP:'.wp_cache_get('TEST'));
      wp_cache_set('TEST','TEST');
      if ($ip!=='')
      {
        // @codingStandardsIgnoreStart
        global $wpdb;
        $table=atec_WPDB_table();

        $url = add_query_arg( NULL, NULL );
        $short = substr($url, 0, strpos($url, "&"));
        $url=$short==''?$url:$short;
                
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT id, count, now()-ts as time FROM {$table} WHERE `ip` LIKE %s AND `url` LIKE %s", $ip, $url) );
        if (empty($results)) $wpdb->query( $wpdb->prepare( "INSERT INTO {$table} (`ip`,`url`) values (%s,%s)", $ip, $url) ); 
        else
        {
          $wpdb->query( $wpdb->prepare( "UPDATE {$table} SET `count`=%d, `ts`=NOW() WHERE `id`=%d", $results[0]->count+1, $results[0]->id) );
          if ($results[0]->time>60) // request older than a minute
          { 
            $wpdb->query( $wpdb->prepare( "UPDATE {$table} SET `count`=1, `ts`=NOW() WHERE `id`=%d", $results[0]->id) );
          }
          else // request within a minute
          {
            if ($results[0]->count>6) // is attack
            {
              $results2 = $wpdb->get_results( $wpdb->prepare( "SELECT id, count FROM {$table}_ed WHERE `ip` LIKE %s AND `url` LIKE %s", $ip, $url) );
              if (empty($results2)) $wpdb->query( $wpdb->prepare( "INSERT INTO {$table}_ed (`ip`,`url`) values (%s,%s)", $ip, $url) );
              else $wpdb->get_results( $wpdb->prepare( "UPDATE {$table}_ed SET `count`=%d, `ts`=NOW() WHERE `id`=%d", $results2[0]->count+1, $results2[0]->id) );

              $pluginsUrl=plugins_url('', __DIR__ );
              wp_die('<h2>'.esc_html(get_bloginfo('name')).'</h2><sub><img src="'.esc_url($pluginsUrl).'/atec-ddos-block/assets/img/atec_wpdb_icon.svg" style="height:22px;"></sub> "atec DDoS block" protection.<br><br>Sorry, your request was blocked due to too many requests ('.esc_html($results[0]->count).') - please wait a minute.',200);
            }
          }
        }
      }
      // @codingStandardsIgnoreEnd
    }
  }
add_action( 'init', 'atec_ddos_check' );
}

function atec_ddos_custom_login_message() 
{
  $pluginsUrl=plugins_url('', __DIR__ );
  return '<center>
              <h1>'.get_bloginfo('name').'</h1>
              <h3 style="color:#999;">&middot; WP Login &middot;</h3><br>
              <img src="'.esc_url($pluginsUrl).'/atec-ddos-block/assets/img/atec_wpdb_icon.svg" style="height:22px;">
              <h4>Protected by <a href="https://wordpress.org/support/plugin/atec-ddos-block/" target="_blank" style="text-decoration: none;">"atec DDoS block"</a> plugin.</h4><br>
            </center>';
}
add_filter('login_message', 'atec_ddos_custom_login_message');

function atec_ddos_custom_loginlogo() 
{
  $logo_url=esc_url(get_site_icon_url());
  if ($logo_url!='') echo '<style type="text/css">h1 a { background-image:url('.esc_url($logo_url).') !important; height:50px !important; width:auto !important; background-size: contain !important; margin: 0 !important; }</style>';
}
add_action('login_head', 'atec_ddos_custom_loginlogo');

function atec_ddos_custom_login_url() { return home_url(); }
add_filter('login_headerurl', 'atec_ddos_custom_login_url');

?>