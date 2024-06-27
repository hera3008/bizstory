<?
/*
	생성 : 2012.12.26
	수정 : 2012.12.26
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 최근업데이트목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and prosh.pro_idx = '" . $pro_idx . "'";
	$list = project_status_history_data('list', $where, '', '', '');
?>
<div class="update">
	<table class="list_update">
		<tbody>
<?
	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
?>
			<tr>
				<td class="date"><?=date_replace($data['reg_date'], 'Y-m-d H:i');?></td>
				<td class="subject">
					<?=$data['status_memo'];?>
		<?
			if ($data['contents'] != '') echo '<br />', $data['contents'];
		?>
				</td>
				<td class="charge"><?=$data['mem_name'];?></td>
			</tr>
<?
			$num--;
		}
	}
?>
		</tbody>
	</table>
</div>