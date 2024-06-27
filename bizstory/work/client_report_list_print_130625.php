<?
/*
	생성 : 2012.04.09
	수정 : 2013.04.01
	위치 : 고객관리 > 점검보고서 - 인쇄
*/
	include "../common/setting.php";
	include "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$rr_idx    = $idx;

	$form_chk = 'N';
	if ($auth_menu['print'] == 'Y' && $rr_idx != '') // 인쇄권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				alert("인쇄권한이 없습니다.");
				window.close();
			//]]>
			</script>
		';
	}
	include $local_path . "/include/header_print.php";

	if ($form_chk == 'Y')
	{
		$where = " and rr.rr_idx = '" . $rr_idx . "'";
		$data = receipt_report_data('view', $where);

		$receipt_date = date_replace($data['receipt_date'], 'Y.m');

	// 로고파일
		$comp_img_where = " and cf.comp_idx = '" . $data['comp_idx'] . "' and cf.sort = '1'";
		$comp_img_data  = company_file_data('view', $comp_img_where);

		if ($comp_img_data['total_num'] == 0) $comp_logo_img = '';
		else $comp_logo_img = '<img src="' . $comp_company_dir . '/' . $comp_img_data['img_sname'] . '" width="180px" alt="' . $comp_img_data['comp_name'] . '" />';

	// 점검타입
		$check_where = " and rrd.comp_idx = '" . $data['comp_idx'] . "' and rrd.rr_idx = '" . $rr_idx . "' and rrd.rrd_type = '2'";
		$check_list = receipt_report_detail_data('list', $check_where, '', '', '');
		$chk_total = $check_list['total_num'];

	// 접수
		$receipt_where = " and rrd.comp_idx = '" . $data['comp_idx'] . "' and rrd.rr_idx = '" . $rr_idx . "' and rrd.rrd_type = '1'";
		$receipt_list = receipt_report_detail_data('list', $receipt_where, '', '', '');
		$receipt_total = $receipt_list['total_num'] + 1;
?>
<style>
.div_table {text-align:center;}
.tableview {
	clear:both;
	float:none;
	font-size:13px;
	margin-top:10px;
	margin-left:10px;
	border:0;

	width: 990px;
	table-layout:fixed;
	color: #333333;
}
.tableview, .tableview tr, .tableview td {
	margin:0;
	padding:0;
	outline:0;
	vertical-align:baseline;
	background:transparent;
	font-family:Dotum, 돋움;

	border-collapse:collapse;
	border-spacing:0;
	empty-cells:show;
}
.tableview td {
	font-weight:400;
	line-height:18px;
	text-align:center;
	vertical-align:middle;
	padding:3px;
}
.tableview td.left {
	clear:both;
	float:none;
	text-align:left;
	padding-left:5px;
}
.b_all {
	BORDER-RIGHT:  #828282 1px solid; BORDER-TOP:    #828282 1px solid;
	BORDER-LEFT:   #828282 1px solid; BORDER-BOTTOM: #828282 1px solid;
}
.b_tl {
	BORDER-RIGHT:  #828282 1px;       BORDER-TOP:    #828282 1px solid;
	BORDER-LEFT:   #828282 1px solid; BORDER-BOTTOM: #828282 1px;
}
.b_trl {
	BORDER-RIGHT:  #828282 1px solid; BORDER-TOP:    #828282 1px solid;
	BORDER-LEFT:   #828282 1px solid; BORDER-BOTTOM: #828282 1px;
}
.b_tbl {
	BORDER-RIGHT:  #828282 1px;       BORDER-TOP:    #828282 1px solid;
	BORDER-LEFT:   #828282 1px solid; BORDER-BOTTOM: #828282 1px solid;
}
</style>

<div class="div_table">
	<table class="tableview">
		<colgroup>
	<?
		for ($i = 1; $i <= 33; $i++)
		{
	?>
			<col width="30px"/>
	<?
		}
	?>
		</colgroup>
		<tr>
	<?
		for ($i = 1; $i <= 33; $i++)
		{
	?>
			<td></td>
	<?
		}
	?>
		</tr>
		<tr>
			<td class="b_tl" colspan="6" rowspan="2" style="padding:0;"><?=$comp_logo_img;?></td>
			<td class="b_tl" rowspan="2" colspan="18"><strong style="font-size:20px; font-weight:900;">정기점검 보고서 (<?=$receipt_date;?>)</strong></td>
			<td class="b_tl" colspan="4">작성자</td>
			<td class="b_trl" colspan="5"><?=$data['mem_name'];?></td>
		</tr>
		<tr>
			<td class="b_tl" colspan="4">작성일자</td>
			<td class="b_trl" colspan="5"><?=date_replace($data['reg_date'], 'Y-m-d');?></td>
		</tr>
		<tr>
			<td class="b_tl" height="25px" colspan="4" rowspan="3">고객정보</td>
			<td class="b_tl" height="25px" colspan="3">기관명</td>
			<td class="b_tl left" height="25px" colspan="15"><?=$data['client_name'];?></td>
			<td class="b_tl" height="25px" colspan="3">인수자</td>
			<td class="b_trl left" height="25px" colspan="8"><?=$data['client_charge'];?></td>
		</tr>
		<tr>
			<td class="b_tl" height="25px" colspan="3">연락처</td>
			<td class="b_tl left" height="25px" colspan="6"><?=$data['client_telnum'];?></td>
			<td class="b_tl" height="25px" colspan="3">핸드폰</td>
			<td class="b_tl left" height="25px" colspan="6"><?=$data['client_hpnum'];?></td>
			<td class="b_tl" height="25px" colspan="3">이메일</td>
			<td class="b_trl left" height="25px" colspan="8"><?=$data['client_email'];?></td>
		</tr>
		<tr>
			<td class="b_tl" height="25px" colspan="3">주&nbsp;&nbsp;&nbsp;&nbsp;소</td>
			<td class="b_trl left" height="25px" colspan="26"><?=$data['client_address'];?></td>
		</tr>
	<?
		foreach ($check_list as $check_k => $check_data)
		{
			if (is_array($check_data))
			{
				if ($check_data['menu_depth'] == 2)
				{
					$num++;
					$emt_str = $num . '. ';
				}
				else
				{
					$emt_str = str_repeat('&nbsp;', $check_data['menu_depth'] * 2);
				}

				if ($check_k == 0)
				{
	?>
		<tr>
			<td class="b_tl" colspan="4" rowspan="<?=$chk_total;?>">점검내용</td>
			<td class="b_tl left" height="25px" colspan="25"><?=$emt_str;?><?=$check_data['report_name'];?></td>
			<td class="b_trl" height="25px" colspan="4"><?=$check_data['report_value'];?></td>
		</tr>
	<?
				}
				else
				{
	?>
		<tr>
			<td class="b_tl left" height="25px" colspan="25"><?=$emt_str;?><?=$check_data['report_name'];?></td>
			<td class="b_trl" height="25px" colspan="4"><?=$check_data['report_value'];?></td>
		</tr>
	<?
				}
			}
		}
	?>
		<tr>
			<td class="b_tl" colspan="4" rowspan="<?=$receipt_total;?>">유지보수</td>
			<td class="b_tl" height="25px" colspan="3">접수자</td>
			<td class="b_tl" height="25px" colspan="3">접수일</td>
			<td class="b_tl" height="25px" colspan="3">업무자</td>
			<td class="b_tl" height="25px" colspan="3">완료일</td>
			<td class="b_trl" height="25px" colspan="17">완료문구</td>
		</tr>

	<?
		foreach ($receipt_list as $receipt_lk => $receipt_ldata)
		{
			if (is_array($receipt_ldata))
			{
	?>
		<tr>
			<td class="b_tl" height="25px" colspan="3"><?=$receipt_ldata['writer'];?></td>
			<td class="b_tl" height="25px" colspan="3"><?=date_replace($receipt_ldata['receipt_date'], 'Y.m.d');?></td>
			<td class="b_tl" height="25px" colspan="3"><?=$receipt_ldata['mem_name'];?></td>
			<td class="b_tl" height="25px" colspan="3"><?=date_replace($receipt_ldata['end_date'], 'Y.m.d');?></td>
			<td class="b_trl left" colspan="17">
				<strong><?=$receipt_ldata['report_name'];?></strong><br />
				<?=nl2br($receipt_ldata['remark']);?>
			</td>
		</tr>
	<?
			}
		}
	?>
		<tr>
			<td class="b_tl" height="25px" colspan="4" rowspan="2">점검자</td>
			<td class="b_tl" height="25px" colspan="4">소속</td>
			<td class="b_tl left" height="25px" colspan="9"><?=$data['report_part'];?></td>
			<td class="b_tl" height="25px" colspan="4">담당자</td>
			<td class="b_trl left" height="25px" colspan="13"><?=$data['report_charge'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(인)</td>
		</tr>
		<tr>
			<td class="b_tl" height="25px" colspan="4">연락처</td>
			<td class="b_tl left" height="25px" colspan="9"><?=$data['report_telnum'];?></td>
			<td class="b_tl" height="25px" colspan="4">메일주소</td>
			<td class="b_trl left" height="25px" colspan="13"><?=$data['report_email'];?></td>
		</tr>
		<tr>
			<td class="b_tbl" height="80px" colspan="4">고객의견</td>
			<td class="b_all" height="80px" colspan="29">&nbsp;</td>
		</tr>
	</table>
</div>

</body>
</html>
<?
	}
?>