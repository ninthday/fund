<?php
//block direct access
if(!isset($login_user))
{
	echo '<meta http-equiv="refresh" content="2; url=../index.php">';
}
else
{
?>

<div class="container">
<?
//sidebar menu
require_once('sidebar.php');
?>
<div class="main">
<hr noshade style="color:#EEE;">
<div id="func"></div>
<?
//other forms and etc.
$getdata=sanitize($_GET);
$postdata=sanitize($_POST);


if($getdata['p'] && $_SESSION['gid']!='4')
{
	require_once('include/htm/form_'.$getdata['p'].'.htm');
}

if($getdata['r'])
{
	require_once('include/htm/form_'.$getdata['r'].'.htm');
}

switch($getdata['r'])
{
	default:
		break;
	case "record_view":
		$db_conn->fill_option_project_notree('project', $_SESSION['uid']);
		break;
	case "user_manage":
		$db_conn->fill_table_user("tbl");
		break;		
}


//feed data to form - admin mode
switch($getdata['p'])
{
	default:
		break;
	case "user_add":
		$db_conn->fill_option_group("group");
		break;
	case "user_manage":
		$db_conn->fill_table_user("tbl");
		break;
	case "user_modify":
		$userdata=$db_conn->user_info($getdata['uid']);
		$db_conn->fill_text('uid', $userdata['u_id']);
		$db_conn->fill_text('username', $userdata['username']);
		$db_conn->fill_text('cname', $userdata['cname']);
		$db_conn->fill_text('email', $userdata['email']);
		$db_conn->fill_option_group("group", $userdata['g_id']);
		break;
	case "project_add":
		if(isset($getdata['parent']))
		{
			$p_data=$db_conn->project_info($getdata['parent']);
			$db_conn->fill_text('parent', $p_data['p_id']);
			$db_conn->fill_html('func', '<h3>新增子計畫&nbsp;於&nbsp;'.$p_data['name'].'<\/h3>');
		}
		else
		{
			$db_conn->fill_html('func', '<h3>新增計畫<\/h3>');
		}
		$db_conn->fill_option("yy", date("Y")+10, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm", 12);
		$db_conn->fill_option("dd", 31);
		$db_conn->fill_option("yy2", date("Y")+10, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm2", 12);
		$db_conn->fill_option("dd2", 31);
		$db_conn->hide_DOM("end_text");
		$db_conn->hide_DOM("yy2");
		$db_conn->hide_DOM("mm2");
		$db_conn->hide_DOM("dd2");
		$db_conn->fill_option_period("period");
		$db_conn->fill_checkbox_user('user');
		break;
	case "project_manage":
		$db_conn->fill_html('func', '<h3>管理計畫<\/h3>');
		if(isset($getdata['parent']))
		{
			$p_data=$db_conn->project_info($getdata['parent']);
			$db_conn->fill_project_header('func', $p_data['p_id']);
			$db_conn->fill_table_project('tbl', $p_data['p_id']);
		}
		else
		{
			$db_conn->fill_project_header('func');
			$db_conn->fill_table_project('tbl', NULL);
		}
		break;
	case "project_modify":
		$db_conn->fill_html('func', '<h3>修改計畫<\/h3>');
		$p_data=$db_conn->project_info($getdata['pid']);
		$db_conn->fill_text('pid', $p_data['p_id']);
		$db_conn->fill_text('name', $p_data['name']);
		$db_conn->fill_text('budget', $p_data['budget']);
		$db_conn->fill_text('desc', $p_data['desc']);
		$db_conn->fill_option("yy", date("Y")+10, date("Y")-10, date("Y", $p_data['start_time']));
		$db_conn->fill_option("mm", 12, 1, date("n", $p_data['start_time']));
		$db_conn->fill_option("dd", 31, 1, date("d", $p_data['start_time']));
		$db_conn->fill_option("yy2", date("Y")+10, date("Y")-10, date("Y", $p_data['stop_time']));
		$db_conn->fill_option("mm2", 12, 1, date("n", $p_data['stop_time']));
		$db_conn->fill_option("dd2", 31, 1, date("d", $p_data['stop_time']));
		$db_conn->fill_option_period("period", $p_data['period']);
		$db_conn->fill_option_status("status", $p_data['status']);
		$db_conn->fill_checkbox_user('user', $db_conn->project_owners($p_data['p_id']));
		break;
	case "tag_add":
		$db_conn->fill_html('func', '<h3>新增分類<\/h3>');
		break;
	case "tag_manage":
		$db_conn->fill_html('func', '<h3>管理分類<\/h3>');
		$db_conn->fill_tag_header('func');
		$db_conn->fill_table_tag('tbl');
		break;
	case "tag_modify":
		$db_conn->fill_html('func', '<h3>修改分類<\/h3>');
		$t_data=$db_conn->tag_info($getdata['tid']);
		$db_conn->fill_text('t_id', $t_data['type_id']);
		$db_conn->fill_text('name', $t_data['name']);
		$db_conn->fill_text('desc', $t_data['desc']);
		break;
	case "acct_add":
		$db_conn->fill_html('func', '<h3>新增會計科目<\/h3>');
		break;
	case "acct_manage":
		$db_conn->fill_html('func', '<h3>管理會計科目<\/h3>');
		$db_conn->fill_acct_header('func');
		$db_conn->fill_acct_tag('tbl');
		break;
	case "acct_modify":
		$db_conn->fill_html('func', '<h3>修改會計科目<\/h3>');
		$a_data=$db_conn->acct_info($getdata['aid']);
		$db_conn->fill_text('a_id', $a_data['a_id']);
		$db_conn->fill_text('name', $a_data['name']);
		$db_conn->fill_text('desc', $a_data['desc']);
		break;
	case "record_add":
		$db_conn->fill_html('func', '<h3>新增帳目<\/h3>');
		$db_conn->fill_option_project("project");
		$db_conn->fill_option_tag('type');
		$db_conn->fill_option_acct('acct_type');
		$db_conn->fill_option("yy", date("Y")+5, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm", 12);
		$db_conn->fill_option("dd", 31);
		if(isset($getdata['rid']))
		{
			$r_data=$db_conn->record_info($getdata['rid']);
			if($r_data!=null)
			{
				$db_conn->fill_option_project("project");
				$db_conn->fill_text("project", $r_data['p_id']);
				$db_conn->fill_option_tag('type', $r_data['type']);
				$db_conn->fill_option("yy", date("Y")+5, date("Y")-10, date("Y", $r_data['record_time']));
				$db_conn->fill_option("mm", 12, 1, date("n", $r_data['record_time']));
				$db_conn->fill_option("dd", 31, 1, date("d", $r_data['record_time']));
				$db_conn->fill_text('desc', $r_data['desc']);
				$db_conn->fill_text('desc_ticket', $r_data['desc_ticket']);
				$db_conn->fill_text('desc_register', $r_data['desc_register']);
				$db_conn->fill_text('amount', $r_data['amount']);
				$db_conn->fill_text('inc_exp', $r_data['inc_exp']);
			}
		}
		break;
	case "record_browse":
		$db_conn->fill_html('func', '<a href="?p=record_browse_project">依計畫瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_timespan">依時間瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_search">條件搜尋<\/a>', "a");
		//$db_conn->fill_html('func', '<a href="?p=record_browse_tag">依類別瀏覽<\/a>', "a");

		$db_conn->fill_table_record_recent('tbl', 10);
		break;
	case "record_browse_project":
		$db_conn->fill_html('func', '<a href="?p=record_browse_project">依計畫瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_timespan">依時間瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_search">條件搜尋<\/a>', "a");
		//$db_conn->fill_html('func', '<a href="?p=record_browse_tag">依類別瀏覽<\/a>', "a");
		//$db_conn->fill_html('func', '<a href="?p=record_browse_custom">進階瀏覽<\/a>', "a");
		$db_conn->fill_option_project("project");
		break;
	case "record_browse_timespan":
		$db_conn->fill_html('func', '<a href="?p=record_browse_project">依計畫瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_timespan">依時間瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_search">條件搜尋<\/a>', "a");
		//$db_conn->fill_html('func', '<a href="?p=record_browse_tag">依類別瀏覽<\/a>', "a");
		//$db_conn->fill_html('func', '<a href="?p=record_browse_custom">進階瀏覽<\/a>', "a");
		$db_conn->fill_option("yy", date("Y")+5, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm", 12);
		$db_conn->fill_option("dd", 31);
		$db_conn->fill_option("yy2", date("Y")+5, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm2", 12);
		$db_conn->fill_option("dd2", 31);
		break;
	case "record_browse_search":
		$db_conn->fill_html('func', '<a href="?p=record_browse_project">依計畫瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_timespan">依時間瀏覽<\/a>', "a");
		$db_conn->fill_html('func', '<a href="?p=record_browse_search">條件搜尋<\/a>', "a");
		$db_conn->fill_option_project("project");
		$db_conn->fill_option_tag("type");
		$db_conn->fill_option_acct("acct_type");
		break;
	case "record_modify":
		$r_data=$db_conn->record_info($getdata['rid']);
		$db_conn->fill_option_project("project");
		$db_conn->fill_text("rid", $r_data['r_id']);
		$db_conn->fill_text("project", $r_data['p_id']);
		$db_conn->fill_option_tag('type', $r_data['type']);
		$db_conn->fill_option_acct('acct_type', $r_data['acct_type']);
		$db_conn->fill_option("yy", date("Y")+5, date("Y")-10, date("Y", $r_data['record_time']));
		$db_conn->fill_option("mm", 12, 1, date("n", $r_data['record_time']));
		$db_conn->fill_option("dd", 31, 1, date("d", $r_data['record_time']));
		$db_conn->fill_text('desc', $r_data['desc']);
		$db_conn->fill_text('desc_ticket', $r_data['desc_ticket']);
		$db_conn->fill_text('desc_register', $r_data['desc_register']);
		$db_conn->fill_text('amount', $r_data['amount']);
		$db_conn->fill_text('inc_exp', $r_data['inc_exp']);
		break;
	case "record_batch_add_pre":
		$db_conn->fill_html('func', '<h3>新增多筆帳目<\/h3>');
		break;
	case "record_batch_add":
		$db_conn->fill_html('func', '<h3>新增多筆帳目<\/h3>');
		$amount=$getdata['amount'];
		if($getdata['dist']=="hare")
		{
			$amt[1]=round($amount/2);
			$leftover=$amount-$amt[1];
			$left_proj=$getdata['num']-1;
			$remainder=$leftover%$left_proj;
			if($remainder>0)
			{
				$leftover=$leftover-$remainder;
				$amt[1]=$amt[1]+$remainder;
			}
			$to_each=$leftover/$left_proj;
			for($i=2;$i<=$getdata['num'];$i++)
			{
				$amt[$i]=$to_each;
			}
			$db_conn->fill_text('amt_0', round($amount/2));
		}
		else
		{
			$remainder=$amount%$getdata['num'];
			if($remainder>0)
			{
				$amount=$amount-$remainder;
			}
			$to_each=$amount/$getdata['num'];
			for($i=1;$i<=$getdata['num'];$i++)
			{
				$amt[$i]=$to_each;
			}
			if($remainder>0)
			{
				$amt[1]=$amt[1]+$remainder;
			}

		}
		for($i=1;$i<=$getdata['num'];$i++)
		{
			$db_conn->fill_html('project', '<select name="project[]" id="project_'.$i.'"></select>:<input type="text" name="amt[]" id="amt_'.$i.'" value=""><br>', "a");
			$db_conn->fill_option_project("project_".$i, $getdata['project']);
			$db_conn->fill_text('amt_'.$i, $amt[$i]);
		}		
		$db_conn->fill_text('amount', $getdata['amount']);
		$db_conn->fill_option_tag('type');
		$db_conn->fill_option_acct('acct_type');
		$db_conn->fill_option("yy", date("Y")+5, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm", 12);
		$db_conn->fill_option("dd", 31);
		break;
	case "fund_dist_pre":
		$db_conn->fill_html('func', '<h3>經費分配<\/h3>');
		$db_conn->fill_option_project("project");
		break;
	case "fund_dist":
		$db_conn->fill_html('func', '<h3>經費分配<\/h3>');
		$amount=$getdata['amount'];
		if($getdata['dist']=="hare")
		{
			$amt[1]=round($amount/2);
			$leftover=$amount-$amt[1];
			$left_proj=$getdata['num']-1;
			$remainder=$leftover%$left_proj;
			if($remainder>0)
			{
				$leftover=$leftover-$remainder;
				$amt[1]=$amt[1]+$remainder;
			}
			$to_each=$leftover/$left_proj;
			for($i=2;$i<=$getdata['num'];$i++)
			{
				$amt[$i]=$to_each;
			}
			$db_conn->fill_text('amt_0', round($amount/2));
		}
		else
		{
			$remainder=$amount%$getdata['num'];
			if($remainder>0)
			{
				$amount=$amount-$remainder;
			}
			$to_each=$amount/$getdata['num'];
			for($i=1;$i<=$getdata['num'];$i++)
			{
				$amt[$i]=$to_each;
			}
			if($remainder>0)
			{
				$amt[1]=$amt[1]+$remainder;
			}

		}
		for($i=1;$i<=$getdata['num'];$i++)
		{
			$db_conn->fill_html('project', '<select name="project[]" id="project_'.$i.'"></select>:<input type="text" name="amt[]" id="amt_'.$i.'" value=""><br>', "a");
			$db_conn->fill_option_project("project_".$i, $getdata['project']);
			$db_conn->fill_text('amt_'.$i, $amt[$i]);
		}		
		$db_conn->fill_text('amount', $getdata['amount']);
		$db_conn->fill_option_tag('type');
		$db_conn->fill_option_acct('acct_type');
		$db_conn->fill_option("yy", date("Y")+5, date("Y")-10, date("Y"));
		$db_conn->fill_option("mm", 12);
		$db_conn->fill_option("dd", 31);
		break;		
}
//message box

//echo $db_conn->project_tree(NULL, "+", "-", "-<br>", "", 0);
//

?>
<div id="res"></div>

</div>
</div>

<?
}
?>
