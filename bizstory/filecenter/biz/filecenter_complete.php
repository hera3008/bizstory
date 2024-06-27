<?
/*
	생성 : 2013.07.09
	수정 : 2013.07.09
	위치 : 올린파일목록
*/
	$page_chk = "json";
	require_once "../../common/setting.php";
	require_once "../../common/no_direct.php";
	require_once "../../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

// idx_common 값에 table_name, table_idx 값을 넣는다.
// img_fname 파일이름 img_sname 저장이름 img_size 파일크기 img_type 파일종류 img_ext 파일확장자 table_name 테이블 table_idx

	$query = "update temp_file_info set table_name = '" . $table_name . "', table_idx = '" . $table_idx . "' where idx_common = '" . $idx_common . "'";
	db_query($query);

// 올린파일정보
	$query = "select * from temp_file_info where table_name = '" . $table_name . "' and table_idx = '" . $table_idx . "' order by reg_date asc";

	$result = db_query($query);
	
	$list = array();
	if ($result == false) {
		$success_chk = "N";	
	} else {
		$success_chk = "Y";	
		$i = 0;
		while($old_list = query_fetch_array($result, MYSQL_ASSOC)) {
			$list[$i++] = $old_list; 
		}
	}
	
	$json = json_encode(array("success_chk"=>$success_chk, "file_list"=>$list));
	db_close();
	echo $json;
	/*
	foreach ($old_list as $old_k => $old_data)
	{
		if (is_array($old_data))
		{
			$view_html .= '<li>' . $old_data['img_fname'] . '(' . number_format($old_data['img_size']) . ' Byte)';
			$view_html .= '	<a href="javascript:void(0);" class="btn_con" onclick="filecenter_delete(\'' . $old_data['idx'] . '\')"><span>삭제</span></a>';
			$view_html .= '		<input type="hidden" name="filecenter_' . $view_i . '_save_name" value="' . $old_data['img_sname'] . '" />';
			$view_html .= '		<input type="hidden" name="filecenter_' . $view_i . '_file_name" value="' . $old_data['img_fname'] . '" />';
			$view_html .= '		<input type="hidden" name="filecenter_' . $view_i . '_file_size" value="' . $old_data['img_size'] . '" />';
			$view_html .= '		<input type="hidden" name="filecenter_' . $view_i . '_file_type" value="' . $old_data['img_type'] . '" />';
			$view_html .= '		<input type="hidden" name="filecenter_' . $view_i . '_file_ext"  value="' . $old_data['img_ext'] . '" />';
			$view_html .= '	</li>';

			$view_i++;
		}
	}
	echo $view_html;
	 * */
?>