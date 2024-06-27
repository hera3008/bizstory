<?
    /** Error reporting */
    //error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    date_default_timezone_set('Europe/London');

    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    require_once '../../common/excel/Classes/PHPExcel.php';

	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($list_type == '')
	{
		$list_type = 'month'; $send_list_type = 'month'; $recv_list_type = 'month';
	}
	if ($syear == '')
	{
		$syear = date('Y'); $send_syear = date('Y'); $recv_syear = date('Y');
	}
	if ($smonth == '')
	{
		$smonth = date('m'); $send_smonth = date('m'); $recv_smonth = date('m');
	}

	$chk_month = $syear . $smonth;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	if ($set_part_yn == 'N') $part_where = " and rid.part_idx = '" . $code_part . "'";
	if ($code_staff != '') {
		if (strpos($code_staff, ',')) {
			$code_staff = str_replace(",", "','", $code_staff);
		}
		$client_where = " and csg.csg_idx in ('" . $code_staff . "')";
	}
	if ($list_type == 'day') {
		$day_where = " and date_format(rid.end_date, '%Y%m') = '" . $chk_month . "'";
		$day_column = " date_format(rid.end_date, '%Y%m%d') ";
	} else {
		$day_where = " and date_format(rid.end_date, '%Y') = '" . $syear . "'";
		$day_column = " date_format(rid.end_date, '%Y%m') ";
	}
	if ($sclass != '') $class_where = " and (concat(code1.up_code_idx, ',') like '%," . $sclass . ",%' or rid.receipt_class = '" . $sclass . "')";

	$query_string = "select csg_idx, group_name from company_staff_group where csg_idx in ('" . $code_staff . "')";
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';
	$client_list = query_list($data_sql);

	$query_string = "
	select group_name, csg_idx, ymd, count(*) cnt from (
		select
			csg.group_name, rid.ri_idx, csg.csg_idx, rid.receipt_class, " . $day_column . " ymd
		from
			receipt_info_detail rid
			left join receipt_info ri on ri.ri_idx = rid.ri_idx
			left join code_receipt_class code1 on code1.del_yn = 'N' and code1.comp_idx = rid.comp_idx and code1.part_idx = rid.part_idx and code1.code_idx = rid.receipt_class
			left join member_info mi on ri.charge_mem_idx = mi.mem_idx
			left join company_staff_group csg
				on csg.del_yn = 'N'
				and csg.comp_idx = mi.comp_idx
				and csg.csg_idx = mi.csg_idx
		where
			rid.del_yn = 'N' and ri.del_yn = 'N'
			and rid.comp_idx = '" . $code_comp . "'
			" . $part_where . "
			" . $client_where . "
			and rid.receipt_status = 'RS90'
			" . $day_where . "
			" . $class_where . "
		) t
			group by group_name, csg_idx, ymd
			order by group_name asc,
				ymd asc
	";

	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("유비스토리")
    ->setLastModifiedBy("유비스토리")
    ->setTitle("부서별 통계 다운로드")
    ->setSubject("부서별 통계 다운로드")
    ->setDescription("")
    ->setKeywords("")
    ->setCategory("");
	
	function toAlpha($num) {
		$col_number = "";

		if ($num > 26) {
			$col_number = chr(65) . chr(64 + ($num - 26));
		} else {
			$col_number = chr(64 + $num);
		}

		return $col_number;
	}


	function setPHPExcelData($objPHPExcel, $last_number, $title, $client_infos) {
		$last_column = "";
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', '부서명');

		for ($idx = 2; $idx <= $last_number + 1; $idx++) {
			$col_number = toAlpha($idx);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col_number . '1', ($idx-1) . $title);
		}

		$jdx = 2;
		foreach ($client_infos as $k => $data)
		{
			
			if (is_array($data))
			{
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue("A$jdx", $data[0]);

				for ($idx = 2; $idx <= $last_number + 1; $idx++) {
					$col_number = toAlpha($idx);

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($col_number . $jdx, $data[($idx-1)]);
				}
				$jdx++;
			}
		}
		$count = --$jdx;
		$last_column = toAlpha($last_number+1);

		$objPHPExcel->getActiveSheet()->setTitle('부서별 통계');
		// 가로 넓이 조정
	
		$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth(30);

		// 전체 세로 높이 조정
		$objPHPExcel->getActiveSheet()
				->getDefaultRowDimension()
				->setRowHeight(15);

		// 전체 가운데 정렬
		$objPHPExcel->getActiveSheet()
				->getStyle(sprintf("A1:%s%s", $last_column, $count))
				->getAlignment()
				->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		// 전체 테두리 지정
		$objPHPExcel->getActiveSheet()
				->getStyle(sprintf("A1:%s%s", $last_column, $count))
				->getBorders()
				->getAllBorders()
				->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		return $count;
	}
	$last_column = "";

	if ($list_type == 'day') {
		$check_date = $syear . '-' . $smonth . '-01';

		$last_day = date('t', strtotime($check_date));

		$client_infos = array();
		//0으로 초기화
		foreach ($client_list as $k => $client_data)
		{
			if (is_array($client_data))
			{
				$array_day[0] = $client_data['group_name'];
				for ($idx = 1; $idx <= $last_day; $idx++) {
					$array_day[$idx] = 0;
				}
				array_push($client_infos, $array_day);
			}
		}

		$list = query_list($data_sql);
		$client_count = count($client_infos);

		//해당 날짜에 값 삽입
		for ($idx = 0; $idx < $client_count; $idx++) {
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					if ($client_infos[$idx][0] == $data['group_name']) {
						$client_infos[$idx][(int)(substr($data['ymd'], -2))] = $data['cnt'];
					}
				}
			}
		}

		$count = setPHPExcelData($objPHPExcel, $last_day, '일', $client_infos);
		$last_column = toAlpha($last_day + 1);

		$filename = $syear . "년 " . $smonth ."월 일별 부서별 통계";
	} else {
		$client_infos = array();
		$last_month = 12;
		//0으로 초기화
		foreach ($client_list as $k => $client_data)
		{
			if (is_array($client_data))
			{
				$array_month[0] = $client_data['group_name'];
				for ($idx = 1; $idx <= $last_month; $idx++) {
					$array_month[$idx] = 0;
				}
				array_push($client_infos, $array_month);
			}
		}
	
		$list = query_list($data_sql);
		$client_count = count($client_infos);

		for ($idx = 0; $idx < $client_count; $idx++) {
			foreach ($list as $k => $data)
			{
				if (is_array($data))
				{
					if ($client_infos[$idx][0] == $data['group_name']) {
						$client_infos[$idx][(int)(substr($data['ymd'], 4, 2))] = $data['cnt'];
					}
				}
			}
		}

		$count = setPHPExcelData($objPHPExcel, $last_month, '월', $client_infos);
		$last_column = toAlpha($last_month + 1);
		
		$filename = $syear . "년도 월별 부서별 통계";
	}

    // 타이틀 부분
	$objPHPExcel->getActiveSheet()->getStyle(sprintf("A1:%s1", $last_column))->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle(sprintf("A1:%s1", $last_column))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB("CECBCA");
    $objPHPExcel->setActiveSheetIndex(0);

	//$filename = iconv("UTF-8", "EUC-KR", "부서별 통계");

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment;filename=" . $filename . ".xlsx");
    header("Cache-Control: max-age=0");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save("php://output");    
?>