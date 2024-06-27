<?
/*
	생성 : 2012.04.25
	수정 : 2012.6.19
	위치 : 업무폴더 > 나의업무 > 업무 - 상태 - 대기 -> 업무진행
*/
	//require_once "../../common/set_info.php";
	//require_once "../../common/no_direct.php";
	//require_once "../../common/ajax_member_chk.php";
	include "../../common/setting.php";
	include "../process/mobile_setting.php";
	include "../process/ajax_member_chk.php";
	include "../process/no_direct.php";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$idx = $_POST['idx'];

	switch($idx) {
		case 'WS02':
		case 'WS80_02':
			$work_info = new work_info();
			$work_info->wi_idx = $wi_idx;
			$work_info->data_path = $comp_work_path;
			$work_info->data_dir = $comp_work_dir;
			
			$data        = $work_info->work_info_view();
			$deadline_list = deadline_date();
			
			echo json_encode(array('result_code'=>"0", 'data'=>$data, 'deadline_list'=>$deadline_list, 'set_work_deadline_txt'=>$set_work_deadline_txt));		
			break;
	}
	
	
?>