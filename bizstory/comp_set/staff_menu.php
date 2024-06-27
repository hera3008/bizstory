<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 메뉴권한
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$mem_idx   = $idx;

	$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
	$mem_data = member_info_data('view', $mem_where);

	$where = " and mac.comp_idx = '" . $code_comp . "' and mac.view_yn = 'Y'";
	$orderby = "mi.sort asc";
	$list = menu_auth_company_data('list', $where, $orderby, '', '');

	$menu_url = $local_dir . '/bizstory/comp_set/staff_menu.php';
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$mem_data['mem_name'];?></strong> 님 메뉴권한을 설정하세요..
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">
		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post">
			<input type="hidden" id="other_fmode" name="fmode" value="<?=$fmode;?>" />
			<input type="hidden" id="other_smode" name="smode" value="<?=$smode;?>" />

			<input type="hidden" id="other_comp_idx"   name="comp_idx"   value="<?=$code_comp;?>" />
			<input type="hidden" id="other_part_idx"   name="part_idx"   value="<?=$code_part;?>" />
			<input type="hidden" id="other_mem_idx"    name="mem_idx"    value="<?=$mem_idx;?>" />
			<input type="hidden" id="other_sub_type"   name="sub_type"   value="auth_menu" />
			<input type="hidden" id="other_sub_action" name="sub_action" value="" />
			<input type="hidden" id="other_idx"        name="idx"        value="" />
			<input type="hidden" id="other_post_value" name="post_value" value="" />

			<table class="tinytable view">
			<colgroup>
				<col width="60px" />
				<col width="60px" />
				<col width="60px" />
				<col width="60px" />
				<col width="60px" />
				<col width="60px" />
				<col width="60px" />
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>목록</h3></th>
					<th class="nosort"><h3>보기</h3></th>
					<th class="nosort"><h3>등록</h3></th>
					<th class="nosort"><h3>수정</h3></th>
					<th class="nosort"><h3>삭제</h3></th>
					<th class="nosort"><h3>인쇄</h3></th>
					<th class="nosort"><h3>다운</h3></th>
					<th class="nosort"><h3>메뉴명</h3></th>
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
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sub_where = " and mam.comp_idx = '" . $code_comp . "' and mam.mem_idx = '" . $mem_idx . "' and mam.mi_idx = '" . $data["mi_idx"] . "'";
				$auth_data = menu_auth_member_data('view', $sub_where);
				if ($auth_data['yn_list']  == 'Y') $list_yn = 'Y';  else $list_yn = 'N';
				if ($auth_data['yn_view']  == 'Y') $view_yn = 'Y';  else $view_yn = 'N';
				if ($auth_data['yn_int']   == 'Y') $int_yn = 'Y';   else $int_yn = 'N';
				if ($auth_data['yn_mod']   == 'Y') $mod_yn = 'Y';   else $mod_yn = 'N';
				if ($auth_data['yn_del']   == 'Y') $del_yn = 'Y';   else $del_yn = 'N';
				if ($auth_data['yn_print'] == 'Y') $print_yn = 'Y'; else $print_yn = 'N';
				if ($auth_data['yn_down']  == 'Y') $down_yn = 'Y';  else $down_yn = 'N';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_list  = "other_check_code('auth_menu', 'yn_list', '" . $data["mi_idx"] . "', '" . $list_yn . "', '" . $menu_url . "')";
					$btn_view  = "other_check_code('auth_menu', 'yn_view', '" . $data["mi_idx"] . "', '" . $view_yn . "', '" . $menu_url . "')";
					$btn_int   = "other_check_code('auth_menu', 'yn_int', '" . $data["mi_idx"] . "', '" . $int_yn . "', '" . $menu_url . "')";
					$btn_mod   = "other_check_code('auth_menu', 'yn_mod', '" . $data["mi_idx"] . "', '" . $mod_yn . "', '" . $menu_url . "')";
					$btn_del   = "other_check_code('auth_menu', 'yn_del', '" . $data["mi_idx"] . "', '" . $del_yn . "', '" . $menu_url . "')";
					$btn_print = "other_check_code('auth_menu', 'yn_print', '" . $data["mi_idx"] . "', '" . $print_yn . "', '" . $menu_url . "')";
					$btn_down  = "other_check_code('auth_menu', 'yn_down', '" . $data["mi_idx"] . "', '" . $down_yn . "', '" . $menu_url . "')";
				}
				else
				{
					$btn_list  = "check_auth_popup('modify')";
					$btn_view  = "check_auth_popup('modify')";
					$btn_int   = "check_auth_popup('modify')";
					$btn_mod   = "check_auth_popup('modify')";
					$btn_del   = "check_auth_popup('modify')";
					$btn_print = "check_auth_popup('modify')";
					$btn_down  = "check_auth_popup('modify')";
				}

				$sub_where = " and mc.comp_idx = '" . $data['comp_idx'] . "' and mc.part_idx = '" . $auth_data['part_idx'] . "' and mc.mi_idx = '" . $data['mi_idx'] . "'";
				$sub_data = menu_company_data('view', $sub_where);

				$menu_name = $sub_data['menu_name'];
				if ($menu_name == '') $menu_name = $data['menu_name'];
?>
				<tr>
					<td><img src="bizstory/images/icon/<?=$list_yn;?>.gif" alt="<?=$list_yn;?>" class="pointer" onclick="<?=$btn_list;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$view_yn;?>.gif" alt="<?=$view_yn;?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$int_yn;?>.gif" alt="<?=$int_yn;?>" class="pointer" onclick="<?=$btn_int;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$mod_yn;?>.gif" alt="<?=$mod_yn;?>" class="pointer" onclick="<?=$btn_mod;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$del_yn;?>.gif" alt="<?=$del_yn;?>" class="pointer" onclick="<?=$btn_del;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$print_yn;?>.gif" alt="<?=$print_yn;?>" class="pointer" onclick="<?=$btn_print;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$down_yn;?>.gif" alt="<?=$down_yn;?>" class="pointer" onclick="<?=$btn_down;?>" /></td>
					<td>
						<div class="left depth_<?=$data["menu_depth"];?>"><?=$menu_name;?></div>
					</td>
				</tr>
<?
				$i++;
			}
		}
	}
?>
			</tbody>
			</table>
		</form>
	</div>
</div>