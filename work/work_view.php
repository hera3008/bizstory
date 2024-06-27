<?
/*
	생성 : 2012.04.23
	수정 : 2013.05.20
	위치 : 업무폴더 > 나의업무 > 업무 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$code_part    = search_company_part($code_part);
	$code_mem     = $_SESSION[$sess_str . '_mem_idx'];
	$code_level   = $_SESSION[$sess_str . '_ubstory_level'];
	$code_ubstory = $_SESSION[$sess_str . '_ubstory_yn'];

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];
	$wi_idx    = $idx;

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

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and wi.wi_idx = '" . $wi_idx . "'";
        $file_where = " and wi_idx = '" . $wi_idx . "'";
		$data = work_list_info('view', $where, $file_where, $code_mem);
        
		$data = work_list_data($data, $wi_idx); // 작업내용

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

	// 파일목록
		$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
		$file_list = work_file_data('list', $file_where, '', '', '');

		$work_report_yn = $data['report_yn'];
		$work_report_yn = $data['report_yn'];
		$read_work      = $data['read_work'];
		$read_report    = $data['read_report'];
		$read_comment   = $data['read_comment'];
?>
<div class="info_text">
	<ul>
		<li>다수 담당자의 업무요청일 경우 모두 완료요청을 할 경우 등록자가 '업무완료' 할 수 있습니다.</li>
		<li>다수 담당자의 승인요청일 경우 모두 승인요청을 할 경우 승인자가 '승인'할 수 있습니다.</li>
	</ul>
</div>

<div class="etc_bottom">
	<select id="print_print_type" name="print_type">
		<option value="1">모두</option>
		<option value="2">업무내용</option>
		<option value="3">업무내용+업무보고</option>
		<option value="4">업무내용+코멘트</option>
		<option value="5">업무보고+코멘트</option>
	</select>
	<a href="javascript:void(0);" class="btn_sml" onclick="view_print()"><span><em class="print"></em>인쇄</span></a>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type"    id="view_sub_type" />
			<input type="hidden" name="part_idx"    id="view_part_idx"    value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="wi_idx"      id="view_wi_idx"      value="<?=$wi_idx;?>" />
			<input type="hidden" name="work_status" id="view_work_status" value="<?=$data['work_status'];?>" />
			<input type="hidden" name="print_type"  id="view_print_type"  value="1" />
			<input type="hidden" name="force_yn"    id="view_force_yn"    />

		<fieldset>
			<legend class="blind">업무정보</legend>
			<table class="tinytable view" summary="등록한 업무에 대한 상세정보입니다.">
			<caption>업무정보</caption>
			<colgroup>
				<col width="80px" />
				<col />
				<col width="80px" />
				<col width="100px" />
				<col width="80px" />
				<col width="200px" />
			</colgroup>
			<tbody>
				<tr>
					<th>업무제목</th>
					<td colspan="5">
						<div class="left">
							<?=$data['work_img'];?>
							<?=$data['part_img'];?>
							<strong><?=$data['subject'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['open_img'];?>
							<?=$data['file_str'];?>
							<?=$data['report_str'];?>
							<?=$data['comment_str'];?>
						<!-- 	<?=$data['read_work_str'];?> -->
						</div>
					</td>
				</tr>
				<tr>
					<th>담당자</th>
					<td>
						<div class="left"><?=$data['total_charge_str'];?></div>
					</td>
					<th>승인자</th>
					<td>
						<div class="left"><?=$data['apply_name'];?></div>
					</td>
					<th>등록자</th>
					<td>
						<div class="left"><?=$data['reg_name_view'];?>(<?=$data['reg_date'];?>)</div>
					</td>
				</tr>
				<tr>
					<th>기한</th>
					<td colspan="5">
						<div class="left"><?=$data['deadline_date_str'];?></div>
					</td>
				</tr>
				<tr>
					<th>분류</th>
					<td colspan="5">
						<div class="left"><?=$data['work_class_str'];?></div>
					</td>
				</tr>
				<tr>
					<th>내용</th>
					<td colspan="5">
						<div class="left">
							<p class="memo">
								<?=$data['remark'];?>
							</p>
						</div>
					</td>
				</tr>
				<tr>
					<th>첨부파일</th>
					<td colspan="5">
						<div class="left file">
				<?
					if ($file_list['total_num'] > 0) {
				?>
							<ul>
				<?
						foreach ($file_list as $file_k => $file_data)
						{
							if (is_array($file_data))
							{
								$img_path = $file_data['img_path'];
								$in_out   = $file_data['in_out'];
								$fsize = $file_data['img_size'];
								$fsize = byte_replace($fsize);

								$btn_str = preview_file($comp_work_dir, $file_data['wf_idx'], 'work');
								

								if ($in_out == 'CENTER')
								{
									$down_url = $set_filecneter_url . '/biz/work_download.php?wf_idx=' . $file_data['wf_idx'];
								}
								else if ($in_out == 'OUT')
								{
									$down_url = $set_filecneter_url . '/biz/work_download.php?wf_idx=' . $file_data['wf_idx'];
								}
								else
								{
									$down_url = $local_dir . '/bizstory/work/work_download.php?wf_idx=' . $file_data['wf_idx'];
								}

								if ($img_path != '')
								{
									$file_url = substr($img_path, 1, strlen($img_path)) . '/<strong>' . $file_data['img_fname'] . '</strong>';
								}
								else
								{
									$file_url = '<strong>' . $file_data['img_fname'] . '</strong>';
								}
				?>
								<li>
									<?=$btn_str;?>
									<a href="<?=$down_url;?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
// 등록자만 가능하다.
	if ($data['reg_id'] == $code_mem || $code_level <= 11)
	{
		$btn_modify = '<span class="btn_big_blue"><input type="button" value="수정" onclick="data_form_open(\'' . $wi_idx . '\')" /></span>';
		$btn_delete = '<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete(\'' . $wi_idx . '\')" /></span>';
	}

// 완료일 경우 수정, 삭제가 안됨
	if ($data['work_status'] == 'WS90')
	{
		$btn_modify = '';
		$btn_delete = '';
	}

	$section_str = work_status_view($data); // 진행상태
?>
			<div class="section">
				<div class="fl">
					<div class="status_box_<?=$section_str['status_title_bg'];?>"></div>
					<div class="status_box">
						<div class="status_top">
							<p class="count">
								<?=$section_str['status_title'];?>
							</p>
						</div>
					<?
						if ($section_str['status_comment'] != '')
						{
					?>
						<div class="status">
							<div class="status_info">
								<?=$section_str['status_comment'];?>
							</div>
						</div>
					<?
						}
					?>
					</div>
				</div>
				<div class="fr">
		<?
			if ($data['work_status'] != 'WS90')
			{
				if ($code_ubstory == 'Y' && $code_level <= '11')
				{
					$button_ws90_value = '업무완료';
					$button_ws90_key   = 'WS90';
		?>
						<span class="btn_big_violet"><input type="button" value="강제업무완료" onclick="check_work_status('WS90', 'Y')" /></span>
		<?
				}

				if ($section_str['button_ws02_value'] != '') {
		?>
						<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws02_value'];?>" onclick="check_work_status('<?=$section_str['button_ws02_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws20_value'] != '') {
		?>
						<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws20_value'];?>" onclick="check_work_status('<?=$section_str['button_ws20_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws20_02_value'] != '') {
		?>
						<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws20_02_value'];?>" onclick="check_work_status('<?=$section_str['button_ws20_02_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws30_value'] != '') {
		?>
						<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws30_value'];?>" onclick="check_work_status('<?=$section_str['button_ws30_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws30_02_value'] != '') {
		?>
						<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws30_02_value'];?>" onclick="check_work_status('<?=$section_str['button_ws30_02_key'];?>')" /></span>
		<?
				}
				if ($section_str['button_ws90_value'] != '') {
		?>
						<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws90_value'];?>" onclick="check_work_status('<?=$section_str['button_ws90_key'];?>')" /></span>
		<?
				}
			}
			if ($section_str['button_ws70_value'] != '') {
		?>
					<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws70_value'];?>" onclick="check_work_status('<?=$section_str['button_ws70_key'];?>')" /></span>
		<?
			}
			if ($section_str['button_ws80_value'] != '') {
		?>
					<span class="btn_big_violet"><input type="button" value="<?=$section_str['button_ws80_value'];?>" onclick="check_work_status('<?=$section_str['button_ws80_key'];?>')" /></span>
		<?
			}
		?>
					<?=$btn_modify;?>
					<?=$btn_delete;?>
				</div>
			</div>
		</fieldset>
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
		<div class="dotted"></div>

		<div id="task_report" class="report_box">
			<div class="report_top">
				<p class="count">
					<a id="report_gate" class="btn_i_minus" title="보고서목록" onclick="report_view()"></a> 업무보고 <span id="report_total_value">[<?=number_format($report_list['total_num']);?>]</span>
			<?
			echo $data['read_work_str'];
            /*
			if ($read_report > 0)
			{
				echo '
				<span class="today_num" title="읽을 업무보고"><em>', number_format($read_report), '</em></span>';
			}
            */
			?>
				</p>
	<?
		if ($work_report_yn == 'Y')
		{
	?>
				<div class="new" id="report_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_report.png" alt="업무보고 쓰기" class="pointer" onclick="report_insert_form('open')" /></div>
	<?
		}
	?>
			</div>

			<div id="new_report" title="업무보고쓰기"></div>

			<form id="reportlistform" name="reportlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="reportlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="reportlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="reportlist_wi_idx"    name="wi_idx"    value="<?=$wi_idx;?>" />
				<input type="hidden" id="reportlist_wr_idx"    name="wr_idx"    value="" />
				<?=$form_page;?>
				<div id="report_list_data"></div>
			</form>
		</div>
