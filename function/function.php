<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 기본 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//------------------------------------ 에러페이지
	function error_page($error_type, $string_url)
	{
		global $local_dir;

		$string_url = urlencode($string_url);
		echo '<meta http-equiv="refresh" content="0; url=' . $local_dir . '/bizstory/include/error_page.php?error_type=' . $error_type . '&string_url=' . $string_url . '" />';
		exit;
	}

//------------------------------------ 에러메세지
	function error_message($error_type, $string_url)
	{
		global $set_error_message;

		$error_contents = '
			<div class="error_view">
				<p><strong>에러내용</strong></p>
				<p>' . $set_error_message[$error_type]['message'] . '</p>
				<span class="button red strong"><a href="' . $string_url . '"><strong>' . $set_error_message[$error_type]['error'] . '</strong></a></span>
			</div>
		';

		echo $error_contents;
	}

//------------------------------------ 변수넘길 때사용
	function chk_input($str)
	{
		if(is_array($str))
		{
			foreach ($str as $k => $v)
			{
				$str[$k] = trim($str[$k]);
				$str[$k] = htmlspecialchars($str[$k]);
				$str[$k] = urlencode($str[$k]);
			}
		}
		else
		{
			$str = trim($str);
			$str = htmlspecialchars($str);
			$str = urlencode($str);
		}
		return $str;
	}

//------------------------------------ 저장시
	function string_input($str)
	{
		
		if(is_array($str))
		{
			foreach ($str as $k => $v)
			{
				$str[$k] = trim($str[$k]);
				$str[$k] = str_replace("<script", "&lt;script", $str[$k]);
				$str[$k] = str_replace("</script>", "&lt;/script&gt;", $str[$k]);
				$str[$k] = query_real_escape_string($str[$k]);
			}
		}
		else
		{
			$str = trim($str);
			$str = str_replace("<script", "&lt;script", $str);
			$str = str_replace("</script>", "&lt;/script&gt;", $str);
			$str = query_real_escape_string($str);
		}

		return $str;
	}

//------------------------------------ 출력시
	function string_output($str)
	{
		if(is_array($str))
		{
			foreach ($str as $k => $v)
			{
				$str[$k] = trim($str[$k]);
				$str[$k] = stripslashes($str[$k]);
			}
		}
		else
		{
			$str = trim($str);
			$str = stripslashes($str);
		}

		return $str;
	}

//------------------------------------ 원하는 길이에 자르기
	function string_cut($str, $length, $str_type = "")
	{
		$str = trim($str);
		$str_len = strlen($str);

		$total_str = "";
		$ii = 0;
		for ($i = 0; $i < $length; $i++)
		{
			$new_str = mb_substr($str, $i, 1, "UTF-8");
			$total_str .= $new_str;

			if(ord($new_str) > 127)
			{
				$ii = $ii + 2;
			}
			$ii++;
		}

		if($str_len > $ii)
		{
			if ($str_type != "no")
			{
				$total_str .= "..";
			}
		}

		return $total_str;
	}

//------------------------------------ 원하는 길이, 원하는 시작점에 자르기
	function string_cut_to_from($str, $start, $length)
	{
		$str = trim(stripslashes($str));

		if(str_len($str) < $length)
		{
			return $str;
		}
		else
		{
			$ii = 0;
			for($i = 0; $i < $length; $i++)
			{
				if(ord($str[$i]) > 127)
				{
					$ii++;
				}
				$ii++;
			}
			$str = mb_substr($str, $start, $ii, "UTF-8");

			return $str;
		}
	}

//------------------------------------ 글자길이 구하기
// 유니코드일 경우 3byte, 그외 2byte
	function str_len($str)
	{
		$str = trim($str);
		$str_len = strlen($str) / 3;

		Return $str_len;
	}

//------------------------------------ 라디오 버튼이나 체크박스에 checked를 설정
	function checked($val1, $val2)
	{
		if ($val1 != "" || $val2 != "")
		{
			if ($val1 == $val2) return ' checked="checked"';
		}
		else Return '';
	}

//------------------------------------ select 박스의 selected를 설정
	function selected($val1, $val2)
	{
		if ($val1 != "" || $val2 != "")
		{
			if ($val1 == $val2) return ' selected="selected"';
		}
		else Return '';
	}

