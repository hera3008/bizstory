<?
/*
	생성 : 2013.02.26
	수정 : 2013.02.26
	위치 : 파일센터 > 파일관리- 목록 - 검색
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($up_level == '') $up_level = 1;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;menu1=' . $send_menu1 . '&amp;menu2=' . $send_menu2 . '&amp;start_date=' . $send_start_date . '$amp;end_date=' . $send_end_date;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_spage_size;
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
		<input type="hidden" name="smenu1"    value="' . $send_menu1 . '" />
		<input type="hidden" name="smenu2"    value="' . $send_menu2 . '" .>
        <input type="hidden" name="sstart_date" value="' . $send_start_date . '" />
        <input type="hidden" name="send_date" value="' . $send_end_date . '"/>
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$auth_data['total_num'] = 1;
	$form_chk = 'N';
	if ($auth_data['total_num'] > 0) // 등록권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
        $join = "";
		$where = " and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "'";
		
		if ($swhere == 'folder_name')    $where .= " and fi.dir_file = 'folder' and fi.file_name like '%" . $stext . "%' ";
		else if ($swhere == 'file_name') $where .= " and fi.dir_file = 'file' and fi.file_name like '%" . $stext . "%' ";
		else if ($swhere == 'reg_date')  $where .= "";
		else if ($swhere == 'reg_id')    $where .= " and reg.mem_name like '%" . $stext . "%'";
		else if ($swhere == 'mod_date')  $where .= "";
		else if ($swhere == 'mod_id')    $where .= " and mod.mem_name like '%" . $stext . "%'";
		
		if ($start_date != '') {
		    $where .= " and fi.reg_date >= unix_timestamp('" . $start_date . "') ";
		}
        
        if ($end_date != '') {
            $where .= " and fi.reg_date <= unix_timestamp('" . $end_date . "') ";
        }
        
        if ($menu1 != '') {
            $join .= " join (select p.pro_idx from project_info p where p.comp_idx='" . $code_comp . "' and p.part_idx='" . $code_part . "' and p.menu1_code='" . $menu1 . "' ";
        }
        
        if ($menu2 != '') {
            $join .= " and p.menu2_code='" . $menu2 . "' ";
        }
        
        if ($join != '') {
            $join .= " and p.del_yn='N') p on fi.pro_idx = p.pro_idx ";
        }
        
		$order = "fi.dir_file desc, fi.file_path asc, fi.file_name asc";
		$list = filecenter_info_data('list', $where, $order, '', '', 1, $join);
?>

<div class="details">
	<span>검색결과</span>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col />
		<col width="80px" />
		<col width="80px" />
		<col width="90px" />
		<col width="90px" />
		<col width="180px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>이름</h3></th>
			<th class="nosort"><h3>크기</h3></th>
			<th class="nosort"><h3>종류</h3></th>
			<th class="nosort"><h3>올린날짜</h3></th>
			<th class="nosort"><h3>올린사람</h3></th>
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
				$next_depth = $data['dir_depth'] + 1;
				$next_up    = $data['up_fi_idx'] . ',' . $data['fi_idx'];

				if ($data['dir_file'] == 'folder')
				{
					$icon_img   = '<img src="' . $local_dir . '/common/images/icon/folder3.png" alt="folder" /> ';
					$file_url   = '<a href="javascript:void(0)" onclick="file_list_view(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">' . $data["file_name"] . '</a>';
					$file_size  = '';
					$file_type  = 'folder';
					$down_total = 0;

					$check_modify  = "popup_folder('" . $data['up_fi_idx'] . "', '" . $data['dir_depth'] . "', '" . $data['fi_idx'] . "')";
					$check_delete  = "folder_delete('" . $data['up_fi_idx'] . "', '" . $data['dir_depth'] . "', '" . $data['fi_idx'] . "')";

					$btn_preview = '';
					$btn_history = '';
					$btn_modify  = '<a href="javascript:void(0);" onclick="' . $check_modify . ' " class="btn_con"><span>수정</span></a>';
					$btn_delete  = '<a href="javascript:void(0);" onclick="' . $check_delete . ' " class="btn_con"><span>삭제</span></a>';

					if ($data['dir_depth'] == 1)
					{
						if ($data['file_name'] == 'project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
						else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
					}
					else
					{
					// 하위값 확인
						$chk_where = " and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "' and fi.up_fi_idx = '" . $next_up . "'";
						$down_data = filecenter_info_data('page', $chk_where, '', '', '', 1, $join);
						$down_total = $down_data['total_num'];
					}
				}
				else
				{
					$icon_img   = file_ext_img($data['file_ext']) . ' ';
					$file_url   = $data['file_name'] . ' (' . $data['file_sname'] . ')';
					$file_size  = byte_replace($data['file_size']);
					$file_type  = $data["file_ext"];
					$down_total = 0;

					$check_history = "file_history('" . $data['fi_idx'] . "')";
					$check_modify  = "file_modify('" . $data['up_fi_idx'] . "', '" . $data['dir_depth'] . "', '" . $data['fi_idx'] . "')";
					$check_delete  = "file_delete('" . $data['up_fi_idx'] . "', '" . $data['dir_depth'] . "', '" . $data['fi_idx'] . "')";

					$btn_preview = '<a href="javascript:void(0);" onclick="alert(\'준비중입니다.\')" class="btn_con"><span>미리보기</span></a>';
					$btn_history = '<a href="javascript:void(0);" onclick="' . $check_history . ' " class="btn_con"><span>이력</span></a>';
					$btn_modify  = '<a href="javascript:void(0);" onclick="' . $check_modify . ' " class="btn_con"><span>수정</span></a>';
					$btn_delete  = "";
				}

				if ($chk_data['set_type'] == 'nofix')
				{
					if ($data["dir_depth"] == 1)
					{
						$btn_modify = '';
						$btn_delete = '';
					}
					else
					{
						if ($down_total > 0)
						{
							$btn_delete = '';
						}
					}
				}
				else
				{
					if ($data["dir_depth"] < 3)
					{
						$btn_modify = '';
						$btn_delete = '';
					}
					else
					{
						if ($down_total == 0)
						{
							$btn_delete = '';
						}
					}
				}
?>
		<tr>
			<td>
				<div class="left"><?=$icon_img;?><?=$file_url;?></div>
				<div class="left"><?=$data['file_path'];?></div>
			</td>
			<td><div class="right"><span class="eng"><?=$file_size;?></span></div></td>
			<td><span class="eng"><?=$file_type;?></span></td>
			<td><span class="eng"><?=date('Y-m-d', $data["reg_date"]);?></span></td>
			<td><?=$data['reg_name'];?></td>
			<td>
				<?=$btn_preview;?>
				<?=$btn_history;?>
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

<div id="file_upload_view"></div>
<?
	}
?>
