<?
/*
	생성 : 2012.10.12
	수정 : 2012.10.12
	위치 : 상담게시판 - 목록
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and cons.ci_idx = '" . $client_idx . "' and cons.macaddress = '" . $macaddress . "'";
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'cons.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = consult_info_data('list', $where, $orderby, $page_num, $page_size);

	$page_num = $list['page_num'];

	$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_ins fr"><span>등록</span></a>';

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
	<div class="etc_bottom">
		<?=$btn_down;?>
		<?=$btn_print;?>
		<?=$btn_print_sel;?>
		<?=$btn_write;?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col width="100px" />
		<col />
		<col width="100px" />
		<col width="100px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'cons.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록자', 'cons.writer');?></h3></th>
			<th class="nosort"><h3>담당자</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="6">등록된 데이타가 없습니다.</td>
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
				$list_data = consult_list_data($data['cons_idx'], $data);
?>
		<tr>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$list_data['class_str']['first_class'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="view_open('<?=$data["cons_idx"];?>')"><?=$list_data['subject'];?></a>
					<?=$list_data['important_str'];?>
					<?=$list_data['total_file_str'];?>
					<?=$list_data['total_comment_str'];?>
					<?=$list_data['read_comment_str'];?>
				</div>
			</td>
			<td><span class="num"><?=$list_data['consult_name'];?></span></td>
			<td><?=$list_data['charge_list'];?></td>
			<td>
				<span class="num"><?=date_replace($list_data['reg_date'], 'Y.m.d');?><?=$list_data['end_date_str'];?></span>
			</td>
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
