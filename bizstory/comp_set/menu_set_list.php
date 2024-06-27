<?
/*
	수정 : 2013.03.26
	위치 : 설정관리 > 코드관리 > 메뉴명관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$where = " and mac.comp_idx = '" . $code_comp . "' and mac.view_yn = 'Y'";
	$order = "mi.sort asc";
	$list = menu_auth_company_data('list', $where, $order, '', '');

	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$org_comp_idx   = $data['comp_idx'];
			$org_mi_idx     = $data['mi_idx'];
			$org_up_mi_idx  = $data['up_mi_idx'];
			$org_menu_depth = $data['menu_depth'];
			$org_menu_num   = $data['menu_num'];
			$org_menu_name  = $data['menu_name'];
			$org_default_yn = $data['default_yn'];
			$org_sort       = $data['sort'];

			$sub_where = " and mc.comp_idx = '" . $org_comp_idx . "' and mc.part_idx = '" . $code_part . "' and mc.mi_idx = '" . $org_mi_idx . "'";
			$sub_data = menu_company_data('view', $sub_where);

			$new_comp_idx   = $sub_data['comp_idx'];
			$new_part_idx   = $sub_data['part_idx'];
			$new_mi_idx     = $sub_data['mi_idx'];
			$new_menu_name  = $sub_data['menu_name'];
			$new_default_yn = $sub_data['default_yn'];
			$new_sort       = $sub_data['sort'];
			$mc_idx         = $sub_data['mc_idx'];
            
			if ($new_menu_name == '') $menu_name = $org_menu_name;
			else $menu_name = $new_menu_name;

			if ($new_default_yn == '') $default_yn = $org_default_yn;
			else $default_yn = $new_default_yn;

			if ($new_sort == '' || $new_sort == 0) $sort = $org_sort;
			else $sort = $new_sort;

			//echo $sort . " : " . $menu_name . " => " . $org_sort . " , " . $new_sort . "<br>";

			$menu_depth = $org_menu_depth;
			$menu_num   = $org_menu_num;
			$up_mi_idx  = $org_up_mi_idx;

			if ($auth_menu['mod'] == "Y")
			{
				$btn_up      = "check_code_data('sort_up', '', '" . $mc_idx . "', '', '" . $org_mi_idx . "')";
				$btn_down    = "check_code_data('sort_down', '', '" . $mc_idx . "', '', '" . $org_mi_idx . "')";
				$btn_default = "check_code_data('check_yn', 'default_yn', '" . $org_mi_idx . "', '" . $default_yn . "')";
			}
			else
			{
				$btn_up      = "check_auth_popup('modify')";
				$btn_down    = "check_auth_popup('modify')";
				$btn_default = "check_auth_popup('modify')";
			}

			$data_list[$sort]['comp_idx']    = $org_comp_idx;
			$data_list[$sort]['part_idx']    = $new_part_idx;
			$data_list[$sort]['up_mi_idx']   = $up_mi_idx;
			$data_list[$sort]['menu_depth']  = $menu_depth;
			$data_list[$sort]['menu_num']    = $menu_num;
			$data_list[$sort]['menu_name']   = $menu_name;
			$data_list[$sort]['default_yn']  = $default_yn;
			$data_list[$sort]['sort']        = $sort;
			$data_list[$sort]['btn_up']      = $btn_up;
			$data_list[$sort]['btn_down']    = $btn_down;
			$data_list[$sort]['btn_default'] = $btn_default;
			$data_list[$sort]['mi_idx']      = $org_mi_idx;
			$data_list[$sort]['mc_idx']      = $mc_idx;

			$sort_chk[$menu_depth][$up_mi_idx][$sort] = $sort;
		}
	}
	unset($data);
	unset($list);
	
	//echo "<!--";
	//print_r($data_list);
	//echo "-->";
	
	ksort($data_list);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($auth_menu['int'] == "Y" || $auth_menu['mod'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="check_form(\'\')" class="btn_big_blue"><span>수정</span></a>';
	}
?>
<div class="info_text">
	<ul>
		<li>메뉴명 수정시 특수문자를 사용하지 마세요.</li>
	</ul>
</div>
<hr />

<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable view">
	<colgroup>
		<col width="80px" />
		<col width="130px" />
		<col width="80px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>기본값</h3></th>
			<th class="nosort"><h3>메뉴명</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 1;

	
	foreach($data_list as $k => $data)
	{
		$chk_depth = $data['menu_depth'];
		$chk_up    = $data['up_mi_idx'];

		$sort_data = $sort_chk[$chk_depth][$chk_up];
		ksort($sort_data);
		$min_sort_arr = array_slice($sort_data, 0, 1);
		$max_sort_arr = array_slice($sort_data, -1, 1);

		$min_sort = $min_sort_arr[0];
		$max_sort = $max_sort_arr[0];
?>
		<tr>
			<td><?=$i;?></td>
			<td>
				<div class="left sort sort_<?=$data["menu_depth"];?>">
<?
				if ($min_sort != $data["sort"] && $min_sort != "")
				{
					echo '<img src="bizstory/images/icon/up.gif" alt="위로" class="pointer" onclick="', $data['btn_up'], '" />';
				}
				if ($max_sort != $data["sort"] && $max_sort != "")
				{
					echo '<img src="bizstory/images/icon/down.gif" alt="아래로" class="pointer" onclick="', $data['btn_down'], '" />';
				}
?>
				</div>
			</td>
			<td><img src="bizstory/images/icon/<?=$data['default_yn'];?>.gif" alt="<?=$data['default_yn'];?>" class="pointer" onclick="<?=$data['btn_default'];?>" /></td>
			<td>
				<div class="left depth_<?=$data["menu_depth"];?>">
					<input type="text" name="chk_menu_name_<?=$i;?>" id="chk_menu_name_<?=$i;?>" value="<?=$data["menu_name"];?>" size="50" class="type_text" onkeyup="check_string(this.value)" onkeydown="check_string(this.value)" />
					<input type="hidden" name="chk_mc_idx_<?=$i;?>" id="chk_mc_idx_<?=$i;?>" value="<?=$data["mc_idx"];?>" />
					<input type="hidden" name="chk_mi_idx_<?=$i;?>" id="chk_mi_idx_<?=$i;?>" value="<?=$data["mi_idx"];?>" />
				</div>
			</td>
		</tr>
<?
		$i++;
	}

	unset($data);
	unset($data_list);
?>
	</tbody>
</table>
<input type="hidden" name="menu_total"   id="menu_total" value="<?=$i;?>" />
<input type="hidden" name="chk_comp_idx" id="chk_comp_idx" value="<?=$code_comp;?>" />
<input type="hidden" name="chk_part_idx" id="chk_part_idx" value="<?=$code_part;?>" />
<hr />

<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />