<?
/*
	수정 : 2012.10.31
	위치 : 알림게시판 - 목록
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "
		and abn.comp_idx = '" . $client_comp . "'
		and abn.part_idx = '" . $client_part . "'
		and (
			if (abn.client_type = '1'
				, if (abn.ccg_idx = '" . $client_ccg_idx . "', 'Y', 'N')
				, if (abn.client_type = '3'
					, if (concat(abn.ci_idx, ',') like '%," . $client_idx . ",%', 'Y', 'N')
					, 'Y')
			) = 'Y'
		)";

	if ($sbclass != '' && $sbclass != 'all') // 분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $sbclass . ",%' or abn.bnotice_class = '" . $sbclass . "')";
	}
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = agent_bnotice_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . 'swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sbclass=' . $send_sbclass;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="comp_idx"    value="' . $client_comp . '" />
		<input type="hidden" name="part_idx"    value="' . $client_part . '" />
		<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
		<input type="hidden" name="client_code" value="' . $client_code . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
		<input type="hidden" name="sbclass" value="' . $send_sbclass . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col width="100px" />
		<col />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3>제목</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="4">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$list_data = agent_bnotice_list_data($data, $data['abn_idx']);
?>
		<tr>
			<td><?=$num;?></td>
			<td><?=$list_data['class_str']['first_class'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="view_open('<?=$list_data["abn_idx"];?>')">
		<?
			if ($list_data['bnotice_check'] > 0)
			{
				echo '<span class="no_read">', $list_data['subject'], '</span>';
			}
			else
			{
				echo $list_data['subject'];
			}
		?>
					</a>
					<?=$list_data['important_img'];?>
					<?=$list_data['file_str'];?>
				</div>
			</td>
			<td><span class="num"><?=date_replace($list_data['reg_date'], 'Y.m.d');?></span></td>
		</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>