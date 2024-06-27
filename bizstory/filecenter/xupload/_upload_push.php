<?php
	include "../../common/setting.php";

	$total_num = $_POST['total_num'];
	$add_name  = $_POST['add_name'];
	$mem_idx   = $_POST['mem_idx'];

	for ($i = 1; $i <= $total_num; $i++)
	{
		$file_name = urldecode($_POST['file_name_' . $i]);
		$file_size = urldecode($_POST['file_size_' . $i]);
		$file_type = urldecode($_POST['file_type_' . $i]);
		$file_ext  = urldecode($_POST['ex_name_' . $i]);
		$save_name = urldecode($_POST['save_name_' . $i]);

		if ($file_name != '')
		{
			if ($i == 1)
			{
				$total_file_name = $file_name;
				$total_file_size = $file_size;
				$total_file_type = $file_type;
				$total_file_ext  = $file_ext;
				$total_save_name = $save_name;
			}
			else
			{
				$total_file_name += '||' . $file_name;
				$total_file_size += '||' . $file_size;
				$total_file_type += '||' . $file_type;
				$total_file_ext  += '||' . $file_ext;
				$total_save_name += '||' . $save_name;
			}
		}
	}
?>