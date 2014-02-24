<?php
function is_selected($e)
{
	if(!isset($_GET['p']) && $e=="")
	{
		echo 'class="selected"';
	}
	else if($_GET['p']==$e)
	{
		echo 'class="selected"';
	}
}

//var_dump($_SESSION);

if($_SESSION['gid']=='4')
{
//menu for read-only users
?>
<div class="sidebar">
<ul>
<li <?php is_selected("");?>><a href="index.php">首頁</a></li>
<li class="divider">&nbsp;</li>
<li <?php is_selected("record_browse");?>><a href="?r=record_view">瀏覽帳目</a></li>
<li class="divider">&nbsp;</li>
<!-- li><a href="?p=7">管理群組</a></li>
<li class="divider">&nbsp;</li>
<li><a href="?p=8">帳目通知</a></li>
<li><a href="?p=9">個人資料</a></li -->
<li><a href="logout.php">登出</a></li>
</ul>
</div>

<?
}
else
{
//menu for administrative users
?>

<div class="sidebar">
<ul>
<li <?php is_selected("");?>><a href="index.php">首頁</a></li>
<li class="divider">&nbsp;</li>
<li <?php is_selected("record_browse"); is_selected("record_browse_project"); is_selected("record_browse_timespan"); is_selected("record_browse_search");?>><a href="?p=record_browse">瀏覽帳目</a></li>
<li <?php is_selected("record_add");?>><a href="?p=record_add">新增帳目</a></li>
<li <?php is_selected("record_batch_add"); is_selected("record_batch_add_pre");?>><a href="?p=record_batch_add_pre">新增多筆帳目</a></li>
<li <?php is_selected("fund_dist"); is_selected("fund_dist_pre");?>><a href="?p=fund_dist_pre">經費分配</a></li>
<li <?php is_selected("tag_manage"); is_selected("tag_add"); is_selected("tag_modify");?>><a href="?p=tag_manage">帳目分類</a></li>
<li <?php is_selected("acct_manage");?>><a href="?p=acct_manage">會計科目</a></li>
<li <?php is_selected("project_manage");?>><a href="?p=project_manage">管理計畫</a></li>

<li class="divider">&nbsp;</li>
<li <?php is_selected("user_add");?>><a href="?p=user_add">新增使用者</a></li>
<li <?php is_selected("user_manage");?>><a href="?p=user_manage">管理使用者</a></li>
<!-- li><a href="?p=7">管理群組</a></li>
<li class="divider">&nbsp;</li>
<li><a href="?p=8">帳目通知</a></li>
<li><a href="?p=9">個人資料</a></li -->
<li class="divider">&nbsp;</li>
<li><a href="logout.php">登出</a></li>
</ul>
</div>

<?
}
?>

