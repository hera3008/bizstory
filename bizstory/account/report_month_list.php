<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비월별 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
	$code_part   = search_company_part($code_part);
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
	if ($asyear != '') $where .= " and date_format(ai.account_date, '%Y') = '" . $asyear . "'";

	$orderby = " code1.sort asc, ai.account_date asc";
	$list = account_info_data('list', $where, $orderby, '', '');
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$account_date     = $data['account_date'];
			$account_month    = substr($account_date, 0, 7);
			$class_code       = $data['class_code'];
			$class_code_value = $data['class_code_value'];
			$class_code_name  = $data['class_code_name'];
			$account_price    = $data['account_price'];
			$account_type     = $data['account_type'];

			if ($account_type == 'OUT')
			{
				$account_price = $account_price * -1;
			}
			$data_total += $account_price;

			$data_month[$account_month] += $account_price;
			$data_class[$class_code]    += $account_price;

			$data_class_month[$class_code][$account_month] += $account_price;

			$class_name[$class_code]['idx']   = $class_code;
			$class_name[$class_code]['value'] = $class_code_value;
			$class_name[$class_code]['name']  = $class_code_name;
		}
	}
?>
<table class="tinytable">
	<colgroup>
		<col />
<?
	for ($i = 1; $i <= 12; $i++)
	{
?>
		<col width="90px" />
<?
	}
?>
		<col width="100px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>계정과목</h3></th>
<?
	for ($i = 1; $i <= 12; $i++)
	{
?>
			<th class="nosort"><h3><?=$i;?>월</h3></th>
<?
	}
?>
			<th class="nosort"><h3>합게</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="14">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		foreach ($data_class_month as $k => $data)
		{
			$code_idx   = $class_name[$k]['idx'];
			$code_name  = $class_name[$k]['name'];
			$code_value = $class_name[$k]['value'];
?>
		<tr>
			<td><?=$code_name;?>(<?=$code_value;?>)</td>
<?
			for ($i = 1; $i <= 12; $i++)
			{
				$ii = str_pad($i, 2, '0', STR_PAD_LEFT);
				$chk_month = $asyear . '-' . $ii;
				$value_data = $data[$chk_month];
?>
			<td>
				<span class="eng right"><a href="javascript:void(0)" onclick="popup_open('<?=$code_idx;?>', '<?=$chk_month;?>')"><?=number_format($value_data);?></a></span></span>
			</td>
<?
			}
?>
			<td>
				<strong><span class="eng right"><a href="javascript:void(0)" onclick="popup_open('<?=$code_idx;?>', '')"><?=number_format($data_class[$k]);?></a></span></strong>
			</td>
		</tr>
<?
		}
	}
?>
	</tbody>
	<tfoot>
		<tr>
			<td><strong>합계</strong></td>
<?
	for ($i = 1; $i <= 12; $i++)
	{
		$ii = str_pad($i, 2, '0', STR_PAD_LEFT);
		$chk_month = $asyear . '-' . $ii;
?>
			<td>
				<strong><span class="eng right"><a href="javascript:void(0)" onclick="popup_open('', '<?=$chk_month;?>')"><?=number_format($data_month[$chk_month]);?></a></span></strong>
			</td>
<?
	}
?>
			<td>
				<strong><span class="eng right"><a href="javascript:void(0)" onclick="popup_open('', '')"><?=number_format($data_total);?></a></span></strong>
			</td>
		</tr>
	</tfoot>
</table>

<script type="text/javascript">
//<![CDATA[
	function popup_open(str1, str2)
	{
		$('#list_class_code').val(str1);
		$('#list_acc_month').val(str2);
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/account/report_month_list_detail.php',
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}
//]]>
</script>