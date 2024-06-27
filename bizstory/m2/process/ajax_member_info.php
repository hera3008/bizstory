<?
	include "../../common/setting.php";
	include "../process/mobile_setting.php";
	include "../process/ajax_member_chk.php";
	include "../process/no_direct.php";
	
	//$mem_idx = $_REQUEST['mem_idx'];

	$mem_where = " and mem.mem_idx = '" . $mem_idx . "'";
	$mem_data = member_info_data('view', $mem_where, '', '', '', 2);
	
	$mem_img = member_img_view($mem_data['mem_idx'], $comp_member_dir); // 등록자 이미지
	
	echo json_encode(array("data"=>$mem_data, "mem_img"=>$mem_img));
?>