<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.23
	위치 : 파일센터 > 파일관리- 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($up_level == '') $up_level = 1;

	if ($pro_end == '')
	{
		$pro_end      = 'N';
		$send_pro_end = 'N';
		$recv_pro_end = 'N';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;pro_end=' . $send_pro_end;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="pro_end" value="' . $send_pro_end . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$path_data     = filecenter_folder_path($up_idx); // 현위치
	$dir_auth_page = filecenter_auth_folder($up_idx); // 권한확인

	$first_fi   = $path_data['path_up_idx'][1];
	$first_name = $path_data['path_up_name'][1];

	$form_chk = 'N';
	if ($dir_auth_page['dir_view_auth'] == 'Y') // 목록권한
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		if ($first_name == 'Project')
		{
			$where = "
				and fi.comp_idx = '" . $code_comp . "'
				and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
			";
			if ($pro_end == 'N')
			{
				$where .= " and ifnull(pro.pro_status, '') != 'PS90'";
			}
		}
		else
		{
			$where = "
				and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "'
				and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
			";
		}
		$order = "fi.dir_file desc, fi.file_path asc, fi.file_name asc";
		$list = filecenter_info_data('list', $where, $order, $page_num, $page_size);
?>
<div class="details">
	<span>현위치 : <?=$path_data['navi_path'];?></span>
	<div class="data_left">
	<?
		if ($up_level > 1)
		{
			$path_up_idx   = $path_data['path_up_idx'];
			$path_up_level = $path_data['path_up_level'];
			$chk_up_level  = $up_level - 2;

			if ($first_name == 'Project')
			{
				$file_query['query_string'] = "
					select
						fi.fi_idx, fi.dir_file, fi.file_size
						, fa.dir_view, fa.dir_read, fa.dir_write
					from
						filecenter_info fi
						left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $code_mem . "'
						left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
					where
						fi.del_yn = 'N'
						and fi.comp_idx = '" . $code_comp . "'
						and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'";
				if ($pro_end == 'N') $file_query['query_string'] .= " and ifnull(pro.pro_status, 'PS01') != 'PS90'";
				$file_query['query_string'] .= "
					order by
						fi.dir_file desc
				";
			}
			else
			{
				$file_query['query_string'] = "
					select
						fi.fi_idx, fi.dir_file, fi.file_size
						, fa.dir_view, fa.dir_read, fa.dir_write
					from
						filecenter_info fi
						left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $code_mem . "'
					where
						fi.del_yn = 'N'
						and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "'
						and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
					order by
						fi.dir_file desc
				";
			}
			$file_query['page_size'] = '';
			$file_query['page_num'] = '';
			$chk_list = query_list($file_query);

			foreach ($chk_list as $chk_k => $chk_data)
			{
				if (is_array($chk_data))
				{
					$tap_fi_idx    = $chk_data['fi_idx'];
					$tap_dir_file  = $chk_data['dir_file'];
					$tap_file_size = $chk_data['file_size'];
					if ($tap_dir_file == 'folder') // 폴더일 경우
					{
						if ($chk_data['dir_view']  == '1') $dir_auth['dir_view_auth']  = 'Y'; else $dir_auth['dir_view_auth']  = 'N';
						if ($chk_data['dir_read']  == '1') $dir_auth['dir_read_auth']  = 'Y'; else $dir_auth['dir_read_auth']  = 'N';
						if ($chk_data['dir_write'] == '1') $dir_auth['dir_write_auth'] = 'Y'; else $dir_auth['dir_write_auth'] = 'N';
					}
					else
					{
						$dir_auth = filecenter_auth_file($up_idx, $chk_data); // 권한 - 현 위치 폴더에 대한 권한 - up_idx
					}

					if ($dir_auth['dir_view_auth'] == 'Y' || $dir_auth['dir_read_auth'] == 'Y' || $dir_auth['dir_write_auth'] == 'Y')
					{
						if ($tap_file_size == '') $tap_file_size = 0;

						$data_file_num[$tap_dir_file]++;
						$data_file_size[$tap_dir_file] += $tap_file_size;
					}
				}
			}
			$tap_file_size = $data_file_size['file'];
			if ($tap_file_size > 0)
			{
				$tap_file_size = ' (' . byte_replace1($tap_file_size) . ')';
			}
	?>
		<div class="btn_default_dark pointer" onclick="file_list_view('<?=$path_up_idx[$chk_up_level];?>', '<?=$path_up_level[$chk_up_level];?>')"><span>상위폴더</span></div>
		<span class="ico01">폴더 : <?=number_format($data_file_num['folder']);?>개</span>
		<span class="ico02">파일 : <?=number_format($data_file_num['file']);?>개<?=$tap_file_size;?></span>
	<?
		}
	?>
	</div>
	<div class="etc_bottom">
		<?=$dir_auth_page['btn_sel_down'];?>
		<?=$dir_auth_page['btn_folder2'];?>
		<?=$dir_auth_page['btn_folder1'];?>
		<?=$dir_auth_page['btn_file_copy'];?>
		<?=$dir_auth_page['btn_file_move'];?>
		<?=$dir_auth_page['btn_file'];?>
		<?=$dir_auth_page['btn_sel_del'];?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col />
		<col width="70px" />
		<col width="65px" />
		<col width="75px" />
		<col width="65px" />
		<col width="45px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="fiidx" onclick="check_all('fiidx', this); addFiles()" /></th>
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
	$i = 1;
	$chk_num = 0;
	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$fi_idx     = $data['fi_idx'];
			$next_depth = $data['dir_depth'] + 1;
			$next_up    = $data['up_fi_idx'] . ',' . $data['fi_idx'];

			if ($data['dir_file'] == 'folder') // 폴더일 경우
			{
				$checkbox_html = '';
				$icon_img   = '<img src="' . $local_dir . '/common/images/icon/folder3.png" alt="folder" /> ';
				$file_url   = '<a href="javascript:void(0)" onclick="file_list_view(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">' . $data["file_name"] . '</a>';
				$file_size  = '';
				$file_type  = 'folder';

				if ($data['dir_depth'] == 1)
				{
					if ($data['file_name'] == 'Project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
					else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
				}
				else
				{
					if ($data['set_type'] == 'fix' && $data['file_name'] == 'Member')
					{
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_m.png" alt="Member" title="Member" /> ';
					}
				}

				$dir_auth = filecenter_auth_folder($fi_idx); // 권한 - 선택한 폴더에 대한 권한

				$chk_level = $up_level - 1;
				$chk_type  = $path_data['path_set_type'][$chk_level];
				$chk_name  = $path_data['path_up_name'][$chk_level];
				if (($first_name == 'Project' || $first_name == 'V-Drive') && $chk_name == 'Member' && $chk_type == 'fix')
				{
					$mem_where = " and mem.mem_idx = '" . $data["file_sname"] . "'";
					$mem_data = member_info_data('view', $mem_where);
					if ($mem_data['total_num'] > 0)
					{
						$mem_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span> (' . $mem_data['group_name'] . ')] : ' . $data['file_name'] . '</span>';

						$file_url = '<a href="javascript:void(0)" onclick="file_list_view(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">' . $mem_name . '</a>';
					}
				}
			}
			else
			{
				$checkbox_html = '<input type="checkbox" id="fiidx_' . $i . '" name="chk_fi_idx[]" value="' . $data['fi_idx'] . '" title="선택" onclick="addFiles()" />';
				$icon_img   = file_ext_img($data['file_ext']) . ' ';
				$file_size  = byte_replace($data['file_size']);
				$file_type  = $data["file_ext"];

				$dir_auth = filecenter_auth_file($up_idx, $data); // 권한 - 현 위치 폴더에 대한 권한 - up_idx

				if ($dir_auth['dir_read_auth'] == 'Y' || $dir_auth['dir_write_auth'] == 'Y')
				{
					$file_url = '<a href="http://' . $filecneter_url . '/filemanage/file_download.php?idx=' . $data['fi_idx'] . '&amp;idx2=' . $code_mem . '" title="' . $data['file_name'] . ' 다운로드">' . $data['file_name'] . '</a>';
				}
				else
				{
					$file_url = $data['file_name'];
				}
			}

			if ($dir_auth['dir_view_auth'] == 'Y' || $dir_auth['dir_read_auth'] == 'Y' || $dir_auth['dir_write_auth'] == 'Y')
			{
				$charge_str = staff_layer_form($data['reg_id'], '', 'N', $set_color_list2, 'fileliststtaff', $data['fi_idx'], '');
?>
		<tr>
			<td><?=$checkbox_html;?></td>
			<td><div class="left"><?=$icon_img;?><?=$file_url;?>
				<?=$dir_auth['btn_preview'];?>
				<?=$dir_auth['btn_history'];?></div></td>
			<td><div class="right"><span class="eng"><?=$file_size;?></span></div></td>
			<td><span class="eng"><?=$file_type;?></span></td>
			<td><span class="eng"><?=date('Y-m-d', $data["reg_date"]);?></span></td>
			<td><?=$charge_str;?></td>
			<td>
				<?=$dir_auth['btn_modify'];?><br />
				<?=$dir_auth['btn_delete'];?>
			</td>
		</tr>
<?
				$i++;
				$num--;
				$chk_num++;
			}
		}
	}
	if ($chk_num == 0)
	{
?>
		<tr>
			<td colspan="7">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
?>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />
<?
		if ($dir_auth_page['btn_sel_down'] != '') // 다운로드권한을 가지고 있을 경우
		{
?>
<script type="text/javascript">
//<![CDATA[
	var btn_html = $('#button_download').html()
	$('#filecenter_download').html('');
	$('#filecenter_download').append(btn_html);
//]]>
</script>
<?
		}
	}
?>
