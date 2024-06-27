<?
/*
	생성 : 2012.05.16
	위치 : 게시판 - 보기
*/
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

	$b_idx = $idx;

	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

// 관리자일 경우
	$set_board['auth_yn'] = "N";
	if ($_SESSION[$sess_str . "_ubstory_level"] == "1" || $_SESSION[$sess_str . "_ubstory_level"] == "11")
	{
		$set_board['auth_yn'] = "Y";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;scate=' . $send_scate;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$where = " and b.b_idx = '" . $b_idx . "'";
	$data = pro_board_info_data("view", $set_board['name_db'], $where, "", "", "");

// 말머리
	if ($set_board['category_yn'] == 'Y')
	{
		$cate_where = " and bc.bc_idx = '" . $data['bc_idx'] . "'";
		$cate_data = pro_board_category_data('view', $cate_where);
		if ($cate_data['menu_name'] == '')
		{
			$data["category_view"] = '';
		}
		else
		{
			$data['category_view'] = '[' . $cate_data['menu_name'] . ']';
		}
	}

// 첨부파일
	$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
	$file_list = pro_board_file_data('list', $file_where, '', '', '');

	$file_form_view = '
				<tr>
					<th>파일</th>
					<td>
						<div class="left file">
							<ul>';

	foreach ($file_list as $file_k => $file_data)
	{
		if (is_array($file_data))
		{
			$file_chk = $file_data['sort'];
			$fsize = $file_data['img_size'];
			$fsize = byte_replace($fsize);

			$file_form_view .= '
								<li id="file_fname_' . $file_chk . '_liview" class="org_file">
									<a href="' . $local_dir . '/cms/board_project/download.php?bf_idx=' . $file_data['bf_idx'] . '" title="' . $file_data['img_fname'] . ' 다운로드" class="fileicon">' . $file_data['img_fname'] .'  (' . $fsize .' )</a>
								</li>';
		}
	}
	$file_form_view .= '
							</ul>
						</div>
					</td>
				</tr>';

// 링크
	if ($set_board['link_yn'] == "Y")
	{
		$link_where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $b_idx . "'";
		$link_list  = pro_board_link_data("list", $link_where, '', '', '');

		$link_form_view .= '
				<tr>
					<th>링크</th>
					<td>';

		foreach ($link_list as $link_k => $link_data)
		{
			if (is_array($link_data))
			{
				if ($link_data['link_target'] == '_blank')
				{
					$link_title = '새 창으로 이동';
				}
				else
				{
					$link_title = '현재창으로 이동';
				}

				$link_form_view .= '
						<div class="left">
							<a href="' . $link_data['link_url'] . '" target="' . $link_data['link_target'] . '" title="' . $link_title . '">' . $link_data["link_url"] . '</a>
						</div>';
			}
		}
		$link_form_view .= '
					</td>
				</tr>';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
// 이전글
	$where = "
		and b.order_idx > '" . $bdata_order_idx ."'
		and bn.b_idx is NULL
		and (b.del_screen_yn = 'Y' or b.del_yn = 'N')
		" . $bbs_where . "
	";
	$orderby = "b.order_idx asc";
	$prev_data = bbs_info_data("view", $set_name_db, $where, $orderby, "", "", 2);
	$data_prev = bbs_list_data($prev_data, $set_bbs, "prev");

	foreach($data_prev as $key => $value)
	{
		$key  = "prev_" . $key;
		$$key = $value;
	}

// 다음글
	$where = "
		and b.order_idx < '" . $bdata_order_idx ."'
		and bn.b_idx is NULL
		and (b.del_screen_yn = 'Y' or b.del_yn = 'N')
		" . $bbs_where . "
	";
	$orderby = "b.order_idx desc";
	$next_data = bbs_info_data("view", $set_name_db, $where, $orderby, "", "", 2);
	$data_next = bbs_list_data($next_data, $set_bbs, "next");

	foreach($data_next as $key => $value)
	{
		$key  = "next_" . $key;
		$$key = $value;
	}
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 버튼들 ------------------------------------------------------------------------------
	$set_btn_write  = '<span class="btn_big fl"><input type="button" value="글쓰기" onclick="project_form(\'\')" /></span>'; // 글쓰기
	$set_btn_modify = '<span class="btn_big fl"><input type="button" value="수정" onclick="project_form(\'' . $b_idx . '\')" /></span>'; // 수정
	$set_btn_delete = '<span class="btn_big fl"><input type="button" value="삭제" onclick="project_delete(\'' . $b_idx . '\')" /></span>'; // 삭제

	if ($set_board['reply_yn'] == "Y")
	{
		$set_btn_reply = '<span class="btn_big fl"><input type="button" value="답글" onclick="project_reply_form(\'' . $b_idx . '\')" /></span>'; // 답글쓰기
	}
?>
<div class="ajax_write">
	<div class="ajax_frame">
	<?
		if (file_exists($set_board['skin_path'] . "/view_form.php") == true) include $set_board['skin_path'] . "/view_form.php";
		else echo $set_board['skin_dir'] . "/view_form.php 지정한 파일이 없습니다.<br />";
	?>


	<!-- 이전, 다음 //-->
	<?	if ($set_prev_next_yn == "Y") { ?>
		<div class="bbs_pr_ne">
		<?	if ($prev_total_num > 0) { ?>
			<div class="bbs_pr">
			<?
				if ($prev_read_url != "") {
			?>
				<a href="<?=$prev_read_url;?>"><strong>이전글</strong></a><?=$prev_gab;?> <?=$prev_cate_title_img;?> <a href="<?=$prev_read_url;?>"><?=$prev_subject;?></a><?=$prev_key_yn;?> <?=$prev_new_yn;?>
			<?
				} else {
			?>
				이전글<?=$prev_gab;?> <?=$prev_cate_title_img;?> <?=$prev_subject;?><?=$prev_key_yn;?> <?=$prev_new_yn;?>
			<?
				}
			?>
			</div>
		<?	} ?>
		<?	if ($next_total_num > 0) { ?>
			<div class="bbs_ne">
			<?
				if ($next_read_url != "") {
			?>
				<a href="<?=$next_read_url;?>"><strong>다음글</strong></a><?=$next_gab;?> <?=$next_cate_title_img;?> <a href="<?=$next_read_url;?>"><?=$next_subject;?></a><?=$next_key_yn;?> <?=$next_new_yn;?>
			<?
				} else {
			?>
				다음글<?=$next_gab;?> <?=$next_cate_title_img;?> <?=$next_subject;?><?=$next_key_yn;?> <?=$next_new_yn;?>
			<?
				}
			?>
			</div>
		<?	} ?>
		</div>
	<?	} ?>

		<div class="section">
			<div class="fr">
				<?=$set_btn_write;?>
				<?=$set_btn_reply;?>
				<?=$set_btn_modify;?>
				<?=$set_btn_delete;?>
			</div>
		</div>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 댓글
	$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
	$comment_list = pro_board_comment_data('list', $comment_where, '', '', '');
?>
		<div class="dotted2"></div>

		<div id="task_comment" class="report_box">
			<div class="report_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="댓글목록" onclick="comment_view()"></a> 댓글 <span id="comment_total_value">[<?=number_format($comment_list['total_num']);?>]</span>
				</p>
				<div class="new" id="comment_new_btn"><span class="btn_sml"><input type="button" value="댓글 쓰기" onclick="comment_insert_form('open')" /></span></div>
			</div>

			<div id="new_comment" title="댓글쓰기"></div>

			<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="commentlist_sub_type" name="sub_type" value="" />
				<input type="hidden" id="commentlist_comp_idx" name="comp_idx" value="<?=$client_comp;?>" />
				<input type="hidden" id="commentlist_part_idx" name="part_idx" value="<?=$client_part;?>" />
				<input type="hidden" id="commentlist_ci_idx"   name="ci_idx"   value="<?=$client_idx;?>" />
				<input type="hidden" id="commentlist_bs_idx"   name="bs_idx"   value="<?=$bs_idx;?>" />
				<input type="hidden" id="commentlist_b_idx"    name="b_idx"    value="<?=$b_idx;?>" />
				<input type="hidden" id="commentlist_bco_idx"  name="bco_idx"  value="" />
				<?=$form_page;?>
				<div id="comment_list_data"></div>
			</form>
		</div>

	</div>
</div>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 삭제하기
	function project_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$("#popup_notice_view").hide();

			$('#list_sub_type').val(sub_type)
			$('#list_sub_action').val(sub_action);
			$('#list_idx').val(idx);
			$('#list_post_value').val(post_value);

			$.ajax({
				type: "post", dataType: 'html', url: link_ok,
				data: $('#listform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success  : function(msg) {
					if (msg.success_chk == "Y") list_data();
					else
					{
						$("#popup_notice_view").show();
						$("#popup_notice_memo").html('' + msg.error_string);
					}
				}
			});
		}
	}

//------------------------------------ 답글
	function project_reply_form(idx)
	{
		$("#popup_notice_view").hide();
		$('#list_idx').val(idx);
		$('#list_sub_type').val('replyform');
		if (idx == '') $('#list_sub_type').val('postform');
		else $('#list_sub_type').val('modifyform');
		location.href = local_dir + '/cms/board_project/board.php?' + $('#listform').serialize();
	}

//------------------------------------ 댓글 관련
	var comment_list = '<?=$local_dir;?>/cms/board_project/comment_list.php';
	var comment_form = '<?=$local_dir;?>/cms/board_project/comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/cms/board_project/comment_ok.php';

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
				data: $('#commentlistform').serialize(),
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
//]]>
</script>
