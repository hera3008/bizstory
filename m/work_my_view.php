<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./process/no_direct.php";
	include  "./header.php";

	$send_fmode = "work";
	$send_smode = "work";
		
	$form_chk = 'Y';
	/*
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			alertMsg('');
		//]]>
		</script>
<?
	}
	*/
	if ($form_chk == "Y")
	{
		$where = " and wi.wi_idx = '" . $wi_idx . "'";
		
		$data = work_info_data('view', $where);

		$data = work_list_data2($data, $wi_idx); // 작업내용

		check_work_type_status($data); // 승인대기, 읽기
		

	// 업무읽은 표시 - 알림을 제외
		if ($data['work_type'] != 'WT04')
		{
			$read_where = " and wre.wi_idx = '" . $wi_idx . "' and wre.mem_idx = '" . $code_mem . "'";
			$read_data = work_read_data('view', $read_where);
			if ($read_data['total_num'] == 0)
			{
				$insert_query = "
					insert into work_read set
						  comp_idx  = '" . $code_comp . "'
						, part_idx  = '" . $code_part . "'
						, wi_idx    = '" . $wi_idx . "'
						, mem_idx   = '" . $code_mem . "'
						, read_date = '" . date('Y-m-d H:i:s') . "'
						, reg_id    = '" . $code_mem . "'
						, reg_date  = '" . date('Y-m-d H:i:s') . "'
				";
				db_query($insert_query);
				query_history($insert_query, 'work_read', 'insert');
			}
		}
		
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 변수
		$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
		$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
		$f_search  = $f_search . '&amp;swtype=' . $send_swtype . '&amp;shwstatus=' . $send_shwstatus . '&amp;smember=' . $send_smember;
		$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
		$f_all     = $f_page . '&amp;page_num=' . $page_num;
		$f_script  = str_replace('&amp;', '&', $f_all);
		$field_str = str_replace('&amp;', '|', $f_all);
	
		$form_default  = '
			<input type="hidden" name="fmode" value="' . $send_fmode . '" />
			<input type="hidden" name="smode" value="' . $send_smode . '" />
		';
		$form_search = $form_default . '
			<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
			<input type="hidden" name="stext"     value="' . $send_stext . '" />
			<input type="hidden" name="swtype"    value="' . $send_swtype . '" />
			<input type="hidden" name="shwstatus" value="' . $send_shwstatus . '" />
			<input type="hidden" name="smember"   value="' . $send_smember . '" />
		';
		$form_page = $form_search . '
			<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
			<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
		';
		$form_all = $form_page . '
			<input type="hidden" name="page_size" value="' . $send_page_size . '" />
			<input type="hidden" name="page_num"  value="' . $page_num . '" />
			<input type="hidden" name="field_str" value="' . $field_str . '" />
		';

	// 파일목록
		$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
		$file_list = work_file_data('list', $file_where, '', '', '');

		$work_report_yn = $data['report_yn'];
		$work_report_yn = $data['report_yn'];
		$read_work      = $data['read_work'];
		$read_report    = $data['read_report'];
		$read_comment   = $data['read_comment'];
?>
<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>

		<!-- 안내문구 -->
		<div id="advice">
			<div class="advice-body">
					
				<ul>
					<li>다수 담당자의 업무요청일 경우 모두 완료요청을 할 경우 등록자가 '업무완료' 할 수 있습니다.</li>
					<li>다수 담당자의 승인요청일 경우 모두 승인요청을 할 경우 승인자가 '승인'할 수 있습니다.</li>
				</ul>	

			</div>
			<button class="control-btn"><em>도움말 닫기</em></button>
		</div>
		<script type="text/javascript">$('#advice').mainVisual();</script>
		<!-- //안내문구 -->
	</div>

	<div id="content">
		<article class="mt_4">
			<h2>업무관리</h2>
		</article>
		<div id="wrapper" class="work">
			<div id="scroller">

				<form id="viewform" name="viewform" method="post" action="./work_my_view.php">
				<input type="hidden" name="sub_type"    id="view_sub_type" />
				<input type="hidden" name="part_idx"    id="view_part_idx"    value="<?=$data['part_idx'];?>" />
				<input type="hidden" name="wi_idx"      id="view_wi_idx"      value="<?=$wi_idx?>" />
				<input type="hidden" name="work_status" id="view_work_status" value="<?=$data['work_status'];?>" />
				<input type="hidden" name="print_type"  id="view_print_type"  value="1" />

				<div class="work_area">
					<div class="work_inner">
						
						<div class="title">
							<?=$data['work_img'];?>
							<span class="mem_regist"><?=$data['reg_name_view'];?><span class="data"> <?=$data['reg_date'];?> </span></span>
							<br />
							<?=$data['part_img'];?>
							<strong><?=$data['subject'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['open_img'];?>
							<?=$data['file_str'];?>
							<?=$data['report_str'];?>
							<?=$data['comment_str'];?>
							<?=$data['read_work_str'];?>
							<!--
							<span class="btn01">요청</span>
							<strong>김희철 회계사 무역업무 개발사항</strong>
							<span class="report" title="업무보고서">40</span>
							<span class="cmt" title="코멘트">13</span>
							<em class="push" title="읽을 업무보고/코멘트">1</em>
							-->
							<!-- strong class="regist"><?=$data['reg_name_view'];?><span class="data"> <?=$data['reg_date'];?> </span><strong -->
						</div>
						<table border="1" cellspacing="0" summary="업무내용" class="table02">
							<tr>
								<th class="w100">담당자</th>
								<td>
									<?=$data['total_charge_str'];?>
								</td>
							</tr>
							<tr>
								<th>승인자</th>
								<td>
									<strong style="color:#ff6c00"><?=$data['apply_name'];?></strong>
								</td>
							</tr>
							<tr>
								<th>기한</th>
								<td>
									<?=$data['deadline_date_str'];?>
									- <?=$data['end_date_str'];?>
								</td>
							</tr>
							<tr>
								<th>분류</th>
								<td><?=$data['work_class_str'];?></td>
							</tr>
							<tr>
								<td colspan="2" class="remark_area">
									<?=$data['work_class_str'];?>
									<p>
									<?=$data['remark'];?>
									</p>
								</td>
							</tr>
							<tr>
								<th>첨부파일</th>
								<td>
				<?
					if ($file_list['total_num'] > 0) {
				?>
							<ul>
				<?
						foreach ($file_list as $file_k => $file_data)
						{
							if (is_array($file_data))
							{
								$fsize = $file_data['img_size'];
								$fsize = byte_replace($fsize);

								$btn_str = preview_file($comp_work_dir, $file_data['wf_idx'], 'work');
				?>
								<li>
									<?=$btn_str;?>
									<a href="<?=$local_diir;?>/bizstory/work/work_download.php?wf_idx=<?=$file_data['wf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								</li>
				<?
							}
						}
						$btn_img = preview_images($wi_idx, 'work');
						if ($btn_img != '')
						{
							echo '
								<li>' . $btn_img . '</li>
							';
						}
				?>
							</ul>
				<?
					}
				?>									
								</td>
							</tr>
						</table>

					</div>
				</div>
<?
// 등록자만 가능하다.
	if ($data['reg_id'] == $code_mem || $code_level <= 11)
	{
		$btn_modify = '<span class="btn07"><input type="button" value="수정" onclick="data_form_open(\'' . $wi_idx . '\')" /></span>';
		$btn_delete = '<span class="btn06"><input type="button" value="삭제" onclick="check_delete(\'' . $wi_idx . '\')" /></span>';
	}

// 완료일 경우 수정, 삭제가 안됨
	if ($data['work_status'] == 'WS90')
	{
		$btn_modify = '';
		$btn_delete = '';
	}

	$section_str = work_status_view($data, 'mobile'); // 진행상태
?>
	
				<!-- 업무승인 상태 -->
				<div class="status_section">
					<div class="title">
						<?=$section_str['status_title'];?>
					</div>
					<?
						if ($section_str['status_comment'] != '')
						{
					?>
					<div class="status">
						<div class="status_inner">

							<?=$section_str['status_comment'];?>
							
						</div>						
					</div>
					<?
						}
					?>
				</div>
				<!-- //업무승인 상태 -->
				
				<div class="work_btn">
		<?
			if ($data['work_status'] != 'WS90')
			{
				if ($code_ubstory == 'Y' && $code_level <= '11')
				{
					$button_ws90_value = '업무완료';
					$button_ws90_key   = 'WS90';
		?>
						<span class="btn03"><input type="button" value="강제업무완료" onclick="check_work_status('WS90')" /></span>
		<?
				}

				if ($section_str['button_ws02_value'] != '') {
		?>
						<span class="btn03"><input type="button" value="<?=$section_str['button_ws02_value'];?>" onclick="check_work_status('<?=$section_str['button_ws02_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws20_value'] != '') {
		?>
						<span class="btn03"><input type="button" value="<?=$section_str['button_ws20_value'];?>" onclick="check_work_status('<?=$section_str['button_ws20_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws20_02_value'] != '') {
		?>
						<span class="btn03"><input type="button" value="<?=$section_str['button_ws20_02_value'];?>" onclick="check_work_status('<?=$section_str['button_ws20_02_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws30_value'] != '') {
		?>
						<span class="btn03"><input type="button" value="<?=$section_str['button_ws30_value'];?>" onclick="check_work_status('<?=$section_str['button_ws30_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws30_02_value'] != '') {
		?>
						<span class="btn03"><input type="button" value="<?=$section_str['button_ws30_02_value'];?>" onclick="check_work_status('<?=$section_str['button_ws30_02_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws90_value'] != '') {
		?>
						<span class="btn03"><input type="button" value="<?=$section_str['button_ws90_value'];?>" onclick="check_work_status('<?=$section_str['button_ws90_key'];?>')" /></span>
		<?
				}
			}
			if ($section_str['button_ws70_value'] != '') {
		?>
					<span class="btn03"><input type="button" value="<?=$section_str['button_ws70_value'];?>" onclick="check_work_status('<?=$section_str['button_ws70_key'];?>')" /></span>
		<?
			}
			if ($section_str['button_ws80_value'] != '') {
		?>
					<span class="btn03"><input type="button" value="<?=$section_str['button_ws80_value'];?>" onclick="check_work_status('<?=$section_str['button_ws80_key'];?>')" /></span>
		<?
			}
		?>
					<!--<?=$btn_modify;?>-->
					<?=$btn_delete;?>
				</div>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 업무보고서
	$report_where = " and wr.wi_idx = '" . $wi_idx . "'";
	$report_list = work_report_data('page', $report_where);

	if ($data['work_type'] == 'WT04' && $report_list['total_num'] == 0) // 업무알림일 경우 업무보고 나오지 않음
	{ }
	else
	{
		if ($data['work_type'] == 'WT04')
		{
			$work_report_yn  = 'N';
		}
?>
				<!-- 업무보고 -->
				<div id="task_report" class="report_box1">
					<div class="report_top">
						<p class="count"><a href="javascript:void(0)" onclick="report_view()" id="report_gate" title="업무보고목록" class="ui-link btn_i_minus"><span class="empty"></span> 업무보고 <span id="report_total_value">[<?=number_format($report_list['total_num']);?>]</span></a>
							
			<?
				if ($read_report > 0)
				{
					echo '
					<span class="today_num" title="읽을 업무보고"><em>', number_format($read_report), '</em></span>';
				}
			?>
							
						</p>
	<?
		if ($work_report_yn == 'Y')
		{
	?>
						<!-- div class="new" id="report_new_btn"><img src="/bizstory/images/btn/btn_report.png" alt="업무보고 쓰기" onclick="report_insert_form('open', 'insert')" /></div -->
						<div class="new" id="report_new_btn"><span class="btn_report" onclick="report_insert_form('open', 'insert')">업무보고 쓰기</span></div>
	<?
		}
	?>
					</div>

					<div id="new_report" title="업무보고쓰기"></div>

					<form id="reportlistform" name="reportlistform" method="post">
					<input type="hidden" id="reportlist_sub_type" name="sub_type" value="" />
					<input type="hidden" id="reportlist_wi_idx"   name="wi_idx"   value="<?=$wi_idx;?>" />
					<input type="hidden" id="reportlist_wr_idx"   name="wr_idx"   value="" />
					<?=$form_page;?>
					<div id="report_list_data">
						<!--
						<div class="report">
							<div class="report_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-03-07 10:42:34</span>
							</div>

							<div class="report_wrap">
								<div class="report_data">
									<p>안산교육지원청(웹와치)_축!!! 합격입니다^^ </p>
									<div class="file">
										<ul>
											<li>
												WA인증마크_정밀심사_보고서_(경기도안산교육지원청)_2차_합격.pdf (2.0 MB)
											</li>
											<li>
												WA인증마크2013_standard.png (8.4 KB)
											</li>
											<li>
												WA인증마크_부착_주의사항.html (1.9 KB)
											</li>
											<li>
												WA인증마크2013.ai (104.2 KB)
											</li>
											<li>
												WA인증마크2013_simple.png (4.1 KB)
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="report">
							<div class="report_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-02-20 13:22:40		</span>
							</div>

							<div class="report_wrap">
								<div class="report_data">
									<p>웹와치WA인증마크(안산교육지원청)</p>
									<p>- 유예기간 : 2월18일 부터 2월 28일까지 (2주간)</p>
									<p>유예기간안에 수정/보완 완료 후 재심사신청</p>
									<div class="file"></div>
								</div>
							</div>

						</div>
						<div class="report">
							<div class="report_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-02-19 15:49:14		</span>
							</div>

							<div class="report_wrap">
								<div class="report_data">
									안산교육지원청 정밀심사 1차결과			<div class="file">
										<ul>
											<li>
												WA인증마크_가이드라인_V2.0.pdf (2.2 MB)
											</li>
											<li>
												WA인증마크_정밀심사_보고서_(경기도안산교육지원청)_1차_수정보완필요.pdf (2.1 MB)
											</li>
										</ul>
									</div>
								</div>
							</div>

						</div>
						-->
					</div>					
					</form>
				</div>
				<!-- //업무보고 -->
<?
	}
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	$comment_where = " and wc.wi_idx = '" . $wi_idx . "'";
	$comment_list = work_comment_data('page', $comment_where);
?>
				<!-- 코멘트 -->
				<div id="task_comment" class="comment_box1">
					<div class="comment_top">
						<p class="count"><a href="javascript:void(0)" onclick="comment_view()" id="comment_gate" title="코멘트목록" class="ui-link btn_i_minus"><span class="empty"></span> 코멘트 <span id="comment_total_value">[<?=number_format($comment_list['total_num']);?>]</span></a>
			<?
				if ($read_comment > 0)
				{
					echo '
					<span class="today_num" title="읽을 댓글"><em>', number_format($read_comment), '</em></span>';
				}
			?>
							
						</p>
						<!-- div class="new" id="comment_new_btn"><img src="/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')"></div -->
						<div class="new" id="comment_new_btn"><span class="btn_comment" onclick="comment_insert_form('open')">코멘트 쓰기</span></div>
					</div>

					<div id="new_comment" title="코멘트쓰기"></div>

					<form id="commentlistform" name="commentlistform" method="post" action="/bizstory/m/work_my_view.php">
					<input type="hidden" id="commentlist_sub_type"  name="sub_type"  value="" />
					<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$code_part;?>" />
					<input type="hidden" id="commentlist_wi_idx"    name="wi_idx"    value="<?=$wi_idx;?>" />
					<input type="hidden" id="commentlist_wc_idx"    name="wc_idx"    value="" />
					<?=$form_page;?>
					<div id="comment_list_data">
						<!--
						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/2/member_2_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">서경원</span>
								<span class="date">2013-02-20 18:16:19</span>
							</div>

							<div class="comment_wrap">
								<div class="comment_data">의정부건은 이의신청 해주세요</div>
							</div>
						</div>

						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/2/member_2_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">서경원</span>
								<span class="date">2013-01-27 21:27:05</span>
							</div>
							<div class="comment_wrap">
								<div class="comment_data">
									안양과천, 안산, 의정부 총3개 까지만진행 3개면 입찰에는 충분함
								</div>
							</div>
						</div>
						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-01-25 14:14:27</span>
							</div>
							<div class="comment_wrap">
								<div class="comment_data">
									<p>두 기관에 모두 공지 완료</p><p>_신청기간은 다음주 목(31일)요일까지 </p>
								</div>
							</div>
						</div>
						<div class="comment">
							<div class="comment_info">
								<span class="mem"><img class="photo" src="/data/company/1/member/15/member_15_1.png" alt="" height="26px" width="26px"></span>
								<span class="user">김나영</span>
								<span class="date">2013-01-25 13:43:05</span>
							</div>

							<div class="comment_wrap">
								<div class="comment_data">
									- 모든 기관에 다 알릴까요? 안양과천, 의정부교육지원청
								</div>
							</div>
						</div>
						-->
					</div>
					</form>
				</div>
				<!-- //코멘트 -->

			</div>

		</div>
	</div>
	

<script type="text/javascript">
//<![CDATA[
	$(function() {
		/*
		$(".work_inner tr td").find("span").each(function(idx, item) {
			if ($(this).hasClass("btn_state")) {
				$(this).removeClass("btn_state");
			}
		});
		*/
		/*
		$(".btn_state").each(function(idx, item) {
			$(this).removeClass("btn_state");
		});
		*/
	});
	
		
//------------------------------------ 삭제하기
	function check_delete(idx)
	{
		var message = [];

		message.push('<li><div class="user_edit">선택하신 데이터를 삭제하시겠습니까?</div></li>');
		message.push('<li><div class="popup_button"><a href="javascript:" onclick="check_code_data(\'delete\', \'\', ' + idx + ',\'\')" class="md-close btn07">확인</a>');
		message.push('<a href="javascript:" onclick="" class="md-close btn07">닫기</a></div>');
		
		confirmMessage("데이터 삭제", message.join(''));
		/*
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			check_code_data('delete', '', idx, '');
			view_close();
		}
		*/
		
	}

//------------------------------------ 목록 처리
	function check_code_data(sub_type, sub_action, idx, post_value)
	{
		$('#list_sub_type').val(sub_type)
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'json', url: link_ok,
			data: $('#listform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					if (msg.error_string != '')
					{
						alertMsg(msg.error_string);
					}
					list_data();
				}
				else
				{
					alertMsg(msg.error_string);
				}
			}
		});
	}
	
