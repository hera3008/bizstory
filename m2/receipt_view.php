<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./process/no_direct.php";
	include "./header.php";
	
	$send_fmode = "receipt";
	$send_smode = "receipt";
	

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$receipt_data      = $receipt_info->receipt_info_view();
	$file_list = $receipt_info->receipt_file();
	$history_list = $receipt_info->receipt_status_history();
	
	$list_data = receipt_list_data2($ri_idx, $receipt_data);
	//	exit;
		//echo "data 	:".print_r($receipt_data)."</br>";
	//	echo "file_list 	;".print_r($file_list)."</br>";
	
// 등록된 하위값
	$where = " and rid.ri_idx = '" . $ri_idx . "'";
	$data = receipt_info_detail_data('page', $where);

	$detail_data['end_pre_date']  = date('Y-m-d');
	$detail_data['receipt_class'] = $receipt_data['receipt_class'];
	$detail_data['mem_idx']       = $receipt_data['charge_mem_idx'];
	
	$link_ok           = $local_dir . "/bizstory/receipt/receipt_ok.php";        // 저장
		
?>

<!-- <script type="text/javascript" src="<?=$mobile_dir;?>/js/_myScroll.js" charset="utf-8"></script> -->
<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article class="mt_4">
			<h2>접수목록</h2>
		</article>
		<div id="wrapper" class="receipt">
			<div id="scroller">
				<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type" id="view_sub_type" />
				<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$receipt_data['part_idx'];?>" />
				<input type="hidden" name="ri_idx"   id="view_ri_idx"  value="<?=$ri_idx;?>" />
				<input type="hidden" name="rid_idx"  id="view_rid_idx" value="" />
				<input type="hidden" name="rid_type" id="view_rid_type" value="" />
				
				<div class="work_area">
					<div class="title">
						<strong class="s_title"><?=$receipt_data['subject'];?></strong>
						
						<strong class="regist"><?=$receipt_data['writer'];?><span class="data">(<a class="call_me" href="tel:<?=$receipt_data['tel_num'];?>"><?=$receipt_data['tel_num'];?></a>) <?=$receipt_data['reg_date'];?> </span><strong>
					</div>
					<div class="work_inner">
						<table border="1" cellspacing="0" summary="업무내용" class="table02">
							<tr>
								<th class="w100">거래처명</th>
								<td><?=$receipt_data['client_name'];?></td>
							</tr>
							<tr>
								<th>담당자</th>
								<td>
									<?=$receipt_data['charge_mobile_str'];?>									
								</td>
							</tr>
							<tr>
								<th>접수분류</th>
								<td>
				<?
								$receipt_class = $receipt_data['receipt_class_str'];
								foreach ($receipt_class as $k => $v)
								{
									if ($k == 1) echo $v;
									else echo ' &gt; ', $v;
								}
				?>
								</td>
							</tr>
							<!--tr>
								<th>분류</th>
								<td>일반업무</td>
							</tr-->
							<tr>
								<td colspan="2" class="remark_area">
									<?=$receipt_data['remark'];?>
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
				
										$btn_str = preview_file($comp_receipt_dir, $file_data['rf_idx'], 'receipt');
				?>
									<li>
									<?=$btn_str;?>
									<a href="<?=$local_dir;?>/bizstory/receipt/receipt_download.php?rf_idx=<?=$file_data['rf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
									</li>
				<?
									}
								}
								$btn_img = preview_images($ri_idx, 'receipt');
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

				<!-- 접수등록 상태 -->
				<!-- 접수상태, 내역
				<div class="status_box">
					<div id="receipt_section" class="receipt_section"></div>
					<div class="status_info" id="status_history_info"></div>
				</div> -->
				</form>
				<!-- //접수등록 상세업무등록 상태 -->

<?

