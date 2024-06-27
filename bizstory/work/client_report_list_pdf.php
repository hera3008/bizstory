<?
/*
	생성 : 2013.04.05
	수정 : 2013.04.05
	위치 : 고객관리 > 점검보고서 - PDF
*/
	include "../common/setting.php";
	include "../common/member_chk.php";

	require_once(dirname(__FILE__) . '/../add/tcpdf/config/lang/kor.php');
	require_once(dirname(__FILE__) . '/../add/tcpdf/tcpdf.php');

		ob_start();

		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(15, 10, 15);
		$pdf->SetAutoPageBreak(TRUE, 10);
		$pdf->SetFont('arialunicid0', '', 9);

		$pdf_css = '1234';
		$pdf_html = 'asdf';


		$pdf_string = $pdf_css . $pdf_html;

		$pdf->AddPage();
		$pdf->writeHTML($pdf_string, true, false, true, false, '');
		$pdf->lastPage();

		//$pdf->Output('invoice_' . $mri_idx . '.pdf', 'D'); // 저장
		$pdf->Output('report.pdf', 'I'); // 보기
?>