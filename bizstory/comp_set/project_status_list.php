<?
/*
	생성 : 2012.12.26
	수정 : 2013.03.22
	위치 : 설정폴더 > 코드관리 > 프로젝트상태 - 목록
*/
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
	$list = code_project_status_data('list', $where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col width="130px" />
		<col />
		<col width="60px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>지사명</h3></th>
			<th class="nosort"><h3>상태명</h3></th>
			<th class="nosort"><h3>굵게</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="4">등록된 데이타가 없습니다.</td>
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
				$code_idx = $data['code_idx'];

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up     = "check_code_data('sort_up', '', '" . $code_idx . "', '')";
					$btn_down   = "check_code_data('sort_down', '', '" . $code_idx . "', '')";
					$btn_bold   = "check_code_data('check_yn', 'code_bold', '" . $code_idx . "', '" . $data["code_bold"] . "')";
					$btn_modify = "popupform_open('" . $code_idx . "')";
				}
				else
				{
					$btn_up     = "check_auth_popup('modify')";
					$btn_down   = "check_auth_popup('modify')";
					$btn_bold   = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
				}

				$code_name = '<span style="';
				if ($data['code_bold'] == 'Y') $code_name .= 'font-weight:900;';
				if ($data['code_color'] != '') $code_name .= 'color:' . $data['code_color'] . ';';
				$code_name .= '">' . $data["code_name"] . '</span>';
?>
		<tr>
			<td><?=$data["part_name"];?></td>
			<td><div class="left"><?=$code_name;?></div></td>
			<td><img src="bizstory/images/icon/<?=$data["code_bold"];?>.gif" alt="<?=$data["code_bold"];?>" class="pointer" onclick="<?=$btn_bold;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
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
