<?php
header('Content-Type: application/json');

include "../../common/setting.php";

$code_comp = $_SESSION[$sess_str . '_comp_idx'];
$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

$comp_set_where = " and cs.comp_idx = '" . $code_comp . "'";
$comp_set_data  = company_setting_data('view', $comp_set_where);

$filecneter_url = $comp_set_data['file_out_url']; // 파일센터 주소

// 파일 정보
// $files = [
	// //'FILE ID(checkbox 선택 값)' => ['파일 이름', '파일 크기', '파일 유형']
   	// '1' => ['name' => '1.jpg', 'size' => '12361649', 'type' => 'image/jpeg'],
   	// '2' => ['name' => '2.jpg', 'size' => '1315686',  'type' => 'image/jpeg'],
   	// '3' => ['name' => '3.jpg', 'size' => '3578840',  'type' => 'image/jpeg'],
// ];
	
$qfiles = explode(",", $_POST['qfiles']);
$fileData = array();

foreach ($qfiles as $fileId) {
    
    $fi_idx = $fileId;

    $where = " and fi.fi_idx = '" . $fi_idx . "'";
    $data = filecenter_info_data('view', $where);
    if ($data) {
        $tmp_data = ['name' => $data['file_name'], 'size' => $data['file_size'], 'type' => $data['file_type']];
        array_push($fileData, $tmp_data);
    }
}

echo urldecode(json_encode($fileData));
?>
