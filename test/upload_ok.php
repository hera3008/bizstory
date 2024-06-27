<?php
	include "../common/setting.php";

	$file_num = $_POST['file_upload_num'];
	$file_chk = 1;
	for ($i = 1; $i <= $file_num; $i++)
	{
		$chk_file_save = $_POST[$file_form_name . $fnum . '_save_name'];

		if ($chk_file_save != '')
		{
			$chk_file_name = $_POST[$file_form_name . $fnum . '_file_name'];
			$chk_file_size = $_POST[$file_form_name . $fnum . '_file_size'];
			$chk_file_type = $_POST[$file_form_name . $fnum . '_file_type'];
			$chk_file_ext  = $_POST[$file_form_name . $fnum . '_file_ext'];

			$chk_file_size = str_replace(',', '', $chk_file_size);
			$new_file_name = $new_name . '_' . $idx . '_' . $fnum . '.' . $chk_file_ext;

			$old_file = $tmp_path . '/' . $chk_file_save;
			$new_file = $new_path . '/' . $new_file_name;

		// 총 주소 알아오기


/*
			if (file_exists($old_file))
			{
				if(!copy($old_file, $new_file))
				{
					$upfile_data[$fnum]['error']  = '{"success_chk" : "N", "error_string" : "저장시 오류가 생겼습니다. <br />다시 확인하고 파일을 올리세요."}';
					$upfile_data[$fnum]['f_name'] = '';
					$upfile_data[$fnum]['s_name'] = '';
					$upfile_data[$fnum]['f_size'] = '';
					$upfile_data[$fnum]['f_type'] = '';
					$upfile_data[$fnum]['f_ext']  = '';
				}
				else
				{
					$upfile_data[$fnum]['error']  = '';
					$upfile_data[$fnum]['f_name'] = $chk_file_name;
					$upfile_data[$fnum]['s_name'] = $new_file_name;
					$upfile_data[$fnum]['f_size'] = $chk_file_size;
					$upfile_data[$fnum]['f_type'] = $chk_file_type;
					$upfile_data[$fnum]['f_ext']  = $chk_file_ext;

					unlink($old_file);
				}
				Return $upfile_data;
			}
			*/
		}
	}
?>