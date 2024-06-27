<?
/*
	생성 : 2023.08.03
	위치 : 임시 업로드된 파일삭제
*/
	//include "../../bizstory/common/setting.php";
	//include "../../bizstory/common/no_direct.php";

	$file_path = $tmp_path . '/' . $save_name;
	if (is_file($file_path))
	{
		@unlink($tmp_path . '/' . $save_name);
	}
	
	$str = '{"success_chk" : "Y", "error_string" : ""}';
	echo $str;
?>