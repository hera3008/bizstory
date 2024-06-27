<?
// 일반페이지관련
	$page_size      = 10; // 페이지당 게시물
	$recv_page_size = urldecode($page_size);
	$send_page_size = chk_input($recv_page_size);
	$page_size      = string_input($page_size);

	$block_size      = 10; // 블럭당 페이지수
	$recv_block_size = urldecode($block_size);
	$send_block_size = chk_input($recv_block_size);
	$block_size      = string_input($block_size);

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = $_SESSION[$sess_str . '_part_idx'];
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$mobile_dir  = $local_dir . '/bizstory/mobile';
	$mobile_path = $root_dir . $mobile_dir;

/* 기본정보 */
	$tel_num     = "02-1544-7325";
	$mobile_eng  = "BIZSTORY";
	$mobile_name = "비즈스토리";

/* 상단 기타버튼 */
	$btn_reload = '<a class="right_b" href="javascript:void(0)" onclick="window.location.reload()">refresh</a>';
	$btn_back   = '<a class="back" href="javascript:history.go(-1)">go back</a>';
	$btn_menu   = '<a class="button menu" href="javascript:void(0);" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'">menu</a>';
	$btn_logout = '<a class="button menu" href="javascript:void(0)" onclick="login_out()">로그아웃</a>';

/* 카피라이트 */
	$address = '
		<address>
			<em>Copyright &copy; 2012</em>
			<strong>' . $mobile_eng . '</strong>
			<span>All Rights Reserved.</span>
		</address>
	';

	$set_part_yn      = $company_set_data['part_yn'];
	$set_part_work_yn = $company_set_data['part_work_yn'];
?>