<?
/*
	생성 : 2012.10.12
	위치 : 상담 파일다운로드
*/
	include "../bizstory/common/setting.php";
	include "../bizstory/common/no_direct.php";
	include $local_path . "/agent/include/agent_chk.php";

	$where = " and consf.consf_idx = '" . $consf_idx . "'";
	$data = consult_file_data('view', $where);

	$file_path = $consult_path . '/' . $data['cons_idx'] . '/' . $data['img_sname'];

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