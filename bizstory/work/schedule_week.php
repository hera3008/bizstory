<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($sdate == "") $sdate = date("Y-m-d");
	$syear  = substr($sdate, 0, 4);
	$smonth = substr($sdate, 5, 2);
	$sday   = substr($sdate, 8, 2);
	$year_month  = $syear . '-' . $smonth;
	$month_first = $syear . '-' . $smonth . '-01';
	$today_date =  $sdate = date("Y-m-d");

// 해당하는 주값 가지고 오기
	$data_date = query_view("
		select
			date_format('" . $sdate . "','%U') as week_num
			, date_format('" . $sdate . "','%w') as week_name
	");
	$week_num   = $data_date['week_num'];
	$week_name  = $data_date['week_name'];
	$week_start = 0 - $week_name;
	$week_end   = 6 - $week_name;
	$week_sort = 0;
	for ($i = $week_start; $i < 0; $i++)
	{
		$data_date = query_view("select date_format(date_add('" . $sdate . "',interval " . $i . " day),'%Y-%m-%d') as week_date");
		$week_date = $data_date['week_date'];
		$sche_date[$week_sort] = $week_date;
		$week_sort++;
	}
	for ($i = 0; $i <= $week_end; $i++)
	{
		$data_date = query_view("select date_format(date_add('" . $sdate . "',interval " . $i . " day),'%Y-%m-%d') as week_date");
		$week_date = $data_date['week_date'];
		$sche_date[$week_sort] = $week_date;
		$week_sort++;
	}

// 일정정보
	$chk_sdate = str_replace('-', '', $sche_date[0]);
	$chk_edate = str_replace('-', '', $sche_date[6]);
	$query_string = "
		select
			sche.*
			, if (sche.repeat_class = 'N', datediff(sche.end_date, sche.start_date), 0) as diff_days
		from
			schedule_info sche
		where
			sche.del_yn = 'N'
			and sche.comp_idx = '" . $code_comp . "'
			and (concat(',', sche.charge_idx, ',') like '%," . $code_mem . ",%' or sche.mem_idx = '" . $code_mem . "')
			and (date_format(sche.start_date, '%Y%m%d') <= '" . $chk_sdate . "' or date_format(sche.start_date, '%Y%m%d') <= '" . $chk_edate . "')
			and (date_format(sche.end_date, '%Y%m%d') >= '" . $chk_sdate . "' or date_format(sche.end_date, '%Y%m%d') >= '" . $chk_edate . "')
		order by
			sche.start_date asc, sche.start_time asc, sche.sche_idx asc
	";
	//echo $query_string, '<br />';
	$data_sql['query_page']   = $query_string;
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';
	$sche_list = query_list($data_sql);

	foreach ($sche_list as $sche_k => $sche_data)
	{
		if (is_array($sche_data))
		{
			$sche_idx     = $sche_data['sche_idx'];
			$repeat_class = $sche_data['repeat_class'];
			$start_date   = $sche_data['start_date'];
			$start_time   = $sche_data['start_time'];
			$end_date     = $sche_data['end_date'];
			$repeat_num   = $sche_data['repeat_num'];
			$repeat_for   = $sche_data['repeat_for'];
			$subject      = $sche_data['subject'];
			$diff_days    = $sche_data['diff_days'];

			$backcolor = $set_color_list3[$sort];
			if ($backcolor == '') $backcolor = '#ffff00';

			if ($repeat_class == 'N') // 일반등록
			{
				$schedule_data[$start_date][$sche_idx]['sche_idx']   = $sche_idx;
				$schedule_data[$start_date][$sche_idx]['start_time'] = $start_time;
				$schedule_data[$start_date][$sche_idx]['subject']    = $subject;
				$schedule_data[$start_date][$sche_idx]['bgcolor']    = $backcolor;

				if ($start_date != $end_date)
				{
					for ($i = 1; $i <= $diff_days; $i++)
					{
						$date_add = query_view("select date_format(date_add('" . $start_date . "',interval " . $i . " day),'%Y-%m-%d') as add_date");
						$add_date = $date_add['add_date'];

						$schedule_data[$add_date][$sche_idx]['sche_idx']   = $sche_idx;
						$schedule_data[$add_date][$sche_idx]['start_time'] = $start_time;
						$schedule_data[$add_date][$sche_idx]['subject']    = $subject;
						$schedule_data[$add_date][$sche_idx]['bgcolor']    = $backcolor;
					}
				}
			}
			else // 반복설정일 경우
			{
				$chk_date = repeat_date_setting($repeat_class, $start_date, $month_last, $repeat_num);
				$chk_date = $start_date . $chk_date;
				$chk_date_arr = explode(',', $chk_date);
				foreach ($chk_date_arr as $chk_k => $chk_v)
				{
					$chk_v_other = str_replace('-', '', $chk_v);
					if ($chk_v != '' && $chk_v_other >= $chk_sdate)
					{
						$schedule_data[$chk_v][$sche_idx]['sche_idx']   = $sche_idx;
						$schedule_data[$chk_v][$sche_idx]['start_time'] = $start_time;
						$schedule_data[$chk_v][$sche_idx]['subject']    = $subject;
						$schedule_data[$chk_v][$sche_idx]['bgcolor']    = $backcolor;

						for ($i = 1; $i <= $repeat_for; $i++)
						{
							$date_add = query_view("select date_format(date_add('" . $chk_v . "',interval " . $i . " day),'%Y-%m-%d') as add_date");
							$add_date = $date_add['add_date'];

							$schedule_data[$add_date][$sche_idx]['sche_idx']   = $sche_idx;
							$schedule_data[$add_date][$sche_idx]['start_time'] = $start_time;
							$schedule_data[$add_date][$sche_idx]['subject']    = $subject;
							$schedule_data[$add_date][$sche_idx]['bgcolor']    = $backcolor;
						}
					}
				}
			}
		}
	}
	echo '<pre>';
	//print_r($schedule_data);
	echo '</pre>';
?>
<div>
	<a href="javascript:void(0);" onclick="sche_view('<?=$prev_month;?>', '1')" class="pre" title="이전달"><span>이전달</span></a>
	<?=$syear;?>년 <?=$this_month;?>월
	<a href="javascript:void(0);" onclick="sche_view('<?=$next_month;?>', '1')" class="next" title="다음달"><span>다음달</span></a>

	(음)<?=$today_l_date;?>
	<a href="javascript:void(0);" onclick="sche_view('<?=$today_date;?>', '3')" class="btn_con"><span>오늘보기</span></a>
</div>

<div>
	<table class="schetable_week">
		<caption>캘린더</caption>
		<colgroup>
			<col width="11%" />
			<col width="12%" />
			<col width="13%" />
			<col width="13%" />
			<col width="13%" />
			<col width="13%" />
			<col width="13%" />
			<col width="12%" />
		</colgroup>
		<thead>
			<tr>
				<th scope="col">날짜</th>
				<th scope="col"><?=$sche_date[0];?></th>
				<th scope="col"><?=$sche_date[1];?></th>
				<th scope="col"><?=$sche_date[2];?></th>
				<th scope="col"><?=$sche_date[3];?></th>
				<th scope="col"><?=$sche_date[4];?></th>
				<th scope="col"><?=$sche_date[5];?></th>
				<th scope="col"><?=$sche_date[6];?></th>
			</tr>
		</thead>
		<tbody>
<?
	$time_i = 1;
	foreach ($set_sche_time as $k => $v)
	{
		$time_chk = $time_i % 2;
?>
			<tr>
<?
		if ($time_chk == 1)
		{
?>
				<td rowspan="2"><?=$k;?><br/><?=$v;?></td>
<?
		}
?>
<?
		for ($sub_i = 0; $sub_i <= 6; $sub_i++)
		{
			$chk_date  = $sche_date[$sub_i];
			$sche_data = $schedule_data[$chk_date];
			$total_str = '';
			if (is_array($sche_data))
			{
				foreach ($sche_data as $sche_k => $sche_v)
				{
					$start_time = $sche_v['start_time'];
					if ($k == $start_time)
					{
						$total_str .= $set_sche_time[$sche_v['start_time']] . '<br />';
						$total_str .= $sche_v['subject'] . '<br />';
					}
				}
			}
?>
			<td><?=$total_str;?>&nbsp;</td>
<?
		}
?>
			</tr>
<?
		$time_i++;
	}
?>
		</tbody>
	</table>
</div>