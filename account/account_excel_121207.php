<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$s_subject = '운영비내역_';
	$file_name = utf_han($s_subject) . date('YmdHis');

	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=" . $file_name . ".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_part_yn = $company_set_data['part_yn'];

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$list = account_info_data('list', $where, $orderby, '', '');
?>
<style>
	table * {mso-number-format:'\@';}
</style>
<table border="1">
	<colgroup>
		<col width="30px" />
		<col width="60px" />
		<col width="80px" />
		<col width="80px" />
		<col width="150px" />
		<col width="80px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th>NO</th>
			<th>종류</th>
			<th>날짜</th>
			<th>구분</th>
			<th>계정</th>
			<th>금액</th>
			<th>적요</th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="7">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$ai_idx = $data['ai_idx'];

			// 구분
				$gubun_code_name = '<span style="';
				if ($data['class_code_bold'] == 'Y') $gubun_code_name .= 'font-weight:900;';
				if ($data['class_code_color'] != '') $gubun_code_name .= 'color:' . $data['class_code_color'] . ';';
				$gubun_code_name .= '">' . $data["gubun_code_name"] . '</span>';

				if ($data['account_type'] == 'OUT')
				{
					$total_account_price -= $data['account_price'];
				}
				else
				{
					$total_account_price += $data['account_price'];
				}
?>
		<tr>
			<td align="center"><?=$i;?></td>
			<td align="center"><?=$set_account_type[$data['account_type']];?></td>
			<td align="center"><?=$data['account_date'];?></td>
			<td align="center"><?=$gubun_code_name;?></td>
			<td align="center"><?=$data['class_code_name'];?>(<?=$data['class_code_value'];?>)</td>
			<td align="right"><?=number_format($data['account_price']);?></td>
			<td align="left"><?=$data['content'];?></td>
		</tr>
<?
				$i++;
			}
		}
	}
?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4" align="center">합계</td>
			<td colspan="2"><span class="eng right"><?=number_format($total_account_price);?></span></td>
			<td>&nbsp;</td>
		</tr>
	</tfoot>
</table>