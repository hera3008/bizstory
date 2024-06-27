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
		$root_path   = $_SERVER['DOCUMENT_ROOT'];
		$tempFile    = $_FILES['Filedata']['tmp_name'];
		$file_name   = $_FILES['Filedata']['name'];
		$file_size   = $_FILES["Filedata"]['size'];
		$file_type   = $_FILES["Filedata"]['type'];

		$folder      = $_REQUEST['folder'];
		$fileext     = $_REQUEST['fileext'];

	// scriptData
		$upload_name = $_REQUEST['upload_name'];
		$add_name    = $_REQUEST['add_name'];
		$file_max    = $_REQUEST['file_max'];
		$upload_ext  = $_REQUEST['upload_ext'];
		$view_name   = $_REQUEST['view_name'];
		$sort        = $_REQUEST['sort'];

		$file_ex    = explode(".", $file_name);
		$ex_name    = strtolower($file_ex[sizeof($file_ex) - 1]);
		$save_name  = time() . '_' . $add_name . '_' . $file_name;

		$targetPath = $root_path . $folder . '/';
		$targetPath = str_replace('//', '/', $targetPath);
		$targetFile = $targetPath . $save_name;
		$fileParts  = pathinfo($file_name);
		
	// 확장자확인
		if ($fileext != '')
		{
			$file_ok = 'N';
			$fileTypes  = str_replace('*.', '', $fileext);
			$fileTypes  = str_replace(';', '|', $fileTypes);
			$typesArray = explode('|', $fileTypes);
			foreach ($typesArray as $k => $v)
			{
				if ($v == $ex_name)
				{
					$file_ok = 'Y';
					break;
				}
			}
		}
		else
		{
			$file_ok = 'Y';
		}

		if ($file_ok == 'Y')
		{
			move_uploaded_file($tempFile, $targetFile);

			$str  = $file_name . '|';                // 0
			$str .= number_format($file_size) . '|'; // 1
			$str .= $save_name . '|';                // 2
			$str .= $file_type . '|';                // 3
			$str .= $ex_name . '|';                  // 4

			echo $str;
		}
		else
		{
			$str = 'N| Invalid file type.';
			echo $str;
		}
	}
?>