// 값이 한개도 없을 경우 단일
	if ($data['total_num'] == 0 && $rid_type == '')
	{
		include "./receipt_view_section_single.php";

		include "./receipt_view_section_plural.php";
	}
	else
	{
		if ($sub_type == '')
		{
		// 다수값이 없으면 단일로 인식
			$plural_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '2'";
			$plural_list = receipt_info_detail_data('page', $plural_where);
			if ($plural_list['total_num'] == 0)
			{
				$sub_type = 'singular_view';

				$singular_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '1'";
				$singular_data = receipt_info_detail_data('view', $singular_where);

				$rid_idx = $singular_data['rid_idx'];
			}
			else
			{
				$sub_type = 'plural_list';
			}
		}

		if ($sub_type == 'plural_form') // 다수접수 등록/수정
		{
			include "./receipt_view_section_plural.php";
		}
		else if ($sub_type == 'plural_list') // 다수접수 목록
		{
			include "./receipt_view_section_plural.php";
		}
		else if ($sub_type == 'singular_view') // 단일 보기
		{
			include "./receipt_view_section_single.php";
		}
		else if ($sub_type == 'singular_form') // 단일 수정
		{
			include "./receipt_view_section_single.php";
		}
	}
?>

				<!-- 접수등록 상태 -->
				<!--
				<div class="status_section" id="receipt_section">
					<div class="title">
						<div class="dp_b">
							<span class="mb4 dp_ib">
								<span class="fw700 c_blown title_st">접수분류</span> 
					<?
						$receipt_class = $receipt_data['receipt_class_str'];
						foreach ($receipt_class as $k => $v)
						{
							if ($k == 1) echo $v;
							else echo ' &gt; ', $v;
						}
					?>
							</span>
						</div>
						
						<div class="dp_b">
							<span class="mb4 dp_ib">
								<span class="fw700 c_blown title_st">담당자</span> 
								<select id="detail_mem_idx" name="detail_mem_idx" title="담당자 선택">
									<option value="">담당자 선택</option>
								</select>
							</span>
						</div>
						
						<div class="dp_b">
							<span class="v dp_ib">
								<span class="fw700 c_blown title_st">완료예정일</span>
								<input type="text" id="detail_end_pre_date" name="detail_end_pre_date" class="type_text date" />
								<input id="detail_end_pre_date" name="detail_end_pre_date" class="type_text datepicker hasDatepicker" title="완료예정일 입력하세요." size="10" value="2013-10-04" type="text" /><img title="..." alt="..." src="/bizstory/images/btn/calendar.png" class="ui-datepicker-trigger">
							</span>
						</div>
					</div>
					<div class="ico_mem">
						<span><span class="fw700 c_red">접수등록</span> [장인록 : 2013-07-04 11:08:37]</span>
					</div>
				</div>
				-->
				<!-- //접수등록 상태 -->

				<!-- 접수승인 상태 -->
				<!--
				<div class="status_section" id="receipt_section">
					<div class="title">
						<span><span class="fw700 c_blown">접수분류</span> : 웹사이트관련</span>
						
						<span><span class="fw700 c_blown">담당자</span> : 지인영</span>
						
						<span><span class="fw700 c_blown">완료예정일</span> : 2013-07-04</span>
					</div>
					<div class="ico_mem">
						<span><span class="fw700 c_red">접수등록</span> [장인록 : 2013-07-04 11:08:37]</span>
					</div>
					<div class="mem_regist">
						<div class="mem_user">
							<span><img src="/data/company/1/member/86/member_86_1.jpg" alt="지인영" /></span>
							<span class="user"><a class="name_ui">지인영</a></span>
						</div>
						<ul>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_orange fw700">접수승인</span> [지인영 : 2013-07-04 11:13:36]
							</li>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_green fw700">작업진행</span> [지인영 : 2013-07-04 11:13:36]
							</li>
						</ul>
					</div>
				</div>
				-->
				<!-- //접수승인 상태 -->

				<!-- 작업진행 상태 -->
				<!--
				<div class="status_section" id="receipt_section">
					<div class="title">
						<span><span class="fw700 c_blown">접수분류</span> : 웹사이트관련</span>
						
						<span><span class="fw700 c_blown">담당자</span> : 지인영</span>
						
						<span><span class="fw700 c_blown">완료예정일</span> : 2013-07-04</span>

						<span class="btn08"><input value="수정" onclick="singular_modify('37153')" type="button"></span>
					</div>
					<div class="title">
						<span><span class="fw700 c_blown">접수상태</span></span>
						
						<span>
							<select id="detail_receipt_status_37153" name="detail_receipt_status_37153" title="접수상태 선택" onchange="receipt_status_end(this.value, '37153')">
								<option value="">접수상태 선택</option>
								<option value="RS90">완료처리</option>
								<option value="RS80">보류처리</option>
								<option value="RS60">취소처리</option>
							</select>
							<a href="javascript:void(0)" onclick="receipt_status_change('37153');" class="btn05 c_white">적용</a>
						</span>
					</div>
					<div class="ico_mem">
						<span><span class="fw700 c_red">접수등록</span> [장인록 : 2013-07-04 11:08:37]</span>
					</div>
					<div class="mem_regist">
						<div class="mem_user">
							<span><img src="/data/company/1/member/86/member_86_1.jpg" alt="지인영" /></span>
							<span class="user"><a class="name_ui">지인영</a></span>
						</div>
						<ul>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_orange fw700">접수승인</span> [지인영 : 2013-07-04 11:13:36]
							</li>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_green fw700">작업진행</span> [지인영 : 2013-07-04 11:13:36]
							</li>
						</ul>
					</div>
				</div>
				-->
				<!-- //작업진행 상태 -->

				<!-- 완료처리 상태 -->
				<!--
				<div class="status_section" id="receipt_section">
					<div class="title">
						<span><span class="fw700 c_blown">접수분류</span> : 웹사이트관련</span>
						
						<span><span class="fw700 c_blown">담당자</span> : 지인영</span>
						
						<span><span class="fw700 c_blown">완료예정일</span> : 2013-07-04</span>

						<span class="btn08"><input value="수정" onclick="singular_modify('37153')" type="button"></span>
					</div>

					<div class="title">
						<span><span class="fw700 c_blown">접수상태</span></span>
						
						<span>
							<select id="detail_receipt_status_37153" name="detail_receipt_status_37153" title="접수상태 선택" onchange="receipt_status_end(this.value, '37153')">
								<option value="">접수상태 선택</option>
								<option value="RS90">완료처리</option>
								<option value="RS80">보류처리</option>
								<option value="RS60">취소처리</option>
							</select>
							<a href="javascript:void(0)" onclick="receipt_status_change('37153');" class="btn05 c_white">적용</a>
						</span>
					</div>
					
					<div class="plural_view" style="display: block;">
						<div class="info_text">
							<ul>
								<li>담당자의 [완료처리] 내역은 [보고서] 완료내역에 출력됩니다.<br />
								<span id="status_end_text_37153" style="display: block;" class="status_end_text">
									<span><img src="/bizstory/images/icon/icon_04.png" alt="금지"></span> 완료, 취소처리시 수정, 삭제 불가
								</span>
							</ul>
						</div>
						<div class="info_status">
							<div class="mem_img">
								<img class="photo" src="/data/company/1/member/86/member_86_1.jpg" alt="지인영">
							</div>
							<div class="info_status_remark">
								<div class="info_status_remark_area">
									<textarea cols="30" rows="5" title="완료문구를 입력하세요."></textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="ico_mem">
						<span><span class="fw700 c_red">접수등록</span> [장인록 : 2013-07-04 11:08:37]</span>
					</div>
					<div class="mem_regist">
						<div class="mem_user">
							<span><img src="/data/company/1/member/86/member_86_1.jpg" alt="지인영" /></span>
							<span class="user"><a class="name_ui">지인영</a></span>
						</div>
						<ul>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_orange fw700">접수승인</span> [지인영 : 2013-07-04 11:13:36]
							</li>
							<li>
								<span class="mem_list">&nbsp;</span> <span class="icon03 c_green fw700">작업진행</span> [지인영 : 2013-07-04 11:13:36]
							</li>
						</ul>
					</div>
				</div>
				-->
				<!-- //완료처리 상태 -->


				<!-- //업무상태 -->
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	//$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
	//$comment_list = receipt_comment_data('list', $comment_where, '', '', '');
