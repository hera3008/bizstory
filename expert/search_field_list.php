<?
/*
	생성 : 2013.01.15
	수정 : 2013.01.17
	위치 : 전문가코너 > 코드설정 > 거래처검색분류 - 목록
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

	$where = " ";
	$list = expert_client_search_field_data('list', $where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col />
		<col width="120px" />
		<col width="120px" />
		<col width="100px" />
		<col width="60px" />
		<col width="60px" />
		<col width="100px" />
		<col width="120px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>검색조건명</h3></th>
			<th class="nosort"><h3>필드명</h3></th>
			<th class="nosort"><h3>필드형식</h3></th>
			<th class="nosort"><h3>필드길이</h3></th>
			<th class="nosort"><h3>사용</h3></th>
			<th class="nosort"><h3>필수</h3></th>
			<th class="nosort"><h3>구성항목</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="9">등록된 데이타가 없습니다.</td>
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
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from expert_client_search_field where del_yn = 'N'");

				$ecsf_idx = $data['ecsf_idx'];
				$url_data = $local_dir . '/bizstory/expert/search_field_data.php';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up        = "check_code_data('sort_up', '', '" . $ecsf_idx . "', '')";
					$btn_down      = "check_code_data('sort_down', '', '" . $ecsf_idx . "', '')";
					$btn_view      = "check_code_data('check_yn', 'view_yn', '" . $ecsf_idx . "', '" . $data["view_yn"] . "')";
					$btn_essential = "check_code_data('check_yn', 'essential_yn', '" . $ecsf_idx . "', '" . $data["essential_yn"] . "')";
					$btn_modify    = "open_data_form('" . $ecsf_idx . "')";
					$btn_field     = "other_open_data_form('" . $url_data . "', '" . $ecsf_idx . "', '')";
				}
				else
				{
					$btn_up        = "check_auth_popup('modify')";
					$btn_down      = "check_auth_popup('modify')";
					$btn_view      = "check_auth_popup('modify')";
					$btn_essential = "check_auth_popup('modify')";
					$btn_modify    = "check_auth_popup('modify')";
					$btn_field     = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $ecsf_idx . "')";
				else $btn_delete = "check_auth_popup('delete');";
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
			<td><div class="left"><?=$data['field_subject'];?></div></td>
			<td><?=$data['field_name'];?></td>
			<td><?=$set_field_type[$data['field_type']];?></td>
			<td><?=$data['field_length'];?></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["essential_yn"];?>.gif" alt="<?=$data["essential_yn"];?>" class="pointer" onclick="<?=$btn_essential;?>" /></td>
			<td><a href="javascript:void(0);" onclick="<?=$btn_field;?>" class="btn_con"><span>구성항목</span></a></td>
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
