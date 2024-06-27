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

	$data_date = query_view("
		select
			date_format('" . $month_first . "','%w') as month_first_w,
			last_day('" . $month_first . "') as month_last,
			date_format(last_day('" . $month_first . "'),'%w') as month_last_w,
			datediff(last_day('" . $month_first . "'),'" . $month_first . "') + 1 as month_day,

			date_format(date_sub('" . $month_first . "',interval 1 month),'%Y') as prev_yyyy,
			date_format(date_sub('" . $month_first . "',interval 1 month),'%m') as prev_mm,

			date_format(date_add('" . $month_first . "',interval 1 month),'%Y') as next_yyyy,
			date_format(date_add('" . $month_first . "',interval 1 month),'%m') as next_mm
	");

	$month_day     = $data_date['month_day'];
	$month_last    = $data_date['month_last'];
	$month_first_w = $data_date['month_first_w'];
	$month_last_w  = $data_date['month_last_w'];

	$prev_yyyy  = $data_date['prev_yyyy'];
	$prev_mm    = $data_date['prev_mm'];
	$prev_month = $prev_yyyy . '-' . $prev_mm . '-' . $sday;

	$next_yyyy  = $data_date['next_yyyy'];
	$next_mm    = $data_date['next_mm'];
	$next_month = $next_yyyy . '-' . $next_mm . '-' . $sday;

	$this_year   = $syear;
	$this_month  = $smonth * 1;
	$this_day    = $sday * 1;
	$total_month = $month_first_w + $month_day;
	if($total_month % 7 > 0) $total_month = $total_month + 7 - ($total_month % 7);

// 음력
	$l_where = " and cal.cd_sy = '" . $this_year . "' and cal.cd_sm = '" . $this_month . "' and cal.cd_sd = '" . $this_day . "'";
	$l_data = calenda_info_data('view', $l_where);
	$today_l_date = $l_data['cd_lm'] . '.' . $l_data['cd_ld'];

// 일정정보
	$chk_sdate = $syear . $smonth . '01';
	$chk_edate = $syear . $smonth . $month_day;
	$query_string = "
		select
			sche.*
			, if (sche.repeat_class = 'N', datediff(sche.end_date, sche.start_date), 0) as diff_days
			, date_format(sche.start_date,'%U') as week_start
			, date_format(sche.end_date,'%U') as week_end
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
	$data_sql['query_page']   = $query_string;
	$data_sql['query_string'] = $query_string;
	$data_sql['page_size']    = '';
	$data_sql['page_num']     = '';
	$sche_list = query_list($data_sql);

	$sort = 1;
	foreach ($sche_list as $sche_k => $sche_data)
	{
		if (is_array($sche_data))
		{
			$sche_idx     = $sche_data['sche_idx'];
			$repeat_class = $sche_data['repeat_class'];
			$start_date   = $sche_data['start_date'];
			$start_time   = $sche_data['start_time'];
			$end_date     = $sche_data['end_date'];
			$diff_days    = $sche_data['diff_days'];
			$week_start   = $sche_data['week_start'];
			$week_end     = $sche_data['week_end'];
			$repeat_num   = $sche_data['repeat_num'];
			$repeat_for   = $sche_data['repeat_for'];
			$subject      = $sche_data['subject'];

			$backcolor = $set_color_list3[$sort];
			if ($backcolor == '') $backcolor = '#ffff00';

			if ($repeat_class == 'N') // 일반등록
			{
				$schedule_week[$week_start]++;
				$schedule_data[$week_start][$start_date][$sort]['week_num']   = $week_start;
				$schedule_data[$week_start][$start_date][$sort]['sche_idx']   = $sche_idx;
				$schedule_data[$week_start][$start_date][$sort]['start_time'] = $start_time;
				$schedule_data[$week_start][$start_date][$sort]['subject']    = $subject;
				$schedule_data[$week_start][$start_date][$sort]['bgcolor']    = $backcolor;
				$schedule_data[$week_start][$start_date][$sort]['week_sort']  = $schedule_week[$week_start];

				if ($start_date != $end_date)
				{
					for ($i = 1; $i <= $diff_days; $i++)
					{
						$date_add = query_view("select date_format(date_add('" . $start_date . "',interval " . $i . " day),'%Y-%m-%d') as add_date");
						$add_date = $date_add['add_date'];

						$date_week = query_view("select date_format('" . $add_date . "','%U') as week_num, date_format('" . $add_date . "','%w') as week_name");
						$week_num  = $date_week['week_num'];
						$week_name = $date_week['week_name'];

						if ($week_name == 0) $schedule_week[$week_num]++;
						$schedule_data[$week_num][$add_date][$sort]['week_num']   = $week_num;
						$schedule_data[$week_num][$add_date][$sort]['sche_idx']   = $sche_idx;
						$schedule_data[$week_num][$add_date][$sort]['start_time'] = $start_time;
						$schedule_data[$week_num][$add_date][$sort]['subject']    = $subject;
						$schedule_data[$week_num][$add_date][$sort]['bgcolor']    = $backcolor;
						$schedule_data[$week_num][$add_date][$sort]['week_sort']  = $schedule_week[$week_num];
					}
				}
				$sort++;
			}
			else // 반복설정일 경우
			{
				$chk_date = repeat_date_setting($repeat_class, $start_date, $month_last, $repeat_num);
				$chk_date = $start_date . $chk_date;
				$chk_date_arr = explode(',', $chk_date);
				$chk_num = 0;
				foreach ($chk_date_arr as $chk_k => $chk_v)
				{
					$chk_v_other = str_replace('-', '', $chk_v);
					if ($chk_v != '' && $chk_v_other >= $chk_sdate)
					{
						$date_week = query_view("select date_format('" . $chk_v . "','%U') as week_num");
						$week_num  = $date_week['week_num'];

						$schedule_week[$week_num]++;
						$schedule_data[$week_num][$chk_v][$sort]['week_num']   = $week_num;
						$schedule_data[$week_num][$chk_v][$sort]['sche_idx']   = $sche_idx;
						$schedule_data[$week_num][$chk_v][$sort]['start_time'] = $start_time;
						$schedule_data[$week_num][$chk_v][$sort]['subject']    = $subject;
						$schedule_data[$week_num][$chk_v][$sort]['bgcolor']    = $backcolor;
						$schedule_data[$week_num][$chk_v][$sort]['week_sort']  = $schedule_week[$week_num];

						for ($i = 1; $i <= $repeat_for; $i++)
						{
							$date_add = query_view("select date_format(date_add('" . $chk_v . "',interval " . $i . " day),'%Y-%m-%d') as add_date");
							$add_date = $date_add['add_date'];

							$date_week = query_view("select date_format('" . $add_date . "','%U') as week_num, date_format('" . $add_date . "','%w') as week_name");
							$week_num  = $date_week['week_num'];
							$week_name = $date_week['week_name'];

							if ($week_name == 0) $schedule_week[$week_num]++;
							$schedule_data[$week_num][$add_date][$sort]['week_num']   = $week_num;
							$schedule_data[$week_num][$add_date][$sort]['sche_idx']   = $sche_idx;
							$schedule_data[$week_num][$add_date][$sort]['start_time'] = $start_time;
							$schedule_data[$week_num][$add_date][$sort]['subject']    = $subject;
							$schedule_data[$week_num][$add_date][$sort]['bgcolor']    = $backcolor;
							$schedule_data[$week_num][$add_date][$sort]['week_sort']  = $schedule_week[$week_num];
						}
						$chk_num++;
					}
				}
				if ($chk_num > 0) $sort++;
			}
		}
	}
	$max_sort = $sort;
?>
<div>
	<a href="javascript:void(0);" onclick="sche_view('<?=$prev_month;?>', '1')" class="pre" title="이전달"><span>이전달</span></a>
	<?=$syear;?>년 <?=$this_month;?>월
	<a href="javascript:void(0);" onclick="sche_view('<?=$next_month;?>', '1')" class="next" title="다음달"><span>다음달</span></a>

	(음)<?=$today_l_date;?>
	<a href="javascript:void(0);" onclick="sche_view('<?=$today_date;?>', '3')" class="btn_con"><span>오늘보기</span></a>
</div>

<div>
	<table class="schetable">
		<caption>캘린더</caption>
		<colgroup>
			<col width="14%" />
			<col width="15%" />
			<col width="15%" />
			<col width="14%" />
			<col width="14%" />
			<col width="14%" />
			<col width="14%" />
		</colgroup>
		<thead>
			<tr>
				<th scope="col">일</th>
				<th scope="col">월</th>
				<th scope="col">화</th>
				<th scope="col">수</th>
				<th scope="col">목</th>
				<th scope="col">금</th>
				<th scope="col">토</th>
			</tr>
		</thead>
		<tbody>
			<tr>
<?
	for($i = 1; $i <= $total_month; $i++)
	{
		$day        = $i - $month_first_w;
		$sche_day   = str_pad($day, 2, 0, STR_PAD_LEFT);
		$sche_month = $smonth . '-' . $sche_day;
		$sche_date  = $syear . '-' . $smonth . '-' . $sche_day;

		if ($day > 0 && $day <= $month_day)
		{
			$sch_day = $day;
		}
		else
		{
			$sch_day = '';
		}

		if($i % 7 == 1) $week_css = ' sun'; // 일요일
		else
		{
			if($i % 7 == 0) $week_css = ' sat'; // 토요일
			else $week_css = '';
		}

		$s_where = " and cal.cd_sy = '" . $this_year . "' and cal.cd_sm = '" . $this_month . "' and cal.cd_sd = '" . $day . "'";
		$s_data = calenda_info_data('view', $s_where);
		$s_holiday  = $s_data['holiday'];
		$s_sol_plan = $s_data['cd_sol_plan'];
		$s_lun_plan = $s_data['cd_lun_plan'];
		$cd_kterms  = $s_data['cd_kterms'];
		if ($s_holiday == '1') $week_css = ' sun'; // 휴일

		if ($s_sol_plan != '') $holi_name = $s_sol_plan;
		else if ($s_lun_plan != '') $holi_name = $s_lun_plan;
		else $holi_name = '';
		if ($cd_kterms != '' && $cd_kterms != 'NULL')
		{
			if ($holi_name != '') $s_kterms = ', ' . $cd_kterms;
			else $s_kterms = $cd_kterms;
		}
		else $s_kterms = '';

		$l_where = " and cal.cd_sy = '" . $this_year . "' and cal.cd_sm = '" . $this_month . "' and cal.cd_sd = '" . $day . "'";
		$l_data = calenda_info_data('view', $l_where);
		$l_date = str_pad($l_data['cd_lm'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($l_data['cd_ld'], 2, 0, STR_PAD_LEFT);
		foreach ($set_holiday['L'] as $day_k => $day_v)
		{
			if ($l_date == $day_k)
			{
				$week_css = ' sun'; // 휴일
				break;
			}
		}

	// 일정값
		$total_schedule = '';
		if ($sch_day != '')
		{
			$date_week = query_view("select date_format('" . $sche_date . "','%U') as week_num");
			$week_num = $date_week['week_num'];
			$sche_week_num = $schedule_week[$week_num];

			$schedule_date = $schedule_data[$week_num][$sche_date];
			$data_sort = 1;
			if (is_array($schedule_date))
			{
				$total_schedule .= '
						<ul>';
				for ($sort_i = 1; $sort_i < $max_sort; $sort_i++)
				{
					$sr_data = $schedule_date[$sort_i];
					if (is_array($sr_data))
					{
						for ($emp_i = $data_sort; $emp_i < $sr_data['week_sort']; $emp_i++)
						{
							if ($sr_data['week_sort'] != $data_sort)
							{
								$total_schedule .= '
									<li style="width:100%; height:20px; text-align:left; margin-bottom:2px; font-size:11px;"></li>';
								$data_sort++;
							}
						}
						$total_schedule .= '
							<li style="width:100%; height:20px; background-color:' . $sr_data['bgcolor'] . '; text-align:left; margin-bottom:2px;">
								<a href="javascript:void(0)" onclick="view_open(\'' . $sr_data["sche_idx"] . '\');" title="' . $sr_data['subject'] . '">
									<span style="color:#000000">
										' . $sr_data['start_time'] . ' ' . $sr_data['subject'] . '
									</span>
								</a>
							</li>';
						$data_sort++;
					}
				}
				$total_schedule .= '
						</ul>';
			}
		}

		if ($total_schedule != '') $week_css = ' job';
		if ($today_date == $sche_date) $week_css = ' today'; // 오늘일
?>
				<td>
					<div class="day<?=$week_css;?>"><?=$sch_day;?><span class="holi_css"><?=$holi_name;?><?=$s_kterms;?></span></div>
					<?=$total_schedule;?>
					<?//=$total_repeat;?>
				</td>
<?
		if ($i % 7 == 0)
		{
			echo '
			</tr>';
		}
		if(($i % 7 == 0) && ($i < $total_month))
		{
			echo '
			<tr>';
		}
	}

	if ($i % 7 == 0)
	{
		echo'
			</tr>';
	}
?>
		</tbody>
	</table>
</div>