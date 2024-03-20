<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class atec_wpdb_results
{
	
function __construct()
{	

$pluginsUrl=plugins_url('', __DIR__ );

echo '<div class="atec-page">';	

	echo '<h3 style="text-align: center;"><sub><img src="'.esc_url( plugins_url( '/assets/img/atec_wpdb_icon.svg', __DIR__ ) ) .'" style="height:22px;"></sub> atec DDoS block <font style="font-size:80%;">v'.esc_attr(wp_cache_get('atec_WPDB_version')).'</font></h3>';
	
	require_once('progress.php');
	
	atec_little_block('Statistics','h3','atec-header');

	global $wpdb;
	$table=atec_WPDB_table();
	
	echo '<div class="atec-g atec-border"><div class="atec-u-1-1">';
		
			// @codingStandardsIgnoreStart
			$results = $wpdb->get_results( "SELECT ip, url, count, now()-ts as time FROM {$table}_ed ORDER BY time LIMIT 500");
			// @codingStandardsIgnoreEnd
			
			echo '<h3 style="margin-bottom: 0;">BLOCKED WP Login attempts (last 24 hours)</h3>';
			echo '<p style="margin-top: 5px;">More than 6 attempts per minute.</p>';
			echo  '<div class="atec-u-1-1 atec-overflow">';
				echo '<table class="atec-table">';
				echo '<thead><tr><th>#</th><th>IP</th><th>Time <span style="font-size:0.9em;">(s)</span></th><th>Count</th><th>URL</th></tr></thead>';
				echo '<tbody>';
					$c=0;
					foreach($results as $result)
					{	
						if (atec_wpdb_contains($result->url,['wp-login.php','wp-admin']))
						{
							$c++;
							echo '<tr><td>'.esc_html($c).'</td><td>'.esc_html($result->ip).'</td><td>'.esc_html($result->time).'</td><td>'.esc_html($result->count).'</td><td>'.esc_html($result->url).'</td></tr>';
						}
					}
				echo '</tbody>';
				echo '</table>';
			echo '</div>';
			echo '<br>';
			
			echo '<h3 style="margin-bottom: 0;">BLOCKED URL Requests (last 24 hours)</h3>';
			echo '<p style="margin-top: 5px;">More than 6 same URL requests per minute.</p>';
			echo  '<div class="atec-u-1-1 atec-overflow">';
				echo '<table class="atec-table">';
				echo '<thead><tr><th>#</th><th>IP</th><th>Time <span style="font-size:0.9em;">(s)</span></th><th>Count</th><th>URL</th></tr></thead>';
				echo '<tbody>';
					$c=0;
					foreach($results as $result)
					{	
						if (!atec_wpdb_contains($result->url,['wp-login.php','wp-admin']))
						{
							$c++;
							echo '<tr><td>'.esc_html($c).'</td><td>'.esc_html($result->ip).'</td><td>'.esc_html($result->time).'</td><td>'.esc_html($result->count).'</td><td>'.esc_html($result->url).'</td></tr>';
						}
					}
				echo '</tbody>';
				echo '</table>';
			echo '</div>';
			echo '<br>';
	
			// @codingStandardsIgnoreStart
			$results = $wpdb->get_results( "SELECT ip, url, count, now()-ts as time FROM {$table} ORDER BY time LIMIT 500");
			// @codingStandardsIgnoreEnd
			
			echo '<h3>Requests in the last hour</h3>';
			echo  '<div class="atec-u-1-1 atec-overflow">';
				echo '<table class="atec-table">';
				echo '<thead><tr><th>#</th><th>IP</th><th>Time&nbsp;<span style="font-size:0.9em;">(s)</span></th><th>Count</th><th>URL</th></tr></thead>';
				echo '<tbody>';
					$c=0;
					foreach($results as $result)
					{	
						$c++;
						echo '<tr><td>'.esc_html($c).'</td><td>'.esc_html($result->ip).'</td><td>'.esc_html($result->time).'</td><td>'.esc_html($result->count).'</td><td>'.esc_html($result->url).'</td></tr>';
					}
				echo '</tbody>';
				echo '</table>';
			echo '</div>';
			echo '<br>';
		
	echo '</div></div>';

echo '</div>';

include_once('atec-footer.php');

}
}

$atec_wpds_results = new atec_wpdb_results;
?>