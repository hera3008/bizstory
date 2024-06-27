<?
/*
	생성 : 2012.10.25
	위치 : 메인화면 > 달력
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = $_SESSION[$sess_str . '_part_idx'];
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn = $comp_set_data['part_yn'];

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
			date_format(date_add('" . $month_first . "',interval 1 month),'%m') as next_mm,

			date_format(date_sub('" . $sdate . "',interval 1 day),'%Y') as p_year,
			date_format(date_sub('" . $sdate . "',interval 1 day),'%m') as p_month,
			date_format(date_sub('" . $sdate . "',interval 1 day),'%d') as p_day,
			date_format(last_day(concat(date_format(date_sub('" . $month_first . "',interval 1 month),'%Y-%m'), '-01')),'%d') as prev_month_last,

			date_format(date_add('" . $sdate . "',interval 1 day),'%Y') as n_year,
			date_format(date_add('" . $sdate . "',interval 1 day),'%m') as n_month,
			date_format(date_add('" . $sdate . "',interval 1 day),'%d') as n_day,
			date_format(last_day(concat(date_format(date_add('" . $month_first . "',interval 1 month),'%Y-%m'), '-01')),'%d') as next_month_last
	");

	$month_day     = $data_date['month_day'];
	$month_last    = $data_date['month_last'];
	$month_first_w = $data_date['month_first_w'];
	$month_last_w  = $data_date['month_last_w'];

	$prev_yyyy       = $data_date['prev_yyyy'];
	$prev_mm         = $data_date['prev_mm'];
	$prev_date       = $data_date['p_year'] . '-' . $data_date['p_month'] . '-' . $data_date['p_day'];
	$prev_month      = $prev_yyyy . '-' . $prev_mm . '-' . $sday;
	$prev_month_last = $data_date['prev_month_last'];

	$next_yyyy       = $data_date['next_yyyy'];
	$next_mm         = $data_date['next_mm'];
	$next_date       = $data_date['n_year'] . '-' . $data_date['n_month'] . '-' . $data_date['n_day'];
	$next_month      = $next_yyyy . '-' . $next_mm . '-' . $sday;
	$next_month_last = $data_date['next_month_last'];

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
			$end_date     = $sche_data['end_date'];
			$diff_days    = $sche_data['diff_days'];
			$repeat_num   = $sche_data['repeat_num'];
			$repeat_for   = $sche_data['repeat_for'];

			if ($repeat_class == 'N') // 일반등록
			{
				$schedule_data[$start_date]++;

				if ($start_date != $end_date)
				{
					for ($i = 1; $i <= $diff_days; $i++)
					{
						$date_add = query_view("select date_format(date_add('" . $start_date . "',interval " . $i . " day),'%Y-%m-%d') as add_date");
						$add_date = $date_add['add_date'];

						$schedule_data[$add_date]++;
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
						$schedule_data[$chk_v]++;

						for ($i = 1; $i <= $repeat_for; $i++)
						{
							$date_add = query_view("select date_format(date_add('" . $chk_v . "',interval " . $i . " day),'%Y-%m-%d') as add_date");
							$add_date = $date_add['add_date'];

							$schedule_data[$add_date]++;
						}
						$chk_num++;
					}
				}
				if ($chk_num > 0) $sort++;
			}
		}
	}
?>
	<div class="schedule_body">
		<div class="schedule_left">
			<h3 class="year_<?=$syear;?>"><?=$syear;?>년</h3>
			<div class="month_frame">
				<a href="javascript:void(0);" onclick="schedule_cal('<?=$prev_month;?>')" class="pre" title="이전달"><span>이전달</span></a>
				<div class="month_<?=$this_month;?>"><?=$this_month;?>월</div>
				<a href="javascript:void(0);" onclick="schedule_cal('<?=$next_month;?>')" class="next" title="다음달"><span>다음달</span></a>
			</div>
			<div class="lunar">(음)<?=$today_l_date;?></div>
			<button type="button" class="today" title="오늘보기"><span>오늘보기</span></button>
		</div>
		<div class="schedule_right">
			<table id="schedule_tb" class="schedule_tb">
				<caption>캘린더</caption>
				<thead>
					<tr>
						<th scope="col" class="sun">일</th>
						<th scope="col" class="mon">월</th>
						<th scope="col" class="tue">화</th>
						<th scope="col" class="wed">수</th>
						<th scope="col" class="thu">목</th>
						<th scope="col" class="fri">금</th>
						<th scope="col" class="sat">토</th>
					</tr>
				</thead>
				<tbody>
					<tr>
<?
	$prev_day = $prev_month_last - $month_first_w + 1;
	$next_day = 1;
	for($i = 1; $i <= $total_month; $i++)
	{
		$day        = $i - $month_first_w;
		$sche_day   = str_pad($day, 2, 0, STR_PAD_LEFT);
		$sche_month = $smonth . '-' . $sche_day;
		$sche_date  = $syear . '-' . $smonth . '-' . $sche_day;

		if ($day > 0 && $day <= $month_day)
		{
			$sch_day = $day;
			$class_str = 'hb_' . $day;
		}
		else
		{
			if ($day <= 0)
			{
				$sch_day = $prev_day;
				$class_str = 'hb_' . $sch_day . ' off';
				$prev_day++;
			}
			else
			{
				$class_str = 'hb_' . $next_day . ' off';
				$next_day++;
			}
		}

		if($i % 7 == 1) $class_str .= ' sun'; // 일요일
		else
		{
			foreach ($set_holiday['S'] as $day_k => $day_v)
			{
				if ($sche_month == $day_k) $class_str .= ' sun'; // 휴일
			}
			$l_where = " and cal.cd_sy = '" . $this_year . "' and cal.cd_sm = '" . $this_month . "' and cal.cd_sd = '" . $sch_day . "'";
			$l_data = calenda_info_data('view', $l_where);
			$l_date = str_pad($l_data['cd_lm'], 2, 0, STR_PAD_LEFT) . '-' . str_pad($l_data['cd_ld'], 2, 0, STR_PAD_LEFT);
			foreach ($set_holiday['L'] as $day_k => $day_v)
			{
				if ($l_date == $day_k) $class_str .= ' sun'; // 휴일
			}
		}

		if ($today_date == $sche_date) $class_str .= ' today'; // 오늘일

	// 일정이 있을 경우
		$schedule_date = $schedule_data[$sche_date];
		if ($schedule_date > 0) $class_str .= ' job';
?>
						<td class="<?=$class_str;?>"><a href="javascript:void(0)" onclick="" title="<?=$sche_date;?>"><span><?=$sch_day;?></span></a></td>
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
	</div>
	<div class="schedule_write">
		<form id="scheduleform" name="scheduleform" method="post" action="">

			<fieldset>
				<legend class="blind">일정등록 폼</legend>
				<div class="schedule_memo">
					<div class="schedule_label">
						<label for="schedule_write">일정</label>
					</div>
					<div class="schedule_textarea">
						<textarea name="schedule_write" id="schedule_write" title="오늘의 일정을 등록해 보세요." cols="20" rows="10"></textarea>
					</div>
				</div>
				<div id="schedule_detail">
					<div class="schedule_detail">
						<div><input type="text" name="param[start_date]" id="sche_start_date" value="2010-09-21" alt="2010-09-21" class="type_text datepicker" /></div>
						<label for="sche_start_time" class="blind">시작 시간</label>
						<select name="param[start_time]" id="sche_start_time" title="시작 시간" size="1">
							<option value="00:00">00:00</option>
							<option value="01:00" selected="selected">01:00</option>
							<option value="02:00">02:00</option>
							<option value="03:00">03:00</option>
							<option value="04:00">04:00</option>
							<option value="05:00">05:00</option>
							<option value="06:00">06:00</option>
							<option value="07:00">07:00</option>
							<option value="08:00">08:00</option>
							<option value="09:00">09:00</option>
							<option value="10:00">10:00</option>
							<option value="11:00">11:00</option>
							<option value="12:00">12:00</option>
							<option value="13:00">13:00</option>
							<option value="14:00">14:00</option>
							<option value="15:00">15:00</option>
							<option value="16:00">16:00</option>
							<option value="17:00">17:00</option>
							<option value="18:00">18:00</option>
							<option value="19:00">19:00</option>
							<option value="20:00">20:00</option>
							<option value="21:00">21:00</option>
							<option value="22:00">22:00</option>
							<option value="23:00">23:00</option>
						</select>
						<label for="sche_end_time" class="blind">종료 시간</label>
						<select name="param[end_time]" id="sche_end_time" title="종료 시간" size="1">
							<option value="00:00">00:00</option>
							<option value="01:00">01:00</option>
							<option value="02:00" selected="selected">02:00</option>
							<option value="03:00">03:00</option>
							<option value="04:00">04:00</option>
							<option value="05:00">05:00</option>
							<option value="06:00">06:00</option>
							<option value="07:00">07:00</option>
							<option value="08:00">08:00</option>
							<option value="09:00">09:00</option>
							<option value="10:00">10:00</option>
							<option value="11:00">11:00</option>
							<option value="12:00">12:00</option>
							<option value="13:00">13:00</option>
							<option value="14:00">14:00</option>
							<option value="15:00">15:00</option>
							<option value="16:00">16:00</option>
							<option value="17:00">17:00</option>
							<option value="18:00">18:00</option>
							<option value="19:00">19:00</option>
							<option value="20:00">20:00</option>
							<option value="21:00">21:00</option>
							<option value="22:00">22:00</option>
							<option value="23:00">23:00</option>
						</select>
						<label for="sche_long_time"><input type="checkbox" name="user_param[long_time]" id="sche_long_time" class="type_checkbox" title="종일" /><span>종일</span></label>
					</div>
					<div class="schedule_footer">
						<ul>
							<li><input type="submit" value="저장" /></li>
							<li><button type="button" title="취소"><span>취소</span></button></li>
						</ul>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
<?
    db_close();
?>
