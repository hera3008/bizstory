<?
/*
	생성 : 2012.08.30
	생성 : 2013.05.13
	위치 : 설정관리 > 통계 > 접수통계 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($list_type == '')
	{
		$list_type = 'month'; $send_list_type = 'month'; $recv_list_type = 'month';
	}
	if ($syear == '')
	{
		$syear = date('Y'); $send_syear = date('Y'); $recv_syear = date('Y');
	}
	if ($smonth == '')
	{
		$smonth = date('m'); $send_smonth = date('m'); $recv_smonth = date('m');
	}

	$chk_month = $syear . $smonth;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	if ($set_part_yn == 'N') $part_where = " and rid.part_idx = '" . $code_part . "'";
	if ($code_member != '') {
		if (strpos($code_member, ',')) {
			$code_member = str_replace(",", "','", $code_member);
		}
		$client_where = " and ri.charge_mem_idx in ('" . $code_member . "')";
	}
	if ($list_type == 'day') {
		$day_where = " and date_format(rid.end_date, '%Y%m') = '" . $chk_month . "'";
		$day_column = " date_format(rid.end_date, '%Y%m%d') ";
	} else {
		$day_where = " and date_format(rid.end_date, '%Y') = '" . $syear . "'";
		$day_column = " date_format(rid.end_date, '%Y%m') ";
	}
	if ($sclass != '') $class_where = " and (concat(code1.up_code_idx, ',') like '%," . $sclass . ",%' or rid.receipt_class = '" . $sclass . "')";

	$query_string = "
		select mem_name, mem_idx, ymd, count(*) cnt from (
		select
			mi.mem_name, rid.ri_idx, mi.mem_idx, rid.receipt_class, " . $day_column . " ymd
		from
			receipt_info_detail rid
			left join receipt_info ri on ri.ri_idx = rid.ri_idx
			left join code_receipt_class code1 on code1.del_yn = 'N' and code1.comp_idx = rid.comp_idx and code1.part_idx = rid.part_idx and code1.code_idx = rid.receipt_class
			left join member_info mi on ri.charge_mem_idx = mi.mem_idx
		where
			rid.del_yn = 'N' and ri.del_yn = 'N'
			and rid.comp_idx = '" . $code_comp . "'
			" . $part_where . "
			" . $client_where . "
			and rid.receipt_status = 'RS90'
			" . $day_where . "
			" . $class_where . "
			) t
			group by mem_name, mem_idx, ymd
			order by mem_name asc,
				ymd asc
	";
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';
	//print_r($query_string);
	$list = query_list($data_sql);
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$receipt_data[$data['mem_idx']]['name'] = $data['mem_name'];
			$receipt_data[$data['mem_idx']][$data['ymd']] = $data['cnt'];
		}
	}

	if ($list_type == 'day')
	{
		$i = 1;
		
		$client_name = '';
		if (is_array($receipt_data))
		{
			foreach ($receipt_data as $k => $v)
			{
				$client_name = $v['name'];

				for ($sub_i = 1; $sub_i <= 31; $sub_i++)
				{
					$ii = str_pad($sub_i, 2, '0', STR_PAD_LEFT);
					$chk_date = $syear . $smonth . $ii;

					$month_num = $v[$chk_date];
					if ($month_num == '') $month_num = 0;

					if ($sub_i == 1) $class_num = $month_num;
					else $class_num .= ', ' . $month_num;
				}

				if ($i == 1)
				{
					$grapth_data = "{name: '" . $client_name . "', data: [" . $class_num . "]}";
				}
				else
				{
					$grapth_data .= ", {name: '" . $client_name . "', data: [" . $class_num . "]}";
				}

				$i++;
			}
		}
		if ($smonth == '1' || $smonth == '3' || $smonth == '5' || $smonth == '7' || $smonth == '8' || $smonth == '10' || $smonth == '12')
		{
			$categories = "'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'";
		}
		else if ($smonth == '2')
		{
			$categories = "'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'";
		}
		else
		{
			$categories = "'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'";
		}
		$title_text = '접수 일별';
	}
	else
	{
		$i = 1;
		
		if (is_array($receipt_data))
		{
			$client_name = '';
			foreach ($receipt_data as $k => $v)
			{
				$client_name = $v['name'];

				for ($sub_i = 1; $sub_i <= 12; $sub_i++)
				{
					$ii = str_pad($sub_i, 2, '0', STR_PAD_LEFT);
					$chk_date = $syear . $ii;

					if ($v[$chk_date]) {
						$month_num = $v[$chk_date];
					} else {
						$month_num = 0;
					}

					if ($sub_i == 1)
						$class_num = $month_num;
					else
						$class_num .= ', ' . $month_num;
				}

				if ($i == 1)
				{
					$grapth_data = "{name: '" . $client_name . "', data: [" . $class_num . "]}";
				}
				else
				{
					$grapth_data .= ", {name: '" . $client_name . "', data: [" . $class_num . "]}";
				}

				$i++;
			}
		}
		$categories = "'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'";
		$title_text = '접수 월별';
	}
?>
<div id="tableheader">
	<div class="search">
		<p>검&nbsp;&nbsp;&nbsp;색</p>
		<select id="search_sclass" name="sclass" title="전체분류">
			<option value="">전체분류</option>
<?
	$class_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "' and code.menu_depth = '1'";
	$class_list = code_receipt_class_data('list', $class_where, '', '', '');
	foreach ($class_list as $class_k => $class_data)
	{
		if (is_array($class_data))
		{
?>
			<option value="<?=$class_data['code_idx'];?>"<?=selected($sclass, $class_data['code_idx']);?>><?=$class_data['code_name'];?></option>
<?
		}
	}
?>
		</select>

		<select id="search_syear" name="syear" title="년">
			<option value="">년</option>
	<?
		for ($i = 2012; $i <= date('Y')+1; $i++)
		{
	?>
			<option value="<?=$i;?>"<?=selected($syear, $i);?>><?=$i;?>년</option>
	<?
		}
	?>
		</select>
<?
	if ($list_type == 'day')
	{
?>
		<select id="search_smonth" name="smonth" title="월">
			<option value="">월</option>
	<?
		for ($i = 1; $i <= 12; $i++)
		{
			$ii = str_pad($i, 2, '0', STR_PAD_LEFT);
	?>
			<option value="<?=$ii;?>"<?=selected($smonth, $ii);?>><?=$ii;?>월</option>
	<?
		}
	?>
		</select>
<?
	}
?>
		<a href="javascript:void(0);" class="btn_sml" onclick="check_search('<?=$list_type;?>')"><span>검색</span></a>
		<a href="javascript:void(0);" class="btn_sml" onclick="down_excel('<?=$list_type;?>')"><span>엑셀</span></a>
	</div>
</div>

<div style="clear:both;"></div>
<script type="text/javascript">
	$(function () {
		var chart;
		
		$(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'chartview',
					type: 'line',
					marginBottom: 100				},
				title: {
					text: '<?=$title_text;?>'
				},
				xAxis: {
					categories: [<?=$categories;?>]
				},
				yAxis: {
					title: {
						text: '건'
					}
				},
				tooltip: {
					enabled: false,
					formatter: function() {
						return '<strong>'+ this.series.name +'</strong><br/>'+
						this.x +': '+ this.y +'건';
					}
				},
				plotOptions: {
					line: {
						dataLabels: {
							enabled: true
						},
						enableMouseTracking: false
					}
				},
				series: [<?=$grapth_data;?>]
			});
		});

		//$('#client_<?=$code_member?>').props('class', 'select');
	});
</script>
<div id="chartview" style="min-width: 400px; height: 500px;"></div>