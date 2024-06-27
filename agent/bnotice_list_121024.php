<?
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

// 해당 그룹값 가지고 오기
	$cgg_where = '';
	//echo 'client_ccg_idx -> ', $client_ccg_idx, '<br/>';
	if ($client_ccg_idx > 0)
	{
		$chk_where = " and ccg.ccg_idx = '" . $client_ccg_idx . "'";
		$chk_data = company_client_group_data('view', $chk_where);

		$up_ccg_idx = $chk_data['up_ccg_idx'];
		$ccg_level  = $chk_data['menu_depth'];

		$ccg_idx_arr = explode(',', $up_ccg_idx);
		$chk_i = 1;
		foreach ($ccg_idx_arr as $k => $v)
		{
			if ($v != '')
			{
				$chk_where1 = " and ccg.ccg_idx = '" . $v . "'";
				$chk_data1 = company_client_group_data('view', $chk_where1);

				$chk_depth = $chk_data1['menu_depth'];

				$str[$chk_depth]['idx'] = $chk_data1['ccg_idx'];
				if ($chk_i == 1)
				{
					$cgg_where .= "abn.ccg_idx = '" . $chk_data1['ccg_idx'] . "'";
				}
				else
				{
					$cgg_where .= " or abn.ccg_idx = '" . $chk_data1['ccg_idx'] . "'";
				}
				$chk_i++;
			}
		}
		$str[$ccg_level]['idx'] = $chk_data['ccg_idx'];
		if ($chk_i == '1')
		{
			$cgg_where .= " abn.ccg_idx = '" . $chk_data['ccg_idx'] . "'";
		}
		else
		{
			$cgg_where .= " or abn.ccg_idx = '" . $chk_data['ccg_idx'] . "'";
		}
	}
	else
	{
		$cgg_where = "abn.ccg_idx = '0'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "
		and abn.comp_idx = '" . $client_comp . "'
		and abn.part_idx = '" . $client_part . "'
		and (
			-- abn.ccg_idx = '0'
			" . $cgg_where . "
			or abn.client_all = 'Y'
			or concat(abn.ci_idx, ',') like '%," . $client_idx . ",%'
		)";
		//echo 'where -> ', $where, '<br />';
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = agent_bnotice_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
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
		<col />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort">번호</th>
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
			<td colspan="3">등록된 데이타가 없습니다.</td>
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
			// 중요도
				if ($data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
				else if ($data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
				else if ($data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
				else $important_span = '';

			// 첨부파일
				$file_where = " and abnf.abn_idx = '" . $data['abn_idx'] . "'";
				$file_list = agent_bnotice_file_data('page', $file_where);
				$total_file = $file_list['total_num'];
?>
		<tr>
			<td><?=$num;?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="view_open('<?=$data["abn_idx"];?>')"><?=$data['subject'];?></a>
					<?=$important_span;?>
	<?
		if ($total_file > 0)
		{
			echo '
					<span class="attach" title="첨부파일">', number_format($total_file), '</span>';
		}
	?>
				</div>
			</td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y.m.d');?></span></td>
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