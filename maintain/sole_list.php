<?
/*
	생성 : 2012.12.10
	수정 : 2012.12.10
	위치 : 설정폴더 > 설정관리 > 총판관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = '';
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

	$list = sole_info_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="80px" />
		<col />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="60px" />
		<col width="70px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>상호명</h3></th>
			<th class="nosort"><h3>아이디</h3></th>
			<th class="nosort"><h3>담당자</h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3>보기</h3></th>
			<th class="nosort"><h3>관리업체</h3></th>
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
		$i = 1;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				if ($auth_menu['mod'] == "Y")
				{
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $data["sole_idx"] . "', '" . $data["view_yn"] . "')";
					$btn_modify = "open_data_form('" . $data['sole_idx'] . "')";

					$comp_url = $local_dir . '/bizstory/maintain/sole_company.php';
					$btn_comp = "other_page_open('" . $data["sole_idx"] . "', '" . $comp_url . "')";
				}
				else
				{
					$btn_view   = "check_auth_popup('modify')";
					$btn_comp   = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data['sole_idx'] . "')";
				else $btn_delete = "check_auth_popup('delete');";
?>
		<tr>
			<td><?=$i;?></td>
			<td>
				<div class="left"><?=$data["comp_name"];?></div>
			</td>
			<td><span class="eng"><?=$data["sole_id"];?></span></td>
			<td><?=$data["charge_name"];?></td>
			<td><span class="eng"><?=$data["tel_num"];?></span></td>
			<td><img src="bizstory/images/icon/<?=$data['view_yn'];?>.gif" alt="<?=$data['view_yn'];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_comp;?>" class="btn_con_violet"><span>관리업체</span></a>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
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
<hr />