<?
/*
	생성 : 2012.08.30
	위치 : 설정관리 > 통계 > 접수통계 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$set_part_yn = $comp_set_data['part_yn'];

	if ($list_type == '')
	{
		$list_type      = 'month';
		$send_list_type = 'month';
		$recv_list_type = 'month';
	}
	if ($syear == '')
	{
		$syear      = date('Y');
		$send_syear = date('Y');
		$recv_syear = date('Y');
	}
	if ($smonth == '')
	{
		$smonth      = date('m');
		$send_smonth = date('m');
		$recv_smonth = date('m');
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	if ($set_part_yn == 'N') $part_where = " and rid.part_idx = '" . $code_part . "'";
	if ($list_type == 'day') $day_where = " and date_format(rid.end_date, '%Y%m') = '" . $syear . $smonth . "'";
	if ($sclass != '') $class_where = " and (concat(code1.up_code_idx, ',') like '%" . $sclass . ",%' or rid.receipt_class = '" . $sclass . "')";
	$query_page = "
		select
			count(rid.rid_idx)
		from
			receipt_info_detail rid
		where
			rid.del_yn = 'N'
	";
	$query_string = "
		select
			  rid.ri_idx, rid.ci_idx, rid.receipt_class, rid.end_date
			, ci.client_name
			, code1.code_name as receipt_class_name
		from
			receipt_info_detail rid
			left join receipt_info ri on ri.ri_idx = rid.ri_idx
			left join client_info ci on ci.comp_idx = rid.comp_idx and ci.ci_idx = rid.ci_idx
			left join code_receipt_class code1 on code1.del_yn = 'N' and code1.comp_idx = rid.comp_idx and code1.part_idx = rid.part_idx and code1.code_idx = rid.receipt_class
		where
			rid.del_yn = 'N' and ri.del_yn = 'N'
			and rid.comp_idx = '" . $code_comp . "'
			" . $part_where . "
			and rid.receipt_status = 'RS90'
			and date_format(rid.end_date, '%Y') = '" . $syear . "'
			" . $day_where . "
			" . $class_where . "
		order by
			rid.end_date asc
	";
	//echo 'query_string -> ', $query_string, '<br />';

	$data_sql['query_page']   = $query_page;
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';

	$list = query_list($data_sql);
	foreach ($list as $k => $data)
	{
		if (is_array($data))
		{
			$ci_idx             = $data['ci_idx'];
			$client_name        = $data['client_name'];
			$receipt_class      = $data['receipt_class'];
			$receipt_class_name = $data['receipt_class_name'];
			$end_date           = date_replace($data['end_date'], 'Ymd');
			$end_month          = date_replace($data['end_date'], 'Ym');

		// 1단계메뉴별로 값
			$chk_where = " and code.code_idx = '" . $receipt_class . "'";
			$chk_data = code_receipt_class_data('view', $chk_where);

			$menu_depth  = $chk_data['menu_depth'];
			$up_code_idx = $chk_data['up_code_idx'];
			$up_code_arr = explode(',', $up_code_idx);

			if ($menu_depth == 1)
			{
				$first_class  = $receipt_class;
				$second_class = 0;
			}
			else if ($menu_depth == 2)
			{
				$first_class  = $up_code_arr[1];
				$second_class = $receipt_class;
			}
			else
			{
				$first_class  = $up_code_arr[1];
				$second_class = $up_code_arr[2];
			}

			$receipt_data['class_name'][$receipt_class] = $receipt_class_name;
			$receipt_data['class_num'][$receipt_class]++;
			$receipt_data['class_first'][$first_class]++;
			$receipt_data['class_second'][$second_class]++;

			$receipt_data['class_month'][$receipt_class][$end_month]++;
			$receipt_data['class_month_first'][$first_class][$end_month]++;
			$receipt_data['class_month_second'][$second_class][$end_month]++;

			$receipt_data['class_day'][$receipt_class][$end_date]++;
			$receipt_data['class_day_first'][$first_class][$end_date]++;
			$receipt_data['class_day_second'][$second_class][$end_date]++;

		// 거래처별
			$receipt_data['client_name'][$ci_idx] = $client_name;
			$receipt_data['client_class'][$ci_idx][$receipt_class]++;
			$receipt_data['client_first'][$ci_idx][$first_class]++;
			$receipt_data['client_second'][$ci_idx][$second_class]++;

			$receipt_data['client_month_name'][$ci_idx][$end_month] = $client_name;
			$receipt_data['client_month_class'][$ci_idx][$receipt_class][$end_month]++;
			$receipt_data['client_month_first'][$ci_idx][$first_class][$end_month]++;
			$receipt_data['client_month_second'][$ci_idx][$second_class][$end_month]++;
		}
	}

	if ($list_type == 'day')
	{
		$i = 1;
		if ($sclass == '')
		{
			$list_data = $receipt_data['class_day_first'];
		}
		else
		{
			$list_data = $receipt_data['class_day_second'];
		}
		if (is_array($list_data))
		{
			foreach ($list_data as $k => $v)
			{
				$class_name = $receipt_data['class_name'][$k];

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
					$grapth_data = "
						{
							name: '" . $class_name . "',
							data: [" . $class_num . "]
						}
					";
				}
				else
				{
					$grapth_data .= "
						, {
							name: '" . $class_name . "',
							data: [" . $class_num . "]
						}
					";
				}

				$i++;
			}
		}
		$categories = "'1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'";
		$title_text = '접수 일별';
	}
	else
	{
		$i = 1;
		if ($sclass == '')
		{
			$list_data = $receipt_data['class_day_first'];
		}
		else
		{
			$list_data = $receipt_data['class_day_second'];
		}
		if (is_array($list_data))
		{
			foreach ($list_data as $k => $v)
			{
				$class_name = $receipt_data['class_name'][$k];

				for ($sub_i = 1; $sub_i <= 12; $sub_i++)
				{
					$ii = str_pad($sub_i, 2, '0', STR_PAD_LEFT);
					$chk_date = $syear . $ii;

					$month_num = $v[$chk_date];
					if ($month_num == '') $month_num = 0;

					if ($sub_i == 1) $class_num = $month_num;
					else $class_num .= ', ' . $month_num;
				}

				if ($i == 1)
				{
					$grapth_data = "
						{
							name: '" . $class_name . "',
							data: [" . $class_num . "]
						}
					";
				}
				else
				{
					$grapth_data .= "
						, {
							name: '" . $class_name . "',
							data: [" . $class_num . "]
						}
					";
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
