<?
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ri.ci_idx = '" . $client_idx . "'";
	if ($shstatus != '' && $shstatus != 'all') // 접수상태
	{
		if ($shstatus == 'no_list')
		{
			$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
		}
		else
		{
			$where .= " and ri.receipt_status = '" . $shstatus . "'";
		}
	}
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = receipt_info_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shstatus=' . $send_shstatus;
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
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list         = $local_dir . "/bizstory/work/receipt_list.php";      // 목록
	$link_form         = $local_dir . "/bizstory/work/receipt_form.php";      // 등록
	$link_view         = $local_dir . "/bizstory/work/receipt_view.php";      // 보기
	$link_ok           = $local_dir . "/bizstory/work/receipt_ok.php";        // 저장
	$link_excel        = $local_dir . "/bizstory/work/receipt_excel.php";     // 액셀
	$link_print        = $local_dir . "/bizstory/work/receipt_print.php";     // 인쇄
	$link_print_detail = $local_dir . "/bizstory/work/receipt_print_sel.php"; // 상세인쇄

	$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big fr"><span>접수등록</span></a>';
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom">
		<?=$btn_write;?>
		<a href="javascript:void(0);" class="btn_sml fr btn_b1" onclick="list_no_search()"><span>미처리건</span></a>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="100px" />
		<col />
		<col width="100px" />
		<col width="70px" />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="riidx" onclick="check_all('riidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3>제목</h3></th>
			<th class="nosort"><h3>등록자</h3></th>
			<th class="nosort"><h3>상태</h3></th>
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
			<td colspan="8">등록된 데이타가 없습니다.</td>
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
				$list_data = receipt_list_data($data['ri_idx'], $data);

				$rc_data['chk_ci']  = $data['client_idx'];
				$rc_data['chk_mac'] = $macaddress;
				$rc_data['ri_idx']  = $data['ri_idx'];
				$read_chk = receipt_read_check($rc_data);
				$read_comment = $read_chk['read_comment'];
?>
		<tr>
			<td><input type="checkbox" id="riidx_<?=$i;?>" name="chk_ri_idx[]" value="<?=$data["ri_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$list_data['receipt_class_str']['first_class'];?></td>
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

		if ($read_comment > 0)
		{
			echo '
					<span class="today_num" title="읽을 코멘트"><em>', number_format($read_comment), '</em></span>';
		}
	?>
				</div>
			</td>
			<td><span class="num"><?=$list_data['receipt_name'];?></span></td>
			<td><?=$list_data['receipt_status_str'];?></td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y.m.d');?></span></td>
			<td><span class="num"><?=date_replace($data['end_date'], 'Y.m.d');?></span></td>
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
