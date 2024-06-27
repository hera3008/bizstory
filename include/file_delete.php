<?
/*
	생성 : 2012.04.30
	위치 : 업로드된 파일삭제
*/
	include "../common/setting.php";
	include "../common/no_direct.php";

	$file_path = $tmp_path . '/' . $save_name;
	if (is_file($file_path))
	{
		@unlink($tmp_path . '/' . $save_name);
	}

	$html_string = '<input type="file" name="' . $upload_name . '" id="' . $upload_name . '" class="type_text type_file type_multi" title="파일 선택하기" />';

	$str = '<?xml version="1.0" encoding="utf-8"?>
<result_data>
<success_chk>Y</success_chk>
<file_view><![CDATA[' . $html_string . ']]></file_view>
</result_data>';
	echo $str;
?>