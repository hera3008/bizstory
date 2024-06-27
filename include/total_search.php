<?
/*
	생성 : 2013.01.18
	수정 : 2013.01.18
	위치 : 검색페이지
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	echo 'total_search_keyword -> ', $total_search_keyword, '<br />';
?>