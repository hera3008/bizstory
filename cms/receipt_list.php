<?
/*
	수정 : 2012.05.08
	위치 : 접수 - 목록
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ri.comp_idx = '" . $client_comp . "' and ri.ci_idx = '" . $client_idx . "'";
	if ($shclass != '' && $shclass != 'all') // 접수분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $shclass . ",%'";
		$shclass_arr = explode(',', $shclass);
		foreach ($shclass_arr as $shclass_k => $shclass_v)
		{
			if ($shclass_k > 0) $where .= " or ri.receipt_class = '" . $shclass_v . "'";
		}
		$where .= ")";
	}
	if ($shstatus != '' && $shstatus != 'all') // 접수상태
	{
		if ($shstatus == 'no_list')
		{
			$where .= " and (code2.code_value <> '99' and code2.code_value <> '90' and code2.code_value <> '80')";
		}
		else
		{
			$where .= " and ri.receipt_status = '" . $shstatus . "'";
		}
	}
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'ri.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(ri.tel_num, '-', '') like '%" . $stext . "%' or
				replace(ri.tel_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ri.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = receipt_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom">
		<span class="btn_big fr"><input type="button" value="접수등록" onclick="data_form_open('')" /></span>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col width="100px" />
		<col />
		<col width="70px" />
		<col width="80px" />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'ri.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('상태', 'code2.sort');?></h3></th>
			<th class="nosort"><h3>접수자</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
			<th class="nosort"><h3>완료일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="7">등록된 데이타가 없습니다.</td>
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
				$receipt_class = receipt_class_view($data['receipt_class']); // 분류

			// 댓글수
				$sub_where = " and rc.ri_idx='" . $data['ri_idx'] . "'";
				$sub_data = receipt_comment_data('page', $sub_where);
				$data['total_tail'] = $sub_data['total_num'];

			// 접수상태
				$receipt_status_str = '<span style="';
				if ($data['receipt_status_bold'] == 'Y') $receipt_status_str .= 'font-weight:900;';
				if ($data['receipt_status_color'] != '') $receipt_status_str .= 'color:' . $data['receipt_status_color'] . ';';
				$receipt_status_str .= '">' . $data['receipt_status_str'] . '</span>';

				if ($data['status_del_yn'] == 'Y') $status_str = '<span style="color:#CCCCCC">' . $data['receipt_status_str'] . '</span>';
				else $status_str = $receipt_status_str;

			// 접수자, 전화번호
				$tel_num = str_replace('-', '', $data['tel_num']);
				$tel_num = str_replace('.', '', $tel_num);
				if ($tel_num == '')
				{
					$receipt_name = $data['writer'];
				}
				else
				{
					$receipt_name = $data['writer'] . '<br />(' . $data['tel_num'] . ')';
				}

				$list_data = receipt_list_data($data['ri_idx'], $data);
?>
		<tr>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$receipt_class['first_class'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="view_open('<?=$data["ri_idx"];?>')"><?=$list_data['subject'];?></a>
					<?=$list_data['important_str'];?>
	<?
		if ($list_data['total_file'] > 0)
		{
			echo '
					<span class="attach" title="첨부파일">', number_format($list_data['total_file']), '</span>';
		}
		if ($list_data['total_comment'] > 0)
		{
			echo '
					<span class="cmt" title="코멘트">', number_format($list_data['total_comment']), '</span>';
		}

		if ($read_work > 0)
		{
			echo '
					<span class="today_num" title="읽을 코멘트"><em>', number_format($read_work), '</em></span>';
		}
	?>
				</div></td>
			<td><?=$list_data['receipt_status_str'];?></td>
			<td><?=$list_data['writer'];?></td>
			<td><span class="num"><?=date_replace($list_data['reg_date'], 'Y.m.d');?></span></td>
			<td><span class="num"><?=date_replace($list_data['end_date'], 'Y.m.d');?></span></td>
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
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />