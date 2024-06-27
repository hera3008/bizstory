<?
/*
	수정 : 2013.03.25
	위치 : 설정폴더 > 직원관리 > 직원등록/수정 - 파일다운로드
*/
	include "../common/setting.php";
	include "../common/no_direct.php";

	$where = " and mf.mf_idx = '" . $mf_idx . "'";
	$data = member_file_data('view', $where);

	$file_path = $comp_member_path . '/' . $data['mem_idx'] . '/' . $data['img_sname'];

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