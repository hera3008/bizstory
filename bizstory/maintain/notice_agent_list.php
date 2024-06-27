<?
/*
	생성 : 2012.07.04
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 컨텐츠관리 > 공지관리 > 에이전트공지 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$notice_type = '1';

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

	$where = " and ni.notice_type = '" . $notice_type . "'";
	$list = notice_info_data('list', $where, '', '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="etc_bottom"><?=$btn_write;?></div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col />
		<col />
		<col width="40px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>내용</h3></th>
			<th class="nosort"><h3>업체</h3></th>
			<th class="nosort"><h3>보기</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="5">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from notice_info where del_yn = 'N' and notice_type = '" . $notice_type . "'");

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up     = "check_code_data('sort_up', '', '" . $data['ni_idx'] . "', '')";
					$btn_down   = "check_code_data('sort_down', '', '" . $data['ni_idx'] . "', '')";
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $data['ni_idx'] . "', '" . $data["view_yn"] . "')";
					$btn_modify = "open_data_form('" . $data['ni_idx'] . "')";
				}
				else
				{
					$btn_up     = "check_auth_popup('modify')";
					$btn_down   = "check_auth_popup('modify')";
					$btn_view   = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data['ni_idx'] . "')";
				else $btn_delete = "check_auth_popup('delete');";

			// 업체
				if ($data['comp_all'] == 'Y')
				{
					$comp_view = '모든업체';
				}
				else
				{
					$comp_view = '';
					$comp_idx = $data['comp_idx'];
					$comp_idx_arr = explode(',', $comp_idx);
					foreach ($comp_idx_arr as $comp_k => $comp_v)
					{
						if ($comp_k > 0)
						{
							$comp_where = " and comp.comp_idx = '" . $comp_v . "'";
							$comp_data = company_info_data('view', $comp_where);

							if ($comp_k == 1)
							{
								$comp_view = $comp_data['comp_name'];
							}
							else
							{
								$comp_view .= ', ' . $comp_data['comp_name'];
							}
						}
					}
				}

			// 중요도
				if ($data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
				else if ($data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
				else if ($data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
				else $important_span = '';
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
			<td>
				<div class="left">
					<?=$data['content'];?><?=$important_span;?><br />
					<a href="<?=$data['link_url'];?>" target="_blank"><?=$data['link_url'];?></a>
				</div>
			</td>
			<td>
				<div class="left">
					<?=$comp_view;?>
				</div>
			</td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
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