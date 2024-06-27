<?
	$database_connect = false;
//------------------------------------- 데이타 베이스 연결하기
	function db_connect()
	{
		global $database_connect;

		if (db_host != '' && db_user != '' && db_pass != '')
		{
		
			$database_connect  = mysql_connect(db_host, db_user, db_pass);
			$status_type = mysql_select_db(db_name, $database_connect);

			if(!$status_type)
			{
				$error_number  = mysql_errno($database_connect);
				$error_message = mysql_error($database_connect);
				exit;
			}				

		}
	}

//------------------------------------ 디비호출
	function db_query($query_string)
	{
		global $database_connect, $query_path, $this_page;

		db_connect();

		mysql_query('SET NAMES UTF8');
		$query_result = mysql_query($query_string, $database_connect);

		if (!$query_result)
		{
			$error_number  = mysql_errno($database_connect);
			$error_message = mysql_error($database_connect);

			$text_file  = date('Y-m-d H:i:s') . '\n';
			$text_file .= $this_page . '\n';
			$text_file .= $error_number . ':' ;
			$text_file .= $error_message . '\n';
			$text_file .= $query_string . '\n\n';

			$save_name = date('Ymd');
			$save_file = fopen($query_path . '/' . $save_name . '.txt', 'a+');
			fwrite($save_file, $text_file);
			fclose($save_file);
			echo 'Query Error<br />';
			echo $error_message, '<br />';
			echo '<pre>', $query_string, '</pre><br />';
			exit;
		}
		return $query_result;
	}

//------------------------------------ 디비닫기
	function db_close()
	{
		global $database_connect;

		mysql_close($database_connect);
	}

//------------------------------------ 데이타 총개수
	function query_page($query_page)
	{
		$sql = db_query($query_page);
		//echo 'query_page -> ', $query_page, '<br />';
		$data = mysql_fetch_array($sql);

		$data_info["total_num"] = $data[0];
		$data_info['query_string'] = $query_page;

		db_close();

		return $data_info;
	}

//------------------------------------ 데이타보기
	function query_view($query_string)
	{
		$sql = db_query($query_string);

		$data = mysql_fetch_array($sql);
		$data_info = string_output($data);
		$data_info['query_string'] = $query_string;
		$data_info['total_num'] = mysql_num_rows($sql);

		db_close();

		return $data_info;
	}

//------------------------------------ 데이타목록
	function query_list($data_sql)
	{
		$query_page   = $data_sql['query_page'];
		$query_string = $data_sql['query_string'];
		$page_size    = $data_sql['page_size'];
		$page_num     = $data_sql['page_num'];

	// 총개수, 총페이지수
		if ($query_page == '')
		{
			$sql = db_query($query_string);
			$data_info["total_num"] = mysql_num_rows($sql);
		}
		else
		{
			$sql = db_query($query_page);
			$data = mysql_fetch_array($sql);
			$data_info["total_num"] = $data[0];
		}
		if ($page_size != '' && $page_size != "0")
		{
			$data_info["total_page"] = ceil($data_info["total_num"] / $page_size);
			if ($page_num > $data_info['total_page'])
			{
				$page_num = $data_info['total_page'];
			}
		}

	// 목록
		if ($page_num == '' || $page_num == "0") $page_num = 1;
		if ($page_size != '')
		{
			$start = ($page_num - 1) * $page_size;
			$end   = $page_size;
		}
		else
		{
			$start = 0;
			$end   = 0;
		}

		if ($start > 0 || $end > 0) $limit = " limit " . $start . ", " . $end;

		$query_string .= $limit;
		$sql = db_query($query_string);
		$i = 0;
		while ($data = mysql_fetch_array($sql))
		{
			$data_info[$i] = string_output($data);
			$i++;
		}
		$data_info['query_string'] = $query_string;
		$data_info['page_num'] = $page_num;

		db_close();

		return $data_info;
	}

//------------------------------------ 쿼리만들기
	function make_sql($variable, $command, $table, $conditions)
	{
		$cnt = count($variable);
		$sql = '';

		$i = 0;
		foreach($variable as $key => $val)
		{
			$val  = string_input($val);
			$sql .= "
				{$key} = '{$val}'
			";

			if($i < $cnt - 1)
			{
				$sql .= ", ";
			}
			$i++;
		}

		if(strtolower($command) == "insert") $command .= " into";

		$sql = "
			{$command}
				{$table}
			set
				{$sql}
		";
		$sql .= ($conditions != '') ? " WHERE " . $conditions : '';

		return $sql;
	}

//------------------------------------ 쿼리내역
	function query_history($query_string, $table_query, $command)
	{
		global $_SESSION, $sess_str, $ip_address, $create_query;

		$table_name    = "query_history_" . date("Ym");
		$query_history = $create_query['query_history'];
		$query_history = str_replace("[table_name]", $table_name, $query_history);
		db_query($query_history);

		$query_string = str_replace(chr(10), '', $query_string);
		$query_string = str_replace(chr(9), " ", $query_string);
		$query_string = string_input($query_string);
		$query_str = "
			insert into " . $table_name . " set
				  comp_idx     = '" . $_SESSION[$sess_str . '_comp_idx'] . "'
				, part_idx     = '" . $_SESSION[$sess_str . '_part_idx'] . "'
				, query_string = '" . $query_string . "'
				, table_name   = '" . $table_query . "'
				, query_type   = '" . $command . "'
				, reg_ip       = '" . $ip_address . "'
				, reg_id       = '" . $_SESSION[$sess_str . '_mem_idx'] . "'
				, reg_date     = '" . date("Y-m-d H:i:s") . "'
		";
		db_query($query_str);
	}
?>
