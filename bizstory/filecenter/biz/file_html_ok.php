<?php
	require_once "../../common/setting.php";

	$sess_id       = $_POST['sess_id'];
	$comp_idx      = $_POST['comp_idx'];
	$part_idx      = $_POST['part_idx'];
	$mem_idx       = $_POST['mem_idx'];
	$table_name    = $_POST['table_name'];
	$table_idx     = $_POST['table_idx'];
	$idx_common    = $_POST['idx_common'];


	$targetDir = $tmp_path . '/';

	@set_time_limit(5 * 60);

	$sess_name = 'tmp_' . $comp_idx . '_' . $part_idx . '_' . $mem_idx . '_' . $idx_common;
	$chk_num = $_COOKIE[$sess_name];
	if ($chk_num == '') $chk_num = 0;
	else $chk_num++;
	$_COOKIE[$sess_name] = $chk_num;
	setcookie($sess_name, $chk_num, time() + 10, "/");

	$chunk    = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
	$chunks   = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
	$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

	$fileName = preg_replace('/[^\w\._]+/', '', $fileName); // Clean the fileName for security reasons

// Make sure the fileName is unique but only if chunking is disabled
	if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName))
	{
		$ext        = strrpos($fileName, '.');
		$fileName_a = substr($fileName, 0, $ext);
		$fileName_b = substr($fileName, $ext);

		$count = 1;
		while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
		{
			$count++;
		}

		$fileName = $fileName_a . '_' . $count . $fileName_b;
	}

	if (!file_exists($targetDir)) @mkdir($targetDir); // Create target dir

// Look for the content type header
	if (isset($_SERVER["HTTP_CONTENT_TYPE"])) $contentType = $_SERVER["HTTP_CONTENT_TYPE"];
	if (isset($_SERVER["CONTENT_TYPE"])) $contentType = $_SERVER["CONTENT_TYPE"];

// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
	if (strpos($contentType, "multipart") !== false)
	{
		if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name']))
		{
		// Open temp file
			//$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
			$out = fopen($targetDir . $fileName, $chunk == 0 ? "wb" : "ab");
			if ($out)
			{
			// Read binary input stream and append it to temp file
				$in = fopen($_FILES['file']['tmp_name'], "rb");
				if ($in)
				{
					while ($buff = fread($in, 4096))
					{
						fwrite($out, $buff);
					}
				}
				else
				{
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				}
				fclose($in);
				fclose($out);
				@unlink($_FILES['file']['tmp_name']);
			}
			else
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			}
		}
		else
		{
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		}
	}
	else
	{
		$out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab"); // Open temp file
		if ($out)
		{
		// Read binary input stream and append it to temp file
			$in = fopen("php://input", "rb");
			if ($in)
			{
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			}
			else
			{
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			}
			fclose($in);
			fclose($out);
		}
		else
		{
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
	}

// 파일 다른곳으로 저장, 디비에 저장
	$tmp_file  = $targetDir . DIRECTORY_SEPARATOR . $fileName;
	$file_name = $_FILES['file']['name'];
	$file_size = $_FILES['file']['size'];
	$file_type = $_FILES['file']['type'];

	$file_ex   = explode('.', $file_name);
	$ex_name   = strtolower($file_ex[sizeof($file_ex) - 1]);
	$save_name = $table_name . '_' . $comp_idx . '_' . $part_idx . '_' . $mem_idx . '_' . time() . '_' . $_COOKIE[$sess_name] .'.' . $ex_name;

	$target_path = $tmp_path . '/';
	$target_path = str_replace('//', '/', $target_path);
	$target_file = $target_path . $save_name;

	copy($tmp_file, $target_file);
	unlink($tmp_file);

	$file_name = string_input($file_name);
	$file_size = string_input($file_size);
	$file_type = string_input($file_type);
	$ex_name   = string_input($ex_name);
	$save_name = string_input($save_name);

// 테이블에 저장
	$tmp_query = "
		insert into temp_file_info set
			  idx_common = '" . $idx_common . "'
			, sort       = '" . $sort . "'
			, img_fname  = '" . $file_name . "'
			, img_sname  = '" . $tmp_path . '/' . $save_name . "'
			, img_size   = '" . $file_size . "'
			, img_type   = '" . $file_type . "'
			, img_ext    = '" . $ex_name . "'
			, reg_date   = '" . date('Y-m-d H:i:s') . "'
	";
	db_query($tmp_query);
	query_history($tmp_query, 'temp_file_info', 'insert', $comp_idx, $part_idx, $mem_idx);
?>