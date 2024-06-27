<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$contents_title = '나의 업무상세';

	$where = " and wi.wi_idx = '" . $wi_idx . "'";
	$data = work_info_data('view', $where);

	$data = work_list_data($data, $wi_idx); // 작업내용

	work_WT04($data); // 읽기확인

// 파일목록
	$file_where = " and wf.wi_idx = '" . $wi_idx . "'";
	$file_list = work_file_data('list', $file_where, '', '', '');
?>
<script type="text/javascript" src="<?=$mobile_dir;?>/js/myScroll.js" charset="utf-8"></script>

<div id="work_view" class="full sub">

	<div class="toolbar han">
		<?=$btn_back;?>
		<h1>
			<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/work_my_view.php?wi_idx=<?=$wi_idx;?>'"><?=$contents_title;?></a>
		</h1>
		<?=$btn_menu;?>
	</div>

	<!-- Contents -->
	<div id="wrapper">
		<div id="scroller">

			<form id="viewform" name="viewform" method="post" action="<?=$this_page;?>">
				<input type="hidden" name="sub_type"    id="view_sub_type" />
				<input type="hidden" name="wi_idx"      id="view_wi_idx"      value="<?=$wi_idx;?>" />
				<input type="hidden" name="work_status" id="view_work_status" value="<?=$data['work_status'];?>" />

				<table border="1" cellspacing="0" class="board-list" summary="업무제목, 담당자, 기한, 분류, 업무내용 등이 있습니다,">
				<caption><?=$contents_title;?> 콘텐츠</caption>
				<colgroup>
					<col width="90px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>업무제목</th>
						<td class="subject">
							<?=$data['work_img'];?>
							<?=$data['part_img'];?>
							<strong><?=$data['subject_txt'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['open_img'];?>
							<?=$data['file_str'];?>
							<?=$data['report_str'];?>
							<?=$data['comment_str'];?>
							<?=$data['read_work_str'];?>
						</td>
					</tr>
					<tr>
						<th>담당자</th>
						<td><?=$data['total_charge_str'];?></td>
					</tr>
				<?
					if ($data['work_type'] == 'WT03')
					{
				?>
					<tr>
						<th>승인자</th>
						<td><?=$data['apply_name'];?></td>
					</tr>
				<?
					}
				?>
					<tr>
						<th>기한</th>
						<td>
							<strong class="date"><?=$data['deadline_date_str'];?></strong>
							- <?=$data['end_date_str'];?>
						</td>
					</tr>
					<tr>
						<th>분류</th>
						<td><?=$data['work_class_str'];?></td>
					</tr>
					<tr>
						<td colspan="2">
							<?=$data['remark'];?>
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
			?>
								<li><?=$file_data['img_fname'];?> (<?=$fsize;?>)</li>
			<?
						}
					}
			?>
							</ul>
			<?
				}
			?>
						</td>
					</tr>
					<tr>
						<th>등록일</th>
						<td><strong class="date"><?=$data['reg_name'];?>(<?=$data['reg_date'];?>)<strong></td>
					</tr>
				</tbody>
				</table>
<?
// 등록자만 가능하다.
	if ($data['reg_id'] == $code_mem)
	{
		$btn_modify = '<span class="btn_sml2"><input type="button" value="수정" onclick="data_form_open(\'' . $wi_idx . '\')" /></span>';
		$btn_delete = '<span class="btn_sml2"><input type="button" value="삭제" onclick="check_delete(\'' . $wi_idx . '\')" /></span>';
	}

// 완료일 경우 수정, 삭제가 안됨
	if ($data['work_status'] == 'WS90')
	{
		$btn_modify = '';
		$btn_delete = '';
	}

	$section_str = work_status_view2($data);

	//echo '<pre>';
	//print_r($section_str['status_check']);
	//echo '</pre>';
?>
			<div class="section">
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
				<div class="section_button">
					<?=$section_str['button_apply'];?>
					<?=$section_str['button'];?>
					<?=$btn_modify;?>
					<?=$btn_delete;?>
				</div>
			</div>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 업무보고서
	if ($data['work_type'] == 'WT04' && $data['total_report'] == 0) // 업무알림일 경우 업무보고 나오지 않음
	{ }
	else
	{
?>
		<div id="task_report" class="report_box">
			<div class="report_top">
				<p class="count">
					<a href="javascript:void(0)" onclick="report_view()" id="report_gate" title="업무보고목록" class="btn_i_plus"><span class="empty"></span> 업무보고 <span id="report_total_value">[<?=number_format($data['total_report']);?>]</span></a>
			<?
				if ($data['read_report'] > 0)
				{
					echo '
					<span class="today_num" title="읽을 업무보고"><em>', number_format($data['read_report']), '</em></span>';
				}
			?>
				</p>
	<?
		if ($data['report_yn'] == 'Y')
		{
	?>
				<div class="new" id="report_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_report.png" alt="업무보고 쓰기" class="pointer" onclick="report_insert_form('open', 'insert')" /></div>
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
?>
		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" title="코멘트목록" onclick="comment_view()"> 코멘트 <span id="comment_total_value">[<?=number_format($data['total_comment']);?>]</span></a>
			<?
				if ($data['read_comment'] > 0)
				{
					echo '
					<span class="today_num" title="읽을 댓글"><em>', number_format($data['read_comment']), '</em></span>';
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

		</div>
	</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/work_my_list.php\'" class="icon2"><span>나의업무</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\')" class="icon2"><span>업무등록</span></a>
			<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
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
	var report_list = '<?=$mobile_dir;?>/work_my_view_report_list.php';
	var report_form = '<?=$mobile_dir;?>/work_my_view_report_form.php';
	var report_ok   = '<?=$mobile_dir;?>/work_my_view_report_ok.php';

//------------------------------------ 업무보고서 열기/닫기
	var report_chk_val = 'close';
	function report_view()
	{
		report_insert_form('close', '');

		if (report_chk_val == 'close')
		{
			report_chk_val = 'open';
			$('#report_list_data').html('');
			$("#report_gate").removeClass('btn_i_minus');
			$("#report_gate").addClass('btn_i_plus');
			myScroll.refresh();
		}
		else
		{
			report_chk_val = 'close';
			report_list_data();
			$("#report_gate").removeClass('btn_i_plus');
			$("#report_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 업무보고서 등록
	function report_insert_form(form_type, str)
	{
		if (str == 'insert')
		{
			report_chk_val = 'close';
			report_view();
		}

		if (form_type == 'close')
		{
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
					$('#report_new_btn').css({'display':'none'});
					$("#new_report").html(msg);
					myScroll.refresh();
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
				myScroll.refresh();
			}
		});
	}
	report_view();

//------------------------------------ 댓글 관련
	var comment_list = '<?=$mobile_dir;?>/work_my_view_comment_list.php';
	var comment_form = '<?=$mobile_dir;?>/work_my_view_comment_form.php';
	var comment_ok   = '<?=$mobile_dir;?>/work_my_view_comment_ok.php';

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
			myScroll.refresh();
		}
		else
		{
			comment_chk_val = 'close';
			comment_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 댓글 등록
	function comment_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_comment").slideUp("slow");
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
			myScroll.refresh();
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").html(msg);
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
				myScroll.refresh();
			}
		});
	}
	comment_view();
//]]>
</script>
</body>
</html>