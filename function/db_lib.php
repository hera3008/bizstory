<?
	$database_connect = false;
//------------------------------------- 데이타 베이스 연결하기
	function db_connect()
	{
		global $database_connect;

        
		if (db_host != '' && db_user != '' && db_pass != '')
		{ 
		    if (!$database_connect) {
          
    			$database_connect  = mysqli_connect(db_host, db_user, db_pass, db_name);
                //var_dump( $database_connect);
                
    			$status_type = mysqli_select_db($database_connect, db_name);
				mysqli_query($database_connect, 'SET NAMES UTF8');

    			if(!$status_type)
    			{
    				$error_number  = mysqli_errno($database_connect);
    				$error_message = mysqli_error($database_connect);
                    
                    echo '접속오류';
                    
    				exit;
    			}
            }

		}
	}

//------------------------------------ 디비호출
	function db_query($query_string)
	{
		global $database_connect, $query_path, $this_page;
		
		//db_connect();		
		mysqli_query($database_connect, "set sql_mode='ALLOW_INVALID_DATES'");

		$query_result = mysqli_query($database_connect, $query_string);
		if ($query_result === false)
		{
			echo ("<pre> errror </pre>");
			$error_number  = mysqli_errno($database_connect);
			$error_message = mysqli_error($database_connect);

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
            echo 'output : ' . $query_path . $save_name . '.txt';
			exit;
		}
		return $query_result;
	}

//----------------------------------------insert idx
function query_insert_id()
{
		global $database_connect;
		
        return mysqli_insert_id($database_connect);		    
}


//------------------------------------ 디비닫기
	function db_close()
	{
		global $database_connect;

		mysqli_close($database_connect);
	}

//------------------------------------ 데이타 총개수
	function query_page($query_page)
	{
		$sql = db_query($query_page);
		//echo 'query_page -> ', $query_page, '<br />';
		$data = mysqli_fetch_array($sql);
		
		$data_info["total_num"] = $data[0];
		$data_info['query_string'] = $query_page;
		
		//db_close();

		return $data_info;
	}

//------------------------------------ 데이타보기
	function query_view($query_string)
	{

		$sql = db_query($query_string);  
		$data = mysqli_fetch_array($sql);

		if(mysqli_num_rows($sql) > 0)
		{
			$data_info = string_output($data);
		}
        $data_info['query_string'] = $query_string;
		$data_info['total_num'] = mysqli_num_rows($sql);

		return $data_info;
	}
    
    function query_pagelist($data_sql) {
        $query_column = $data_sql['query_column'];
        $query_page   = $data_sql['query_page'];
        $query_string = $data_sql['query_string'];
        $page_size    = $data_sql['page_size'];
        $page_num     = $data_sql['page_num'];

    // 총개수, 총페이지수
        if ($query_page == '')
        {
            //echo $query_string . '<br />';
            $sql = db_query('select count(*) cnt ' . $query_string);
            $data = mysqli_fetch_array($sql);
            $data_info["total_num"] = $data[0];
        }
        else
        {
            $sql = db_query($query_page);
            $data = mysqli_fetch_array($sql);
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
        //echo $query_string;
        $sql = db_query('select ' . $query_column . $query_string);
        $i = 0;
        while ($data = mysqli_fetch_array($sql))
        {
            $data_info[$i] = string_output($data);
            $i++;
        }
        $data_info['query_string'] = $query_string;
        $data_info['page_num'] = $page_num;

        //db_close();

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
            $data_info["total_num"] = mysqli_num_rows($sql);
        }
        else
        {
            $sql = db_query($query_page);
            $data = mysqli_fetch_array($sql);
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
        while ($data = mysqli_fetch_array($sql))
        {
            $data_info[$i] = string_output($data);
            $i++;
        }
        $data_info['query_string'] = $query_string;
        $data_info['page_num'] = $page_num;

        //db_close();

        return $data_info;
    }

//------------------------------------ 데이타목록
    function query_array($data_sql)
    {
        $query_column = $data_sql['query_column'];
        $query_page   = $data_sql['query_page'];
        $query_string = $data_sql['query_string'];
        $page_size    = $data_sql['page_size'];
        $page_num     = $data_sql['page_num'];

        if ($query_column == null) {
            $sql = db_query($query_string);
            $total_num = mysqli_num_rows($sql);
        } else {
            $sql = db_query('select count(*) cnt ' . $query_string);
            $data = mysqli_fetch_array($sql);
            $total_num = $data[0];
        }
        
        
        if ($page_size != '' && $page_size != "0")
        {
            $total_page = ceil($total_num / $page_size);
            
            if ($page_num > $total_page)
            {
                $page_num = $total_page;
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

        
        if ($query_column == null) {
            $sql = db_query($query_string);
        } else {
            $sql = db_query('select ' . $query_column . $query_string);
        }
        
        $query_string .= $limit;
        
        $i = 0;
        while ($data = mysqli_fetch_array($sql))
        {
            $data_info[$i] = string_output($data);
            $i++;
        }
        //$data_info['query_string'] = $query_string;
        //$data_info['page_num'] = $page_num;

        //db_close();
        //echo $query_string;

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
		global $sess_str, $ip_address, $create_query;

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

	function query_fetch_array($query_data, $mode='')
	{
		if($mode != '') $str = mysqli_fetch_array($query_data, $mode);
		else $str = mysqli_fetch_array($query_data);

		return $str;
	}

	function query_real_escape_string($str)
	{
		global $database_connect;
		return $str = mysqli_real_escape_string($database_connect, $str);
	}
?>
