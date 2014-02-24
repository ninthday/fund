<?php

//put included things here
require_once('./include/master.inc.php');

//session start
session_start();

//init db conection
$db_conn = new bookdata($db_host, $db_user, $db_pass, $db_name);


// check if login data posted
if ($_POST['hnd'] == 'login') {
    //echo 'test';
    $auth = $db_conn->user_auth($_POST['username'], $_POST['passwd']);
    echo 'Redirecting...';
    //refresh page
    refresh_page(3);
} else {
    //begin html documents
    flush();
    require_once('include/htm/header.htm');

    // check if login
    if (!isset($_SESSION['auth'])) {
        echo '您尚未登入，請登入後繼續：';
        require_once('include/htm/form_login.htm');
    } else {
        $login_user = $_SESSION['uid'];
        require_once('include/view.php');
    }
}


// !login or expire:
// {
// 	[login form] w/ nccucs mailserver username/pass:
// 	pop3 auth
// 	read local privilege table
// 		if authed username not exist in local table:
// 		add user, set as guest...
// 		need to notify admin to change privilege
// }
// login:
// {
// 	root view
// 		[USER] CRUD
// 		[PROJECT] CRUD
//		[RECORD] CRUD
// 	admin view
// 		[USER] CRU
// 		[PROJECT] CRUD
//		[RECORD] CRUD
// 	super-user view
// 		[PROJECT] CRU
//		[RECORD] CRUD
// 	faculty view
// 		[PROJECT] R
//		[RECORD] R
// 	guest view (no permission)
// }
// 

/*
  $result = $db_conn->query_row('SELECT * FROM `record`');

  print_r($result->num_rows);


  print_r($result->fetch_array(MYSQLI_ASSOC));
  $result->mysqli_data_seek;
  print_r($result->fetch_array(MYSQLI_ASSOC));

 */

//echo exec('cat /etc/hostname');
//phpinfo();
/*

  echo '</pre>';
  print_r($_SESSION);
  print_r($_COOKIE);
  echo $db_conn->user_auth('g9713', 'am0ureu4');
  echo '</pre>';

 */


$db_conn->close_conn();
require_once('include/htm/footer.htm');
?>
