<?
/*
	생성 : 2012.11.02
	생성 : 2013.05.22
	위치 : 설정폴더 > 업체관리 > 업체분류 - 목록
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
	$list = company_class_data('list', $where, '', '', '');

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="100px" />
		<col />
		<col width="60px" />
		<col width="60px" />
		<col width="60px" />
		<col width="60px" />
		<col width="60px" />
		<col width="60px" />
		<col width="50px" />
		<col width="70px" />
		<col width="40px" />
		<col width="40px" />
		<col width="70px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>순서</h3></th>
			<th class="nosort"><h3>분류명</h3></th>
			<th class="nosort"><h3>메인화면</h3></th>
			<th class="nosort"><h3>지사수</h3></th>
			<th class="nosort"><h3>거래처수</h3></th>
			<th class="nosort"><h3>직원수</h3></th>
			<th class="nosort"><h3>배너수</h3></th>
			<th class="nosort"><h3>저장공간</h3></th>
			<th class="nosort"><h3>뷰어</h3></th>
			<th class="nosort"><h3>기본가격</h3></th>
			<th class="nosort"><h3>보기</h3></th>
			<th class="nosort"><h3>기본</h3></th>
			<th class="nosort"><h3>메뉴설정</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="14">등록된 데이타가 없습니다.</td>
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
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from company_class where del_yn = 'N' and up_code_idx = '" . $data["up_code_idx"] . "'");

				$code_idx = $data['code_idx'];
				$url_menu = $local_dir . '/bizstory/maintain/comp_class_menu.php';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up      = "check_code_data('sort_up', '', '" . $code_idx . "', '')";
					$btn_down    = "check_code_data('sort_down', '', '" . $code_idx . "', '')";
					$btn_view    = "check_code_data('check_yn', 'view_yn', '" . $code_idx . "', '" . $data["view_yn"] . "')";
					$btn_viewer  = "check_code_data('check_yn', 'viewer_yn', '" . $code_idx . "', '" . $data["viewer_yn"] . "')";
					$btn_default = "check_code_data('check_yn', 'default_yn', '" . $code_idx . "', '" . $data["default_yn"] . "')";
					$btn_modify  = "popupform_open('" . $code_idx . "')";
					$btn_menu    = "other_page_open('" . $code_idx . "', '" . $url_menu . "')";
				}
				else
				{
					$btn_up      = "check_auth_popup('modify')";
					$btn_down    = "check_auth_popup('modify')";
					$btn_view    = "check_auth_popup('modify')";
					$btn_viewer  = "check_auth_popup('modify')";
					$btn_default = "check_auth_popup('modify')";
					$btn_modify  = "check_auth_popup('modify')";
					$btn_menu    = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $code_idx . "')";
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
			if($sort_data["max_sort"] != $data["sort"] && $sort_data["max_sort"] != "") {
?>
					<img src="bizstory/images/icon/down.gif" alt="아래로" class="pointer" onclick="<?=$btn_down;?>" />
<?
			}
?>
				</div>
			</td>
			<td><div class="left depth_<?=$data["menu_depth"];?>"><?=$data["code_name"];?></div></td>
<?
			if ($data['menu_num'] == 0) {
?>
			<td><span class="num"><?=$data['main_type'];?></span></td>
			<td><span class="num"><?=$data['part_num'];?></span></td>
			<td><span class="num"><?=$data['client_num'];?></span></td>
			<td><span class="num"><?=$data['staff_num'];?></span></td>
			<td><span class="num"><?=$data['banner_num'];?></span></td>
			<td><span class="num"><?=$data['volume_num'];?></span></td>
			<td><img src="bizstory/images/icon/<?=$data["viewer_yn"];?>.gif" alt="<?=$data["viewer_yn"];?>" class="pointer" onclick="<?=$btn_viewer;?>" /></td>
			<td><span class="eng right"><?=number_format($data['default_price']);?></span></td>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["default_yn"];?>.gif" alt="<?=$data["default_yn"];?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
			<td><a href="javascript:void(0);" onclick="<?=$btn_menu;?>" class="btn_con_violet"><span>메뉴설정</span></a></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
			</td>
<?
			}
			else
			{
?>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
			</td>
<?
			}
?>
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