//------------------------------------ 년 월 일로 변경하기
	function date_replace($str, $str_type)
	{
		$str_arr = explode(" ", $str);

		$str_date = $str_arr[0];
		$date_arr = explode("-", $str_date);

		$str_hour = $str_arr[1];
		$hour_arr = explode(":", $str_hour);

		$new_str = '';
		
		if ($str_arr[0] != "0000-00-00" && $str_arr[0] != "")
		{
			if ($str_type == 'Y-m-d')
			{
				$new_str = $date_arr[0] . '-' . $date_arr[1] . '-' . $date_arr[2];
			}
			else if ($str_type == 'Ymd')
			{
				$new_str = $date_arr[0] . $date_arr[1] . $date_arr[2];
			}
			else if ($str_type == 'Ym')
			{
				$new_str = $date_arr[0] . $date_arr[1];
			}
			else if ($str_type == 'YmdHis')
			{
				$new_str = $date_arr[0] . $date_arr[1] . $date_arr[2] . $hour_arr[0] . $hour_arr[1] . $hour_arr[2];
			}
			else if ($str_type == 'Y.m.d')
			{
				$new_str = $date_arr[0] . '.' . $date_arr[1] . '.' . $date_arr[2];
			}
			else if ($str_type == 'Y.m')
			{
				$new_str = $date_arr[0] . '.' . $date_arr[1];
			}
			else if ($str_type == 'y.m.d')
			{
				$new_str = substr($date_arr[0], 2, 2) . '.' . $date_arr[1] . '.' . $date_arr[2];
			}
			else if ($str_type == 'm-d')
			{
				$new_str = $date_arr[1] . '-' . $date_arr[2];
			}
			else if ($str_type == 'Y-m-d H:i:s')
			{
				$new_str = $date_arr[0] . '-' . $date_arr[1] . '-' . $date_arr[2] . ' ' . $hour_arr[0] . ':' . $hour_arr[1] . ':' . $hour_arr[2];
			}
			else if ($str_type == 'Y-m-d H:i')
			{
				$new_str = $date_arr[0] . '-' . $date_arr[1] . '-' . $date_arr[2] . ' ' . $hour_arr[0] . ':' . $hour_arr[1];
			}
			else if ($str_type == 'Y/m/d H:i')
			{
				$new_str = $date_arr[0] . '/' . $date_arr[1] . '/' . $date_arr[2] . ' ' . $hour_arr[0] . ':' . $hour_arr[1];
			}
			else if ($str_type == 'Y.m.d H:i')
			{
				$new_str = $date_arr[0] . '.' . $date_arr[1] . '.' . $date_arr[2] . ' ' . $hour_arr[0] . ':' . $hour_arr[1];
			}
			else if ($str_type == 'Ymdw')
			{
				global $set_week;
				$new_str2 = '';
				$date_time = mktime(0,0,0, $date_arr[1], $date_arr[2], $date_arr[0]);
				$new_str2 = date('w', $date_time);
				if ($new_str2 == 0) $new_str2 = 7;
				$new_str2 = $set_week[$new_str2];

				$new_str = '<span class="date">'.$date_arr[0] . '-' . $date_arr[1] . '-' . $date_arr[2] . ' (' . $new_str2 . ')</span>';
			}
			else if ($str_type == 'w')
			{
				global $set_week;

				$date_time = mktime(0,0,0, $date_arr[1], $date_arr[2], $date_arr[0]);
				$new_str = date('w', $date_time);
				if ($new_str == 0) $new_str = 7;
				$new_str = $set_week[$new_str];
			}
		}

		Return $new_str;
	}

//------------------------------------ 바꾸기
	function han_utf($str) // 출력
	{
		return iconv("EUC-KR", "UTF-8", $str);
	}

	function utf_han($str) // 저장
	{
		return iconv("UTF-8", "EUC-KR", $str);
	}

//-------------------------------------- 디비사용량
	function db_volume()
	{
		$result = db_query("SHOW TABLE STATUS");
		$str = 0;
		while($dbData = query_fetch_array($result))
		{
			$str += $dbData["Data_length"] + $dbData["Index_length"];
		}
		Return $str;
	}

//-------------------------------------- 서버사용량
	function server_volume($local_path)
	{
		$str = `du -sb $local_path`;
		$volume = $str != "" ? trim(explode('/', $str)[0]):0;		
		Return $volume;
	}

//-------------------------------------- 바이트설정
	function byte_replace($str)
	{
		$num1 = 1024;
		$num2 = 1024 * 1024;
		$num3 = 1024 * 1024 * 1024;
		$num4 = 1024 * 1024 * 1024 * 1024;

		if ($str == "" || $str == 0)
		{
			$str = "0 Byte";
		}
		else if ($str > $num1 && $str < $num2)
		{
			$str = sprintf("%0.1f KB", $str / $num1);
		}
		else if ($str > $num2 && $str < $num3)
		{
			$str = sprintf("%0.1f MB", $str / $num2);
		}
		else if ($str > $num3 && $str < $num4)
		{
			$str = sprintf("%0.1f GB", $str / $num3);
		}
		else
		{
			$str = $str . " Byte";
		}
		Return $str;
	}

