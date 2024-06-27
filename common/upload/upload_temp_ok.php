<?php
/*
	생성 : 2023.08.03
	위치 : 임시 업로드 파일 저장
*/

	if (!empty($_FILES))
	{
		$root_path  = $_SERVER['DOCUMENT_ROOT'];
		$tempFile   = $_FILES['filedata']['tmp_name'];
		$file_name  = $_FILES['filedata']['name'];
		$file_size  = $_FILES["filedata"]['size'];
		$file_type  = $_FILES["filedata"]['type'];

		$folder     = $_REQUEST['folder'];
		$fileext    = $_REQUEST['fileext'];

		$upload_name = $_REQUEST['upload_name'];
		$add_name    = $_REQUEST['add_name'];
		$view_name   = $_REQUEST['view_name'];
		$file_max    = $_REQUEST['file_max'];
		$upload_ext  = $_REQUEST['upload_ext'];

		$file_ex    = explode(".", $file_name);
		$ex_name    = strtolower($file_ex[sizeof($file_ex) - 1]);
		$file       = str_replace("\\\\", "\\", $file);
		$save_name  = time() . '_' . $add_name . '_' . $file_name;

		$targetPath = $root_path . $folder . '/';
		$targetPath = str_replace('//', '/', $targetPath);
		$targetFile = $targetPath . $save_name;
		$fileParts  = pathinfo($file_name);
		
	// 확장자확인
		if ($fileext != '')
		{
			$fileTypes  = str_replace('*.', '', $fileext);
			$fileTypes  = str_replace(';', '|', $fileTypes);
			$typesArray = split('\|', $fileTypes);
			if (in_array($fileParts['extension'],$typesArray))
			{
				$file_ok = 'Y';
			}
			else
			{
				$file_ok = 'N';
			}
		}
		else
		{
			$file_ok = 'Y';
		}

		if ($file_ok = 'Y')
		{
			move_uploaded_file($tempFile, $targetFile);

			$str  = $file_name . '|';                // 0
			$str .= number_format($file_size) . '|'; // 1
			$str .= $save_name . '|';                // 2
			$str .= $file_type . '|';                // 3
			$str .= $ex_name . '|';                  // 4
			$str .= $tempFile . '|';                  // 5
			$str .= $targetFile . '|';                  // 6
			
			$str = '{"success_chk" : "Y", "file_info" : "'.$str.'", "error_string" : ""}';
			echo $str;
		}
		else
		{
			$str = '{"success_chk" : "Y", "file_info" : "Invalid file type.", "error_string" : ""}';
			echo $str;
		}
	}
?>