<?
/*
	수정 : 2013.03.04
	위치 : 메인화면
*/
?>
					<div class="hb_contents">
						<div class="layout_frame">
							<div class="layout_box">

							<!-- Tab Contents -->
								<div id="tabs" class="tabs">
									<ul class="tab_tabs">
								<?
									if ($receipt_str == 'Y')
									{
								?>
										<li><a id="ttab-2" href="#tab-2">접수 미처리현황(<?=number_format($receipt_page['total_num']);?>)</a></li>
								<?
									}
									if ($work_str == 'Y') {
								?>
										<li><a id="ttab-1" href="#tab-1">업무리스트</a></li>
								<?
									}
									if ($project_str == 'Y') {
								?>
										<li><a id="ttab-3" href="#tab-3">프로젝트리스트</a></li>
								<?
									}
								?>
									</ul>
					<?
					// 접수
						if ($receipt_str == 'Y')
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
					// 업무
						if ($work_str == 'Y') // 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)
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
													<em><?=$list_data['mem_name'];?></em>
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
					// 프로젝트
						if ($project_str == 'Y') // 보류(WS80), 완료(WS90), 종료(WS99), 취소(WS50)
						{
							$project_where = " and pro.comp_idx = '" . $code_comp . "'";
							if ($set_part_yn == 'N') $project_where .= " and pro.part_idx = '" . $top_code_part . "'";
							$project_where .= "
								and (concat(',', pro.charge_idx, ',') like '%," . $code_mem . ",%' or pro.apply_idx = '" . $code_mem . "' or pro.reg_id = '" . $code_mem . "')
								and pro.pro_status <> 'PS80' and pro.pro_status <> 'PS90' and pro.pro_status <> 'PS99' and pro.pro_status <> 'PS50'
							";
							$project_list = project_info_data('list', $project_where, '', 1, 6);
					?>
								<!-- 프로젝트리스트 -->
									<div id="tab-3" class="showlist">
										<ul>
						<?
							if ($project_list['total_num'] > 0)
							{
								foreach ($project_list as $project_k => $project_data)
								{
									if (is_array($project_data))
									{
										$list_data = project_list_data($project_data, $project_data['pro_idx']);
						?>
											<li>
												<a href="javascript:void(0)" onclick="location.href='<?=$this_page;?>?fmode=project&smode=project&pro_idx=<?=$project_data['pro_idx'];?>'">
													<strong><img src="<?=$local_dir;?>/bizstory/images/icon/icon_project.gif" alt="프로젝트" /></strong>
													<span class="pro_code">[
                                                    <?if ($list_data['menu1'] == '') {?><?=$list_data['project_code'];?><?}else{
                                                        
                                                       echo $list_data['menu1'] . '-';
                                                       
                                                       if ($list_data['menu2'] != '') echo $list_data['menu2'] . '-';
                                                       
                                                       echo $list_data['project_code'];
                                                     
                                                    }?>     
                                                    ]</span>
													<?=$list_data['subject_txt'];?>
													<em><?=$list_data['mem_name'];?></em>
													<?=$list_data['file_str'];?>
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

						<!-- 공지사항 -->
						<?
							$notice_where = "
								and ni.notice_type = '2' and ni.view_yn = 'Y'
								and (concat(ni.comp_idx, ',') like '%" . $code_comp . "%' or ni.comp_all = 'Y')
							";
							$notice_list = notice_info_data('list', $notice_where, '', '', '');
						?>
								<div class="ticker mainticker ticker_frame">
									<div>
										<marquee behavior="scroll" direction="left" scrollamount="2"><p>
						<?
							if ($notice_list['total_num'] == 0)
							{
						?>
											<span style="padding-right:20px;">&nbsp;</span>
						<?
							}
							else
							{
								foreach ($notice_list as $notice_k => $notice_data)
								{
									if (is_array($notice_data))
									{
										$import_type = $notice_data['import_type'];
										$link_url    = $notice_data['link_url'];

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
											<span style="padding-right:20px;">ㆍ <?=$subject;?><?=$important_span;?></span>
						<?
									}
								}
							}
						?>
										</p></marquee>
									</div>
								</div>

							<!-- 직원목록 -->
								<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/main_banner.css" media="all" />
								<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/scrollnews.js"></script>
								<script type="text/javascript">
									$(document).ready(function(){
										$("#main_staff_view").Scroll({line:1,speed:1500,timer:3000,up:"#topbtnid",down:"#btmbtnid",autoplay:'#bannerplay',autostop:'#bannerstop'});
									});
								</script>

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
									if ($work_str == 'Y') {
								?>
										<li><a href="#tabs_status-1">업무이력</a></li>
								<?
									}
									if ($receipt_str == 'Y')
									{
								?>
										<li><a href="#tabs_status-2">접수이력</a></li>
								<?
									}
									if ($project_str == 'Y')
									{
								?>
										<li><a href="#tabs_status-3">프로젝트이력</a></li>
								<?
									}
								?>
									</ul>
							<?
							// 업무이력
								if ($work_str == 'Y')
								{
							?>
									<div id="tabs_status-1" class="statuslist"></div>
									<script type="text/javascript">
									//<![CDATA[
										$.ajax({
											type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/main_work_history.php',
											async: true,
											data: '',
											success: function(msg) {
												$('#tabs_status-1').html(msg);
											}
										});
									//]]>
									</script>
							<?
								}
							// 접수이력
								if ($receipt_str == 'Y')
								{
							?>
									<div id="tabs_status-2" class="statuslist"></div>
									<script type="text/javascript">
									//<![CDATA[
										$.ajax({
											type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/main_receipt_history.php',
											async: true,
											data: '',
											success: function(msg) {
												$('#tabs_status-2').html(msg);
											}
										});
									//]]>
									</script>
							<?
								}
							// 프로젝트이력
								if ($project_str == 'Y')
								{
							?>
									<div id="tabs_status-3" class="statuslist"></div>
									<script type="text/javascript">
									//<![CDATA[
										$.ajax({
											type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/main_project_history.php',
											async: true,
											data: '',
											success: function(msg) {
												$('#tabs_status-3').html(msg);
											}
										});
									//]]>
									</script>
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
							<div id="main_schedule" class="hb_schedule"></div>
							<script type="text/javascript">
							//<![CDATA[
								function schedule_cal(sdate)
								{
									$.ajax({
										type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/main_schedule.php',
										async: true,
										data: {'sdate' : sdate},
										success: function(msg) {
											$('#main_schedule').html(msg);
										}
									});
								}
								schedule_cal('<?=date('Y-m-d');?>');
							//]]>
							</script>
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
							$set_agent_yn   = $comp_set_data['agent_yn'];

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
							if ($set_agent_yn == 'Y')
							{
								$agent_where = " and ad.comp_idx = '" . $code_comp . "' and ci.del_yn = 'N'";
								$agent_list = agent_data_data('page', $agent_where);
						?>
									<li><span>에이전트 갯수 </span><em>: <?=number_format($agent_list['total_num']);?> 개</em></li>
						<?
							}
						?>
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
			async: true,
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
	
	$(function() {
	    <?
        switch ($company_info_data['focus_tab'] == 'project') {
            case "project":
	    ?>
	   $("#ttab-3").click(); 
        <?
	           break;
            case "work":
        ?>
        $("#ttab-1").click();        
        <?
                break;
        }
        ?>
	});
//]]>
</script>
