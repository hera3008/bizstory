<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = '';
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$page_data = service_info_data('page', $where, '', '', $page_size);
	if ($page_num > $page_data['total_page'])
	{
		$page_num      = $page_data['total_page'];
		$send_page_num = $page_num;
		$recv_page_num = $page_num;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'si.sort';
	if ($sorder2 == '') $sorder2 = 'asc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $send_page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $send_page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$list = service_info_data('list', $where, $orderby, $page_num, $page_size);
?>
<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="80px" />
		<col />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="60px" />
		<col width="60px" />
		<col width="120px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="csidx" onclick="check_all('csidx', this);" /></th>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>서비스명</h3></th>
			<th class="nosort"><h3>지사수</h3></th>
			<th class="nosort"><h3>거래처수</h3></th>
			<th class="nosort"><h3>배너수</h3></th>
			<th class="nosort"><h3>SMS수</h3></th>
			<th class="nosort"><h3>가격</h3></th>
			<th class="nosort"><h3>보기</h3></th>
			<th class="nosort"><h3>기본</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="11">등록된 데이타가 없습니다.</td>
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
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from service_info where del_yn = 'N'");

				$btn_up = ''; $btn_down = ''; $btn_view = ''; $btn_default = '';
				$btn_modify = '';
				if ($auth_menu['mod'] == "Y")
				{
					$btn_up      = " class=\"pointer\" onclick=\"check_sort('sort_up', '" . $data["si_idx"] . "')\"";
					$btn_down    = " class=\"pointer\" onclick=\"check_sort('sort_down', '" . $data["si_idx"] . "')\"";
					$btn_view    = " class=\"pointer\" onclick=\"check_yn('view_yn', '" . $data["si_idx"] . "', '" . $data["view_yn"] . "')\"";
					$btn_default = " class=\"pointer\" onclick=\"check_yn('default_yn', '" . $data["si_idx"] . "', '" . $data["default_yn"] . "')\"";
					$btn_modify  = '<a href="' . $local_dir . '?sub_type=modifyform&amp;si_idx=' . $data['si_idx'] . '&amp;' . $f_all . '" class="btn_con_blue"><span>수정</span></a>';
				}

				$btn_delete = '';
				if ($auth_menu['del'] == "Y")
				{
					$btn_delete = '<a href="javascript:void(0);" onclick="del_chk(\'' . $data["si_idx"] . '\')" class="btn_con_red"><span>삭제</span></a>';
				}
?>
		<tr>
			<td><input type="checkbox" id="csidx_<?=$i;?>" name="chk_si_idx[]" value="<?=$data["si_idx"];?>" /></td>
			<td>
<?
			if ($sort_data["min_sort"] != $data["sort"] && $sort_data["min_sort"] != "") {
?>
				<img src="bizstory/images/icon/up.gif" alt="위로"<?=$btn_up;?> />
<?
			}
?>
<?
			if($sort_data["max_sort"] != $data["sort"] && $sort_data["max_sort"] != "") {
?>
				<img src="bizstory/images/icon/down.gif" alt="아래로"<?=$btn_down;?> />
<?
			}
?>
			</td>
			<td><div class="left"><?=$data["subject"];?></div></td>
			<td><div class="right"><?=number_format($data["part_cnt"]);?></div></td>
			<td><div class="right"><?=number_format($data["client_cnt"]);?></div></td>
			<td><div class="right"><?=number_format($data["banner_cnt"]);?></div></td>
			<td><div class="right"><?=number_format($data["sms_cnt"]);?></div></td>
			<td><div class="right"><?=number_format($data["use_price"]);?></div></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>"<?=$btn_view;?> /></td>
			<td><img src="bizstory/images/icon/<?=$data["default_yn"];?>.gif" alt="<?=$data["default_yn"];?>"<?=$btn_default;?> /></td>
			<td><?=$btn_modify;?> <?=$btn_delete;?></td>
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
<input type="hidden" id="new_total_page" value="<?=$page_data['total_page'];?>" />
<input type="hidden" id="new_page_num"   value="<?=$page_num;?>" />
<hr />