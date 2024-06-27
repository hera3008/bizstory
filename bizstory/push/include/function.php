<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 기본 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

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
				$str[$k] = mysql_escape_string($str[$k]);
			}
		}
		else
		{
			$str = trim($str);
			$str = str_replace("<script", "&lt;script", $str);
			$str = str_replace("</script>", "&lt;/script&gt;", $str);
			$str = mysql_escape_string($str);
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

//------------------------------------ 바꾸기
	function han_utf($str) // 출력
	{
		return iconv("EUC-KR", "UTF-8", $str);
	}

	function utf_han($str) // 저장
	{
		return iconv("UTF-8", "EUC-KR", $str);
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
?>