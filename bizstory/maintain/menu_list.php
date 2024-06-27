<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 설정관리 > 메뉴관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$list = menu_info_data('list', '', '', '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="etc_bottom"><?=$btn_write;?></div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="130px" />
		<col />
		<col width="110px" />
		<col width="130px" />
		<col width="40px" />
		<col width="40px" />
		<col width="40px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>메뉴명</h3></th>
			<th class="nosort"><h3>폴더명</h3></th>
			<th class="nosort"><h3>파일명</h3></th>
			<th class="nosort"><h3>탭</h3></th>
			<th class="nosort"><h3>보기</h3></th>
			<th class="nosort"><h3>기본</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="8">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from menu_info where del_yn = 'N' and up_mi_idx = '" . $data["up_mi_idx"] . "'");

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up      = "check_code_data('sort_up', '', '" . $data['mi_idx'] . "', '')";
					$btn_down    = "check_code_data('sort_down', '', '" . $data['mi_idx'] . "', '')";
					$btn_tab     = "check_code_data('check_yn', 'tab_yn', '" . $data['mi_idx'] . "', '" . $data["tab_yn"] . "')";
					$btn_view    = "check_code_data('check_yn', 'view_yn', '" . $data['mi_idx'] . "', '" . $data["view_yn"] . "')";
					$btn_default = "check_code_data('check_yn', 'default_yn', '" . $data['mi_idx'] . "', '" . $data["default_yn"] . "')";
					$btn_modify  = "popupform_open('" . $data['mi_idx'] . "')";
				}
				else
				{
					$btn_up      = "check_auth_popup('modify')";
					$btn_down    = "check_auth_popup('modify')";
					$btn_tab     = "check_auth_popup('modify')";
					$btn_view    = "check_auth_popup('modify')";
					$btn_default = "check_auth_popup('modify')";
					$btn_modify  = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data['mi_idx'] . "')";
				else $btn_delete = "check_auth_popup('delete');";
?>
		<tr>
			<td>
				<div class="left sort sort_<?=$data["menu_depth"];?>">
<?
			if ($sort_data["min_sort"] != $data["sort"] && $sort_data["min_sort"] != "") {
?>
					<img src="bizstory/images/icon/up.gif" alt="위로" class="pointer" onclick="<?=$btn_up;?>" />
<?
			}
?>
<?
			if($sort_data["max_sort"] != $data["sort"] && $sort_data["max_sort"] != "") {
?>
					<img src="bizstory/images/icon/down.gif" alt="아래로" class="pointer" onclick="<?=$btn_down;?>" />
<?
			}
?>
				</div>
			</td>
			<td>
				<div class="left depth_<?=$data["menu_depth"];?>"><?=$data["menu_name"];?></div>
			</td>
			<td><span class="num"><?=$data["mode_folder"];?></span></td>
			<td><span class="num"><?=$data["mode_file"];?></span></td>
			<td><img src="bizstory/images/icon/<?=$data["tab_yn"];?>.gif" alt="<?=$data["tab_yn"];?>" class="pointer" onclick="<?=$btn_tab;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["default_yn"];?>.gif" alt="<?=$data["default_yn"];?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
			</td>
		</tr>
<?
			}
		}
	}
?>
	</tbody>
</table>
<hr />