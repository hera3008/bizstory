<?
    require_once "../../common/setting.php";
    
    $pId = "0";
    $pName = "";
    $pLevel = "";
    $pCheck = "";
    if(is_array($_REQUEST) && array_key_exists( 'id',$_REQUEST)) {
        $pId=$_REQUEST['id'];
    }
    if(is_array($_REQUEST) && array_key_exists( 'lv',$_REQUEST)) {
        $pLevel=$_REQUEST['lv'];
    }
    if(is_array($_REQUEST) && array_key_exists('n',$_REQUEST)) {
        $pName=$_REQUEST['n'];
    }
    if(is_array($_REQUEST) && array_key_exists('chk',$_REQUEST)) {
        $pCheck=$_REQUEST['chk'];
    }
    if ($pId==null || $pId=="") $pId = "0";
    if ($pLevel==null || $pLevel=="") $pLevel = "0";
    if ($pName==null) $pName = "";
    else $pName = $pName.".";

    $params = urldecode($_POST['otherParam']);
    
    $param_array = explode('||', $params);
    
    $comp_idx = $param_array[0];
    $mem_idx = $param_array[1];
    $dir_depth = $param_array[2];
    $up_idx = $param_array[3];
    $pro_end = $param_array[4];
    $ul_id_str = $param_array[5];
    
    $return = filecenter_folder_left_project_json($comp_idx, $mem_idx, $dir_depth, $up_idx, $pro_end, $ul_id_str);
    
    function filecenter_folder_left_project_json($comp_idx, $mem_idx, $dir_depth, $up_idx, $pro_end = 'N', $ul_id_str = 'fsubmenu')
    {
        global $local_dir;

        $common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.dir_file = 'folder'";

        if ($pro_end == 'N') $common_where .= " and 1 = (case when fi.menu_code is null then ifnull(pro.pro_status, 'PS01') != 'PS90' else 1 end ) ";

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
                left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
            where
                fi.del_yn = 'N'
                " . $where . "
            order by
                fi.file_path asc, fi.file_name asc
        ";
        $file_query['page_size'] = '';
        $file_query['page_num'] = '';
        //echo $file_query['query_string'];
        $info_list = query_list($file_query);
        
        $return = $dirs = $fi = array();

        if ($info_list['total_num'] > 0)
        {
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
                   
                    if ($ul_id_str == 'ffsubmenu')
                    {
                        if ($info_data['dir_write'] == '1') $up_type = 'Y';
                        else $up_type = 'N';
                        $btn_click = "open_dir_change('" . $fi_idx . "', '" . $next_depth . "', '" . $up_type . "');";
                        
                        $dirs[] = array('id'=>$fi_idx, 'name'=>$file_name, 'isParent'=>true, 'otherParam'=>$comp_idx . '||' . $mem_idx . '||' . $next_depth . '||' . $fi_idx . '||' . $pro_end . '||' . $ul_id_str);
                    }
                    else
                    {
                        $btn_click = "file_list_view('" . $fi_idx . "', '" . $next_depth . "');";
                        $fi[] = array('id'=>$fi_idx, 'name'=>$file_name, 'isParent'=>true, 'otherParam'=>$comp_idx . '||' . $mem_idx . '||' . $next_depth . '||' . $fi_idx . '||' . $pro_end . '||' . $ul_id_str);
                    }


                    $sort++;
                }
            }
            $return = array_merge($dirs,$fi);
        }

        return $return;
    }

    echo json_encode( $return );
    
    db_close();
?>