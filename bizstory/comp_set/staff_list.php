<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp     = $_SESSION[$sess_str . '_comp_idx'];
	$code_part     = search_company_part($code_part);
	$set_staff_num = $comp_set_data['staff_cnt'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "'";
	if ($shgroup != '' && $shgroup != 'all') $where .= " and mem.csg_idx = '" . $shgroup . "'"; // 부서
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
	$orderby = 'mem.login_yn asc, ' . $sorder1 . ' ' . $sorder2;

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$page_where = " and mem.comp_idx = '" . $code_comp . "'";
		$page_data = member_info_data('page', $page_where);
		if ($page_data['total_num'] >= $set_staff_num) // 직원수확인
		{
			$btn_write = '<a href="javascript:void(0);" onclick="alert(\'더이상 등록할 수 없습니다.\')" class="btn_big_green"><span>등록</span></a>';
		}
		else
		{
			$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
		}
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom"><?=$btn_write;?></div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col />
		<col width="80px" />
		<col width="90px" />
		<col width="90px" />
		<col width="110px" />
		<col width="80px" />
		<col width="60px" />
		<col width="80px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort">번호</th>
			<th class="nosort"><h3><?=field_sort('아이디', 'mem.mem_id');?></h3></th>
			<th class="nosort"><h3><?=field_sort('이름', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('직책', 'cpd.sort');?></h3></th>
			<th class="nosort"><h3><?=field_sort('부서', 'csg.sort');?></h3></th>
			<th class="nosort"><h3><?=field_sort('연락처', 'mem.hp_num');?></h3></th>
			<th class="nosort"><h3><?=field_sort('입사일', 'mem.enter_date');?></h3></th>
			<th class="nosort"><h3>재직</h3></th>
			<th class="nosort"><h3>메뉴권한</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="10">등록된 데이타가 없습니다.</td>
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
				$mem_idx  = $data['mem_idx'];
				$url_menu = $local_dir . '/bizstory/comp_set/staff_menu.php';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_login  = "check_code_data('check_yn', 'login_yn', '" . $mem_idx . "', '" . $data["login_yn"] . "')";
					$btn_modify = "open_data_form('" . $mem_idx . "')";
					$btn_menu   = "other_page_open('" . $mem_idx . "', '" . $url_menu . "')";
                    $btn_move   = "window.open('" . $local_dir . "/bizstory/comp_set/staff_chk.php?idx=" . $mem_idx . "', '_blank')";
				}
				else
				{
					$btn_login  = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
					$btn_menu   = "check_auth_popup('modify')";
                    $btn_move   = "";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_staff_out('" . $mem_idx . "')";
				else $btn_delete = "check_auth_popup('delete');";

				$charge_str = staff_layer_form($mem_idx, '', 'N', $set_color_list2, 'stafflist', $data['mem_idx'], '');
?>
		<tr>
			<td><?=$num;?></td>
			<td><div class="left"><span class="big_eng"><?=$data["mem_id"];?></span></div></td>
			<td><?=$charge_str;?></td>
			<td><?=$data["duty_name"];?></td>
			<td><?=$data["group_name"];?></td>
			<td><span class="num"><?=$data["hp_num"];?></span></td>
			<td><span class="num"><?=date_replace($data["enter_date"], 'Y-m-d');?></span></td>
			<td><img src="bizstory/images/icon/<?=$data["login_yn"];?>.gif" alt="<?=$data["login_yn"];?>" class="pointer" onclick="<?=$btn_login;?>" /></td>
			<td><a href="javascript:void(0);" onclick="<?=$btn_menu;?>" class="btn_con_violet"><span>메뉴권한</span></a></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>퇴사</span></a>
				<?if ($btn_move != '') {?><a href="javascript:void(0);" onclick="<?=$btn_move;?>" class="btn_con_violet"><span>페이지보기</span></a><?}?>
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
<div style="height:200px;"></div>