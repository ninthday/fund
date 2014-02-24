<?php
session_start();
session_destroy();

require_once('include/htm/header.htm');
echo '您已登出系統。';
echo '<meta http-equiv="refresh" content="3; url=index.php">';
?>
