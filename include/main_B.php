<?
/*
	수정 : 2012.07.05
	위치 : 메인화면 B
*/
?>
<strong>B형 메인화면</strong>
					<div class="hb_contents">
						<div class="layout_frame">
							<div class="layout_box">

							<!-- Tab Contents -->
								<div id="tabs" class="tabs">
									<ul class="tab_tabs">
								<?
									if ($set_receipt_yn == 'Y')
									{
								?>
										<li><a href="#tab-2">접수 미처리현황(<?=number_format($receipt_page['total_num']);?>)</a></li>
								<?
									}
									if ($set_work_yn == 'Y') {
								?>
										<li><a href="#tab-1">업무리스트</a></li>
								<?
									}
								?>
									</ul>
					<?
						if ($set_receipt_yn == 'Y')
						{
							$receipt_where = " and ri.comp_idx = '" . $code_comp . "'";
							if ($set_part_yn == 'N') $receipt_where .= " and ri.part_idx = '" . $top_code_part . "'";
							$receipt_where .= " and ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60'";
							$receipt_list = receipt_info_data('list', $receipt_where, '', 1, 6);
					?>
								<!-- 미처리현황 -->
									<div id="tab-2" class="showlist">
										<ul>
						<?
							if ($receipt_list['total_num'] > 0)
							{
								$chk_num = 1;
								foreach ($receipt_list as $receipt_k => $receipt_data)
								{
									if (is_array($receipt_data))
									{
									// 댓글수
										$sub_where = " and rc.ri_idx='" . $receipt_data['ri_idx'] . "'";
										$sub_data = receipt_comment_data('page', $sub_where);
										$receipt_data['total_tail'] = $sub_data['total_num'];
									// 분류
										$receipt_class = receipt_class_view($receipt_data['receipt_class']);
						?>
											<li>
												<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&ri_idx=<?=$receipt_data['ri_idx'];?>'">
													<strong>[<?=$receipt_class['first_class'];?>]</strong>
													<em><?=$receipt_data['client_name'];?></em>
													<span><?=$receipt_data['subject'];?></span>
													[<small class="comment"><?=$receipt_data['total_tail'];?></small>]
												</a>
												<div class="date"><?=date_replace($receipt_data['reg_date'], 'Y.m.d');?></div>
											</li>
						<?
									}
								}
							}
							else
							{
								echo '<li style="height:120px; text-align:center; top:50px;">등록된 내용이 없습니다.</li>';
							}
						?>
										</ul>
									</div>
					<?
						}
						if ($set_work_yn == 'Y') // 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)
						{
							$work_where = " and wi.comp_idx = '" . $code_comp . "'";
							if ($set_part_yn == 'N') $work_where .= " and wi.part_idx = '" . $top_code_part . "'";
							$work_where .= "
								and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')
								and wi.work_status <> 'WS80' and wi.work_status <> 'WS90' and wi.work_status <> 'WS99' and wi.work_status <> 'WS50'
							";
							$work_list = work_info_data('list', $work_where, '', 1, 6);
					?>
								<!-- 업무리스트 -->
									<div id="tab-1" class="showlist">
										<ul>
						<?
							if ($work_list['total_num'] > 0)
							{
								foreach ($work_list as $work_k => $work_data)
								{
									if (is_array($work_data))
									{
										$list_data = work_list_data($work_data, $work_data['wi_idx']);
						?>
											<li>
												<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=work&smode=work&wi_idx=<?=$work_data['wi_idx'];?>'">
													<strong><?=$list_data['work_img'];?></strong>
													<em><?=$list_data['reg_name_view'];?></em>
													<span><?=$list_data['part_img'];?><?=$list_data['subject_main_list'];?></span>
													<?=$list_data['file_str'];?>
													<?=$list_data['report_str'];?>
													<?=$list_data['comment_str'];?>
													<?=$list_data['new_img'];?>
												</a>
												<div class="date"><?=date_replace($work_data['reg_date'], 'Y.m.d');?></div>
											</li>
						<?
									}
								}
							}
							else
							{
								echo '<li style="height:120px; text-align:center; top:50px;">등록된 내용이 없습니다.</li>';
							}
						?>
										</ul>
									</div>
					<?
						}
					?>
								</div>
							<!-- //Tab Contents -->
	<?
		$notice_where = "
			and ni.notice_type = '2' and ni.view_yn = 'Y'
			and (concat(ni.comp_idx, ',') like '%" . $code_comp . "%' or ni.comp_all = 'Y')
		";
		$notice_list = notice_info_data('list', $notice_where, '', '', '');
	?>
							<!-- Ticker -->
								<div id="main_notice" class="ticker mainticker">
									<div class="ticker_frame">
										<div id="ticker-wrapper" class="no-js">
											<ul id="js-news" class="js-hidden">
							<?
								if ($notice_list['total_num'] == 0)
								{
							?>
												<li class="news-item">&nbsp;</li>
							<?
								}
								else
								{
									foreach ($notice_list as $notice_k => $notice_data)
									{
										if (is_array($notice_data))
										{
											$import_type = $notice_data['import_type'];
											$link_url = $notice_data['link_url'];

										// 중요도
											if ($notice_data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
											else if ($notice_data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
											else if ($notice_data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
											else $important_span = '';

											if ($link_url == '')
											{
												$subject = $notice_data['content'];
											}
											else
											{
												$subject = '<a href="http://' . $link_url . '" target="_blank">' . $notice_data['content'] . '</a>';
											}
							?>
												<li class="news-item"><?=$subject;?><?=$important_span;?></li>
							<?
										}
									}
								}
							?>
											</ul>
										</div>
									</div>
								</div>
							<!-- //Ticker -->

								<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/main_banner.css" media="all" />
								<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/scrollnews.js"></script>
								<script type="text/javascript">
									$(document).ready(function(){
										$("#main_notice_view").Scroll({line:1,speed:1500,timer:3000,up:"#topbtnid2",down:"#btmbtnid2",autoplay:'#bannerplay2',autostop:'#bannerstop2'});
										$("#main_staff_view").Scroll({line:1,speed:1500,timer:3000,up:"#topbtnid",down:"#btmbtnid",autoplay:'#bannerplay',autostop:'#bannerstop'});
									});
								</script>

								<div id="main_notice_view" class="main_section">
									<ul id="main_notice_view_list">
							<?
									foreach ($notice_list as $notice_k => $notice_data)
									{
										if (is_array($notice_data))
										{
											$import_type = $notice_data['import_type'];
											$link_url = $notice_data['link_url'];

										// 중요도
											if ($notice_data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
											else if ($notice_data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
											else if ($notice_data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
											else $important_span = '';

											if ($link_url == '')
											{
												$subject = $notice_data['content'];
											}
											else
											{
												$subject = '<a href="http://' . $link_url . '" target="_blank">' . $notice_data['content'] . '</a>';
											}
							?>
										<li>
											<div class="staff_area">
												<?=$subject;?><?=$important_span;?><?=$subject;?><?=$important_span;?><?=$subject;?><?=$important_span;?><?=$subject;?><?=$important_span;?><?=$subject;?><?=$important_span;?><br /><br /><br />
											</div>
										</li>
							<?
										}
									}
							?>
									</ul>
								</div>

							<!-- 직원목록 -->
								<div class="main_section" id="main_staff_view">
									<ul id="main_staff_view_list">
					<?
						$staff_where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $top_code_part . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
						$staff_order = "cpd.sort asc, mem.mem_name asc";
						$staff_list = member_info_data('list', $staff_where, $staff_order, '', '');
						foreach ($staff_list as $staff_k => $staff_data)
						{
							if (is_array($staff_data))
							{
								$mem_info = member_chk_data($staff_data['mem_idx']);
								$mem_img = member_img_view($staff_data['mem_idx'], $comp_member_dir);
					?>
										<li>
											<div class="staff_area">
												<?=$mem_img['img_80'];?>
												<ul class="charge_info">
													<li class="info_name"><span class="title">이름</span> : <?=$staff_data['mem_name'];?> <?=$staff_data['duty_name'];?></li>
													<li class="info_email"><span class="title">E-mail</span> : <span class="info_num"><?=$staff_data['mem_email'];?></span></li>
													<li class="info_tel"><span class="title">연락처</span> : <strong><?=$staff_data['hp_num'];?></strong></li>
												</ul>
												<ul class="staff01">
													<li><span class="staff_01">총업무처리</span> : <span class="staff_num"><?=number_format($mem_info['work_all']);?></span></li>
													<li><span class="staff_02">총접수처리</span> : <span class="staff_num"><?=number_format($mem_info['receipt_all']);?></span></li>
												</ul>
												<ul class="staff02">
													<li><span class="staff_01">진행업무</span> : <span class="staff_num"><?=number_format($mem_info['work_ing']);?></span></li>
													<li><span class="staff_02">진행접수</span> : <span class="staff_num"><?=number_format($mem_info['receipt_ing']);?></span></li>
												</ul>
												<ul class="staff03">
													<li><span class="staff_03">로그인횟수</span> : <span class="staff_num"><?=number_format($mem_info['total_login']);?></span></li>
													<li><span class="staff_03">마지막로그인</span> : <span class="staff_num"><?=date_replace($mem_info['last_login'], 'Y/m/d H:i');?></span></li>
												</ul>
											</div>
										</li>
					<?
							}
						}
					?>
									</ul>
								</div>

							<!-- // Status -->
								<br />
								<div id="tabs_status" class="tabs">
									<ul class="tab_statuslist">
								<?
									if ($set_work_yn == 'Y') {
								?>
										<li><a href="#tabs_status-1">업무이력</a></li>
								<?
									}
									if ($set_receipt_yn == 'Y')
									{
								?>
										<li><a href="#tabs_status-2">접수이력</a></li>
								<?
									}
								?>
									</ul>
						<?
						// 업무이력
							if ($set_work_yn == 'Y')
							{
								$work_where = " and wsh.comp_idx = '" . $code_comp . "'";
								if ($set_part_work_yn == 'Y')
								{ }
								else
								{
									if ($set_part_yn == 'N') $work_where .= " and wsh.part_idx = '" . $top_code_part . "'";
								}
								$work_where .= " and wi.del_yn = 'N' and wsh.mem_idx > 0";
								$work_order = "wsh.reg_date desc";
								$work_list = work_status_history_data('list', $work_where, $work_order, 1, 20);
						?>
									<div id="tabs_status-1" class="statuslist">
										<ul>
							<?
								if ($work_list['total_num'] > 0)
								{
									foreach ($work_list as $work_k => $work_data)
									{
										if (is_array($work_data))
										{
										// 등록자 이미지
											$mem_img = member_img_view($work_data['mem_idx'], $comp_member_dir);

											$work_data['display_type'] = 'display_main';

											$list_data = work_list_data($work_data, $work_data['wi_idx']);

											if ($list_data['view_link_main'] == '')
											{
												$status_memo = $work_data['status_memo'];
											}
											else
											{
												$status_memo = '<a href="javascript:void(0)" onclick="' . $list_data['view_link_main'] . '">' . $work_data['status_memo'] . '</a>';
											}
							?>
											<li class="line">
												<ul class="li_l">
													<li class="li_img"><?=$list_data['work_img'];?></li>
													<li class="li_subject">
														<?=$list_data['part_img'];?>
														<?=$list_data['subject_main'];?>
														<?=$list_data['important_img'];?>
														<?=$list_data['open_img'];?>
													</li>
													<li class="li_memo"><?=$status_memo;?></li>
												</ul>
												<ul class="li_r">
													<li class="li_date">[<?=date_replace($work_data['reg_date'], 'Y-m-d H:i');?>]</li>
													<li class="li_mem"><span><?=$mem_img['img_26'];?></span></li>
												</ul>
											</li>
						<?
									}
								}
							}
							else
							{
								echo '<li style="height:200px; text-align:center; padding-top:120px;">등록된 내용이 없습니다.</li>';
							}
						?>
										</ul>
									</div>
						<?
							}
						// 접수이력
							if ($set_receipt_yn == 'Y')
							{
								$receipt_where = " and rsh.comp_idx = '" . $code_comp . "'";
								if ($set_part_yn == 'N') $receipt_where .= " and rsh.part_idx = '" . $top_code_part . "'";
								$receipt_where .= " and ri.del_yn = 'N' and rsh.mem_idx > 0";
								$receipt_order = "rsh.reg_date desc";
								$receipt_list = receipt_status_history_data('list', $receipt_where, $receipt_order, 1, 20);
						?>
									<div id="tabs_status-2" class="statuslist">
										<ul>
						<?
								if ($receipt_list['total_num'] > 0)
								{
									foreach ($receipt_list as $receipt_k => $receipt_data)
									{
										if (is_array($receipt_data))
										{
										// 담당자 이미지
											$mem_img = member_img_view($receipt_data['mem_idx'], $comp_member_dir);

											$list_data = receipt_list_data($receipt_data['ri_idx'], $receipt_data);
						?>
											<li class="line">
												<ul class="li_l">
													<li class="li_subject">[<strong><?=$list_data['client_name'];?></strong>]</li>
													<li class="li_subject">
														<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&ri_idx=<?=$receipt_data['ri_idx'];?>'"><strong><?=$receipt_data['subject'];?></strong></a>
														<?=$list_data['important_img'];?>
													</li>
													<li class="li_memo">
														<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=receipt&smode=receipt&ri_idx=<?=$receipt_data['ri_idx'];?>'"><?=$receipt_data['status_memo'];?></a>
													</li>
												</ul>
												<ul class="li_r">
													<li class="li_date">[<?=date_replace($receipt_data['reg_date'], 'Y-m-d H:i');?>]</li>
													<li class="li_mem"><span><?=$mem_img['img_26'];?></span></li>
												</ul>
											</li>
						<?
										}
									}
								}
								else
								{
									echo '<li style="height:200px; text-align:center; padding-top:120px;">등록된 내용이 없습니다.</li>';
								}
						?>
										</ul>
									</div>
						<?
							}
						?>
								</div>
							</div>
						</div>
					</div>
					<div class="hb_adspace">
						<div class="layout_box">
							<!-- Schedule -->
<?
	if ($sdate == "") $sdate = date("Y-m-d");
	if ($edate == "") $edate = date("Y-m-d");
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
?>
							<div id="main_schedule" class="hb_schedule">
								<div class="schedule_body">
									<div class="schedule_left">
										<h3 class="year_<?=$syear;?>"><?=$syear;?>년</h3>
										<div class="month_frame">
											<a href="javascript:void(0);" class="pre" title="이전달"><span>이전달</span></a>
											<div class="month_<?=$this_month;?>"><?=$this_month;?>월</div>
											<a href="javascript:void(0);" class="next" title="다음달"><span>다음달</span></a>
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
		$sub_where = " and sche.comp_idx = '" . $code_comp . "'";
		//$sub_where .= " and sche.part_idx = '" . $top_code_part . "'";
		//$sub_where .= " and sche.mem_idx = '" . $code_mem . "'";
		$sub_list = schedule_info_data('list', $sub_where, '', '', '');
		if ($sub_list['total_num'] > 0) $class_str .= ' job';
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
									<form name="scheduleform" method="post" action="./">
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
												<div><input type="text" name="param[start_date]" id="start_date" value="2010-09-21" alt="2010-09-21" class="type_text datepicker" /></div>
												<label for="start_time" class="blind">시작 시간</label>
												<select name="param[start_time]" id="start_time" title="시작 시간" size="1">
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
												<label for="end_time" class="blind">종료 시간</label>
												<select name="param[end_time]" id="end_time" title="종료 시간" size="1">
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
												<label for="long_time"><input type="checkbox" name="user_param[long_time]" id="long_time" class="type_checkbox" title="종일" /><span>종일</span></label>
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
							</div>
							<!-- //Schedule -->

							<!-- Ad Banner -->
						<?
						// 배너목록
							$banner_where = " and bi.view_yn = 'Y' and bi.banner_type = '2' and (bi.comp_all = 'Y' or concat(bi.comp_idx, ',') like '%," . $code_comp . ",%')";
							$banner_list = banner_info_data('list', $banner_where, '', '', '');
						?>
							<div id="main_banner" class="ad_banner">
								<div id="ad_banner">
						<?
							if ($banner_list['total_num'] == 0)
							{
						?>
									<div>
										<a href="./" title="배너제목"><img src="./data/ad_banner01.gif" alt="배너내 텍스트 내용" width="257px" height="125px" /></a>
									</div>
						<?
							}
							else
							{
								foreach ($banner_list as $banner_k => $banner_data)
								{
									if (is_array($banner_data))
									{
										if ($banner_data["img_sname1"] != '')
										{
											$img_str = '<img src="' . $banner_dir . '/' . $banner_data["img_sname1"] . '" alt="' . $banner_data["content"] . '" width="257px" height="125px" />';
										}
										else
										{
											$img_str = '';
										}
						?>
									<div>
										<a href="<?=$banner_data['link_url'];?>" target="_blank" title="<?=$banner_data["content"];?>"><?=$img_str;?></a>
									</div>
						<?
									}
								}
							}
						?>
								</div>
								<div id="ad_counter"></div>
							</div>
							<!-- //Ad Banner -->

						<!-- Servie Information -->
						<?
							$set_comp_idx   = $company_info_data['comp_idx'];
							$set_start_date = $company_info_data['start_date'];
							$set_end_date   = $company_info_data['end_date'];
							$set_icon_yn    = $company_set_data['agent_yn'];

						//사용자 데이터 - /data/company/comp_idx/* 값구해서
							$volume_path = $comp_path . '/' . $set_comp_idx;
							$volume_data = server_volume($volume_path);
						?>
							<div id="main_servie" class="service_info">
								<h4><strong>비즈스토리 사용 내역</strong></h4>
								<ul>
									<li><span>서비스 시작일 </span><em>: <?=date_replace($set_start_date, 'Y.m.d');?></em></li>
									<li><span>서비스 만기일 </span><em>: <?=date_replace($set_end_date, 'Y.m.d');?></em></li>
									<li><span>데이터 사용량 </span><em>: <?=byte_replace($volume_data);?></em></li>
						<?
							if ($set_icon_yn == 'Y')
							{
								$agent_where = " and ad.comp_idx = '" . $code_comp . "' and ci.del_yn = 'N'";
								$agent_list = agent_data_data('page', $agent_where);
						?>
									<li><span>에이전트 갯수 </span><em>: <?=number_format($agent_list['total_num']);?> 개</em></li>
						<?
							}
						?>
									<li>
										<span>부가서비스사용 </span>
										<em class="exception">: </em>
									</li>
								</ul>
								<div>
									<p>문의전화 : <span>1544-7325</span> 담당 : 김나영실장</p>
								</div>
							</div>

						<!-- MEMO -->
							<div id="main_note" class="note">
							<!-- Note -->
								<div class="ui-widget" id="memo_notice_view" style="display:none">
									<div class="ui-state-highlight ui-corner-all">
										<p>
											<span class="ui-icon ui-icon-info"></span>
											<span id="popup_notice_memo">
												<strong>주의</strong> 주의사항 입력
											</span>
										</p>
									</div>
								</div>

							<!-- Write -->
								<div class="loop write">
									<form id="memoform" name="memoform" method="post" action="<?=$this_page;?>" onsubmit="return check_memo_post()">
										<input type="hidden" id="memo_sub_type" name="sub_type" value="post" />
										<fieldset>
											<legend class="blind">메모작성 폼</legend>
											<div class="note_write"><input type="submit" value="저장하기" /></div>
											<div class="note_head"></div>
											<div class="note_body">
												<div>
													<textarea name="param[remark]" id="memo_remark" cols="20" rows="10" title="메모를 입력하세요." onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}">메모를 입력하세요.</textarea>
												</div>
											</div>
											<div class="note_footer"></div>
										</fieldset>
									</form>
								</div>

							<!-- View -->
								<form id="memolistform" name="memolistform" method="post" action="<?=$this_page;?>">
									<input type="hidden" id="memolist_sub_type"  name="sub_type"  value="" />
									<input type="hidden" id="memolist_mm_idx"    name="mm_idx"    value="" />
									<input type="hidden" id="memolist_list_type" name="list_type" value="" />

									<div id="memo_list_data"></div>
								</form>

							</div>
							<!-- //Note -->
						</div>
					</div>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 메모관련함수
	var memo_list = '<?=$local_dir;?>/bizstory/include/memo_list.php';
	var memo_ok   = '<?=$local_dir;?>/bizstory/include/memo_list_ok.php';

//------------------------------------ 메모목록
	function memo_list_data()
	{
		$("#memo_notice_view").hide();
		$.ajax({
			type: 'post', dataType: 'html', url: memo_list,
			data: $('#memolistform').serialize(),
			success: function(msg) {
				$('#memo_list_data').html(msg);
			}
		});
	}

//------------------------------------ 메모등록
	function check_memo_post()
	{
		$("#memo_notice_view").hide();

		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#memo_remark').val(); // 내용
		chk_title = $('#memo_remark').attr('title');
		if (chk_value == chk_title) chk_value = '';

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: memo_ok,
				data: $('#memoform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#memo_remark').val('메모를 입력하세요.');
						memo_list_data();
					}
					else
					{
						$("#memo_notice_view").show();
						$("#memo_notice_note").html(msg.error_string);
					}
				}
			});
		}
		else
		{
			$("#memo_notice_view").show();
			$("#memo_notice_note").html(chk_total);
		}
		return false;
	}