//-------------------------------------- 바이트설정
	function byte_replace1($str)
	{
		$num1 = 1024;
		$num2 = 1024 * 1024;
		$num3 = 1024 * 1024 * 1024;
		$num4 = 1024 * 1024 * 1024 * 1024;

		if ($str == "" || $str == 0)
		{
			$str = "0 B";
		}
		else if ($str > $num1 && $str < $num2)
		{
			$str = sprintf("%0.1f KB", $str / $num1);
		}
		else if ($str > $num2 && $str < $num3)
		{
			$str = sprintf("%0.1f MB", $str / $num2);
		}
		else if ($str > $num3 && $str < $num4)
		{
			$str = sprintf("%0.1f GB", $str / $num3);
		}
		else
		{
			$str = $str . " B";
		}
		Return $str;
	}

//-------------------------------------- 확장자별 파일이미지
	function file_ext_img($ext)
	{
		global $local_path, $local_dir;

		$ext = strtolower($ext);

		$img_path = $local_path . "/bizstory/images/filecenter/" . $ext . ".gif";
		$img_dir  = $local_dir  . "/bizstory/images/filecenter/" . $ext . ".gif";

		if(is_file($img_path) == true)
		{
			$str = '<img src="' . $img_dir . '" alt="' . $ext . '" />';;
		}
		else
		{
			$str = '<img src="' . $local_dir . '/bizstory/images/filecenter/unknown.gif" alt="' . $ext . '" />';
		}
		Return $str;
	}
//-------------------------------------- 확장자별 파일이미지
	function mobile_file_ext_img($ext)
	{
		global $local_path, $local_dir, $mobile_path, $mobile_dir;

		$ext = strtolower($ext);

		$img_path = $mobile_path . "/images/" . $ext . ".png";
		
		$img_dir  = $mobile_dir  . "/images/" . $ext . ".png";

		if(is_file($img_path) == true)
		{
			$str = '<img src="' . $img_dir . '" alt="' . $ext . '" />';;
		}
		else
		{
			$str = '<img src="' . $mobile_dir . '/images/unknown.png" alt="' . $ext . '" />';
		}
		Return $str;
	}
//------------------------------------ 이미지 원래사이즈
// $img_path : 이미지 절대경로
// return  : width, height
	function images_org_size($img_path)
	{
		if (file_exists($img_path))
		{
			$img_size       = getimagesize($img_path);
			$i_size["width"]  = $img_size[0];
			$i_size["height"] = $img_size[1];

			return $i_size;
		}
	}

//------------------------------------ 정렬
	function field_sort($order_name, $order_field)
	{
		global $local_dir;
		$str = '<a href="javascript:void(0);" onclick="order_move(\'' . $order_field . '\', \'asc\')"><img src="' . $local_dir . '/common/images/table/asc.gif" alt="올림정렬" /></a>' . $order_name . ' <a href="javascript:void(0);" onclick="order_move(\'' . $order_field . '\', \'desc\')"><img src="' . $local_dir . '/common/images/table/desc.gif" alt="내림정렬" /></a>';
		echo $str;
	}

//------------------------------------ 페이징 - 댓글
	function page_view_comment($page_size, $page_num, $total_page, $func_name)
	{
		$str = '
			<div class="tablenav_m">
				<ul>
					<li><a href="javascript:void(0);" onclick="page_move_comment(\'first\', \'' . $func_name . '\')" class="first"><<</a></li>
					<li><a href="javascript:void(0);" onclick="page_move_comment(\'prev\', \'' . $func_name . '\')" class="previous"><</a></li>
					<li><a href="javascript:void(0);" onclick="page_move_comment(\'next\', \'' . $func_name . '\')" class="next">></a></li>
					<li><a href="javascript:void(0);" onclick="page_move_comment(\'last\', \'' . $func_name . '\')" class="last">>></a></li>
					<li><a href="javascript:void(0);" onclick="page_move_comment(\'all\', \'' . $func_name . '\')" class="showall">전체보기</a></li>
					<li>
						<select id="' . $func_name . '_page_page_num" name="m_page_num" title="페이지 선택" onchange="page_move_comment(this.value, \'' . $func_name . '\')">';
		for ($i = 1; $i <= $total_page; $i++)
		{
			$str .= '
							<option value="' . $i . '"' . selected($page_num, $i) . '>' . $i . '</option>';
		}
		$str .= '
						</select>
					</li>
				</ul>
			</div>
			<div class="tablelocation_m">
				<select id="' . $func_name . '_page_page_size" name="m_page_size" title="출력게시물수 선택" onchange="page_move_check(\'' . $func_name . '\')">
					<option value="5"' . selected($page_size, '5') . '>5</option>
					<option value="10"' . selected($page_size, '10') . '>10</option>
					<option value="15"' . selected($page_size, '15') . '>15</option>
					<option value="20"' . selected($page_size, '20') . '>20</option>
					<option value="30"' . selected($page_size, '30') . '>30</option>
					<option value="40"' . selected($page_size, '40') . '>40</option>
					<option value="50"' . selected($page_size, '50') . '>50</option>
					<option value="60"' . selected($page_size, '60') . '>60</option>
					<option value="80"' . selected($page_size, '80') . '>80</option>
					<option value="100"' . selected($page_size, '100') . '>100</option>';
		if ($page_size > 100)
		{
			$str .= '
					<option value="' . $page_size . '"' . selected($page_size, $page_size) . '>' . $page_size . '</option>';

		}
		$str .= '
				</select>
				<span>Entries Per Page - </span> Page ' . $page_num . '/' . $total_page . '
			</div>';

		echo $str;
	}

