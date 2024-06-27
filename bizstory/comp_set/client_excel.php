<?
/*
	생성 : 2012.12.20
	수정 : 2013.03.37
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정 - 액셀
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	require_once '../add/excel/Classes/PHPExcel.php';

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	if ($shgroup != '' && $shgroup != 'all') // 거래처분류
	{
		$where .= " and (concat(ccg.up_ccg_idx, ',') like '%" . $shgroup . ",%' or ci.ccg_idx = '" . $shgroup . "')";
	}
	if ($stext != '' && $stext != '검색할 단어 입력' && $swhere != '')
	{
		if ($swhere == 'ci.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(ci.tel_num, '-', '') like '%" . $stext . "%' or
				replace(ci.tel_num, '.', '') like '%" . $stext . "%' or
				replace(ci.fax_num, '-', '') like '%" . $stext . "%' or
				replace(ci.fax_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

	$set_row_val = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R');

	$objPHPExcel = new PHPExcel();
	$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

	$objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
	$objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
	$objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
	$objPHPExcel->getActiveSheet()->mergeCells('D1:G1');
	$objPHPExcel->getActiveSheet()->mergeCells('H1:H2');
	$objPHPExcel->getActiveSheet()->mergeCells('I1:I2');
	$objPHPExcel->getActiveSheet()->mergeCells('J1:R1');

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'NO')
				->setCellValue('B1', '거래처분류')
				->setCellValue('C1', '거래처명')
				->setCellValue('D1', '담당자정보')
				->setCellValue('H1', '접속정보')
				->setCellValue('I1', '간단메모')
				->setCellValue('J1', '계약정보');

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D2', '담당자명')
				->setCellValue('E2', '연락처1')
				->setCellValue('F2', '연락처2')
				->setCellValue('G2', '메일주소');

	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('J2', '계약명')
				->setCellValue('K2', '계약번호')
				->setCellValue('L2', '계약일')
				->setCellValue('M2', '착수일')
				->setCellValue('N2', '완료일')
				->setCellValue('O2', '계약금액')
				->setCellValue('P2', '유지보수')
				->setCellValue('Q2', '구분')
				->setCellValue('R2', '담당자');


	$list = client_info_data('list', $where, $orderby, '', '');
	$chk_num = 3;
	$i = 1;
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 거래처분류
			$group_view = client_group_view($data['ccg_idx']);
			$group_arr = $group_view['group_name'];
			$bold_arr  = $group_view['code_bold'];
			$color_arr = $group_view['code_color'];

			$objRichText = new PHPExcel_RichText();
			$group_name = '';
			$objPayable = '';
			foreach ($group_arr as $group_k => $group_v)
			{
				if ($group_v != '')
				{
					if ($group_k == 1)
					{
						$group_name = $group_v;
						$objPayable = $objRichText->createTextRun($group_v);
					}
					else
					{
						$group_name .= chr(13) . $group_v;
						$objPayable = $objRichText->createTextRun(chr(13) . $group_v);
					}

					if ($bold_arr[$group_k] == 'Y')
					{
						$objPayable->getFont()->setBold(true);
					}
					if ($color_arr[$group_k] != '')
					{
						$code_color = str_replace('#', '', $color_arr[$group_k]);
						$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );
					}
				}
			}
			//$objPHPExcel->setActiveSheetIndex(0)->getCell('B' . $chk_num)->setValue($objRichText);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $chk_num, $group_name);






		/*
			$objRichText = new PHPExcel_RichText();
			$objRichText->createText('This invoice is ');

			$objPayable = $objRichText->createTextRun('payable within thirty days after the end of the month');
			$objPayable->getFont()->setBold(true);
			$objPayable->getFont()->setItalic(true);
			$objPayable->getFont()->setColor( new PHPExcel_Style_Color( PHPExcel_Style_Color::COLOR_DARKGREEN ) );

			$objRichText->createText(', unless specified otherwise on the invoice.');

			$objPHPExcel->getActiveSheet()->getCell('A18')->setValue($objRichText);
		*/






		// 담당자정보
			$charge_info     = $data['charge_info'];
			$charge_info_arr = explode('||', $charge_info);
			$charge_num = $chk_num;
			if (is_array($charge_info_arr))
			{
				foreach ($charge_info_arr as $charge_k => $charge_v)
				{
					$info_str = explode('/', $charge_v);

					$charge_name = $info_str[0];
					$tel_num1    = $info_str[1];
					$tel_num2    = $info_str[3];
					$charge_mail = $info_str[2];

					if ($tel_num1 == '--' || $tel_num1 == '-' || $tel_num1 == '') $tel_num1 = '';
					if ($tel_num2 == '--' || $tel_num2 == '-' || $tel_num2 == '') $tel_num2 = '';
					if ($charge_mail == '@' || $charge_mail == '') $charge_mail = '';

					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $charge_num, $charge_name);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $charge_num, $tel_num1);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $charge_num, $tel_num2);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $charge_num, $charge_mail);

					$charge_num++;
				}
			}

		// 계약수
			$contract_where = " and con.comp_idx = '" . $data['comp_idx'] . "' and con.ci_idx = '" . $data['ci_idx'] . "'";
			$contract_list = contract_info_data('list', $contract_where, '', '', '');
			$con_num = $chk_num;
			foreach ($contract_list as $contract_k => $contract_data)
			{
				if (is_array($contract_data))
				{
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J' . $con_num, $contract_data['subject']);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K' . $con_num, $contract_data['contract_number']);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L' . $con_num, date_replace($contract_data['contract_date'], 'y.m.d'));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('M' . $con_num, date_replace($contract_data['begin_date'], 'y.m.d'));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('N' . $con_num, date_replace($contract_data['complete_date'], 'y.m.d'));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('O' . $con_num, number_format($contract_data['con_price']));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('P' . $con_num, number_format($contract_data['month_price']));
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('Q' . $con_num, $set_contract_type[$contract_data['contract_type']]);
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue('R' . $con_num, $contract_data['charge_name']);

					$con_num++;
				}
			}
			if ($charge_num > $con_num)
			{
				$chk_num1 = $charge_num - 1;
			}
			else
			{
				$chk_num1 = $con_num - 1;
			}

			$objPHPExcel->getActiveSheet()->mergeCells('A' . $chk_num . ':A' . $chk_num1);
			$objPHPExcel->getActiveSheet()->mergeCells('B' . $chk_num . ':B' . $chk_num1);
			$objPHPExcel->getActiveSheet()->mergeCells('C' . $chk_num . ':C' . $chk_num1);
			$objPHPExcel->getActiveSheet()->mergeCells('H' . $chk_num . ':H' . $chk_num1);
			$objPHPExcel->getActiveSheet()->mergeCells('I' . $chk_num . ':I' . $chk_num1);

			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $chk_num, $i);
			//$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $chk_num, $group_name);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $chk_num, $data['client_name']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('H' . $chk_num, $data['remark'], PHPExcel_Cell_DataType::TYPE_STRING);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValueExplicit('I' . $chk_num, $data['memo1'], PHPExcel_Cell_DataType::TYPE_STRING);

			$chk_num = $chk_num1 + 1;
			$i++;
		}
	}
	$chk_num = $chk_num - 1;

// 셀설정
	$objPHPExcel->getActiveSheet()->freezePane('A3');
	$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);
	$objPHPExcel->getActiveSheet()->getStyle('A1:R2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(7);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getStyle('A3:A' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B3:B' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B3:B' . $chk_num)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('H3:H' . $chk_num)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('I3:I' . $chk_num)->getAlignment()->setWrapText(true);
	$objPHPExcel->getActiveSheet()->getStyle('O3:O' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('O3:O' . $chk_num)->getNumberFormat()->setFormatCode('#,###');
	$objPHPExcel->getActiveSheet()->getStyle('P3:P' . $chk_num)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	$objPHPExcel->getActiveSheet()->getStyle('P3:P' . $chk_num)->getNumberFormat()->setFormatCode('#,###');

	$objPHPExcel->getActiveSheet()->getStyle('A1:R' . $chk_num)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

	$objPHPExcel->getActiveSheet()->setTitle('거래처');
	$objPHPExcel->setActiveSheetIndex(0);

	$s_subject = '거래처_';
	$file_name = utf_han($s_subject) . date('YmdHis');

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0, max-age=0");
	header("Pragma: public");

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
?>