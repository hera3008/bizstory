<?
/*
	생성 : 2012.04.09
	수정 : 2012.12.12
	위치 : 고객관리 > 점검보고서 - 등록/수정 - 접수목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($ci_idx == '' && $rr_idx == '')
	{
		echo '거래처를 먼저 선택하세요.';
	}
	else
	{
		if ($rr_idx == '') // 등록시
		{
		// 거래처
			$client_where = " and ci.ci_idx = '" . $ci_idx . "'";
			$client_data = client_info_data('view', $client_where);

		// 접수
			$receipt_where = " and rid.comp_idx = '" . $code_comp . "' and rid.ci_idx = '" . $ci_idx . "' and rid.receipt_status = 'RS90'";
			if ($sdate != "") $receipt_where .= " and date_format(rid.end_date, '%Y-%m-%d') >= '" . $sdate . "'";
			if ($edate != "") $receipt_where .= " and date_format(rid.end_date, '%Y-%m-%d') <= '" . $edate . "'";
			if ($receipt_class != "") $receipt_where .= " and (concat(code1.up_code_idx, ',') like '%" . $receipt_class . ",%' or rid.receipt_class = '" . $receipt_class . "')";
			$receipt_order = 'rid.end_date asc';
			$receipt_list = receipt_info_detail_data('list', $receipt_where, $receipt_order, '', '');
?>
<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="100px" />
		<col width="90px" />
		<col width="90px" />
		<col width="90px" />
		<col width="90px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="rididx" onclick="check_all('rididx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3>접수자</h3></th>
			<th class="nosort"><h3>접수일</h3></th>
			<th class="nosort"><h3>업무자</h3></th>
			<th class="nosort"><h3>완료일</h3></th>
			<th class="nosort"><h3>제목/완료문구</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($receipt_list["total_num"] == 0) {
?>
		<tr>
			<td colspan="8">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($receipt_list as $k => $data)
		{
			if (is_array($data))
			{
				$receipt_class = receipt_class_view($data['receipt_class']);
				$code_arr = $receipt_class['code_name'];
				foreach ($code_arr as $code_k => $code_v)
				{
					if ($code_k > 0)
					{
						if ($code_k == 1)
						{
							$total_class = $code_v;
						}
						else
						{
							$total_class .= ' > ' . $code_v;
						}
					}
				}
?>
		<tr>
			<td>
				<input type="checkbox" id="rididx_<?=$i;?>" name="chk_rid_idx_<?=$i;?>" value="<?=$data["rid_idx"];?>" title="선택" checked="checked" />
			</td>
			<td><span class="num"><?=$i;?></span></td>
			<td><?=$total_class;?>
			</td>
			<td><?=$data['writer'];?></td>
			<td><span class="num"><?=date_replace($data['receipt_date'], 'Y.m.d');?></span></td>
			<td><?=$data['mem_name'];?></td>
			<td><span class="num"><?=date_replace($data['end_date'], 'Y.m.d');?></span></td>
			<td>
				<div class="left">
					<strong><?=$data['subject'];?></strong><br />
					<?=nl2br($data['remark_end']);?>
				</div>
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
<input type="hidden" id="post_receipt_total" name="receipt_total" value="<?=$receipt_list["total_num"];?>" />
<?
		}
		else // 수정이나 보기일 경우
		{
			$rr_where = " and rr.rr_idx = '" . $rr_idx . "'";
			$rr_data = receipt_report_data('view', $rr_where);

			$check_where = " and rrd.comp_idx = '" . $code_comp . "' and rrd.rr_idx = '" . $rr_idx . "' and rrd.rrd_type = '1'";
			$check_list = receipt_report_detail_data('list', $check_where, '', '', '');
?>
<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col width="90px" />
		<col width="90px" />
		<col width="90px" />
		<col width="90px" />
		<col />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>접수자</h3></th>
			<th class="nosort"><h3>접수일</h3></th>
			<th class="nosort"><h3>업무자</h3></th>
			<th class="nosort"><h3>완료일</h3></th>
			<th class="nosort"><h3>제목/완료문구</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($check_list["total_num"] == 0) {
?>
		<tr>
			<td colspan="7">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($check_list as $k => $data)
		{
			if (is_array($data))
			{
?>
		<tr>
			<td><span class="num"><?=$i;?></span></td>
			<td><?=$data['writer'];?></td>
			<td><span class="num"><?=date_replace($data['receipt_date'], 'Y.m.d');?></span></td>
			<td><?=$data['mem_name'];?></td>
			<td><span class="num"><?=date_replace($data['end_date'], 'Y.m.d');?></span></td>
			<td>
				<div class="left">
					<strong><?=$data['report_name'];?></strong><br />
					<?=nl2br($data['remark']);?>
				</div>
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
<?
		}
	}
?>