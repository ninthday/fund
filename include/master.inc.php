<?php
//include other files
require_once('settings.inc.php');
require_once('db.inc.php');

//trivial functions

function hide_element($DOM_id, $message="", $mes_DOM="res")
{
	echo '<script type="text/javascript">'."\n";
	echo "$('#".$DOM_id."').toggle();";
//	echo "document.getElementById('".$mes_DOM."').innerHTML+='".$message."';";
	echo "$('#".$mes_DOM."').html('".$message."');";
	echo '</script>';

}

function refresh_page($second, $page="")
{
	
	if(!isset($page))
	{
	echo '<meta http-equiv="refresh" content="'.$second.'">';
	}
	else
	{
	echo '<meta http-equiv="refresh" content="'.$second.'; url='.$page.'">';
	}
}

function sanitize($arr)
{
	foreach(array_keys($arr) as $key)
	{
	  $clean[$key] = mysql_escape_string($arr[$key]);
	}
	return $clean;
}

?>
