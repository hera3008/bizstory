<?php
/*
	생성 : 2013.04.16
	수정 : 2013.04.16
	위치 : 파일센터 > 파일관리- 다운로드
*/
	include "../common/setting.php";

	//$qry     = $_POST['qfiles'];
	$qry_arr = explode(':', $qfiles);
	$qry_len = count($qry_arr);

	$code_comp = $qry_arr[0];
	$code_mem  = $qry_arr[1];

	$comp_set_where = " and cs.comp_idx = '" . $code_comp . "'";
	$comp_set_data  = company_setting_data('view', $comp_set_where);

	$filecneter_url = $comp_set_data['file_out_url']; // 파일센터 주소

	$rdata = "<files>\n";

	for ($i = 2; $i < $qry_len; $i++)
	{
		$fi_idx = $qry_arr[$i];

		$where = " and fi.fi_idx = '" . $fi_idx . "'";
		$data = filecenter_info_data('view', $where);

		$rdata .= "<file>\n";
		$rdata .= "<name>" . $data['file_name'] . "</name>\n";
		$rdata .= "<size>" . $data['file_size'] . "</size>\n";
		$rdata .= "<path>http://" . $filecneter_url . "/filecenter/upload" . $data['file_rpath'] . "/" . $data['file_sname'] . "</path>\n";
		$rdata .= "</file>\n";

	// 다운로드 이력
		$insert_query = "
			insert into filecenter_history set
				comp_idx     = '" . $code_comp . "',
				part_idx     = '" . $data['part_idx'] . "',
				fi_idx       = '" . $fi_idx . "',
				dir_file     = 'file',
				new_subject  = '" . $data['file_name'] . "',
				history_memo = '" . $data['file_name'] . " 파일을 다운로드했습니다.',
				reg_type     = 'download(fi:" . $fi_idx . ")',

				reg_id     = '" . $code_mem . "',
				reg_date   = '" . time() . "',
				del_yn     = '0'
		";
		db_query($insert_query);
		query_history($insert_query, 'filecenter_history', 'insert', $code_comp, $data['part_idx'], $code_mem);
	}

	$rdata .= "</files>";
	echo $rdata;
?>