// 담당자, 기한 저장
	function form_workstatus(sub_type)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';
		
		switch(sub_type) {
			
			//승인요청 취소
			case 'status20_02':
				chk_value = $('#status_contents').val(); // 취소사유
				chk_title = $('#status_contents').attr('title');
				chk_msg = check_input_value(chk_value);
				if (chk_msg == 'No')
				{
					chk_total = chk_total + chk_title + '<br />';
					action_num++;
				}
				break;
			//승인요청 반려
			case 'status20_70':
				chk_value = $('#status_contents').val(); // 반려사유
				chk_title = $('#status_contents').attr('title');
				chk_msg = check_input_value(chk_value);
				if (chk_msg == 'No')
				{
					chk_total = chk_total + chk_title + '<br />';
					action_num++;
				}
				break;
				
			case 'status20_90':
			
				break;
			//완료요청 취소
			case 'status30_02':
				chk_value = $('#status_contents').val(); // 취소사유
				chk_title = $('#status_contents').attr('title');
				chk_msg = check_input_value(chk_value);
				if (chk_msg == 'No')
				{
					chk_total = chk_total + chk_title + '<br />';
					action_num++;
				}
				break;
				
			//요청완료 반려
			case 'status30_70':
				chk_value = $('#status_contents').val(); // 반려사유
				chk_title = $('#status_contents').attr('title');
				chk_msg = check_input_value(chk_value);
				if (chk_msg == 'No')
				{
					chk_total = chk_total + chk_title + '<br />';
					action_num++;
				}
				break;
				
			//압므 반려
			case 'status70':
			case 'status80':
				chk_value = $('#status_contents').val(); // 반려사유
				chk_title = $('#status_contents').attr('title');
				chk_msg = check_input_value(chk_value);
				if (chk_msg == 'No')
				{
					chk_total = chk_total + chk_title + '<br />';
					action_num++;
				}
				break;
				
			case 'status30_90':
			
				break;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/include/select_work_status_ok.php',
				data: {
					comp_idx : '<?=$code_comp?>',
					part_idx : '<?=$code_part?>',
					wi_idx : '<?=$wi_idx?>',
					sub_type : sub_type
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						//classie.remove( modal, 'md-show' );
						location.reload();
					}
					else alertMsg(msg.error_string);
				}
			});
		}
		else alertMsg(chk_total);
		return false;
	}
	
//------------------------------------ select 박스의 selected를 설정
	function selected(val1, val2)
	{
		if (val1 != "" || val2 != "")
		{
			if (val1 == val2) return ' selected="selected"';
		}
		else return '';
	}

//-------------------------------------- array로 선언된 값을 select
	function code_select(data_list, chk_name, chk_id, chk_value, chk_title, chk_str, data_valuealertMsg, chk_classalertMsg, chk_scriptalertMsg)
	{
		var str = [];
		total_num = data_list.length;
		str.push('<select name="' + chk_name + '" id="' + chk_id + '" title="' + chk_title + '" ' + chk_class + ' ' + chk_script + '>');
		if (chk_str != '')
		{
			str.push('<option value="">' + chk_str + '</option>');
		}
		if (total_num > 0)
		{
			$(data_list).each(function(k, v) {
			
				if (data_value == "value")
				{
					str.push('<option value="' + v + '"' + selected(chk_value, v) + '>' + v + '</option>');
				}
				else
				{
					str.push('<option value="' + k + '"' + selected(chk_value, k) + '>' + v + '</option>');
				}
			});
		}
		str.push('</select>');
		return str.join('');
	}

//------------------------------------ 상태변경
	function check_work_status(idx)
	{
		var action_url = '';
		var action_str = '';
		var title = "";
		var message = [];
		var callback = null;
		
		
		switch(idx) {
			// 업무대기중 -> 업무진행중
			case 'WS02':
			
				//action_str = '02';
				title = "업무를 진행하시겠습니까?";
				
				callback = function(json) {
					if (json.result_code == '0') {
						var data = json.data;
						var deadline_date = json.deadline_date;
						var html = [];
	
						if (data != null) {
							// 담당자가 없을 경우
							if (data.charge_idx != '') {
								message.push('<li><label for="status_charge_idx">담당자</label><div id="charge_view"></div></li>');
							}
							
							// 기한이 없을 경우
							if (data.deadline_date == '') {
								message.push('<li><label for="status_deadline_date1">기한</label>');
								message.push('<div><ul>');
								message.push('<li>');
								message.push('<select name="deadline_date1" id="post_deadline_date1" onchange="deadline_date_view(this.value, \'deadline_date_view\')">');
								
								$(deadline_list.date).each(function(idx, value) {
									message.push('<option value="' + value + '">' + value + ' ' + deadline_list.week[idx] + '</option>');
								});
								
								message.push('<option value="-">-----------------</option>');
								message.push('<option value="select">직접선택하기</option>');
								message.push('</select>');
								message.push('</li>');
								message.push('<li>');
								message.push('<span id="deadline_date_view" class="none">');
								message.push('<input type="text" name="deadline_date2" id="post_deadline_date2" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date('Y-m-d');?>" />');
								message.push('</span>');
								message.push('</li>');
								message.push('<li>');
								message.push(code_select($set_work_deadline_txt, 'deadline_str1', 'post_deadline_str1', '', '덧붙이기(선택사항)', '덧붙이기(선택사항)', '', '', 'onchange="deadline_str_view(this.value, \'deadline_str_view\')"') );
								message.push('</li>');
								message.push('<li>');
								message.push('<span id="deadline_str_view" class="none">');
								message.push('<input type="text" name="deadline_str2" id="post_deadline_str2" class="type_text" title="직접입력하세요." size="20" />');
								message.push('</span>');
								message.push('</li>');
								
								message.push('</ul></div>');
								message.push('</li>');
							}
						}
						
						message.push('<li><div class="user_edit">대기인 업무를 진행시키려면 담당자와 기한을 설정해야 합니다.</div></li>');
						message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status02\')" class="md-close btn04_2">확인</a>');
						message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
						
						confirmMessage(title, message.join(''));
						
						charge_member_list('<?=$data['work_type'];?>', '<?=$data['wi_idx'];?>');
					}
				};
			
			break;
			
			// 업무진행중 -> 업무완료
			case 'WS90': 
				action_str = '90';
				
				title = "업무를 완료하시겠습니까?";
				message.push('<li><div class="user_edit">업무가 완료되면 등록된 보고내용은 수정할 수 없습니다.<br />업무가 완료되는 즉시, 마스터와 업무 등록자에게 업무완료 사실을 알력드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status90\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
		
			// 업무진행중 -> 승인요청
			case 'WS20': 
				action_str = '20';
				
				title = "승인요청을 하시겠습니까?";
				message.push('<li><div class="user_edit">업무 승인요청을 하시면 등록된 보고내용을 수정할 수 없습니다.<br />승인요청하는 즉시, 승인완료자에게 승인요청 사실을 알려드립니다.<br /></div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status20\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
			
			// 승인대기중 -> 승인
			case 'WS20_WS90':
				action_str = '20_90';
				
				title = "해당업무를 승인하시겠습니까?";
				message.push('<li><div class="user_edit">업무가 완료되면 등록된 보고내용은 수정할 수 없습니다.<br />업무가 완료되는 즉시, 마스터와 업무 등록자에게 업무완료 사실을 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status20_90\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
				
			// 승인대기중 -> 승인요청반려
			case 'WS20_WS70':
				action_str = '20_70';
				
				title = "승인요청을 반려하시겠습니까?";
				message.push('<li><span class="user">반려사유</span></li>');
				message.push('<li><span class="date">');
				message.push('<input type="text" name="status_contents" id="status_contents" class="type_text" title="반려사유를 입력하세요." size="40" />');
				message.push('</span></li>');
				message.push('<li><div class="user_edit">승인요청을 취소하면, 해당 업무의 담당자에게 취소사유를 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status20_70\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
			
			// 승인대기중 -> 승인요청취소
			case 'WS20_WS02':
				action_str = '20_02';
				
				title = "승인요청을 취소하시겠습니까?";
				message.push('<li><span class="user">취소사유</span></li>');
				message.push('<li><span class="date">');
				message.push('<input type="text" name="status_contents" id="status_contents" class="type_text" title="취소사유를 입력하세요." size="40" />');
				message.push('</span></li>');
				message.push('<li><div class="user_edit">승인요청을 취소하면, 해당 업무의 담당자에게 취소사유를 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status20_02\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');

				break;
			
			// 업무진행중 -> 완료요청
			case 'WS30': 
				action_str = '30';
				
				title = "완료요청을 하시겠습니까?";
				message.push('<li><div class="user_edit">업무 요청완료를 하시면 등록된 보고내용을 수정할 수 없습니다.<br />요청완료하는 즉시, 등록자에게 요청완료 사실을 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status30\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;

			// 요청대기 -> 업무완료
			case 'WS30_WS90': 
				action_str = '30_90';

				title = "해당업무를 완료하시겠습니까?";
				message.push('<li><div class="user_edit">업무가 완료되면 등록된 보고내용은 수정할 수 없습니다.<br />업무가 완료되는 즉시, 마스터와 업무 등록자에게 업무완료 사실을 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status30_90\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
				
			// 요청대기 -> 요청완료반려
			case 'WS30_WS70':
				action_str = '30_70';
				
				title = "요청완료를 반려하시겠습니까?";
				message.push('<li><span class="user">반려사유</span></li>');
				message.push('<li><span class="date">');
				message.push('<input type="text" name="status_contents" id="status_contents" class="type_text" title="반려사유를 입력하세요." size="40" />');
				message.push('</span></li>');
				message.push('<li><div class="user_edit">요청완료를 반려하면, 해당 업무의 담당자에게 반려사유를 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status30_70\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
				
			// 요청대기 -> 완료요청취소
			case 'WS30_WS02':
				action_str = '30_02';
				
				title = "완료요청을 취소하시겠습니까?";
				message.push('<li><span class="user">취소사유</span></li>');
				message.push('<li><span class="date">');
				message.push('<input type="text" name="status_contents" id="status_contents" class="type_text" title="취소사유를 입력하세요." size="40" />');
				message.push('</span></li>');
				message.push('<li><div class="user_edit">완료요청을 취소하면, 해당 업무의 담당자에게 취소사유를 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status30_02\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
				
			// 업무진행중 -> 업무보류
			case 'WS80':
				action_str = '80';
				
				title = "업무를 보류시키겠습니까?";
				message.push('<li><span class="user">보류사유</span></li>');
				message.push('<li><span class="date">');
				message.push('<input type="text" name="status_contents" id="status_contents" class="type_text" title="보류사유를 입력하세요." size="40" />');
				message.push('</span></li>');
				message.push('<li><div class="user_edit">보류된 업무는 다시 업루를 진행 시킬 수 있습니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status80\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
				
			// 업무보류 -> 업무진행
			case 'WS80_02':
				action_str = '80_02';
			
				//action_str = '02';
				title = "업무를 다시 진행하시겠습니까?";
				
				callback = function(json) {
					if (json.result_code == '0') {
						var data = json.data;
						var deadline_date = json.deadline_date;
						var html = [];
	
						if (data != null) {
							message.push('<li><label for="status_charge_idx">담당자</label><div id="charge_view"></div></li>');
							
							// 기한이 없을 경우
							
							message.push('<li><label for="status_deadline_date1">기한</label>');
							message.push('<div><ul>');
							
							if (data.deadline_date == '') {
								message.push('<li>');
								message.push('<select name="deadline_date1" id="post_deadline_date1" onchange="deadline_date_view(this.value, \'deadline_date_view\')">');
								
								$(deadline_list.date).each(function(idx, value) {
									message.push('<option value="' + value + '">' + value + ' ' + deadline_list.week[idx] + '</option>');
								});
								
								message.push('<option value="-">-----------------</option>');
								message.push('<option value="select">직접선택하기</option>');
								message.push('</select>');
								message.push('</li>');
								message.push('<li>');
								message.push('<span id="deadline_date_view" class="none">');
								message.push('<input type="text" name="deadline_date2" id="post_deadline_date2" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date('Y-m-d');?>" />');
								message.push('</span>');
								message.push('</li>');
								message.push('<li>');
								message.push(code_select($set_work_deadline_txt, 'deadline_str1', 'post_deadline_str1', '', '덧붙이기(선택사항)', '덧붙이기(선택사항)', '', '', 'onchange="deadline_str_view(this.value, \'deadline_str_view\')"') );
								message.push('</li>');
								message.push('<li>');
								message.push('<span id="deadline_str_view" class="none">');
								message.push('<input type="text" name="deadline_str2" id="post_deadline_str2" class="type_text" title="직접입력하세요." size="20" />');
								message.push('</span>');
								message.push('</li>');
							} else {
								/**********
								date_replace($data['deadline_date'], 'Y-m-d'); 형식으로 변경할것
								*********/
								message.push('<li><input type="text" name="deadline_date1" id="post_deadline_date1" class="type_text datepicker" title="기한을 입력하세요." size="10" value="' + deadline_date + '" /></li>');
								message.push('<li><input type="text" name="deadline_str1" id="post_deadline_str1" class="type_text" title="직접입력하세요." size="20" value="' + data.deadline_str + '" /></li>');
							}
							message.push('</ul></div>');
							message.push('</li>');
						
						}
						
						message.push('<li><div class="user_edit">대기인 업무를 진행시키려면 담당자와 기한을 설정해야 합니다.</div></li>');
						message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status02\')" class="md-close btn04_2">확인</a>');
						message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
						
						confirmMessage(title, message.join(''));
						
						charge_member_list('<?=$data['work_type'];?>', '<?=$data['wi_idx'];?>');
					}
				};
			
				break;
				
			// 업무완료 -> 업무반려
			case 'WS70':
				action_str = '70';
				
				title = "업무를 반려하시겠습니까?";
				message.push('<li><span class="user">반려사유</span></li>');
				message.push('<li><span class="date">');
				message.push('<input type="text" name="status_contents" id="status_contents" class="type_text" title="반려사유를 입력하세요." size="40" />');
				message.push('</span></li>');
				message.push('<li><div class="user_edit">업무를 반려하면, 해당 업무의 담당자에게 반려사유를 알려드립니다.</div></li>');
				message.push('<li class="pt10"><div class="popup_button"><a href="javascript:" onclick="form_workstatus(\'status70\')" class="md-close btn04_2">확인</a>');
				message.push('<a href="javascript:" onclick="" class="md-close btn08_2">닫기</a></div>');
				
				break;
		}
		//action_url = '/bizstory/include/select_work_status' + action_str + '.php';
		
		
		
		if (callback == null) {
			confirmMessage(title, message.join(''));
		} else {
			
			
			$.ajax({
				type: "post", 
				dataType: 'json',
				data: {
					comp_idx : '<?=$code_comp?>',
					part_idx : '<?=$code_part?>',
					wi_idx : '<?=$wi_idx?>',
					idx : idx
					}, 
				url: "./process/ajax_work_status.php",
				success: callback,
				complete: function(){
					try {
						myScroll.refresh();
					} catch(e) {}
					
				}
			});
			
		}
		
		//return false;
	}

//------------------------------------ 업무보고서 관련
	var report_list = './work_view_report_list.php';
	var report_form = './work_view_report_form.php';
	var report_ok   = '/bizstory/work/work_view_report_ok.php';

//------------------------------------ 업무보고서 등록
	function report_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_report").slideUp("slow");
			$("#new_report").html('');
			$('#report_new_btn').css({'display':'block'});
			myScroll.refresh();
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: report_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$("#new_report").html(msg);
					$('#report_new_btn').css({'display':'none'});
					//$("#new_report").slideUp("slow");
					$("#new_report").slideDown("slow");
					
					myScroll.refresh();
				},
	    		complete: function() {
	    			//myScroll.refresh();
	    		}
			});
		}
	}
	
// 담당자목록
	function charge_member_list(work_type, wi_idx)
	{
		var apply_idx     = $("#post_apply_idx").val();
		var charge_idx    = $("#post_charge_idx").val();
		var old_work_type = '<?=$data['work_type'];?>';

		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/select_charge_member.php',
			data: {'old_work_type':old_work_type, 'work_type':work_type, 'charge_idx':charge_idx, 'apply_idx':apply_idx, 'wi_idx':wi_idx},
			success: function(msg) {
				$("#charge_view").html(msg);
			}
		});
	}

//------------------------------------ 업무보고서 목록
	function report_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: report_list,
			data: $('#reportlistform').serialize(),
			success: function(msg) {
				$('#report_list_data').html(msg);
			},
    		complete: function() {
    			myScroll.refresh();
    		}
		});
	}

//------------------------------------ 업무보고서 열기/닫기
	var report_chk_val = 'close';
	function report_view()
	{
		if (report_chk_val == 'close')
		{
			report_chk_val = 'open';
			$('#report_list_data').html('');
			$("#report_gate").removeClass('btn_i_minus');
			$("#report_gate").addClass('btn_i_plus');
		}
		else
		{
			report_chk_val = 'close';
			report_list_data();
			$("#report_gate").removeClass('btn_i_plus');
			$("#report_gate").addClass('btn_i_minus');
		}
	}
	report_view();
//------------------------------------ 댓글 관련
	var comment_list = './work_view_comment_list.php';
	var comment_form = './work_view_comment_form.php';
	var comment_ok   = '/bizstory/work/work_view_comment_ok.php';

//------------------------------------ 댓글 등록
	function comment_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_comment").slideUp("slow");
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					//$("#new_comment").slideUp("slow");
					$("#new_comment").slideDown("slow");
					$("#new_comment").html(msg);
					myScroll.refresh();
				},
	    		complete: function() {
	    			myScroll.refresh();
	    		}
			});
		}
	}

//------------------------------------ 댓글 목록
	function comment_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: comment_list,
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
			},
    		complete: function() {
    			myScroll.refresh();
    		}
		});
	}

//------------------------------------ 댓글 열기/닫기
	var comment_chk_val = 'close';
	function comment_view()
	{
		if (comment_chk_val == 'close')
		{
			comment_chk_val = 'open';
			$('#comment_list_data').html('');
			$("#comment_gate").removeClass('btn_i_minus');
			$("#comment_gate").addClass('btn_i_plus');
		}
		else
		{
			comment_chk_val = 'close';
			comment_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}
	comment_view();

//------------------------------------ 최근업데이트 관련
	var history_list = '/bizstory/work/work_view_history_list.php';

//------------------------------------ 최근업데이트 목록
	function history_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: history_list,
			data: $('#historylistform').serialize(),
			success: function(msg) {
				$('#history_list_data').html(msg);
			},
    		complete: function() {
    			myScroll.refresh();
    		}
		});
	}

//------------------------------------ 최근업데이트 열기/닫기
	var history_chk_val = 'close';
	function history_view()
	{
		if (history_chk_val == 'close')
		{
			history_chk_val = 'open';
			$('#history_list_data').html('');
			$("#history_gate").removeClass('btn_i_minus');
			$("#history_gate").addClass('btn_i_plus');
		}
		else
		{
			history_chk_val = 'close';
			history_list_data();
			$("#history_gate").removeClass('btn_i_plus');
			$("#history_gate").addClass('btn_i_minus');
		}
	}
	history_view();
//]]>
</script>
<?
	}

	include "./footer.php";
?>