<?
	}
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	$comment_where = " and wc.wi_idx = '" . $wi_idx . "'";
	$comment_list = work_comment_data('page', $comment_where);
?>
		<div class="dotted2"></div>

		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="코멘트목록" onclick="comment_view()"></a> 코멘트 <span id="comment_total_value">[<?=number_format($comment_list['total_num']);?>]</span>
			<?
				if ($read_comment > 0)
				{
					echo '
					<span class="today_num" title="읽을 댓글"><em>', number_format($read_comment), '</em></span>';
				}
			?>
				</p>
				<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')" /></div>
			</div>

			<div id="new_comment" title="코멘트쓰기"></div>

			<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="commentlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="commentlist_wi_idx"    name="wi_idx"    value="<?=$wi_idx;?>" />
				<input type="hidden" id="commentlist_wc_idx"    name="wc_idx"    value="" />
				<?=$form_page;?>
				<div id="comment_list_data"></div>
			</form>
		</div>

<?
////////////////////////////////////////////////////////////////////////////////////////
// 최근업데이트
?>
		<div class="dotted2"></div>

		<div id="task_history" class="update_box">
			<div class="update_top">
				<p class="count">
					<a id="history_gate" class="btn_i_minus" title="업무로그기록" onclick="history_view()"></a> 업무로그기록
				</p>
				<div class="new"></div>
			</div>

			<form id="historylistform" name="historylistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="historylist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="historylist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="historylist_wi_idx"    name="wi_idx"    value="<?=$wi_idx;?>" />
				<?=$form_page;?>
				<div id="history_list_data"></div>
			</form>
		</div>

		<div class="section">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 상태변경
	function check_work_status(idx)
	{
		var action_url = '';
		var action_str = '';
		if (idx == 'WS02') // 업무대기중 -> 업무진행중
		{
			action_str = '02';
		}
		else if (idx == 'WS90') // 업무진행중 -> 업무완료
		{
			action_str = '90';
			var force_yn = arguments[1];
			
			if (force_yn != null) {
			    $("#view_force_yn").val(force_yn);
			} else {
			    $("#view_force_yn").val('N');
			}
			
		}
		else if (idx == 'WS20') // 업무진행중 -> 승인요청
		{
			action_str = '20';
		}
		else if (idx == 'WS20_WS90') // 승인대기중 -> 승인
		{
			action_str = '20_90';
		}
		else if (idx == 'WS20_WS70') // 승인대기중 -> 승인요청반려
		{
			action_str = '20_70';
		}
		else if (idx == 'WS20_WS02') // 승인대기중 -> 승인요청취소
		{
			action_str = '20_02';
		}
		else if (idx == 'WS30') // 업무진행중 -> 완료요청
		{
			action_str = '30';
		}
		else if (idx == 'WS30_WS90') // 요청대기 -> 업무완료
		{
			action_str = '30_90';
		}
		else if (idx == 'WS30_WS70') // 요청대기 -> 요청완료반려
		{
			action_str = '30_70';
		}
		else if (idx == 'WS30_WS02') // 요청대기 -> 완료요청취소
		{
			action_str = '30_02';
		}
		else if (idx == 'WS80') // 업무진행중 -> 업무보류
		{
			action_str = '80';
		}
		else if (idx == 'WS80_02') // 업무보류 -> 업무진행
		{
			action_str = '80_02';
		}
		else if (idx == 'WS70') // 업무완료 -> 업무반려
		{
			action_str = '70';
		}
		action_url = local_dir + '/bizstory/include/select_work_status' + action_str + '.php';

		if (action_url != '')
		{
			$.ajax({
				type: "post", dataType: 'html', url: action_url,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('html, body').animate({scrollTop:0}, 500 );
					var maskHeight = $(document).height() + 20;
					var maskWidth  = $(window).width();
					var boxHeight  = $(window).height() / 2 - 100;;
					$("#sub_popupform").slideDown("slow");
					$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
					$('.sub_popupform').css('top',  boxHeight);
					$('.sub_popupform').css('left', maskWidth/2-($('.sub_popupform').width()/2));
					$("#sub_popupform").html(msg);
				},
				complete: function(){ }
			});
		}
		return false;
	}

