<?
/*
	생성 : 2012.06.20
	위치 : 업무관리 > 프로젝트목록 > 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$wi_idx    = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shwclass=' . $send_shwclass . '&amp;shwstatus=' . $send_shwstatus;
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
		<input type="hidden" name="shwclass"  value="' . $send_shwclass . '" />
		<input type="hidden" name="shwstatus" value="' . $send_shwstatus . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
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
			check_auth('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$work_info = new work_info();
		$work_info->wi_idx = $wi_idx;
		$work_info->data_path = $comp_work_path;
		$work_info->data_dir = $comp_work_dir;

		$data        = $work_info->work_info_view();
		$file_list   = $work_info->work_file_list();
		$file_images = $work_info->work_file_images();

		if ($data['work_class'] == '') $work_class_value = '해당없음';
		else $work_class_value = $data['work_class_str'];

		if ($data['apply_name'] == '') $apply_value = '해당없음';
		else $apply_value = $data['apply_name'];

	// 첨부파일
		$file_where = " and wf.wi_idx = '" . $data['wi_idx'] . "'";
		$file_page = work_file_data('page', $file_where);
		$total_file = $file_page['total_num'];

	// 업무보고서
		$report_where = " and wr.wi_idx='" . $data['wi_idx'] . "'";
		$report_page = work_report_data('page', $report_where);
		$total_report = $report_page['total_num'];

	// 코멘트
		$comment_where = " and wc.wi_idx='" . $data['wi_idx'] . "'";
		$comment_page = work_comment_data('page', $comment_where);
		$total_comment = $comment_page['total_num'];

	// 업무보고서, 코멘트 목록보기여부
		if ($data['work_status'] != 'WS01' && $data['work_status'] != 'WS60' && $data['work_status'] != 'WS90' && $data['work_status'] != 'WS99' && $data['work_status'] != 'WS20' && $data['work_status'] != 'WS30') // 대기, 취소, 완료, 종료, 승인대기, 요청대기
		{
			$work_report_yn  = 'Y';
			$work_comment_yn = 'Y';
		}
		else
		{
			$work_report_yn  = 'N';
			$work_comment_yn = 'Y';
		}

	// 읽을 업무, 보고
		$check_num = work_read_check($data['wi_idx']);
		$read_work    = $check_num['work_check'];
		$read_report  = $check_num['work_report'];
		$read_comment = $check_num['work_comment'];
?>
<div class="info_text">
	<ul>
		<li>다수 담당자의 업무요청일 경우 모두 완료요청을 할 경우 등록자가 '업무완료' 할 수 있습니다.</li>
		<li>다수 담당자의 승인요청일 경우 모두 승인요청을 할 경우 승인자가 '승인'할 수 있습니다.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type"    id="view_sub_type" />
			<input type="hidden" name="part_idx"    id="view_part_idx"    value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="wi_idx"      id="view_wi_idx"      value="<?=$wi_idx;?>" />
			<input type="hidden" name="work_status" id="view_work_status" value="<?=$data['work_status'];?>" />

		<fieldset>
			<legend class="blind">업무정보</legend>
			<table class="tinytable view" summary="등록한 업무에 대한 상세정보입니다.">
			<caption>업무정보</caption>
			<colgroup>
				<col width="80px" />
				<col />
				<col width="80px" />
				<col />
				<col width="80px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>업무제목</th>
					<td colspan="5">
						<div class="left">
							<?=$data['work_img'];?>
							<strong><?=$data['subject'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['open_img'];?>
			<?
				if ($total_file > 0)
				{
					echo '
							<span class="attach" title="첨부파일">', number_format($total_file), '</span>';
				}
				if ($total_report > 0)
				{
					echo '
							<span class="report" title="업무보고서">', number_format($total_report), '</span>';
				}
				if ($total_comment > 0)
				{
					echo '
							<span class="cmt" title="댓글">', number_format($total_comment), '</span>';
				}

				if ($read_work > 0)
				{
					echo '
							<span class="today_num" title="읽을 업무보고/댓글"><em>', number_format($read_work), '</em></span>';
				}
			?>
						</div>
					</td>
				</tr>
				<tr>
					<th>등록자</th>
					<td>
						<div class="left"><?=$data['reg_name'];?>(<?=$data['reg_date'];?>)</div>
					</td>
					<th>담당자</th>
					<td>
						<div class="left"><?=$data['charge_idx_str'];?></div>
					</td>
					<th>승인자</th>
					<td>
						<div class="left"><?=$apply_value;?></div>
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
						<div class="left"><?=$work_class_value;?></div>
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
				?>
								<li>
									<a href="<?=$local_diir;?>/bizstory/work/work_download.php?wf_idx=<?=$file_data['wf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>
								</li>
				<?
							}
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
	if ($data['reg_id'] == $code_mem || $_SESSION[$sess_str . '_ubstory_level'] <= 11)
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

	$section_str = work_status_view($wi_idx);
?>
			<div class="section">
				<div class="fl">
					<div class="status_box_<?=$section_str['bgimg'];?>"></div>
					<div class="status_box">
					<?
						if ($section_str['text'] != '')
						{
					?>
						<div class="status_top">
							<p class="count">
								<?=$section_str['text'];?>
							</p>
						</div>
					<?
						}
					?>
					<?
						if ($section_str['comment'] != '')
						{
					?>
						<div class="status">
							<div class="status_info">
								<?=$section_str['comment'];?>
							</div>
						</div>
					<?
						}
					?>
					</div>
				</div>
				<div class="fr">
					<?=$section_str['button_apply'];?>
					<?=$section_str['button'];?>
					<?=$btn_write;?>
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
	$report_list = work_report_data('list', $report_where, '', '', '');

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
	$comment_list = work_comment_data('list', $comment_where, '', '', '');
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

		<div id="task_comment" class="update_box">
			<div class="update_top">
				<p class="count">
					<a id="history_gate" class="btn_i_minus" title="업무로그기록" onclick="history_view()"></a> 업무로그기록
				</p>
			</div>

			<div id="new_history" title="댓글쓰기"></div>

			<form id="historylistform" name="historylistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="historylist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="historylist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="historylist_wi_idx"    name="wi_idx"    value="<?=$wi_idx;?>" />
				<?=$form_page;?>
				<div id="history_list_data"></div>
			</form>
		</div>

		<div class="section">
			<span class="btn_big"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=$local_dir;?>/bizstory/editor/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$local_dir;?>/bizstory/js/script_file.js" charset="utf-8"></script>
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
		else if (idx == 'WS30_WS90') // 완료요청 -> 업무완료
		{
			action_str = '30_90';
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
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
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
				}
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
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
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
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
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
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
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
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
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
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
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