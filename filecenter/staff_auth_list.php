<?
/*
	생성 : 2013.02.04
	수정 : 2013.04.01
	위치 : 파일센터 > 권한설정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "' and mem.login_yn = 'Y'";
	if ($shgroup != '' && $shgroup != 'all') // 직원그룹
	{
		$where .= " and mem.csg_idx = '" . $shgroup . "'";
	}
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'mem.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(mem.tel_num, '-', '') like '%" . $stext . "%' or
				replace(mem.tel_num, '.', '') like '%" . $stext . "%' or
				replace(mem.hp_num, '-', '') like '%" . $stext . "%' or
				replace(mem.hp_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = member_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
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
<hr />

<table class="tinytable">
	<colgroup>
		<col width="40px" />
		<col />
		<col width="80px" />
		<col width="90px" />
		<col width="90px" />
		<col width="110px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('아이디', 'mem.mem_id');?></h3></th>
			<th class="nosort"><h3><?=field_sort('이름', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3>직책</h3></th>
			<th class="nosort"><h3>그룹</h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3>관리</h3></th>
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
				$mem_idx = $data['mem_idx'];

				if ($auth_menu['mod'] == "Y")
				{
					$btn_modify = "folder_auth('" . $mem_idx . "', '', '')";
				}
				else
				{
					$btn_modify = "check_auth_popup('modify')";
				}
?>
		<tr>
			<td><?=$num;?></td>
			<td><div class="left"><span class="big_eng"><?=$data["mem_id"];?></span></div></td>
			<td><?=$data["mem_name"];?></td>
			<td><?=$data["duty_name"];?></td>
			<td><?=$data["group_name"];?></td>
			<td><span class="num"><?=$data["hp_num"];?></span></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_violet"><span>폴더권한</span></a>
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