//------------------------------------ 업무보고서 관련
	var report_list = '<?=$local_dir;?>/bizstory/work/work_view_report_list.php';
	var report_form = '<?=$local_dir;?>/bizstory/work/work_view_report_form.php';
	var report_ok   = '<?=$local_dir;?>/bizstory/work/work_view_report_ok.php';

//------------------------------------ 업무보고서 등록
	function report_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_report").slideUp("slow");
			$("#new_report").html('');
			$('#report_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: report_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#report_new_btn').css({'display':'none'});
					$("#new_report").slideUp("slow");
					$("#new_report").slideDown("slow");
					$("#new_report").html(msg);
				}
			});
		}
	}

//------------------------------------ 업무보고서 목록
	function report_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: report_list,
			data: $('#reportlistform').serialize(),
			success: function(msg) {
				$('#report_list_data').html(msg);
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
	var comment_list = '<?=$local_dir;?>/bizstory/work/work_view_comment_list.php';
	var comment_form = '<?=$local_dir;?>/bizstory/work/work_view_comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/bizstory/work/work_view_comment_ok.php';

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
					$("#new_comment").slideUp("slow");
					$("#new_comment").slideDown("slow");
					$("#new_comment").html(msg);
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
	var history_list = '<?=$local_dir;?>/bizstory/work/work_view_history_list.php';

//------------------------------------ 최근업데이트 목록
	function history_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: history_list,
			data: $('#historylistform').serialize(),
			success: function(msg) {
				$('#history_list_data').html(msg);
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
?>