<?
/*
	생성 : 2013.03.08
	생성 : 2013.05.02
	위치 : 파일센터 > 파일관리 - 목록 - 파일이력
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$where   = " and fh.fi_idx = '" . $fi_idx . "'";
	$list = filecenter_history_data('list', $where, '', $m_page_num, $m_page_size, 0);
?>
<table class="tinytable">
<colgroup>
	<col />
	<col width="150px" />
</colgroup>
<tbody>
<?
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$charge_str = staff_layer_form($data['reg_id'], '', 'N', $set_color_list2, 'historyliststtaff', $data['fh_idx'], '');

			$reg_type1    = $data['reg_type'];
			$reg_type_arr = explode('(', $reg_type1);
			$reg_type     = $reg_type_arr[0];

			if ($reg_type == 'insert')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['new_subject'] . ' 파일을 <strong style="color:#0075c8;">[등록]</strong> 했습니다.';
			}
			else if ($reg_type == 'update')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['old_subject'] . ' 파일을 ' . $data['new_subject'] . ' 파일로 <strong style="color:#0075c8;">[파일명 수정]</strong> 했습니다.';
			}
			else if ($reg_type == 'file_update')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['new_subject'] . ' 파일을 <strong style="color:#0075c8;">[수정등록]</strong> 했습니다.';
			}
			else if ($reg_type == 'copy')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['new_subject'] . ' 파일을 <strong style="color:#0075c8;">[복사]</strong> 했습니다.';
			}
			else if ($reg_type == 'move')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['new_subject'] . ' 파일을 <strong style="color:#0075c8;">[이동]</strong> 했습니다.';
			}
			else if ($reg_type == 'download')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['new_subject'] . ' 파일을 <strong style="color:#0075c8;">[다운로드]</strong> 했습니다.';
			}
			else if ($reg_type == 'delete')
			{
				$file_reg = '- ' . $charge_str . ' 님이 ' . $data['new_subject'] . ' 파일을 <strong style="color:#0075c8;">[삭제]</strong> 했습니다.';
			}
?>
	<tr>
		<td>
			<div class="left"><?=$file_reg;?></div>
		<?
			if ($data['reg_type'] != 'update' && $data['reg_type'] != 'delete')
			{
		?>
			<div class="left history_st">
		<?
				echo '<a href="http://' . $filecneter_url . '/filemanage/file_download.php?fh_type=history&amp;idx=' . $data['fh_idx'] . '" title="' . $data['new_subject'] . ' 다운로드">' . $data['new_subject'] . '</a>';
		?>
			</div>
		<?
			}
		?>
		</td>
		<td class="date"><?=date('Y-m-d H:i:s', $data['reg_date']);?></td>
	</tr>
<?
			$num--;
		}
	}
?>
</tbody>
</table>

<input type="hidden" id="history_new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div class="tablefooter_m">
<?
	$str = '
	<div class="tablenav_m">
		<ul>
			<li><a href="javascript:void(0);" onclick="page_move_history(\'first\')" class="first">First Page</a></li>
			<li><a href="javascript:void(0);" onclick="page_move_history(\'prev\')" class="previous">Previous Page</a></li>
			<li><a href="javascript:void(0);" onclick="page_move_history(\'next\')" class="next">Next Page</a></li>
			<li><a href="javascript:void(0);" onclick="page_move_history(\'last\')" class="last">Last Page</a></li>
			<li><a href="javascript:void(0);" onclick="page_move_history(\'all\')" class="showall">View All</a></li>
			<li>
				<select id="history_page_page_num" name="m_page_num" title="페이지 선택" onchange="page_move_history(this.value)">';
	for ($i = 1; $i <= $list['total_page']; $i++)
	{
		$str .= '
					<option value="' . $i . '"' . selected($m_page_num, $i) . '>' . $i . '</option>';
	}
	$str .= '
				</select>
			</li>
		</ul>
	</div>
	<div class="tablelocation_m">
		<select id="history_page_page_size" name="m_page_size" title="출력게시물수 선택" onchange="history_list_data()">
			<option value="5"' . selected($m_page_size, '5') . '>5</option>
			<option value="10"' . selected($m_page_size, '10') . '>10</option>
			<option value="15"' . selected($m_page_size, '15') . '>15</option>
			<option value="20"' . selected($m_page_size, '20') . '>20</option>
			<option value="30"' . selected($m_page_size, '30') . '>30</option>
			<option value="40"' . selected($m_page_size, '40') . '>40</option>
			<option value="60"' . selected($m_page_size, '60') . '>60</option>
			<option value="80"' . selected($m_page_size, '80') . '>80</option>
			<option value="100"' . selected($m_page_size, '100') . '>100</option>';
	if ($m_page_size > 100)
	{
		$str .= '
			<option value="' . $m_page_size . '"' . selected($m_page_size, $m_page_size) . '>' . $m_page_size . '</option>';

	}
	$str .= '
		</select>
		<span>Entries Per Page</span> - Page ' . $m_page_num . '/' . $list['total_page'] . '
	</div>';
	echo $str;
?>
</div>
<div class ="clear"></div>
<hr />