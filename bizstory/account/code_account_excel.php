<?
/*
	생성 : 2013.02.06
	수정 : 2013.03.22
	위치 : 설정관리 > 코드관리 > 회계설정 > 회계계정 - 액셀
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	require_once '../add/excel/Classes/PHPExcel.php';

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'NO')
				->setCellValue('B1', '계정과목코드')
				->setCellValue('C1', '계정과목')
				->setCellValue('D1', '보기여부');

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->freezePane('A2');
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

	$where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "'";
	$list = code_account_class_data('list', $where, '', '', '');
	$chk_num = 2;
	$i = 1;
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$objPHPExcel->getActiveSheet()->getStyle('A' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('D' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			$emp_val = str_repeat(' ', ($data['menu_depth'] - 1) * 4);

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $chk_num, $i)
						->setCellValue('B' . $chk_num, $data['code_value'])
						->setCellValue('C' . $chk_num, $emp_val . $data['code_name'])
						->setCellValue('D' . $chk_num, $data["view_yn"]);

			$chk_num++;
			$i++;
		}
	}

	$objPHPExcel->getActiveSheet()->setTitle('계정과목');
	$objPHPExcel->setActiveSheetIndex(0);

	$s_subject = '계정과목_';
	$file_name = utf_han($s_subject) . date('YmdHis');

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, max-age=0");
	header("Pragma: public");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>