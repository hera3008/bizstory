<?
    require_once "../../common/setting.php";
    
    $params = urldecode($_POST['params']);
    
    $param_array = explode('||', $params);
    
    $comp_idx = $param_array[0];
    $part_idx = $param_array[1];
    $mem_idx = $param_array[2];
    $dir_depth = $param_array[3];
    $up_idx = $param_array[4];
    $ul_id_str = $param_array[5];
    
    filecenter_folder_left_vdrive_write($comp_idx, $part_idx, $mem_idx, $dir_depth, $up_idx, $ul_id_str);
    
    function filecenter_folder_left_vdrive_write($comp_idx, $part_idx, $mem_idx, $dir_depth, $up_idx, $ul_id_str = 'fsubmenu')
    {
        global $local_dir;

        $common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.dir_file = 'folder'";
        $where = $common_where . "
            and fi.dir_depth = '" . $dir_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
            and (fa.dir_view = '1' or fa.dir_read = '1' or fa.dir_write = '1')";

        $file_query['query_string'] = "
            select
                fi.fi_idx, fi.up_fi_idx, fi.dir_depth, fi.file_name
                , fa.dir_view, fa.dir_read, fa.dir_write
            from
                filecenter_info fi
                left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $mem_idx . "'
            where
                fi.del_yn = 'N'
                " . $where . "
            order by
                fi.file_path asc, fi.file_name asc
        ";
        $file_query['page_size'] = '';
        $file_query['page_num'] = '';
        $info_list = query_list($file_query);

        if ($info_list['total_num'] > 0)
        {
            $left_str = '
            <ul id="[ui_id_str]">';
            $sort = 1;
            foreach ($info_list as $info_k => $info_data)
            {
                if (is_array($info_data))
                {
                    $fi_idx      = $info_data['fi_idx'];
                    $file_name   = $info_data['file_name'];
                    $file_depth  = $info_data['dir_depth'];
                    $file_up_idx = $info_data['up_fi_idx'];
                    $next_depth  = $file_depth + 1;
                    $next_up     = $file_up_idx . ',' . $fi_idx;

                    $chk_up_arr = explode(',', $file_up_idx);
                    foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
                    {
                        if ($chk_up_k == 0) $chk_up = $chk_up_v;
                        else $chk_up .= '_' . $chk_up_v;
                    }
                    $li_id_str = 'fleft_' . $chk_up . '_' . $sort;
                    $left_str  = str_replace('[ui_id_str]', $ul_id_str . '_' . $chk_up, $left_str);

                    $li_class = '';
                    $icon_img = '';
                    if ($file_depth == 2)
                    {
                        if ($sort == 1)
                        {
                            $li_class = ' class="frist"';
                        }
                        else if ($sort == $info_list['total_num'])
                        {
                            $li_class = ' class="end"';
                        }

                        if ($sort == 1 && $sort == $info_list['total_num'])
                        {
                            $li_class = ' class="frist end"';
                        }
                    }

                    if ($ul_id_str == 'ffsubmenu')
                    {
                        if ($info_data['dir_write'] == '1') $up_type = 'Y';
                        else $up_type = 'N';
                        $btn_click = "open_dir_change2('" . $fi_idx . "', '" . $next_depth . "', '" . $up_type . "');";
                        $li_class = ' class="directory collapsed"';
                    }
                    else
                    {
                        $btn_click = "file_list_show('" . $fi_idx . "', '" . $next_depth . "');";
                        
                        $li_class = ' class="directory collapsed"';
                    }

                    $left_str .= '
                        <li' . $li_class . ' id="' . $li_id_str . '">
                    <a href="javascript:void(0);" id="' . $next_depth . '_' . $fi_idx . '" rel="' . $comp_idx . '||' . $part_idx . '||' . $mem_idx . '||' . $next_depth . '||' . $next_up . '||' . $ul_id_str . '" onclick="' . $btn_click . '">' . $file_name . '</span></a>';

                    $left_str .= '
                </li>';
                    $sort++;
                }
            }
            $left_str .= '
            </ul>';
        }

        echo $left_str;
    }

    db_close();
?>