?>
				<!-- 코멘트 -->
				<div id="task_comment" class="comment_box1">
					<div class="comment_top">
						<p class="count"><a href="javascript:void(0)" onclick="comment_view()" id="comment_gate" title="코멘트목록" class="ui-link btn_i_minus"><span class="empty"></span> 코멘트 <span id="comment_total_value">[<?=number_format($list_data['total_comment']);?>]</span></a></p>
						
			<?
				if ($receipt_data['read_comment'] > 0)
				{
					echo '
					<span class="today_num" title="읽을 댓글"><em>', number_format($receipt_data['read_comment']), '</em></span>';
				}
			?>
						<!-- div class="new" id="comment_new_btn"><img src="/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')"></div -->
						<div class="new" id="comment_new_btn"><span class="btn_comment" onclick="comment_insert_form('open')">코멘트 쓰기</span></div>
					</div>

					<div id="new_comment" title="코멘트쓰기"></div>

					<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
					<input type="hidden" id="commentlist_sub_type" name="sub_type" value="" />
					<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$code_part;?>" />
					<input type="hidden" id="commentlist_ri_idx"   name="ri_idx"   value="<?=$ri_idx;?>" />
					<input type="hidden" id="commentlist_rc_idx"   name="rc_idx"   value="" />
					<?=$form_page;?>
					<div id="comment_list_data"></div>
					</form>
				</div>
				
				<!-- //코멘트 -->

			</div>
		</div>
	</div>
	

