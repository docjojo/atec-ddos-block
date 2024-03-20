<?php
if (!function_exists('atec_base64_svg'))
{
  function atec_base64_svg($localPath) 
  { 
    global $wp_filesystem;
    WP_Filesystem(); $svg='';
    if ( $wp_filesystem->exists( $localPath ) ) { $svg = $wp_filesystem->get_contents( $localPath ); }
    $svg=str_replace('#000000','#fff',$svg); $base64=base64_encode($svg);
    return $base64;
  }
}
?>