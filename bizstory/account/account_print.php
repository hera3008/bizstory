<?
/*
	수정 : 2012.12.03
	위치 : 회계업무 > 운영비관리 - 인쇄
*/
	include "../common/setting.php";
	include "../common/member_chk.php";

	$navi_where = " and mi.mode_folder = '" . $fmode . "' and mi.mode_file = '" . $smode . "'";
	$navi_data = menu_info_data("view", $navi_where);
	$navi_view = menu_navigation_view($navi_data["mi_idx"]);

	$print_title  = $navi_data['menu_name'] . ' 인쇄페이지';
	$print_header = '';
	$portrait     = 'true';

	$form_chk = 'N';
	if ($auth_menu['print'] == 'Y') // 인쇄권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	include $local_path . "/include/header_print.php";

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

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col width="60px" />
		<col width="80px" />
		<col width="80px" />
		<col width="150px" />
		<col width="80px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort">No</th>
			<th class="nosort"><h3>종류</h3></th>
			<th class="nosort"><h3>날짜</h3></th>
			<th class="nosort"><h3>구분</h3></th>
			<th class="nosort"><h3>계정</h3></th>
			<th class="nosort"><h3>금액</h3></th>
			<th class="nosort"><h3>적요</h3></th>
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
				if ($data['gubun_code_bold'] == 'Y') $gubun_code_name .= 'font-weight:900;';
				if ($data['gubun_code_color'] != '') $gubun_code_name .= 'color:' . $data['gubun_code_color'] . ';';
				$gubun_code_name .= '">' . $data["gubun_code_name"] . '</span>';

			// 계정
				$class_code_name = '<span style="';
				if ($data['class_code_bold'] == 'Y') $class_code_name .= 'font-weight:900;';
				if ($data['class_code_color'] != '') $class_code_name .= 'color:' . $data['class_code_color'] . ';';
				$class_code_name .= '">' . $data["class_code_name"] . '(' . $data['class_code_value'] . ')</span>';

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
			<td><span class="eng"><?=$i;?></span></td>
			<td><?=$set_account_type[$data['account_type']];?></td>
			<td><span class="eng"><?=$data['account_date'];?></span></td>
			<td><?=$gubun_code_name;?></td>
			<td><?=$class_code_name;?></td>
			<td><span class="eng right"><?=number_format($data['account_price']);?></span></td>
			<td><div class="left"><?=$data['content'];?></div></td>
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
			<td colspan="4">합계</td>
			<td colspan="2"><span class="eng right"><?=number_format($total_account_price);?></span></td>
			<td>&nbsp;</td>
		</tr>
	</tfoot>
</table>
