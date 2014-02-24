<?php
session_start();

//block direct access and not login access
if(isset($_SESSION['auth']))
{
	require_once('include/master.inc.php');
	$db_handle=new bookdata($db_host, $db_user, $db_pass, $db_name);
	// create db object
	// for every post data:
	// get POST data
	// sanitize with real_escape_string()
    $postdata=sanitize($_POST);
    $getdata=sanitize($_GET);
    $output='';
    // exception : direct query number
    if ($getdata['q']=='record_view_search')
    {
        $postdata = $getdata;
        $output = 'raw';
    }

	// do query
	// 	on error
	// 		return error message
	// 	on success
	// 		hide submit form (optional, mostly in insert forms)
	// 		return result...usually table, maybe DOM method would be better?
	//	

	//echo $_POST['test'];

	if(!isset($postdata['q']))
	{
		refresh_page(1, "index.php");
	}

	switch ($postdata['q'])
	{

	case "user_add":
		if($postdata['username']=="" || $postdata['email']=="")
		{
			die("帳號與email欄位不得空白。");
		}
		else
		{
			$db_handle->user_add($postdata['username'], $postdata['group'], $postdata['cname'], $postdata['email']);
			hide_element('frm', '使用者新增成功。');
		}
		break;

	case "user_modify":
		if ($postdata['username']=="" || $postdata['email']=="" && $postdata['uid']=="")
		{
			die("Error");
		}
		else
		{
			$db_handle->user_modify($postdata['uid'], $postdata['username'], $postdata['group'], $postdata['cname'], $postdata['email']);
			hide_element('frm', '使用者修改成功。');
		}
		break;

	case "project_add":
		if($postdata['budget']=="")
		{
			$postdata['budget']==NULL;
		}
		if ($postdata['name']=="" || $postdata['start_time']=="" )
		{
			die("您輸入的資料有錯誤，請檢查後再送出。");
		}
		else
		{
			if ($postdata['parent']=="")
			{
				$new_pid=$db_handle->project_add($postdata['name'], $postdata['desc'], $postdata['status'], $postdata['start_time'], $postdata['stop_time'], $postdata['period'], $postdata['budget']);
			}
			else
			{
				$new_pid=$db_handle->project_add($postdata['name'], $postdata['desc'], $postdata['status'], $postdata['start_time'], $postdata['stop_time'], $postdata['period'], $postdata['budget'], $postdata['parent']);
			}
			if (count($_POST['user'])>1)
			{
				foreach ($_POST['user'] as $owner)
				{
					$db_handle->project_own_add($new_pid, $owner);
				}
			}
			hide_element('frm', '計畫新增成功。');
		}
		break;

	case "project_modify":
		if($postdata['budget']=="")
		{
			$postdata['budget']==NULL;
		}
		if ($postdata['name']=="" || $postdata['start_time']=="")
		{
			die("您輸入的資料有錯誤，請檢查後再送出。");
		}
		elseif ($postdata['start_time'] > $postdata['stop_time'] && $postdata['period'] == '3')
		{
			die("計畫結束時間有誤，請檢查後再送出。");
		}
		else
		{
			$db_handle->project_modify($postdata['pid'], $postdata['name'], $postdata['desc'], $postdata['status'], $postdata['start_time'], $postdata['stop_time'], $postdata['period'], $postdata['budget']);

			$db_handle->project_owner_remove($postdata['pid']);
			if(count($_POST['user'])>=1)
			{
				foreach($_POST['user'] as $owner)
				{
					$db_handle->project_own_add($postdata['pid'], $owner);
				}
			}
			hide_element('frm', '計畫更改成功。');
		}
		break;
	case "tag_add":
		if ($postdata['name']=="")
		{
			die("請輸入分類名稱。");
		}
		else
		{
			$db_handle->tag_add($postdata['name'], $postdata['desc']);
			hide_element('frm', '分類新增成功。');
		}
		break;

	case "tag_modify":
		if ($postdata['name']=="")
		{
			die("請輸入分類名稱。");
		}
		else
		{
			$db_handle->tag_modify($postdata['t_id'], $postdata['name'], $postdata['desc']);
			hide_element('frm', '分類修改成功。');
		}
		break;
	case "acct_add":
		if ($postdata['name']=="")
		{
			die("請輸入會計科目名稱。");
		}
		else
		{
			$db_handle->acct_add($postdata['name'], $postdata['desc']);
			hide_element('frm', '會計科目新增成功。');
		}
		break;

	case "acct_modify":
		if ($postdata['name']=="")
		{
			die("請輸入會計科目名稱。");
		}
		else
		{
			$db_handle->acct_modify($postdata['a_id'], $postdata['name'], $postdata['desc']);
			hide_element('frm', '會計科目修改成功。');
		}
		break;
	case "record_add":
		if($postdata['desc']=="")
		{
			die("請輸入帳目摘要。");
		}
		elseif($postdata['amount']=="")
		{
			die("請輸入金額。");
		}
		else
		{
			$new_record = $db_handle->record_add($postdata['project'], $postdata['type'], $postdata['acct_type'], $postdata['inc_exp'], $postdata['amount'], $postdata['desc'], $postdata['record_time'], $postdata['desc_ticket'], $postdata['desc_register']);
			hide_element('frm', '帳目新增成功。<br><a href="?p=record_add&rid='.$new_record.'">複製此筆帳目並再新增帳目</a>');
		}
		break;
	case "record_modify":
		if($postdata['desc']=="")
		{
			die("請輸入帳目摘要。");
		}
		elseif($postdata['amount']=="")
		{
			die("請輸入金額。");
		}
		else
		{
			$db_handle->record_modify($postdata['rid'], $postdata['project'], $postdata['type'], $postdata['acct_type'], $postdata['inc_exp'], $postdata['amount'], $postdata['desc'], $postdata['record_time'], $postdata['desc_ticket'], $postdata['desc_register']);
			hide_element('frm', '帳目修改成功。<br><a href="?p=record_add&rid='.$postdata['rid'].'">複製此筆帳目並再新增帳目</a>');
		}
		break;
	case "record_add_dist":
		//print_r($_POST);
		//print_r($_POST['project']);
		$projects=sanitize($_POST['project']);
		$amount=sanitize($_POST['amt']);

		if(array_sum($amount) != $postdata['amount'])
		{
			die("金額總額與原始金額不符，請檢查後重試。");
		}
		elseif($postdata['desc']=="")
		{
			die("請輸入帳目摘要。");
		}
		else
		{
			$i=0;
			foreach($projects as $project)
			{
				$db_handle->record_add($project, $postdata['type'], $postdata['acct_type'], $postdata['inc_exp'], $amount[$i], $postdata['desc'], $postdata['record_time'], $postdata['desc_ticket'], $postdata['desc_register']);
				$i++;
			}
			hide_element('frm', '帳目分配成功。');
		}

		break;

	case "record_view_project":
		//echo "<iframe>";
		if($postdata['r'] && !$postdata['project'])
		{
			die("請選擇計畫。");
		}
		require_once('report.php');
		//echo "</iframe>";
		break;
	case "record_view_timespan":
		if($postdata['record_time_start'] > $postdata['record_time_end'])
		{
			$temp = $postdata['record_time_start'];
			$postdata['record_time_start'] = $postdata['record_time_end'];
			$postdata['record_time_end'] = $temp;
		}
		$db_handle->fill_table_record_timespan('tbl', $postdata['record_time_start'], $postdata['record_time_end']);
		break;
	case "record_view_search":
        if ($output == 'raw')
        {
        	require_once('include/htm/header.htm');	
            echo '<div class="main"><hr noshade style="color:#EEE;">';
            require_once('include/htm/form_record_browse_search.htm');
            echo '</div>';
        }
        $db_handle->record_read_condition('tbl', $postdata['project'], $postdata['type'], $postdata['acct_type'], $postdata['desc'], $postdata['desc_ticket']);
		break;
	}


}

?>
