<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$plugin=plugin_basename( __FILE__ );
$plugin=substr($plugin,0,strpos($plugin,'/'));
echo '<center id="footer">';
echo '<p style="font-size:100%; margin-bottom:0;">Please consider <a href="https://www.paypal.com/paypalme/atecsystems/5eur" target="_blank">donating via</a> <img style="height:14px;" src="../wp-content/plugins/'.esc_attr($plugin).'/assets/img/paypal.svg"> and <a href="https://wordpress.org/support/plugin/'.esc_attr($plugin).'/reviews/#new-post" target="_blank">post a review </a> if you like it.</p>';
echo '<p style="font-size:90%; margin-top:0;">Plugins by "atec-systems": 
<a href="https://de.wordpress.org/plugins/atec-cache-apcu/" target="_blank">Cache-APCu</a> | 
<a href="https://de.wordpress.org/plugins/atec-cache-info/" target="_blank">Cache-Info</a> | 
<a href="https://de.wordpress.org/plugins/atec-web-map-service/" target="_blank">Web-Map-Service</a>
</p>';
echo '<p style="font-size:80%; margin-top:0;">© 2023/24 by <a href="https://atec-systems.com/" target="_blank" style="text-decoration: none;">atec-systems.com</a></p>';
echo '</center>';

echo '<script>document.getElementById("atec_loading").remove();</script>';
?>
