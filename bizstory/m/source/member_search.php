<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	
	$mode = $_POST['mode'];
	
	if($mode == "changePart") {
		$part = $_POST['part_idx'];
		
		echo "part =>".$part;
		
		$part_where = "and part.comp_idx = '" . $part . "'";
		$part_list     = company_part_data('list', $part_where, '', '', '');
	}

?>