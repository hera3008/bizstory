<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 액셀
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	require_once '../add/excel/Classes/PHPExcel.php';

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_part_yn = $comp_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ai.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ai.part_idx = '" . $code_part . "'";
	if ($astype != '' && $astype != 'all') $where .= " and ai.account_type = '" . $astype . "'";
	if ($asgubun != '' && $asgubun != 'all') $where .= " and ai.gubun_code = '" . $asgubun . "'";
	if ($asclass != '' && $asclass != 'all') $where .= " and ai.class_code = '" . $asclass . "'";
	if ($asbank != '' && $asbank != 'all') $where .= " and ai.bank_code = '" . $asbank . "'";
	if ($ascard != '' && $ascard != 'all') $where .= " and ai.card_code = '" . $ascard . "'";
	if ($asclient != '' && $asclient != 'all') $where .= " and ai.ci_idx = '" . $asclient . "'";
	if ($assdate != '') $where .= " and date_format(ai.account_date, '%Y-%m-%d') >= '" . $assdate . "'";
	if ($asedate != '') $where .= " and date_format(ai.account_date, '%Y-%m-%d') <= '" . $asedate . "'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ai.account_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'NO')
				->setCellValue('B1', '종류')
				->setCellValue('C1', '날짜')
				->setCellValue('D1', '구분1')
				->setCellValue('E1', '구분2')
				->setCellValue('F1', '계정')
				->setCellValue('G1', '금액')
				->setCellValue('H1', '적요');

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('C1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('E1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('F1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('H1')->getFont()->setBold(true);

	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->freezePane('A2');
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 1);

	$list = account_info_data('list', $where, $orderby, '', '');
	$chk_num = 2;
	$i = 1;
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 거래처명
			if ($data['client_name'] == '')
			{
				$client_name = '';
			}
			else
			{
				$client_name = '[' . $data['client_name'] . '] ';
			}

		// 구분2
			if ($data['gubun_code'] == 'bank')
			{
				$gubun_string = $data['bank_code_name'];
			}
			else if ($data['gubun_code'] == 'card')
			{
				$gubun_string = $data['card_code_name'];
			}
			else
			{
				$gubun_string = '';
			}

			$objPHPExcel->getActiveSheet()->getStyle('A' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('B' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('C' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('D' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('E' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('F' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$objPHPExcel->getActiveSheet()->getStyle('G' . $chk_num)->getNumberFormat()->setFormatCode('#,###');
			$objPHPExcel->getActiveSheet()->getStyle('G' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('A' . $chk_num, $i)
						->setCellValue('B' . $chk_num, $set_account_type[$data['account_type']])
						->setCellValue('C' . $chk_num, $data['account_date'])
						->setCellValue('D' . $chk_num, $data["gubun_code_name"])
						->setCellValue('E' . $chk_num, $gubun_string)
						->setCellValue('F' . $chk_num, $data['class_code_name'] . '(' . $data['class_code_value'] . ')')
						->setCellValue('G' . $chk_num, $data['account_price'])
						->setCellValueExplicit('H' . $chk_num, $client_name . $data['content'], PHPExcel_Cell_DataType::TYPE_STRING);

		// 구분
			if ($data['gubun_code_bold'] == 'Y')
			{
				$objPHPExcel->getActiveSheet()->getStyle('D' . $chk_num)->getFont()->setBold(true);
			}
			if ($data['gubun_code_color'] != '')
			{
				$data['gubun_code_color'] = str_replace('#', '', $data['gubun_code_color']);
				$objPHPExcel->getActiveSheet()->getStyle('D' . $chk_num)->getFont()->getColor()->setARGB('FF' . $data['gubun_code_color']);
			}

		// 계정
			if ($data['class_code_bold'] == 'Y')
			{
				$objPHPExcel->getActiveSheet()->getStyle('F' . $chk_num)->getFont()->setBold(true);
			}
			if ($data['class_code_color'] != '')
			{
				$data['class_code_color'] = str_replace('#', '', $data['class_code_color']);
				$objPHPExcel->getActiveSheet()->getStyle('F' . $chk_num)->getFont()->getColor()->setARGB('FF' . $data['class_code_color']);
			}
			if ($data['account_type'] == 'OUT')
			{
				$total_account_price -= $data['account_price'];
			}
			else
			{
				$total_account_price += $data['account_price'];
			}

			$chk_num++;
			$i++;
		}
	}
	$objPHPExcel->getActiveSheet()->mergeCells('A' . $chk_num . ':F' . $chk_num);
	$objPHPExcel->getActiveSheet()->getCell('A' . $chk_num)->setValue('합계');
	$objPHPExcel->getActiveSheet()->getStyle('A' . $chk_num)->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('A' . $chk_num)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->getCell('G' . $chk_num)->setValue(number_format($total_account_price));
	$objPHPExcel->getActiveSheet()->getStyle('G' . $chk_num)->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('G' . $chk_num)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('G' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	$objPHPExcel->getActiveSheet()->setTitle('운영비내역');
	$objPHPExcel->setActiveSheetIndex(0);

	$s_subject = '운영비내역_';
	$file_name = utf_han($s_subject) . date('YmdHis');

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, max-age=0");
	header("Pragma: public");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>