<script type="text/javascript">
//<![CDATA[
	//receipt_change();
	//receipt_history();
	//receipt_comment();
	
	var link_ok           = '<?=$link_ok;?>';
//------------------------------------ 접수구분
	function section_view()
	{
		$.ajax({
			type: "post", dataType: 'html', url: './receipt_view_section.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#receipt_section').html(msg);
				initDateInput();
			}
		});
		//sstatus_history_info();
	}
	
//------------------------------------ 접수상태내역
	function status_history_info()
	{
		$.ajax({
			type: "post", dataType: 'html', url: './receipt_stauts_history.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#status_history_info').html(msg);
			}
		});
	}

//------------------------------------ 접수상태변경
	function receipt_status_change(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('status_modify');
		$("#view_rid_idx").val(idx);

		var status_val = $('#detail_receipt_status_' + idx).val();
		if (status_val == '')
		{
			chk_total = chk_total + '접수상태를 선택하세요.<br />';
			action_num++;
		}
		if (status_val == 'RS90') // 완료일 경우
		{
			chk_value = $('#end_remark_' + idx).val(); // 완료문구
			chk_title = $('#end_remark_' + idx).attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}
		

		if (action_num == 0)
		{
			//$("#loading").fadeIn('slow');
			//$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#view_sub_type').val('');
						$("#view_rid_idx").val('');
						$("#view_rid_type").val('');
						section_view();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					//$("#backgroundPopup").fadeOut("slow");
					
					initScroll();
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

//------------------------------------ 상태완료 문구
	function receipt_status_end(str, idx)
	{
		if (str == 'RS90') // 완료일 경우
		{
			$('#end_view_' + idx).css({display:'block'});
			$('#status_end_text_' + idx).css({display:'block'});
		}
		else
		{
			$('#status_end_text_' + idx).css({display:'none'});
		}
		
		initScroll();
	}

	function plural_list()
	{
		$('#view_sub_type').val('');
		$("#view_rid_idx").val('');
		$("#view_rid_type").val('');
		section_view();
	}

//------------------------------------ 단일 등록/수정
	function check_singular(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('singular_post');
		$("#view_rid_idx").val(idx);
		$("#view_rid_type").val('');

		chk_value = $('#detail_receipt_class').val(); // 접수분류
		chk_title = $('#detail_receipt_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_mem_idx').val(); // 담당자
		chk_title = $('#detail_mem_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_end_pre_date').val(); // 완료예정일
		chk_title = $('#detail_end_pre_date').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#view_sub_type").val('');
						$("#view_rid_idx").val('');
						section_view()
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 단일 수정폼
	function singular_modify(idx)
	{
		$("#view_rid_idx").val(idx);
		$("#view_rid_type").val(1);
		$("#view_sub_type").val('singular_form');
		section_view();
	}

//------------------------------------ 다수 등록/수정폼
	function plural_form(idx)
	{
		$("#view_rid_type").val(2);
		$("#view_sub_type").val('plural_form');
		$("#view_rid_idx").val(idx);
		section_view();
	}

//------------------------------------ 다수 접수등록/수정
	function check_plural()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('plural_post');

		chk_value = $('#detail_receipt_class').val(); // 접수분류
		chk_title = $('#detail_receipt_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_mem_idx').val(); // 담당자
		chk_title = $('#detail_mem_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_end_pre_date').val(); // 완료예정일
		chk_title = $('#detail_end_pre_date').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		oEditors.getById["detail_remark"].exec("UPDATE_CONTENTS_FIELD", []);

		chk_value = $('#detail_remark').val(); // 내용
		chk_title = $('#detail_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#view_sub_type").val('plural_list');
						$("#view_rid_idx").val('');
						section_view();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 접수상태변경
	function status_change(str)
	{
		if (str == 'RS90')
		{
			$("#receipt_status_remark").removeClass('blind');
		}
		else
		{
			$("#receipt_status_remark").addClass('blind');
		}
	}

//------------------------------------ 다수접수 삭제
	function plural_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$("#view_rid_type").val(2);
			$("#view_sub_type").val('plural_delete');
			$("#view_rid_idx").val(idx);

			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#view_sub_type").val('plural_list');
						$("#view_rid_idx").val('');
						section_view();
					}
				}
			});
		}
	}

