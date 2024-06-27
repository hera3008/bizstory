<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 지사관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$set_part_num = $comp_set_data['part_cnt'];

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

	$where = " and part.comp_idx = '" . $code_comp . "'";
	$list = company_part_data('list', $where, '', '', '');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		if ($list['total_num'] >= $set_part_num) // 지사수확인
		{
			$btn_write = '<a href="javascript:void(0);" onclick="alert(\'더이상 등록할 수 없습니다.\')" class="btn_big_green"><span>등록</span></a>';
		}
		else
		{
			$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
		}
	}
?>
<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col />
		<col width="120px" />
		<col width="120px" />
		<col width="50px" />
		<col width="50px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>지사명</h3></th>
			<th class="nosort"><h3>전화번호</h3></th>
			<th class="nosort"><h3>에이전트</h3></th>
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
				$part_idx = $data['part_idx'];

				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from company_part where del_yn = 'N' and comp_idx ='" . $code_comp . "'");

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up      = "check_code_data('sort_up', '', '" . $part_idx . "', '')";
					$btn_down    = "check_code_data('sort_down', '', '" . $part_idx . "', '')";
					$btn_view    = "check_code_data('check_yn', 'view_yn', '" . $part_idx . "', '" . $data["view_yn"] . "')";
					$btn_default = "check_code_data('check_yn', 'default_yn', '" . $part_idx . "', '" . $data["default_yn"] . "')";
					$btn_modify  = "popupform_open('" . $part_idx . "')";
				}
				else
				{
					$btn_up      = "check_auth_popup('modify')";
					$btn_down    = "check_auth_popup('modify')";
					$btn_view    = "check_auth_popup('modify')";
					$btn_default = "check_auth_popup('modify')";
					$btn_modify  = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete_part('" . $part_idx . "')";
				else $btn_delete = "check_auth_popup('delete');";

				$tel_num = $data["tel_num"];
				$tel_num_str = substr($tel_num, 0, 1);
				if ($tel_num == '-' || $tel_num == '--')
				{
					$tel_num = '';
				}
				else if ($tel_num_str == '-')
				{
					$tel_num = substr($tel_num, 1, strlen($tel_num));
				}
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
			<td><div class="left"><?=$data["part_name"];?></div></td>
			<td><span class="eng"><?=$tel_num;?></span></td>
			<td><span class="big_eng"><?=$data["agent_type"];?></span></td>
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