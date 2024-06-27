<?php
/*
	Uploadify v2.1.4
	Release Date: November 8, 2010

	Copyright (c) 2010 Ronnie Garcia, Travis Nickels

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/
	if (!empty($_FILES))
	{
		$root_path  = $_SERVER['DOCUMENT_ROOT'];
		$tempFile   = $_FILES['file_fname']['tmp_name'];
		$file_name  = $_FILES['file_fname']['name'];
		$file_size  = $_FILES["file_fname"]['size'];
		$file_type  = $_FILES["file_fname"]['type'];

		$folder     = $_REQUEST['folder'];
		$fileext    = $_REQUEST['fileext'];

		$upload_name = $_REQUEST['upload_name'];
		$add_name    = $_REQUEST['add_name'];
		$view_name   = $_REQUEST['view_name'];
		$file_max    = $_REQUEST['file_max'];
		$upload_ext  = $_REQUEST['upload_ext'];

		$file_path   = $root_path . '/data/tmp';
		$folder      = $file_path . '/1';

		if (!is_dir($folder)) @mkdir($folder, 0777);
		@chmod($folder, 0777);

		$fileext     = $_REQUEST['fileext'];
		$upload_name = 'file_fname';
		$add_name    = 'check';
		$view_name   = $_REQUEST['view_name'];
		$file_max    = $_REQUEST['file_max'];
		$upload_ext  = $_REQUEST['upload_ext'];

		$file_ex    = explode(".", $file_name);
		$ex_name    = strtolower($file_ex[sizeof($file_ex) - 1]);
		$file       = str_replace("\\\\", "\\", $file);
		$save_name  = time() . '_' . $add_name . '_' . $file_name;

		$targetPath = $folder . '/';
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

			$str  = $file_name . '|<br />';                // 0
			$str .= number_format($file_size) . '|<br />'; // 1
			$str .= $save_name . '|<br />';                // 2
			$str .= $file_type . '|<br />';                // 3
			$str .= $ex_name . '|<br />';                  // 4

			echo $str;
		}
		else
		{
			$str = 'Invalid file type.';
			echo $str;
		}
	}

// 파일삭제
	$delete_file = $file_path . '/1358748942_upload_check__20111231_170646.jpg';
	//unlink($delete_file);

// 폴더생성
	$make_folder = $file_path . '/3';
	//mkdir($make_folder);

// 폴더삭제
	$delete_folder = $file_path . '/2';
	//rmdir($delete_folder);

// 파일이름변경
	$old_name = $file_path . '/1/copy.jpg';
	$new_name = $file_path . '/1/copy2.jpg';
	//rename($old_name,$new_name);

// 파일복사
	$copy_file = $file_path . '/1/1358749140_upload_check_20111231_170646.jpg';
	$new_file = $file_path . '/1/copy.jpg';
	//copy($copy_file, $new_file);
?>