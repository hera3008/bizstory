<?
/*
	생성 : 2012.05.24
	수정 : 2012.06.08
	위치 : 설정폴더(총관리자용) > 푸쉬관리 > 푸시이력- 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ph.comp_idx > 0";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ph.request_time';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = push_history_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
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
</div>

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="150px" />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="80px" />
		<col />
		<col width="130px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="phidx" onclick="check_all('phidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('업체명', 'comp.comp_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('수신자', 'ph.receiver_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('서비스구분', 'ph.service_type');?></h3></th>
			<th class="nosort"><h3><?=field_sort('메세지구분', 'ph.msg_type');?></h3></th>
			<th class="nosort"><h3>전송상태</h3></th>
			<th class="nosort"><h3>상태설명</h3></th>
			<th class="nosort"><h3><?=field_sort('등록일', 'ph.reg_date');?></h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="9">등록된 데이타가 없습니다.</td>
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
?>
		<tr>
			<td><input type="checkbox" id="phidx_<?=$i;?>" name="chk_ph_idx[]" value="<?=$data["ph_idx"];?>" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><div class="left"><?=$data["comp_name"];?></div></td>
			<td><?=$data["mem_name"];?></td>
			<td><?=$set_push_service[$data["service_type"]];?></td>
			<td><?=$set_push_msg[$data["msg_type"]];?></td>
			<td><?=$set_push_status[$data["state"]];?></td>
			<td><div class="left"><?=$data["state_comment"];?></div></td>
			<td><span class="eng"><?=$data['request_time'];?></span></td>
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