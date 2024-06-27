<?
/*
	생성 : 2012.05.17
	위치 : 게시판 - 댓글파일 다운로드
*/
	include "../../bizstory/common/setting.php";
	include $local_path . "/cms/include/client_chk.php";

// 파일
	$where = " and bcof.bcof_idx = '" . $bcof_idx . "'";
	$data = pro_board_comment_file_data("view", $where);

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $data['bs_idx'] . "'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

// 파일 위치
	$file_dir = $set_board["bbs_path"] . "/" . $data["b_idx"] . "/" . $data["img_sname"];
	$r_name   = utf_han($data["img_fname"]);
	$r_name   = str_replace(" ", "_", $r_name);

// 파일다운로드 수 증가
	db_query("update pro_board_comment_file set img_down = img_down + 1 where bcof_idx = '" . $bcof_idx . "'");

	header("Content-Type: " . $data["img_type"]);
	Header("Content-Disposition: attachment; filename=" . $r_name . "");
	header("Content-Transfer-Encoding: binary");
	Header("Content-Length: " . (string)(filesize($file_dir)));
	Header("Cache-Control: cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	if (is_file($file_dir))
	{
		$fp = fopen($file_dir, "rb");
		if (!fpassthru($fp))
		{
			fclose($fp);
		}
	}
?>