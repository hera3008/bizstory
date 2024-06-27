<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

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

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "'";
	$orderby = "code.sort asc";
	$list = code_dili_status_data('list', $where, $orderby, '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="details">
	<div class="etc_bottom">
		<?=$btn_write;?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col width="110px" />
		<col />
		<col width="50px" />
		<col width="50px" />
		<col width="50px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>지사명</h3></th>
			<th class="nosort"><h3>근태상태명</h3></th>
			<th class="nosort"><h3>표시색</h3></th>
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
			<td colspan="7">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"];
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sort_data = query_view("
					select min(sort) as min_sort, max(sort) as max_sort
					from code_dili_status
					where del_yn = 'N' and comp_idx ='" . $data['comp_idx'] . "' and part_idx ='" . $data['part_idx'] . "'");

				$code_idx = $data['code_idx'];

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up        = "check_code_data('sort_up', '', '" . $code_idx . "', '')";
					$btn_down      = "check_code_data('sort_down', '', '" . $code_idx . "', '')";
					$btn_view      = "check_code_data('check_yn', 'view_yn', '" . $code_idx . "', '" . $data["view_yn"] . "')";
					$btn_default   = "check_code_data('check_yn', 'default_yn', '" . $code_idx . "', '" . $data["default_yn"] . "')";
					$btn_request   = "check_code_data('check_yn', 'request_yn', '" . $code_idx . "', '" . $data["request_yn"] . "')";
					$btn_modify    = "popupform_open('" . $code_idx . "')";
					$btn_modify_ok = "check_code_data('modify', '', '" . $code_idx . "', '')";
				}
				else
				{
					$btn_up        = 'check_auth(\'modify\')';
					$btn_down      = 'check_auth(\'modify\')';
					$btn_view      = 'check_auth(\'modify\')';
					$btn_default   = 'check_auth(\'modify\')';
					$btn_modify    = 'check_auth(\'modify\')';
					$btn_modify_ok = 'check_auth(\'modify\')';
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $code_idx . "')";
				else $btn_delete = "check_auth('delete');";

			// 지사명
				$sub_where = "and part.part_idx = '" . $data['part_idx'] . "'";
				$sub_data = company_part_data('view', $sub_where);

				$bg_name = '<span style="';
				if ($data['code_color'] != '') $bg_name .= 'background-color:' . $data['code_color'] . ';color:' . $data['code_color'] . ';';
				$bg_name .= '">' . $data["code_name"] . '</span>';
?>
		<tr>
			<td>
				<div class="sort">
<?
				if ($sort_data["min_sort"] != $data["sort"] && $sort_data["min_sort"] != "")
				{
					echo '<img src="bizstory/images/icon/up.gif" alt="위로" class="pointer" onclick="', $btn_up, '" />';
				}
				if($sort_data["max_sort"] != $data["sort"] && $sort_data["max_sort"] != "")
				{
					echo '<img src="bizstory/images/icon/down.gif" alt="아래로" class="pointer" onclick="', $btn_down, '" />';
				}
?>
				</div>
			</td>
			<td><?=$sub_data["part_name"];?></td>
			<td><div class="left"><?=$data["code_name"];?></div></td>
			<td><?=$bg_name;?></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["default_yn"];?>.gif" alt="<?=$data["default_yn"];?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
		<?
			if ($data['set_yn'] == 'N') {
		?>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
		<?
			}
		?>
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
<hr />
