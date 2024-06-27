<?
/*
	수정 : 2012.11.27
	위치 : 회계업무 > 합계잔액시산표 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_part_yn = $company_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ai.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ai.part_idx = '" . $code_part . "'";
	if ($asyear != '') $where .= " and date_format(ai.account_date, '%Y') = '" . $asyear . "'";

	$list = account_info_data('list', $where, '', '', '');
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$account_type     = $data['account_type'];
			$class_code       = $data['class_code'];
			$class_code_value = $data['class_code_value'];
			$class_code_name  = $data['class_code_name'];
			$account_price    = $data['account_price'];

			$class_where = " and code.code_idx = '" . $class_code . "'";
			$class_data = code_account_class_data('view', $class_where);
			$up_code_idx = $class_data['up_code_idx'];
			$menu_depth  = $class_data['menu_depth'] - 1;
			$up_code_arr = explode(',', $up_code_idx);
			$up_code = $up_code_arr[$menu_depth];

			$class_name[$class_code] = $class_code_name;
			$data_value['class'][$class_code] += $account_price;

			$data_value[$account_type][$up_code] += $account_price;
			$data_value[$account_type][$class_code] += $account_price;
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;asyear=' . $send_asyear;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="asyear"   value="' . $send_asyear . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$class_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.view_yn = 'Y' and code.menu_depth > 1";
	$class_list = code_account_class_data('list', $class_where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col />
		<col />
		<col />
		<col />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort" colspan="2"><h3>차변</h3></th>
			<th class="nosort" rowspan="2"><h3>계정과목</h3></th>
			<th class="nosort" colspan="2"><h3>대변</h3></th>
		</tr>
		<tr>
			<th class="nosort"><h3>잔액</h3></th>
			<th class="nosort"><h3>합계</h3></th>
			<th class="nosort"><h3>합계</h3></th>
			<th class="nosort"><h3>잔액</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	foreach ($class_list as $class_k => $class_data)
	{
		if (is_array($class_data))
		{
			$code_idx    = $class_data['code_idx'];
			$up_code_idx = $class_data['up_code_idx'];
			$menu_depth  = $class_data['menu_depth'];

			$in_price  = $data_value['IN'][$code_idx];
			$out_price = $data_value['OUT'][$code_idx];

			$total_price = $in_price + $out_price;

			if ($menu_depth == '2')
			{
				$code_name = '<strong style="font-size:13px">' . $class_data['code_name'] . '</strong>';
			}
			else
			{
				$code_name = $class_data['code_name'];
			}

			if ($total_price > 0)
			{
?>
		<tr>
			<td></td>
			<td><span class="right"><?=number_format($out_price);?></span></td>
			<td><?=$code_name;?></td>
			<td><span class="right"><?=number_format($in_price);?></span></td>
			<td></td>
		</tr>
<?
			}
		}
	}
?>
	</tbody>
	<tfoot>
		<tr>
			<td><strong></strong></td>
			<td><strong></strong></td>
			<td><strong>합계</strong></td>
			<td><strong></strong></td>
			<td><strong></strong></td>
		</tr>
	</tfoot>
</table>