//------------------------------------ 분리폼
	function plural_remark(str)
	{
		$('#plural_remark_' + str).css({"display":"block"});
	}

//------------------------------------ 댓글 관련
	var comment_list = './receipt_view_comment_list.php';
	var comment_form = './receipt_view_comment_form.php';
	var comment_ok   = '/bizstory/receipt/receipt_view_comment_ok.php';
	
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
	
	function comment_list_data()
	{
		$.ajax({
			async : false,
			type: "get", dataType: 'html', url: comment_list,
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
			},
			complete: function() {
				initScroll();
			}
		});
	}
	
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
				async : false,
				type: "post", dataType: 'html', url: comment_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").slideDown("slow");
					$("#new_comment").html(msg);
				},
				complete: function() {
					initScroll();
				}
			});
		}
	}
	
	function part_information(code_part, select_class, field_id, field_value, select_type)
	{
		if (code_part == "") code_part = $('#post_part_idx').val();
		var shsgroup = $('#search_shsgroup').val();

		$.ajax({
			type: "post", cache: false, async: true, dataType : "json", url: '../../bizstory/comp_set/part_information.php',
			data: {
				"code_part" : code_part,
				"select_class" : select_class,
				"field_value" : field_value,
				"select_type" : select_type,
				"shsgroup" : shsgroup
			},
			success  : function(msg) {
				$('#' + field_id).empty();
				if (select_type == 'select')
				{
					$('#' + field_id).append('<option value="all">' + $('#' + field_id).attr('title') + '</option>');
				}
				else if (select_type == 'select_allno')
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}
				else
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}

				if (msg.success_chk == "Y")
				{
					$.each(msg.result_data, function() {

						var empty_str = ''
						for (var ii = 2; ii <= this.menu_dpeth; ii++)
						{
							empty_str = empty_str + '&nbsp;&nbsp;&nbsp;';
						}

						if (this.selected == 'Y')
						{
							$('#' + field_id).append('<option value= ' + this.idx + ' selected="selected">' + empty_str + this.name + '</option>');
						}
						else
						{
							$('#' + field_id).append('<option value= ' + this.idx + '>' + empty_str + this.name + '</option>');
						}
					});
				}
				else
				{
					if (msg.result_data != '')
					{
						check_auth_popup(msg.result_data);
					}
				}
			},
			error: function(e) {
				alert(e);
			}
		});
	}
	
	 $(function() {
	    initDateInput();
	    try {
	    
			part_information('<?=$code_part;?>', 'receipt_class', 'detail_receipt_class', '<?=$detail_data['receipt_class'];?>', '');
			part_information('<?=$code_part;?>', 'staff_info', 'detail_mem_idx', '<?=$detail_data['mem_idx'];?>', '');
			
	    } catch(e) {
	    	alert(e.message);
	    }
		//status_history_info();
	  });
	  
	var initDateInput = function() {
		try {
			$(".date_input").mask("9999-99-99");
		} catch(e) {

		}
		
	};
	
	var initScroll = function() {
		try {
			myScroll.refresh();	
		} catch(e) {}
		
	}
//]]>
</script>
<?
	include "./footer.php";
?>
