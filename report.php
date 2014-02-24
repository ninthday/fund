<?php
//header('Content-type: application/ms-excel');
//header('Content-Disposition: attachment; filename=xx'.$filename);


$projects=$db_handle->project_tree_as_array($postdata['project'], (array)$projects, 0);


//summary
if($postdata['format']=="sum")
{

	$html_table='<table class="default"><tr style="background-color:#CECECE;"><th>計畫名稱</th><th>預算</th><th>收入</th><th>支出</th><th>餘額</th><th>執行率</th></tr>';
	$sum['budget']=0;
	$sum['income']=0;
	$sum['expense']=0;
	$sum['balance']=0;
	if(count($projects)>0)
	{
		foreach($projects as $project)
		{
			$p_info=$db_handle->project_info($project['pid']);
			$income=$db_handle->project_sum_amount($project['pid'], 2, $postdata['timespan']);
			$expense=$db_handle->project_sum_amount($project['pid'], 1, $postdata['timespan']);
			$balance=$income-$expense;
			if($p_info['budget']!=0)
			{
				$execution=round(($expense/$p_info['budget']*100), 1). " %";
			}
			else
			{
				$execution='--';
			}
			$html_table=$html_table.'<td style="text-align:left;">';
			if($project['depth']>0)
			{
				//$html_table=$html_table."─";
				for($i=0;$i<$project['depth'];$i++)
				{
					$html_table=$html_table."　";
				}
			}
            $html_table=$html_table.$p_info['name'];
            //$html_table .= '　〈預算：'.$p_info['budget'].'〉';
			$html_table=$html_table.'</td>';
			$html_table=$html_table.'<td>';
			$html_table=$html_table.$p_info['budget'];
			$sum['budget']=$sum['budget']+$p_info['budget'];
			$html_table=$html_table.'</td>';
			$html_table=$html_table.'<td>';
			$html_table=$html_table.$income;
			$sum['income']=$sum['income']+$income;
			$html_table=$html_table.'</td>';
			$html_table=$html_table.'<td>';
			$html_table=$html_table.$expense;
			$sum['expense']=$sum['expense']+$expense;
			$html_table=$html_table.'</td>';
			$html_table=$html_table.'<td>';
			$html_table=$html_table.$balance;
			$sum['balance']=$sum['balance']+$balance;
			$html_table=$html_table.'</td>';
			$html_table=$html_table.'<td>';
			$html_table=$html_table.$execution;
			$html_table=$html_table.'</td>';	
			$html_table=$html_table.'</tr>';
		}

		$html_table=$html_table.'<tr><td>總計</td><td>'.$sum['budget'].'</td><td>'.$sum['income'].'</td><td>'.$sum['expense'].'</td><td>'.$sum['balance'].'</td><td>&nbsp;</td></tr>';

		if($postdata['dl']=="xls")
		{
			$filename="./".time().".xls";
			$handle=fopen($filename, 'a+');
			fwrite($handle, $html_table);
			fclose($handle);
			echo '<a href="'.$filename.'">下載檔案</a>';
			//refresh_page(1, $filename);
		}
		else
		{
			echo $html_table;
		}

	}

}

//detail
if($postdata['format']=="detail")
{
	$html_table=$edit_table='<table class="default">';
	$template='
		<tr>
		<th>&nbsp;</th>
		<th>經費用途</th>
		<th>傳票日</th>
		<th>傳票號</th>
		<th>會計科目</th>
		<th>請購單號</th>
		<th>金額</th>
		<th>摘要</th>
		<th>請購人</th>
		</tr>';
	if(count($projects)>0)
	{	
		foreach($projects as $project)
		{
			$p_info=$db_handle->project_info($project['pid']);
            $income=(int)$db_handle->project_sum_amount($project['pid'], 2, $postdata['timespan']);
			$expense=(int)$db_handle->project_sum_amount($project['pid'], 1, $postdata['timespan']);
			$balance=$income-$expense;
			if($p_info['budget']!=0)
			{
				$execution=round(($expense/$p_info['budget']*100), 1). " %";
			}
			else
			{
				$execution='--';
			}

            $html_table=$html_table.'<tr><td colspan=8><strong>';
			$edit_table=$edit_table.'<tr><td colspan=9><strong>';
            if($project['depth']>0)
			{
				for($i=0;$i<$project['depth'];$i++)
				{
					$html_table=$html_table."　";
					$edit_table=$edit_table."　";
				}
			}
			$html_table=$html_table.$p_info['name'].'</strong>　〈預算：'.$p_info['budget'].'　收入：'.$income.'　支出：'.$expense.'　執行率：'.$execution.'〉</td></tr>';
			$edit_table=$edit_table.$p_info['name'].'</strong>　〈預算：'.$p_info['budget'].'　收入：'.$income.'　支出：'.$expense.'　執行率：'.$execution.'〉</td></tr>';
			$records=$db_handle->record_read_by_project($project['pid'], $postdata['timespan']);
			if(count($records)>0)
			{
				$html_table=$html_table.$template;
				foreach($records as $record)
				{
					$html_table=$html_table.'
						<tr>
						<td></td>
						<td></td>
						<td>'.date("Y/m/d", $record['record_time']).'</td>
						<td>'.$record['desc_register'].'</td>
						<td></td>
						<td>'.$record['desc_ticket'].'</td>
						<td>'.$record['amount'].'</td>
						<td>'.$record['desc'].'</td>
						<td></td>
						</tr>';
				}

				$edit_table=$edit_table.$template;
				foreach($records as $record)
				{
					$edit_table=$edit_table.'
						<tr>
						<td>';
					if($_SESSION['gid']!='4')
					{
						$edit_table.='<a href="?p=record_modify&amp;rid='.$record['r_id'].'">編輯</a>&nbsp;';
						$edit_table.='<a href="?p=record_add&amp;rid='.$record['r_id'].'">複製</a>';
				$html=$html.'<\/td>';
					}
					$edit_table.='</td>
						<td></td>
						<td>'.date("Y/m/d", $record['record_time']).'</td>
						<td>'.$record['desc_register'].'</td>
						<td></td>
						<td><a href="http://bookkeeping.cs.nccu.edu.tw/handler.php?q=record_view_search&desc_ticket='.$record['desc_ticket'].'" target="_blank">'.$record['desc_ticket'].'</a></td>
						<td>'.$record['amount'].'</td>
						<td>'.$record['desc'].'</td>
						<td></td>
						</tr>';
				}
			}
			else
			{
				//$html_table=$html_table.'<tr><td colspan=8>--無紀錄--</td></tr>';	
			}
		}

		$html_table=$html_table.'</table>';

		if($postdata['dl']=="xls")
		{
			$filename="./xls/".time().".xls";
			$handle=fopen($filename, 'a+');
			fwrite($handle, $html_table);
			fclose($handle);
			echo '<a href="'.$filename.'">下載檔案</a>';
			//refresh_page(1, $filename);
		}
		else
		{
			echo $edit_table;
		}	

	}
}

?>
