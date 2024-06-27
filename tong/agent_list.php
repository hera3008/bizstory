<?
/*
	생성 : 2013.05.13
	수정 : 2013.05.13
	위치 : 설정관리 > 통계 > 에이전트 - 목록
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
	if ($set_part_yn == 'N') $part_where = " and ci.part_idx = '" . $code_part . "'";
	if ($list_type == 'day') $day_where = " and date_format(ad.reg_date, '%Y%m') = '" . $chk_month . "'";
	else $day_where = " and date_format(ad.reg_date, '%Y') = '" . $syear . "'";

	$query_string = "
		select
			ad.*
			, ci.part_idx
			, part.part_name
		from
			agent_data ad
			left join client_info ci on ci.del_yn = 'N' and ci.ci_idx = ad.ci_idx
			left join company_part part on part.del_yn = 'N' and part.part_idx = ci.part_idx
		where
			ad.del_yn = 'N' and ci.del_yn = 'N' and part.del_yn = 'N'
			and ad.comp_idx = '" . $code_comp . "'
			" . $part_where . "
			" . $day_where . "
		order by
			ad.reg_date asc
	";
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';

	$list = query_list($data_sql);
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$part_idx  = $data['part_idx'];
			$part_name = $data['part_name'];
			$reg_date  = date_replace($data['reg_date'], 'Ymd');
			$reg_month = date_replace($data['reg_date'], 'Ym');

			$data_list['part_name'][$part_idx] = $part_name;
			$data_list['month_part'][$part_idx][$reg_month]++;
			$data_list['day_part'][$part_idx][$reg_date]++;
		}
	}

	if ($list_type == 'day')
	{
		$i = 1;
		$list_data = $data_list['day_part'];
		if (is_array($list_data))
		{
			foreach ($list_data as $k => $v)
			{
				$part_name = $data_list['part_name'][$k];

				for ($sub_i = 1; $sub_i <= 31; $sub_i++)
				{
					$ii = str_pad($sub_i, 2, '0', STR_PAD_LEFT);
					$chk_date = $syear . $smonth . $ii;

					$month_num = $v[$chk_date];
					if ($month_num == '') $month_num = 0;

					if ($sub_i == 1) $data_num = $month_num;
					else $data_num .= ', ' . $month_num;
				}

				if ($i == 1) $grapth_data = "{name: '" . $part_name . "', data: [" . $data_num . "]}";
				else $grapth_data .= ", {name: '" . $part_name . "', data: [" . $data_num . "]}";

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
		$title_text = '에이전트 일별';
	}
	else
	{
		$i = 1;
		$list_data = $data_list['month_part'];
		if (is_array($list_data))
		{
			foreach ($list_data as $k => $v)
			{
				$part_name = $data_list['part_name'][$k];

				for ($sub_i = 1; $sub_i <= 12; $sub_i++)
				{
					$ii = str_pad($sub_i, 2, '0', STR_PAD_LEFT);
					$chk_date = $syear . $ii;

					$month_num = $v[$chk_date];
					if ($month_num == '') $month_num = 0;

					if ($sub_i == 1) $data_num = $month_num;
					else $data_num .= ', ' . $month_num;
				}

				if ($i == 1) $grapth_data = "{name: '" . $part_name . "', data: [" . $data_num . "]}";
				else $grapth_data .= ", {name: '" . $part_name . "', data: [" . $data_num . "]}";

				$i++;
			}
		}
		$categories = "'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'";
		$title_text = '에이전트 월별';
	}
?>
<div id="tableheader">
	<div class="search">
		<p>검&nbsp;&nbsp;&nbsp;색</p>

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
	</div>
</div>

<div style="clear:both;"></div>
<script type="text/javascript">
	$(function () {
		var chart;
		$(document).ready(function() {
			chart = new Highcharts.Chart({
				chart: {
					renderTo: 'chartview',
					type: 'line',
					marginBottom: 100
				},
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
	});
</script>
<div id="chartview" style="min-width: 400px; height: 500px;"></div>