//------------------------------------ 페이징
	function page_view($page_size, $page_num, $total_page)
	{
		$str = '
			<div id="tablenav">
				<ul>
					<li><a href="javascript:void(0);" onclick="page_move(\'first\')" class="first">First Page</a></li>
					<li><a href="javascript:void(0);" onclick="page_move(\'prev\')" class="previous">Previous Page</a></li>
					<li><a href="javascript:void(0);" onclick="page_move(\'next\')" class="next">Next Page</a></li>
					<li><a href="javascript:void(0);" onclick="page_move(\'last\')" class="last">Last Page</a></li>
					<li><a href="javascript:void(0);" onclick="page_move(\'all\')" class="showall">View All</a></li>
					<li>
						<select id="page_page_num" name="page_num" title="페이지 선택" onchange="page_move(this.value)">';
		for ($i = 1; $i <= $total_page; $i++)
		{
			$str .= '
							<option value="' . $i . '"' . selected($page_num, $i) . '>' . $i . '</option>';
		}
		$str .= '
						</select>
					</li>
				</ul>
			</div>
			<div id="tablelocation">
				<select id="page_page_size" name="page_size" title="출력게시물수 선택" onchange="list_data()">
					<option value="5"' . selected($page_size, '5') . '>5</option>
					<option value="10"' . selected($page_size, '10') . '>10</option>
					<option value="15"' . selected($page_size, '15') . '>15</option>
					<option value="20"' . selected($page_size, '20') . '>20</option>
					<option value="30"' . selected($page_size, '30') . '>30</option>
					<option value="40"' . selected($page_size, '40') . '>40</option>
					<option value="50"' . selected($page_size, '50') . '>50</option>
					<option value="60"' . selected($page_size, '60') . '>60</option>
					<option value="80"' . selected($page_size, '80') . '>80</option>
					<option value="100"' . selected($page_size, '100') . '>100</option>';
		if ($page_size > 100)
		{
			$str .= '
					<option value="' . $page_size . '"' . selected($page_size, $page_size) . '>' . $page_size . '</option>';

		}
		$str .= '
				</select>
				<span>Entries Per Page</span> - Page ' . $page_num . '/' . $total_page . '
			</div>';

		echo $str;
	}

//-------------------------------------- array로 선언된 값을 select
	function code_select($data_list, $chk_name, $chk_id, $chk_value, $chk_title, $chk_str = '', $data_value = '', $chk_class = '', $chk_script = '')
	{        
		$str = "";
		$total_num = count($data_list);
		$str .= '
			<select name="' . $chk_name . '" id="' . $chk_id . '" title="' . $chk_title . '" ' . $chk_class . ' ' . $chk_script . '>';
		if ($chk_str != '')
		{
			$str .='
				<option value="">' . $chk_str . '</option>';
		}
		if ($total_num > 0)
		{
			foreach ($data_list as $k => $v)
			{
				if ($data_value == "value")
				{
					$str .= '
				<option value="' . $v . '"' . selected($chk_value, $v) . '>' . $v . '</option>';
				}
				else
				{
					$str .= '
				<option value="' . $k . '"' . selected($chk_value, $k) . '>' . $v . '</option>';
				}
			}
		}
		$str .= '
			</select>';
		return $str;
	}

//-------------------------------------- array로 선언된 값을 radio
	function code_radio($data_list, $chk_name, $chk_id, $chk_value, $data_value = '', $chk_class = '', $chk_script = '')
	{
		$str = "";
		$total_num = count($data_list);
		$str .= '
			<ul>';
		if ($total_num > 0)
		{
			$kk = 1;
			foreach ($data_list as $k => $v)
			{
				$id_str = $chk_id . '_' . $kk;
				if ($data_value == "value")
				{
					$str .= '
					<li><label for="' . $id_str . '"><input type="radio" name="' . $chk_name . '" id="' . $id_str . '" value="' . $v . '" ' . checked($chk_value, $v) . $chk_class . $chk_script . ' />' . $v . '</label></li>';
				}
				else
				{
					$str .= '
					<li><label for="' . $id_str . '"><input type="radio" name="' . $chk_name . '" id="' . $id_str . '" value="' . $k . '" ' . checked($chk_value, $k) . $chk_class . $chk_script . ' />' . $v . '</label></li>';
				}
				$kk++;
			}
		}
		$str .= '
			</ul>';
		return $str;
	}

