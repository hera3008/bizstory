<?
/*
	생성 : 2012.05.07
	위치 : 업무보고서 파일다운로드
*/
	include "../common/setting.php";
	include "../common/no_direct.php";

	$where = " and wrf.wrf_idx = '" . $wrf_idx . "'";
	$data = work_report_file_data('view', $where);

	$file_path = $comp_work_path . '/' . $data['wi_idx'] . '/' . $data['img_sname'];

	$file_name = utf_han($data["img_fname"]);
	$file_name = str_replace(' ', '_', $file_name);

    header("Content-Description: File Transfer");
    header("Content-Type: application/octet-stream");
	//header("Content-Type: " . $data["img_type"]);
	Header("Content-Disposition: attachment; filename=" . $file_name . "");
	header("Content-Transfer-Encoding: binary");
	Header("Content-Length: " . (string)(filesize($file_path)));
	Header("Cache-Control: cache, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");

    flush();

	if (is_file($file_path))
	{
		$fp = fopen($file_path, "rb");
		if (!fpassthru($fp)) fclose($fp);
	}
?>