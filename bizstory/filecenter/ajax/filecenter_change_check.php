<?
    require_once "../../common/setting.php";
    header("Content-type: application/json");
    
    $code_comp = $_SESSION[$sess_str . '_comp_idx'];
    $code_part = search_company_part($code_part);
    $code_mem  = $_SESSION[$sess_str . '_mem_idx'];
    $set_part_yn = $comp_set_data['part_yn'];

    $path_data = filecenter_folder_path($up_idx); // 위치
    $dir_auth  = filecenter_folder_auth($up_idx); // 권한확인

// 펼침
    $result = filecenter_open_check($up_idx, $code_comp, $code_part);
    $chk_up = $result['chk_up'];
    $project_fi_idx = $result['project_fi_idx'];
    $vdrive_fi_idx = $result['vdrive_fi_idx'];
    //$chk_up = filecenter_open_check($up_idx);

    if ($dir_auth['dir_write_auth'] != 'Y') // 등록권한
    {
        $navi_subject = " -> 업로드 권한이 없습니다.";
    }
    
    echo json_encode( array('navi_path'=>$path_data['navi_path'], 'navi_subject'=>$navi_subject, 'chk_up'=>$chk_up, 'dir_write_auth'=>$dir_auth['dir_write_auth']));
?>