//-------------------------------------- array로 선언된 값을 checkbox
	function code_checkbox($data_list, $chk_name, $chk_id, $chk_value, $data_value = '', $chk_class = '', $chk_script = '')
	{
		$str = "";
		$total_num = count($data_list);
		$str .= '
			<ul>';
		if ($total_num > 0)
		{
			$chk_value_arr = explode(',', $chk_value);

			$kk = 1;
			foreach ($data_list as $k => $v)
			{
				$id_str = $chk_id . '_' . $kk;
				$checked = '';
				if (is_array($chk_value_arr))
				{
					foreach ($chk_value_arr as $k1 => $v1)
					{
						if ($data_value == "value")
						{
							if ($v1 == $v)
							{
								$checked = 'checked="checked"';
								break;
							}
						}
						else
						{
							if ($v1 == $k)
							{
								$checked = 'checked="checked"';
								break;
							}
						}
					}
				}

				if ($data_value == "value")
				{
					$str .= '
					<li><label for="' . $id_str . '"><input type="checkbox" name="' . $chk_name . '" id="' . $id_str . '" value="' . $v . '" ' . $checked . $chk_class . $chk_script . ' />' . $v . '</label></li>';
				}
				else
				{
					$str .= '
					<li><label for="' . $id_str . '"><input type="checkbox" name="' . $chk_name . '" id="' . $id_str . '" value="' . $k . '" ' . $checked . $chk_class . $chk_script . ' />' . $v . '</label></li>';
				}
				$kk++;
			}
		}
		$str .= '
			</ul>';
		return $str;
	}

//-------------------------------------- 월
	function select_month($chk_name, $chk_id, $chk_value, $chk_title, $chk_str = '', $chk_class = '', $chk_script = '')
	{
		$str = "";
		$str .= '
			<select name="' . $chk_name . '" id="' . $chk_id . '" title="' . $chk_title . '" ' . $chk_class . ' ' . $chk_script . '>';
		if ($chk_str != '')
		{
			$str .='
				<option value="">' . $chk_str . '</option>';
		}
		for ($i = 1; $i <= 12; $i++)
		{
			$str .= '
				<option value="' . $i . '"' . selected($chk_value, $i) . '>' . $i . '</option>';
		}
		$str .= '
			</select>';
		return $str;
	}

//-------------------------------------- 일
	function select_day($chk_name, $chk_id, $chk_value, $chk_title, $chk_str = '', $chk_class = '', $chk_script = '')
	{
		$str = "";
		$str .= '
			<select name="' . $chk_name . '" id="' . $chk_id . '" title="' . $chk_title . '" ' . $chk_class . ' ' . $chk_script . '>';
		if ($chk_str != '')
		{
			$str .='
				<option value="">' . $chk_str . '</option>';
		}
		for ($i = 1; $i <= 31; $i++)
		{
			$str .= '
				<option value="' . $i . '"' . selected($chk_value, $i) . '>' . $i . '</option>';
		}
		$str .= '
			</select>';
		return $str;
	}

//-------------------------------------- 암호화변환
	function pass_change($str, $sess_str)
	{
		$sess_length = strlen($sess_str);
		$str_length  = strlen($str);

		$total_str = "";

		if ($sess_length > $str_length)
		{
			$chk_length = $sess_length;
		}
		else
		{
			$chk_length = $str_length;
		}

		for ($i = 0; $i < $chk_length; $i++)
		{
			$sess_char = substr($sess_str, $i, 1);
			$str_char  = substr($str, $i, 1);
			$total_str .= $sess_char . $str_char;
		}

		$total_str = $total_str . $sess_str;
		$total_str = md5($total_str);
		$total_str = $sess_str . $total_str;
		$total_str = md5($total_str);
		$str = $total_str;

		Return $str;
	}