//------------------------------------ 메모수정
	function check_memo_modify()
	{
		$("#memo_notice_view").hide();

		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#memomodify_remark').val(); // 내용
		chk_title = $('#memomodify_remark').attr('title');
		if (chk_value == chk_title) chk_value = '';

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$('#memolist_sub_type').val('modify');
			$.ajax({
				type: 'post', dataType: 'json', url: memo_ok,
				data: $('#memolistform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#memolist_sub_type').val('');
						$('#memolist_mm_idx').val('');
						memo_list_data();
					}
					else
					{
						$("#memo_notice_view").show();
						$("#memo_notice_note").html(msg.error_string);
					}
				}
			});
		}
		else
		{
			$("#memo_notice_view").show();
			$("#memo_notice_note").html(chk_total);
		}
		return false;
	}

//------------------------------------ 삭제하기
	function check_memo_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$("#memo_notice_view").hide();

			$('#memolist_sub_type').val('delete');
			$('#memolist_mm_idx').val(idx);

			$.ajax({
				type: 'post', dataType : 'json', url: memo_ok,
				data: $('#memolistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						memo_list_data();
					}
					else
					{
						$("#memo_notice_view").show();
						$("#memo_notice_note").html(msg.error_string);
					}
				}
			});
		}
	}

//------------------------------------ 수정폼
	function memo_modify(mm_idx)
	{
		$('#memolist_sub_type').val('modify_view');
		$('#memolist_mm_idx').val(mm_idx);

		memo_list_data();
	}

	memo_list_data();
//]]>
</script>
