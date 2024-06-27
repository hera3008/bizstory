<?
/*
	수정 : 2013.04.12
	위치 : 고객관리 > 접수목록 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ri_idx    = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
	$f_search  = $f_search . '&amp;sdate1=' . $send_sdate1 . '&amp;sdate2=' . $send_sdate2;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
		<input type="hidden" name="shstaff"  value="' . $send_shstaff . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
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
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

	if ($form_chk == 'Y')
	{
		$receipt_info = new receipt_info();
		$receipt_info->ri_idx = $ri_idx;
		$receipt_info->data_path = $comp_receipt_path;
		$receipt_info->data_dir = $comp_receipt_dir;

		$data      = $receipt_info->receipt_info_view();
		$file_list = $receipt_info->receipt_file();
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="ri_idx"   id="view_ri_idx"   value="<?=$ri_idx;?>" />
			<input type="hidden" name="rid_idx"  id="view_rid_idx"  value="" />
			<input type="hidden" name="rid_type" id="view_rid_type" value="" />

			<fieldset>
				<legend class="blind">접수정보 상세보기</legend>
				<table class="tinytable view" summary="접수정보 상세보기입니다.">
				<caption>접수정보 상세보기</caption>
				<colgroup>
					<col width="100px" />
					<col />
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>거래처명</th>
						<td>
							<div class="left"><?=$data['link_html'];?></div>
						</td>
						<th>담당자</th>
						<td>
							<div class="left"><?=$data['charge_str'];?></div>
						</td>
					</tr>
					<tr>
						<th>제목</th>
						<td colspan="3">
							<div class="left">
								<strong><?=$data['subject'];?></strong>
								<?=$data['important_img'];?>
				<?
					if ($data['total_file'] > 0)
					{
						echo '
								<span class="attach" title="첨부파일">', number_format($data['total_file']), '</span>';
					}
					if ($data['total_comment'] > 0)
					{
						echo '
								<span class="cmt" title="댓글">', number_format($data['total_comment']), '</span>';
					}

					if ($data['read_comment'] > 0)
					{
						echo '
								<span class="today_num" title="읽을 댓글"><em>', number_format($data['read_comment']), '</em></span>';
					}
				?>
							</div>
						</td>
					</tr>
					<tr>
						<th>접수분류</th>
						<td>
							<div class="left">
					<?
						$receipt_class = $data['receipt_class_str'];
						foreach ($receipt_class as $k => $v)
						{
							if ($k == 1) echo $v;
							else echo ' &gt; ', $v;
						}
					?>
							</div>
						</td>
						<th>작성자</th>
						<td>
							<div class="left1">
								<?=$data['writer'];?> (<a href="tel:<?=$data['tel_num'];?>" class="tel"><?=$data['tel_num'];?></a>)
								- <?=$data['reg_date'];?>
							</div>
						</td>
					</tr>
					<tr>
						<th>내용</th>
						<td colspan="3">
							<div class="left">
								<p class="memo">
									<?=$data['remark'];?>
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<th>첨부파일</th>
						<td colspan="3">
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
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">

					<div class="receipt_veiw_area" id="receipt_section_view">
						<div class="receipt_veiw_frame">
							<div class="status_box_bg"></div>
							<div class="status_box">
								<div id="receipt_section"></div>
								<div class="dotted2"></div>
								<div class="status_info" id="status_history_info"></div>
							</div>
						</div>
					</div>
	<?
	// 관리자만
		if ($_SESSION[$sess_str . '_ubstory_level'] <= '11') {
	?>
					<div class="receipt_area">
						<span class="btn_big_blue"><input type="button" value="수정" onclick="data_form_open('<?=$ri_idx;?>')" /></span>
						<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete('<?=$ri_idx;?>')" /></span>
					</div>
	<?
		}
	?>
				</div>
			</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
	$comment_list = receipt_comment_data('list', $comment_where, '', '', '');
?>
		<div class="dotted2"></div>

		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="코멘트목록" onclick="comment_view()"></a> 코멘트 <span id="comment_total_value">[<?=number_format($comment_list['total_num']);?>]</span>
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
				<input type="hidden" id="commentlist_ri_idx"    name="ri_idx"    value="<?=$ri_idx;?>" />
				<input type="hidden" id="commentlist_rc_idx"    name="rc_idx"    value="" />
				<?=$form_page;?>
				<div id="comment_list_data"></div>
			</form>
		</div>

		<div class="section">
			<div class="fr">
				<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 접수구분
	function section_view()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/receipt/receipt_view_section.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#receipt_section').html(msg);
			}
		});
		status_history_info();
	}
	section_view();

//------------------------------------ 접수상태내역
	function status_history_info()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/receipt/receipt_stauts_history.php',
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
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
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
					$("#backgroundPopup").fadeOut("slow");
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
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
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
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
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
	var comment_list = '<?=$local_dir;?>/bizstory/receipt/receipt_view_comment_list.php';
	var comment_form = '<?=$local_dir;?>/bizstory/receipt/receipt_view_comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/bizstory/receipt/receipt_view_comment_ok.php';

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

	$('#client_memo').poshytip();
//]]>
</script>
<?
	}
?>