//-------------------------------------- 저장변수 확인
	function param_check($param, $options, $chk_type = 'json')
	{
		$total_error = '';
		if(is_array($param))
		{
			foreach($options as $opt_k => $opt_v)
			{
			// 필수검사 - 필드명, 에러메세지
				if ($opt_k == "require")
				{
					foreach($opt_v as $chk_k => $chk_v)
					{
						$field = $chk_v['field'];
						$msg   = $chk_v['msg'];

						if($msg == "") $msg = $field;

						foreach($param as $param_k => $param_v)
						{
							if($param_k == $field)
							{
								$param_v = trim($param_v);
								if(!isset($param_v) || $param_v == "")
								{
									$total_error .= '<strong>' . $msg . '</strong>을(를) 반드시 입력하세요.<br />';
								}
							}
						}
					}
				}
			// 중복검사 - 테이블명, 필드명, 검색조건, 에러메세지시의 한글명
				else if ($opt_k == "unique")
				{
					foreach($opt_v as $chk_k => $chk_v)
					{
						$table = $chk_v['table'];
						$field = $chk_v['field'];
						$where = $chk_v['where'];
						$msg   = $chk_v['msg'];

						foreach($param as $param_k => $param_v)
						{
							if($param_k == $field)
							{
								$sql = "SELECT * FROM " . $table . " WHERE " . $field . " = '" . $param_v . "'";
								if ($where != "") $sql .= " AND " . $where;
								$data = query_view($sql);

								if($data['total_num'] > 0)
								{
									$total_error .= $msg;
								}
							}
						}
					}
				}
			}
		}

		if ($chk_type == 'json')
		{
			if ($total_error != '')
			{
				$str = '{"success_chk" : "N", "error_string" : "' . $total_error . '"}';
				echo $str;
				exit;
			}
		}
		else
		{
			$str = '';
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 파일관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//------------------------------------ 파일 디렉토리검사, 생성
	function files_dir($file_path)
	{
		if (!is_dir($file_path)) @mkdir($file_path, 0777);
		@chmod($file_path, 0777);

	}

//------------------------------------ 파일올리기
	function files_upload($file_chk)
	{
		global $tmp_path, $tmp_dir, $_FILES;

		$file      = $_FILES["post_f_name"][tmp_name];
		$file_name = $_FILES["f_name"][name];
		$file_size = $_FILES["f_name"][size];
		$file_type = $_FILES["f_name"][type];
		$file_str  = $_REQUEST["f_str"];

		if ($file_size > 0)
		{
			$f_real[$i] = $file_name;
			$f_size[$i] = $file_size;
			$f_type[$i] = $file_type;
			$f_str[$i]  = $file_str;

			if(!is_uploaded_file($file))
			{
				alerts("업로드할 파일이 없습니다. \\n\\n다시 확인하고 파일을 올리세요.", "history.back();", "func");
				exit;
			}

			$file_ex   = explode(".", $file_name);
			$ex_name   = strtolower($file_ex[sizeof($file_ex) - 1]);
			$file      = str_replace("\\\\", "\\", $file);
			$file_name = time() . $b_idx . $i . "." . $ex_name;

		// 파일 저장
			if(!move_uploaded_file($file, $data_path . "/" . $file_name))
			{
				alerts("저장시 오류가 생겼습니다. \\n\\n다시 확인하고 파일을 올리세요.", "history.back();", "func");
				exit;
			}
			@chmod($file_name, 0777);

			$f_name[$i] = $file_name;

		// 이미지일 경우
			if (substr($f_type[$i], 0, 5) == "image")
			{
			// 메인썸네일 이미지 저장
				$now_path = $data_path . "/" . $file_name;
				$new_path = $data_path . "/" . "main_" . $file_name;

				bbs_image_resize($set_bbs["img_main_width"], $now_path, $new_path, $ex_name, "1");

			// 목록썸네일 이미지 저장
				$now_path = $data_path . "/" . $file_name;
				$new_path = $data_path . "/" . "list_" . $file_name;

				bbs_image_resize($set_bbs["img_list_width"], $now_path, $new_path, $ex_name, "1");

			// 보기용 이미지 저장
				$now_path = $data_path . "/" . $file_name;
				$new_path = $data_path . "/" . "view_" . $file_name;

				bbs_image_resize($bbs_image_view_width, $now_path, $new_path, $ex_name, "1");

			// 원본 유지를 하지 않을 경우 삭제 - 기본 1000보다 클 경우
				if ($set_bbs["image_original_yn"] == "N")
				{
					$now_path = $data_path . "/" . $file_name;
					$new_path = $data_path . "/" . "new_" . $file_name;

					bbs_image_resize($bbs_image_max_width, $now_path, $new_path, $ex_name, "2");

					$f_size[$i] = filesize($now_path);
				}
			}

		}
	}

//-------------------------------------- 스킨목록
	function skin_list($skin_type)
	{
		global $temp_path;

		$path = $temp_path . "/" . $skin_type;
		$dir_handle = @opendir($path) or die("Unable to open " . $path);

		while (false !== ($file = readdir($dir_handle)))
		{
			if ($file != "." && $file != "..")
			{
				$files[] = $file;
			}
		}
		if (is_array($files))
		{
			sort($files);
		}

		Return $files;
	}

//-------------------------------------- 데이타표현 - select
	function display_select($table_name, $table_where, $table_order, $chk_name, $chk_id, $chk_value, $chk_value_str, $field_value, $chk_str = "")
	{
		if ($table_order == "") $table_order = "reg_date desc";

		$data_sql["query_string"] = "
			select * from " . $table_name . "
			where del_yn = 'N' " . $table_where . "
			order by " . $table_order . "
		";
		$data_sql['query_page'] = $data_sql["query_string"];
		$data_sql['page_num']  = "";
		$data_sql['page_size'] = "";
		$list = query_list($data_sql);

		$str = '
		<select name="' . $chk_name . '" id="' . $chk_id . '">
			<option value="">:: ' . $chk_str . ' ::</option>';
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$str .= '
			<option value="' . $data[$chk_value] . '" ' . selected($data[$chk_value], $field_value) . '>' . $data[$chk_value_str] . '</option>';
				$kk++;
			}
		}
		$str .= '
		</select>';

		return $str;
	}

