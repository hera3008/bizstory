<?
/*
	생성 : 2012.07.02
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 에이전트현황 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ad.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ad.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = agent_data_data('list', $where, $orderby, $page_num, $page_size);
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
		<col width="40px" />
		<col width="100px" />
		<col />
		<col width="50px" />
		<col width="50px" />
		<col width="120px" />
		<col width="80px" />
		<col width="50px" />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="adidx" onclick="check_all('adidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처코드', 'ci.client_code');?></h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3>버전</h3></th>
			<th class="nosort"><h3>타입</h3></th>
			<th class="nosort"><h3>CPU</h3></th>
			<th class="nosort"><h3>서비스팩</h3></th>
			<th class="nosort"><h3>기간</h3></th>
			<th class="nosort"><h3><?=field_sort('등록일', 'ad.reg_date');?></h3></th>
			<th class="nosort"><h3><?=field_sort('수정일', 'ad.mod_date');?></h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="12">등록된 데이타가 없습니다.</td>
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
				$hw_Info = $data['hw_info'];
				$hw_arr = explode('/', $hw_Info);

				if ($data["agent_type"] == '' || $data["agent_type"] == '0') $data["agent_type"] = 'A';
?>
		<tr>
			<td><input type="checkbox" id="adidx_<?=$i;?>" name="chk_ad_idx[]" value="<?=$data["ad_idx"];?>" /></td>
			<td><span class="eng"><?=$num;?></span></td>
			<td><span class="eng"><?=$data["client_code"];?></span></td>
			<td><div class="left"><?=$data["client_name"];?></div></td>
			<td><span class="eng"><?=$data["ver_info"];?></span></td>
			<td><span class="eng"><?=$data["agent_type"];?></span></td>
			<td><span class="eng"><?=$data["cpu_info"];?></span></td>
			<td><span class="eng"><?=$data['sw_info'];?></span></td>
			<td><span class="eng"><?=$data["remain_days"];?></span></td>
			<td><span class="eng"><?=date_replace($data['reg_date'], 'Y-m-d');?></span></td>
			<td><span class="eng"><?=date_replace($data['mod_date'], 'Y-m-d');?></span></td>
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