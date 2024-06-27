<?
/*
	생성 : 2012.05.24
	수정 : 2012.07.04
	위치 : 설정폴더(관리자) > 컨텐츠관리 > 배너관리 > 에이전트배너 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$banner_type = '1';

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

	$where = " and bi.banner_type = '" . $banner_type . "'";
	$list = banner_info_data('list', $where, '', '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big fr"><span>배너등록</span></a>';
	}
?>
<div class="details">
	<div class="etc_bottom"><?=$btn_write;?></div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="60px" />
		<col width="380px" />
		<col />
		<col width="40px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>배너이미지</h3></th>
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
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from banner_info where del_yn = 'N' and banner_type = '" . $banner_type . "'");

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up     = "check_code_data('sort_up', '', '" . $data['bi_idx'] . "', '')";
					$btn_down   = "check_code_data('sort_down', '', '" . $data['bi_idx'] . "', '')";
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $data['bi_idx'] . "', '" . $data["view_yn"] . "')";
					$btn_modify = "data_form_open('" . $data['bi_idx'] . "')";
				}
				else
				{
					$btn_up     = "check_auth('modify')";
					$btn_down   = "check_auth('modify')";
					$btn_view   = "check_auth('modify')";
					$btn_modify = "check_auth('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data['bi_idx'] . "')";
				else $btn_delete = "check_auth('delete');";

			// 배너이미지
				if ($data['img_sname1'] == '')
				{
					$img_view = '';
				}
				else
				{
					$img_view = '<img src="' . $banner_dir . '/' . $data['img_sname1'] . '" alt="" width="373px" height="100px" />';
				}

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
				<div class="left"><?=$img_view;?></div>
			</td>
			<td>
				<div class="left">
					<a href="<?=$data['link_url'];?>" target="_blank"><?=$data['link_url'];?></a><br />
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