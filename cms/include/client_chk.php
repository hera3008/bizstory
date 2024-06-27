<?
	If ($_SESSION[$sess_str . "_client_idx"] == "" || $_SESSION[$sess_str . "_cu_idx"] == "")
	{
		$move_url = urldecode($move_url);
		header("Location: " . $local_dir . '/cms/login.php?move_url=' . $move_url);
		exit;
	}
	else
	{
		$client_where = " and ci.ci_idx = '" . $_SESSION[$sess_str . "_client_idx"] . "'";
		$client_data = client_info_data('view', $client_where);

		$comp_set_where = " and cs.comp_idx = '" . $client_data['comp_idx'] . "'";
		$comp_set_data = company_set_data('view', $comp_set_where);

		$user_where = " and cu.cu_idx = '" . $_SESSION[$sess_str . "_cu_idx"] . "'";
		$user_data = client_user_data('view', $user_where);

		$client_comp      = $client_data['comp_idx'];
		$client_part      = $client_data['part_idx'];
		$client_idx       = $client_data['ci_idx'];
		$client_name      = $client_data['client_name'];
		$client_code      = $client_data['client_code'];
		$client_user_idx  = $_SESSION[$sess_str . '_cu_idx'];
		$client_user_name = $user_data['mem_name'];
		$client_tel_num   = $user_data['tel_num'];

		$receipt_path = $comp_path . '/' . $client_comp . '/receipt'; // 접수
		$receipt_dir  = $comp_dir  . '/' . $client_comp . '/receipt';

		$upload_file_num_max   = $comp_set_data['receipt_file_num']; // 최대파일수
		$upload_file_size_max1 = $comp_set_data['receipt_file_max'] * 1024 * 1024; // 최대파일크기
		$upload_file_size_max2 = $comp_set_data['receipt_file_max'];

		$cms_menu = array(
			  "1" => array("접수", "receipt.php", "")
		);
	}
?>