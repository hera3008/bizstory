<?
/*
	생성 : 2012.08.27
	위치 : 거래처메모 파일다운로드
*/
	include "../common/setting.php";
	include "../common/no_direct.php";

	$where = " and cimf.cimf_idx = '" . $cimf_idx . "'";
	$data = client_memo_file_data('view', $where);

	$file_path = $comp_client_path . '/' . $data['ci_idx'] . '/' . $data['img_sname'];

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