//-------------------------------------- 데이타표현 - checkbox
	function display_checkbox($table_name, $table_where, $table_order, $chk_name, $chk_id, $chk_value, $chk_value_str, $field_value)
	{
		if ($table_order == "") $table_order = "reg_date desc";

		$data_sql["query_page"] = "
			select count(*) from " . $table_name . "
			where del_yn = 'N' " . $table_where . "
		";
		$data_sql["query_string"] = "
			select * from " . $table_name . "
			where del_yn = 'N' " . $table_where . "
			order by " . $table_order . "
		";
		$data_sql['page_num']  = "";
		$data_sql['page_size'] = "";
		$list = query_list($data_sql);

		$kk  = 1;
		$str = "";
		foreach ($list as $k => $data)
		{
			if (is_array($data))
			{
				$id_str = $chk_id . '_' . $kk;

				$checked = "";
				if ($field_value != "")
				{
					foreach ($field_value as $k1 => $v1)
					{
						if ($v1 == $data[$chk_value])
						{
							$checked = 'checked="checked"';
							break;
						}
					}
				}

				$str .= '
		<label for="' . $id_str . '">
			<input type="checkbox" name="' . $chk_name . '[]" id="' . $id_str . '" value="' . $data[$chk_value] . '"' . $checked . ' /> ' . $data[$chk_value_str] . '
		</label>
				';
				$kk++;
			}
		}

		return $str;
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 메일관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

	function mail_fsend($tos, $subject, $message = '', $addtion_header = '', $files = array())
	{
		// 추천 해더설정
		$addtion_header["content_type"] ='text/html'; //기본내용형식 : 일반 text / text/plain
		$addtion_header["char_set"]     ='UTF-8';     //기본문자셋 : UTF-8 / 한글: euc-kr

		// $files: 는 서버내의 파일을 지목할 때 사용
		//============================================== 기초 설정
		$boundary = "----=b" . md5(uniqid(time()));

		$content_type = $addtion_header["content_type"];
		if(empty($content_type))
		{
			$content_type = "text/html";
		}

		$char_set = $addtion_header["char_set"];
		if(empty($char_set))
		{
			$char_set = "UTF-8";
		}

		//===================================================== to 설정
		if(is_string($tos))
		{
			$to = $tos;
		}
		// 여러명일 경우
		else if(is_array($tos))
		{
			$to = implode(', ', $tos);
		}

		//===================================================== subject 설정
		if(empty($subject))
		{
			$subject = "No title " . date("Y-m-d H:i:s");
		}
		$subject = "=?" . $char_set . "?B?" . base64_encode($subject) . "?=";

		//=====================================================해더 설정
		$headers = array();
		$headers['mime_version'] = 'MIME-Version: 1.0';
		$headers['content_type'] = "Content-type: multipart/mixed; boundary=\"{$boundary}\"";

		if(!empty($addtion_header["from"]))
		{
			$headers[] = "From: " . $addtion_header["from"];
		}
		else
		{
			$headers[] = "From: webmaster@" . $_SERVER["SERVER_NAME"];
		}
		if(!empty($addtion_header["cc"]))
		{
			$headers[] = "cc: " . $addtion_header["cc"];
		}
		if(!empty($addtion_header["bcc"]))
		{
			$headers[] = "Bcc: " . $addtion_header["bcc"];
		}

		if(!empty($addtion_header["mail_engine"]))
		{
			$headers[] = $addtion_header["mail_engine"];
		}
		if(!empty($addtion_header["return_mail"]))
		{
			$headers[] = "Return-Path: " . $addtion_header["return_mail"] . " ";
		}
		if(!empty($addtion_header["quick_type"]))
		{
			$headers[] = $addtion_header["quick_type"];
		}

		if(!empty($headers))
		{
			$header = implode("\r\n", $headers) . "\r\n";
		}
		else
		{
			$header = '';
		}

		//======================================================== 메세지 인코딩
		$msg_content_type = "Content-type: {$content_type}; charset={$char_set}";

		$msg  = '';
		$msg .= mail_fsend_enc_msg($boundary, $message, $msg_content_type); //본문 메세지 처리

		//======================================================== 업로드 되는 첨부파일 인코딩
		if(!empty($_FILES))
		{
			foreach($_FILES as $key => $value)
			{
				$t = $key;
				break;
			}
			$t_files     = $_FILES[$t]['tmp_name'];
			$t_filenames = $_FILES[$t]['name'];
			$t_error     = $_FILES[$t]['error'];
			if(!is_array($t_files))
			{
				$t_files = array($t_files);
			}
			if(!is_array($t_filenames))
			{
				$t_filenames = array($t_filenames);
			}
			if(!is_array($t_error))
			{
				$t_error = array($t_error);
			}
			for($i = 0, $m = count($t_files); $i < $m; $i++)
			{
				if($t_error[$i] == 0)
				{
					$msg .= mail_fsend_enc_file($boundary, $t_files[$i], $t_filenames[$i]); //첨부파일 처리
				}
			}
		}

		//========================================================= 메세지 닫기
		$msg .= '--' . $boundary . "--";

		//===================================================== 메일 보내기
		$result = mail ($to, $subject, $msg, $header);
		return $result;
	}

	function mail_fsend_enc_msg($boundary, $msg = '', $content_type = 'Content-type: text/plain; charset=euc-kr')
	{
		//본문문자열 인코딩
		$re_str  = '';
		$re_str  = '--' . $boundary . "\r\n"; //바운드리 설정
		$re_str .= $content_type . "\r\n";
		$re_str .= 'Content-Transfer-Encoding: base64' . "\r\n" . "\r\n";
		// RFC 2045 에 맞게 $data를 형식화
		$new_msg = chunk_split(base64_encode($msg));
		$re_str .= $new_msg . "\r\n";
		return $re_str;
	}

	function mail_fsend_enc_file($boundary, $file, $filename = '')
	{
		//첨부파일 인코딩
		$content_type = 'Content-Type: application/octet-stream; charset=euc-kr';
		$re_str  = '';
		$re_str  = '--' . $boundary . "\r\n"; //바운드리 설정
		$re_str .= $content_type . "\r\n";
		$re_str .= 'Content-Transfer-Encoding: base64' . "\r\n";
		if(strlen($filename) == 0)
		{
			$filename = basename($file);
		}
		$re_str .= "Content-Disposition: attachment; filename=\"" . '=?euc-kr?B?' . base64_encode($filename) . '?=' . "\"" . "\r\n" . "\r\n";

		// RFC 2045 에 맞게 $data를 형식화
		$fp = @fopen($file, "r");
		if($fp)
		{
			$msg = fread($fp, filesize($file));
			fclose($fp);
		}

		$new_msg = chunk_split(base64_encode($msg));
		$re_str .= $new_msg . "\r\n";

		return $re_str;
	}




// 기업체 카테고리 그룹 상단 탭
	function part_cate_tab($comp_idx, $active_code = '')
	{
		//  업체정보
		$where = " and comp.comp_idx = ".$comp_idx;
		$comp_info = company_info_data('view', $where);
		
		$str_tab = '';
		if($comp_info['comp_class'] == 1)
		{
			$data_info = company_school_info($comp_info);
			if(!$active_code) $active_code = $data_info[array_key_last($data_info)]['code_val'];

			$str_tab = '<div class="mb-5 hover-scroll-x overflow-y-hidden"><div class="d-grid"><ul class="nav nav-tabs flex-nowrap text-nowrap">';
			foreach($data_info as $key => $val)
			{
				$str_tab .= '<li class="nav-item"><a href="javascript:void(0);" class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0 px-4 px-md-6 px-xl-8" ' . ($active_code == $val['code_val'] ? 'active' : '') . '> ' . $val['comp_name'] .'</a></li>';

			}
			$str_tab .= '</ul></div></div>';
		}
		else
		{}

		return $str_tab;

	}

//학교 그룹 카테고리
	function company_school_info($comp_info)
	{
		$query_string = "
				SELECT
				sc.sc_comp_idx, sc.sc_code, sc.sc_name
				, org.org_comp_idx, org.org_code, org.org_name
				, schul.schul_comp_idx, schul.schul_code, schul.schul_name
			FROM 
				(SELECT comp_idx AS sc_comp_idx, sc_code, sc_name FROM company_info WHERE del_yn='N' AND sc_code='" .$comp_info['sc_code'] . "' AND org_code='' AND schul_code='') AS sc
				JOIN (SELECT comp_idx AS org_comp_idx, org_code, org_name FROM company_info WHERE del_yn='N' AND sc_code='" .$comp_info['sc_code'] . "' AND org_code='" .$comp_info['org_code'] . "' AND schul_code='') AS org
				JOIN (SELECT comp_idx AS schul_comp_idx, schul_code, schul_name FROM company_info WHERE del_yn='N' AND sc_code='" .$comp_info['sc_code'] . "' AND org_code='" .$comp_info['org_code'] . "' AND schul_code='" .$comp_info['schul_code'] . "') AS schul
		";
		$data = query_view($query_string);

		$data_info[] = array('comp_idx' => $data['sc_comp_idx'], 'code_class' => 'sc_code', 'code_val' => $data['sc_code'], 'comp_name' => $data['sc_name']);
		$data_info[] = array('comp_idx' => $data['org_comp_idx'], 'code_class' => 'org_code', 'code_val' => $data['org_code'], 'comp_name' => $data['org_name']);
		$data_info[] = array('comp_idx' => $data['schul_comp_idx'], 'code_class' => 'schul_code', 'code_val' => $data['schul_code'], 'comp_name' => $data['schul_name']);

		return $data_info;
	}
?>