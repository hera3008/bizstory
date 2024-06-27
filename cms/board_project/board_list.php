<?
/*
	생성 : 2012.05.16
	위치 : 게시판 - 목록
*/
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

	$set_where = " and bs.bs_idx = '" . $bs_idx . "' and bs.view_yn = 'Y'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

// 관리자일 경우
	$set_board['auth_yn'] = "N";
	if ($_SESSION[$sess_str . "_ubstory_level"] == "1" || $_SESSION[$sess_str . "_ubstory_level"] == "11")
	{
		$set_board['auth_yn'] = "Y";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and b.bs_idx = '" . $bs_idx . "'";
	if ($scate != "") // 말머리
	{
		$where .= " and (b.bc_idx = '" . $scate . "' or concat(bc.up_bc_idx, ',') like '%," . $scate . ",%') ";
	}
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}
	$where .= ' and bn.b_idx is NULL';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'b.order_idx';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
// 공지게시물
	$notice_where = "
		and bn.bs_idx = '" . $bs_idx . "'
	";
	$notice_list = pro_board_notice_data("list", $set_board['name_db'], $notice_where, "", "", "");

// 일반게시물
	if ($set_board['list_row'] == "" || $set_board['list_row'] == 0) // 한페이지에 다 보이도록
	{
		$list = pro_board_info_data('list', $set_board['name_db'], $where, $orderby, '', '');
	}
	else
	{
		$list = pro_board_info_data('list', $set_board['name_db'], $where, $orderby, $page_num, $set_board['list_row']);
	}
	$page_num = $list['page_num'];

// 총 게시물 수
	$total_board = $list['total_num'] + $notice_list['total_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;scate=' . $send_scate;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

// header view
	if (file_exists($set_board['skin_path'] . "/list_head.php") == true) include $set_board['skin_path'] . "/list_head.php";
	else echo $set_board['skin_dir'] . "/list_head.php 지정한 파일이 없습니다.<br />";

// 공지게시물
	$bbslist_i = 1;
	foreach($notice_list as $notice_k => $board_data)
	{
		if (is_array($board_data))
		{
			$b_data = pro_board_list_data($board_data, $set_board, "no_list");

			if (file_exists($set_board['skin_path'] . "/list_notice.php") == true) include $set_board['skin_path'] . "/list_notice.php";
			else echo $set_board['skin_dir'] . "/list_notice.php 지정한 파일이 없습니다.<br />";

			$bbslist_i++;
		}
	}

// 일반게시물
	$bbs_num = $list['total_num'] - ($page_num - 1) * $set_board['list_row'];
	foreach($list as $k => $board_data)
	{
		if (is_array($board_data))
		{
			$b_data = pro_board_list_data($board_data, $set_board, "list");

			if (file_exists($set_board['skin_path'] . "/list_main.php") == true) include $set_board['skin_path'] . "/list_main.php";
			else echo $set_board['skin_dir'] . "/list_main.php 지정한 파일이 없습니다.<br />";

			$bbs_num--;
		}
	}

// tail view
	if (file_exists($set_board['skin_path'] . "/list_tail.php") == true) include $set_board['skin_path'] . "/list_tail.php";
	else echo $set_board['skin_dir'] . "/list_tail.php 지정한 파일이 없습니다.<br />";
?>