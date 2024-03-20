<?php
if (!function_exists('atec_clean_request')) 
{ 
    function atec_clean_request($t) 
    { 
        $nonce=atec_get_slug().'_nonce';
        if ( !isset($_REQUEST['_wpnonce']) || !function_exists('wp_verify_nonce') || ! wp_verify_nonce( $_REQUEST['_wpnonce'], $nonce ) ) { return ''; } 
        return isset($_REQUEST[$t])?$_REQUEST[$t]:'';  
    } 
}

if (!function_exists('atec_reg_style')) { function atec_reg_style($id,$dir,$css,$ver) { wp_register_style($id, $dir.'assets/css/'.$css, [], esc_html($ver)); wp_enqueue_style($id); } }

if (!function_exists('atec_get_slug'))
{
  function atec_get_slug() 
  { 
    $re = '/\?page=([\w_]+)/';
    preg_match($re, add_query_arg( NULL, NULL ), $match);
    return isset($match[1])?$match[1]:'';
  } 
}

if (!function_exists('atec_get_uri'))
{
  function atec_get_uri() 
  { 
    $url_parts = wp_parse_url( home_url() );
    $uri = $url_parts['scheme'] . "://" . $url_parts['host'] . add_query_arg( NULL, NULL );
    $uri = strtok($uri, "&");
    return $uri;
  } 
}

if (!function_exists('atec_little_block'))
{
  function atec_little_block($str,$tag,$class)
  {
    echo '<div class="atec-g atec-border '.esc_attr($class).'"><div class="atec-u">';
      echo '<'.esc_attr($tag).'>'.esc_html($str).'</'.esc_attr($tag).'>';
    echo '</div></div>';   
  }
}

if (!function_exists('atec_donate_notice'))
{
  function atec_donate_notice() 
  { 
    $plugin=plugin_basename( __FILE__ );
    $plugin=substr($plugin,0,strpos($plugin,'/'));
    $cookie=atec_get_slug().'_donate';
    echo '<div style="margin-left: 0;" class="notice notice-info is-dismissible"';
    if ($cookie!=null) echo ' onmousedown="document.cookie=\''.esc_attr($cookie).'=true\'"';
    echo '>';
    echo '<p><img style="height:14px;" src="../wp-content/plugins/'.esc_attr($plugin).'/assets/img/paypal.svg"> <a href="https://www.paypal.com/paypalme/atecsystems/5eur" target="_blank">Please consider donating.</a></p>';
    echo '</div>';
  }
}

if (!function_exists('atec_admin_notice'))
{
  function atec_admin_notice($type,$message) 
  { 
    echo '<div style="margin-left: 0;" class="notice notice-'.esc_attr($type).' is-dismissible"><p>'.esc_attr($message).'</p></div>';
  }
}

if (!function_exists('atec_new_admin_notice'))
{
  function atec_new_admin_notice($type,$message) 
  { 
    // error, warning, success, info
    add_action('admin_notices', function() use ( $type, $message ) { atec_admin_notice($type,$message); }); 
  }
}
?>