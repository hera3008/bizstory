<?
/*
	생성 : 2012.04.30
	위치 : 설정폴더(관리자) > 업체관리 > 업체목록 - 업체로그인
*/
	include "../common/setting.php";
	include "../common/member_chk.php";

// 그룹관리자 아이디구하기
	$mem_where = " and mem.comp_idx = '" . $comp_idx . "' and mem.ubstory_level = '11'";
	$mem_data = member_info_data('view', $mem_where);

	member_login_action($mem_data, $sess_str);

// 최고관리자값 가지고 있도록 작업할것
	echo '<meta http-equiv="refresh" content="0; url=../../index.php">';
?>