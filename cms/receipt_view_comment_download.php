<?
/*
	생성 : 2013.01.21
	수정 : 2013.01.21
	위치 : 접수목록 - 보기 - 댓글다운로드
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

	$where = " and rcf.rcf_idx = '" . $rcf_idx . "'";
	$data = receipt_comment_file_data('view', $where);

	$file_path = $comp_receipt_path . '/' . $data['ri_idx'] . '/' . $data['img_sname'];

	$file_name = utf_han($data["img_fname"]);
	$file_name = str_replace(' ', '_', $file_name);

	header("Content-Type: " . $data["img_type"]);
	Header("Content-Disposition: attachment; filename=" . $file_name . "");
	header("Content-Transfer-Encoding: binary");
	Header("Content-Length: " . (string)(filesize($file_path)));
	Header("Cache-Control: cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

	if (is_file($file_path))
	{
		$fp = fopen($file_path, "rb");
		if (!fpassthru($fp)) fclose($fp);
	}
?>