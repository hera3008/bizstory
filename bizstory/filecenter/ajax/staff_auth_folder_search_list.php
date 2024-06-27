<?
    require_once "../../common/setting.php";
    require_once "../../common/no_direct.php";
    require_once "../../common/member_chk.php";
    
    $comp_idx = $_POST['comp_idx'];
    $part_idx = $_POST['part_idx'];
    $mem_idx = $_POST['mem_idx'];
    $search_text = $_POST['search_text'];
    $chk_complete = $_POST['chk_complete'];
    $swhere = $_POST['swhere'];
    
    if ($chk_complete == 'Y') {
        $pro_end = 'Y';
    } else {
        $pro_end = 'N';
    }
    
    filecenter_staff_folder_write($comp_idx, $part_idx, $mem_idx, $swhere, $search_text, $pro_end);
    
    function filecenter_staff_folder_write($comp_idx, $part_idx, $mem_idx, $swhere, $search_text, $pro_end)
    {
        global $local_dir, $auth_menu;

        $common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "' and fi.dir_file = 'folder' and fi.file_name != ''";
        if ($pro_end == 'N')
        {
            $common_where .= " and ifnull(pro.pro_status, '') != 'PS90'";
        }

        if ($swhere == 'project_code') {
            //$where = $common_where . " and fi.file_name like '%" . $search_text . "%' ";            
            $where = $common_where . " and pro.project_code like '%" . $search_text . "%' ";
        } else if ($where == 'subject') {
            $where = $common_where . " and pro.subject like '%" . $search_text . "%' ";
        } else {
            $where = $common_where . " and fi.file_name like '%" . $search_text . "%' ";
        }
        
        $order = "fi.file_path asc, fi.file_name asc";
        $info_list = filecenter_level_data('list', $where, $order, '', '');
        //print_r($info_list);
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
                        <th>폴더경로</th>
                        <th>보기</th>
                        <th>읽기</th>
                        <th>쓰기</th>
                        <th>삭제</th>
                    </tr>
                </thead>
                </table>
            </li>';

        $sort = 1;
        foreach ($info_list as $info_k => $info_data)
        {
            if (is_array($info_data))
            {
                $file_name  = $info_data['file_name'];
                $file_path  = $info_data['file_path'];
                $next_depth = $info_data['dir_depth'] + 1;

                $write_auth_yn = 'Y';
                
            // V-Drive/Member
                if ($info_data['dir_depth'] == 2 && $info_data['file_path'] == '/V-Drive' && $file_name == 'Member' && $info_data['set_type'] == 'fix')
                {
                    $write_auth_yn = 'N';
                }
            // Project/Project_code/Member
                $project_dir = '/Project/' . $info_data['project_code'];
                if ($info_data['dir_depth'] == 3 && $info_data['file_path'] == $project_dir && $file_name == 'Member' && $info_data['set_type'] == 'fix')
                {
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

                $left_str .= '
                    <li class="directory collapsed">
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
                            <td class="left"><span>&nbsp;' . substr($file_path, 1, strlen($file_path)) . '/' . $file_name . '</span></td>
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