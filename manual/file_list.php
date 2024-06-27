<?
//------------------------------------------------ 디렉토리 구하기
	function get_folder_list($dir_path)
	{
		$dir_handle = opendir($dir_path);
		while (false !== ($element = readdir($dir_handle)))
		{
			if(is_dir($dir_path . $element))
			{
				if($element != "." && $element != "..")
				{
					if ($element != 'data' && $element != 'images' && $element != 'css' && $element != 'add' && $element != 'editor' && $element != 'sencha' && $element != 'tmp')
					{
						$new_path = $dir_path . $element . "/";
						//$dir_list = str_replace("/", "	", $new_path);
						$dir_list = $new_path;
						echo $dir_list . "<br>";

						$element_file[] = $new_path;
						$folder_down = get_folder_list($new_path);
						if ($folder_down != "")
						{
							$element_file[] = $folder_down;
						}
					}
				}
			}
			if(is_file($dir_path . $element))
			{
				$element_file[] = $element;
				$element_check = substr($element, 0, 1);
				if ($element_check != '_')
				{
					$new_file = $dir_path . $element;
					//$file_list = str_replace("/", "	", $new_file);
					echo $new_file . "<br>";
				}
			}
		}
		closedir($dir_handle);

		Return $element_file;
	}

	$dir_path = "/www/data/bizstory/";
	$folder_list = get_folder_list($dir_path);
?>