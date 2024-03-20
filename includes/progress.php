<?php
ob_start();
echo '<div id="atec_loading" class="progress"><div class="progressBar"></div></div>';
ob_end_flush(); @ob_flush(); flush();
?>