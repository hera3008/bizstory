<?
    require_once "../../common/setting.php";
    require_once "../../common/no_direct.php";
    require_once "../../common/member_chk.php";
    
    $params = urldecode($_POST['params']);
    
    $param_array = explode('||', $params);
    
    $comp_idx = $param_array[0];
    $part_idx = $param_array[1];
    $mem_idx = $param_array[2];
    $part_yn = $param_array[3];
    $dir_depth = $param_array[4];
    $up_idx = $param_array[5];
	$pro_end = $param_array[6];
	
	filecenter_staff_folder_write($comp_idx, $part_idx, $mem_idx, $part_yn, $dir_depth, $up_idx, $pro_end);
	
	function filecenter_staff_folder_write($comp_idx, $part_idx, $mem_idx, $part_yn, $dir_depth = 1, $up_idx = '', $pro_end)
	{
		global $local_dir, $auth_menu;

		$common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "' and fi.dir_file = 'folder' and fi.file_name != ''";
		if ($pro_end == 'N')
		{
			$common_where .= " and ifnull(pro.pro_status, '') != 'PS90'";
		}

		$where = $common_where . " and fi.dir_depth = '" . $dir_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'";
		$order = "fi.file_path asc, fi.file_name asc";
		$info_list = filecenter_level_data('list', $where, $order, '', '');

		if ($dir_depth == 1)
		{
			$left_str = '
			<ul id="type_folder_navi">
				<li>
					<table class="typetable">
					<colgroup>
						<col />
						<col width="70px" />
						<col width="70px" />
						<col width="70px" />
						<col width="70px" />
					</colgroup>
					<thead>
						<tr>
							<th>폴더명</th>
							<th>보기</th>
							<th>읽기</th>
							<th>쓰기</th>
							<th>삭제</th>
						</tr>
					</thead>
					</table>
				</li>';
		}
		else
		{
			$left_str .= '
			<ul id="[ui_id_str]">';
		}

		$sort = 1;
		foreach ($info_list as $info_k => $info_data)
		{
			if (is_array($info_data))
			{
				$file_name  = $info_data['file_name'];
				$next_depth = $info_data['dir_depth'] + 1;
				if ($info_data['up_fi_idx'] == '') $next_up = $info_data['fi_idx'];
				else $next_up = $info_data['up_fi_idx'] . ',' . $info_data['fi_idx'];

			// 하위메뉴
				//$down_where = $common_where . " and fi.dir_depth = '" . $next_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $next_up . ",%'";
				//$down_menu = filecenter_info_data('view', $down_where);

				$chk_up_idx = $info_data['up_fi_idx'];
				$chk_up_arr = explode(',', $chk_up_idx);
				foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
				{
					if ($chk_up_k == 0) $chk_up = $chk_up_v;
					else $chk_up .= '_' . $chk_up_v;
				}
				if ($chk_up == '') $li_id_str = 'authleft_' . $sort;
				else $li_id_str = 'authleft_' . $chk_up . '_' . $sort;
				$left_str = str_replace('[ui_id_str]', 'authsubmenu_' . $chk_up, $left_str);

				$icon_img = '';
				$write_auth_yn = 'Y';
				if ($info_data['dir_depth'] == 1)
				{
					if ($file_name == 'Project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
					else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
				}
			// V-Drive/Member
				if ($info_data['dir_depth'] == 2 && $info_data['file_path'] == '/V-Drive' && $file_name == 'Member' && $info_data['set_type'] == 'fix')
				{
					$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_m.png" alt="Member" title="Member" /> ';
					$write_auth_yn = 'N';
				}
			// Project/Project_code/Member
				$project_dir = '/Project/' . $info_data['project_code'];
				if ($info_data['dir_depth'] == 3 && $info_data['file_path'] == $project_dir && $file_name == 'Member' && $info_data['set_type'] == 'fix')
				{
					$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_m.png" alt="Member" title="Member" /> ';
					$write_auth_yn = 'N';
				}

			// 권한확인
				$sub_where = " and fa.comp_idx = '" . $comp_idx . "' and fa.mem_idx = '" . $mem_idx . "' and fa.fi_idx = '" . $info_data["fi_idx"] . "'";
				$auth_data = filecenter_auth_data('view', $sub_where);
				if ($auth_data['dir_view'] == '1') $dir_view = 'Y'; else { $auth_data['dir_view'] = '0'; $dir_view = 'N'; }
				if ($auth_data['dir_read'] == '1') $dir_read = 'Y'; else { $auth_data['dir_read'] = '0'; $dir_read = 'N'; }
				if ($auth_data['dir_write'] == '1') $dir_write = 'Y'; else { $auth_data['dir_write'] = '0'; $dir_write = 'N'; }
                if ($auth_data['dir_delete'] == '1') $dir_delete = 'Y'; else { $auth_data['dir_delete'] = '0'; $dir_delete = 'N'; }

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view  = "check_mem_auth(this, 'dir_view', '" . $info_data["fi_idx"] . "')";
					$btn_read  = "check_mem_auth(this, 'dir_read', '" . $info_data["fi_idx"] . "')";
					$btn_write = "check_mem_auth(this, 'dir_write', '" . $info_data["fi_idx"] . "')";
                    $btn_delete = "check_mem_auth(this, 'dir_delete', '" . $info_data["fi_idx"] . "')";
				}
				else
				{
					$btn_view  = "check_auth_popup('modify')";
					$btn_read  = "check_auth_popup('modify')";
					$btn_write = "check_auth_popup('modify')";
                    $btn_delete= "check_auth_popup('modify')";
				}
            /*
			// 쓰기권한일 경우
				if ($dir_write == 'Y')
				{
					$btn_view  = "";
					$btn_read  = "";
				}
			// 읽기권한일 경우
				else if ($dir_read == 'Y')
				{
					$btn_view  = "";
				}
              */  
                $class_name = "";
                
                if ($info_data['cnt'] > 0) {
                    if ($info_data['pro_status'] == 'PS90') {
                        $class_name = "collapsed2";
                    } else {
                        $class_name = "collapsed";
                    }
                    
                } else {
                    $class_name = "node_blank";
                }

			    $left_str .= '
			    	<li class="directory collapsed" id="' . $li_id_str . '">
					<table class="typetable">
					<colgroup>
						<col />
						<col width="70px" />
						<col width="70px" />
						<col width="70px" />
						<col width="70px" />
					</colgroup>
					<tbody>
						<tr>
							<td class="left"><span>&nbsp;<a href="javascript:void(0);" id="' . $next_depth . '_' . $next_up . '" rel="' . $comp_idx . '||' . $part_idx . '||' . $mem_idx . '||' . $part_yn . '||' . $next_depth . '||' . $next_up . '||' . $pro_end . '" >' . $icon_img . $file_name . '<strong class="' . $class_name . '"></strong></a></span></td>
							<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_view . '.gif" alt="' . $dir_view . '" class="pointer" onclick="' . $btn_view . '" val="' . $auth_data['dir_view'] . '"/></td>
							<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_read . '.gif" alt="' . $dir_read . '" class="pointer" onclick="' . $btn_read . '" val="' . $auth_data['dir_read'] . '"/></td>';

			     // Member 일 경우
				if ($write_auth_yn == 'N')
				{
					$left_str .= '
							<td>&nbsp;</td>
							<td>&nbsp;</td>';
				}
				else
				{
					$left_str .= '
							<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_write . '.gif" alt="' . $dir_write . '" class="pointer" onclick="' . $btn_write . '" val="' . $auth_data['dir_write'] . '"/></td>
							<td><img src="' . $local_dir . '/bizstory/images/icon/' . $dir_delete . '.gif" alt="' . $dir_delete . '" class="pointer" onclick="' . $btn_delete . '" val="' . $auth_data['dir_delete'] . '"/></td>';
				}

				$left_str .= '
						</tr>
					</tbody>
					</table>';
                ;
																															
				$left_str .= '
				</li>';
				$sort++;
			}
		}
		$left_str .= '
			</ul>';

		echo $left_str;
	}
	
	db_close();
?>