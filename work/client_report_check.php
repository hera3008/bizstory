<?
/*
	생성 : 2012.04.09
	수정 : 2012.12.12
	위치 : 고객관리 > 점검보고서 - 등록/수정 - 점검항목
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($report_class == '' && $rr_idx == '')
	{
		echo '점검타입을 먼저 선택하세요.';
	}
	else
	{
		if ($rr_idx == '') // 등록시
		{
			$check_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and concat(code.up_code_idx, ',') like '%," . $report_class . ",%'";
			$check_list = code_report_class_data('list', $check_where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col />
		<col width="120px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>점검항목</h3></th>
			<th class="nosort"><h3>확인</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($check_list["total_num"] == 0) {
?>
		<tr>
			<td colspan="2">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = 0;
		foreach($check_list as $k => $data)
		{
			if (is_array($data))
			{
?>
		<tr>
			<td>
				<div class="left">
<?
				if ($data['menu_depth'] == 2)
				{
					$emt_str = '';
					$num++;
					echo $num, '.';
				}
				else
				{
					$emt_str = str_repeat('&nbsp;', $data['menu_depth'] * 2);
				}
?>
					<?=$emt_str;?><?=$data['code_name'];?>
				</div>
				<input type="hidden" id="codeidx_<?=$i;?>" name="code_idx_<?=$i;?>" value="<?=$data["code_idx"];?>" />
			</td>
			<td>
<?
	$input_default = trim($data['input_default']);
	$input_value   = $data['input_value'];

	if ($data['input_type'] == 'radio')
	{
		$input_value_arr = explode(',', $input_value);
		foreach ($input_value_arr as $chk_k => $chk_v)
		{
			$chk_v = trim($chk_v);

			echo '<label for="inputvalue_' . $i . '_' . $chk_k . '"><input type="radio" id="inputvalue_' . $i . '_' . $chk_k . '" name="input_value_' . $i . '" value="' . $chk_v . '" ' . checked($input_default, $chk_v) . '/>', $chk_v, '</label>';
		}
	}
?>
			</td>
		</tr>
<?
				$i++;
			}
		}
	}
?>
	</tbody>
</table>
<input type="hidden" id="post_code_total" name="code_total" value="<?=$i;?>" />
<?
		}
		else // 수정이나 보기일 경우
		{
			$rr_where = " and rr.rr_idx = '" . $rr_idx . "'";
			$rr_data = receipt_report_data('view', $rr_where);

			$check_where = " and rrd.comp_idx = '" . $code_comp . "' and rrd.rr_idx = '" . $rr_idx . "' and rrd.rrd_type = '2'";
			$check_list = receipt_report_detail_data('list', $check_where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col />
		<col width="120px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>점검항목</h3></th>
			<th class="nosort"><h3>확인</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($check_list["total_num"] == 0) {
?>
		<tr>
			<td colspan="2">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = 0;
		foreach($check_list as $k => $data)
		{
			if (is_array($data))
			{
?>
		<tr>
			<td>
				<div class="left">
<?
				if ($data['menu_depth'] == 2)
				{
					$emt_str = '';
					$num++;
					echo $num, '.';
				}
				else
				{
					$emt_str = str_repeat('&nbsp;', $data['menu_depth'] * 2);
				}
?>
					<?=$emt_str;?><?=$data['report_name'];?>
				</div>
			</td>
			<td><?=$data['report_value'];?></td>
		</tr>
<?
				$i++;
			}
		}
	}
?>
	</tbody>
</table>
<?
		}
	}
?>