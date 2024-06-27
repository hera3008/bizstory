<?
/*
	생성 : 2013.01.17
	수정 : 2013.01.17
	위치 : 전문가코너 > 코드설정 > 알림분류 - 목록
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

	$where = "";
	$list = expert_code_notify_class_data('list', $where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col />
		<col width="50px" />
		<col width="50px" />
		<col width="50px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>분류명</h3></th>
			<th class="nosort"><h3>굵게</h3></th>
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
			<td colspan="6">등록된 데이타가 없습니다.</td>
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
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from expert_code_notify_class where del_yn = 'N'");

				$code_idx = $data['code_idx'];

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up      = "check_code_data('sort_up', '', '" . $code_idx . "', '')";
					$btn_down    = "check_code_data('sort_down', '', '" . $code_idx . "', '')";
					$btn_bold    = "check_code_data('check_yn', 'code_bold', '" . $code_idx . "', '" . $data["code_bold"] . "')";
					$btn_view    = "check_code_data('check_yn', 'view_yn', '" . $code_idx . "', '" . $data["view_yn"] . "')";
					$btn_default = "check_code_data('check_yn', 'default_yn', '" . $code_idx . "', '" . $data["default_yn"] . "')";
					$btn_modify  = "popupform_open('" . $code_idx . "')";
				}
				else
				{
					$btn_up      = "check_auth_popup('modify')";
					$btn_down    = "check_auth_popup('modify')";
					$btn_bold    = "check_auth_popup('modify')";
					$btn_view    = "check_auth_popup('modify')";
					$btn_default = "check_auth_popup('modify')";
					$btn_modify  = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $code_idx . "')";
				else $btn_delete = "check_auth('delete');";

				$code_name = '<span style="';
				if ($data['code_bold'] == 'Y') $code_name .= 'font-weight:900;';
				if ($data['code_color'] != '') $code_name .= 'color:' . $data['code_color'] . ';';
				$code_name .= '">' . $data["code_name"] . '</span>';
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
			<td><div class="left"><?=$code_name;?></div></td>
			<td><img src="bizstory/images/icon/<?=$data["code_bold"];?>.gif" alt="<?=$data["code_bold"];?>" class="pointer" onclick="<?=$btn_bold;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["default_yn"];?>.gif" alt="<?=$data["default_yn"];?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
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
