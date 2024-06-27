<?
/*
	생성 : 2013.07.09
	수정 : 2013.07.09
	위치 : 올린파일삭제
*/
	require_once "../../common/setting.php";
	require_once "../../common/no_direct.php";
	require_once "../../common/member_chk.php";

// 올린파일정보
	$chk_query = "select * from temp_file_info where idx = '" . $idx . "'";
	$chk_data = query_view($chk_query);

	$file_path = $chk_data['img_sname'];
	@unlink($file_path);

	$delete_query = "delete from temp_file_info where idx = '" . $idx . "'";
	db_query($delete_query);

// 올린파일정보
	$query = "select * from temp_file_info where table_name = '" . $table_name . "' and table_idx = '" . $table_idx . "' order by reg_date asc";

	$result = db_query($query);
	
	$list = array();
	if ($result == false) {
		$success_chk = "N";	
	} else {
		$success_chk = "Y";	
		$i = 0;
		while($old_list = query_fetch_array($result, MYSQLI_ASSOC)) {
			$list[$i++] = $old_list; 
		}
	}
	
	$json = json_encode(array("success_chk"=>$success_chk, "file_list"=>$list));
	db_close();
	echo $json;
?>