<?
	include "bizstory/common/setting.php";
	include $local_path . "/bizstory/common/member_chk.php";
	include $local_path . "/include/top.php";

	if ($fmode == '' || $smode == '')
	{
		if ($_SESSION[$sess_str . '_ubstory_level'] == '1')
		{
			include $local_path . "/include/main_highest.php";
		}
		else
		{
			if ($company_set_data['main_type'] == 'A')
			{
				include $local_path . "/include/main.php";
			}
			else
			{
				if (file_exists($local_path . "/include/main_" . $company_set_data['main_type'] . ".php") == true)
				{
					include $local_path . "/include/main_" . $company_set_data['main_type'] . ".php";
				}
				else
				{
					include $local_path . "/include/main.php";
				}
			}
		}

	}
	else
	{
		$link_file = $local_path . '/bizstory/' . $fmode . '/' . $smode . '.php';

		if (is_file($link_file)) include $link_file;
		else echo "파일이 없습니다.";
	}
	
	include $local_path . "/include/tail.php";
?>