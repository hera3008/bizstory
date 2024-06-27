<?
/*
	생성 : 2012.06.07
	수정 : 2012.12.19
	위치 : 게시판 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$b_idx = $idx;

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_bbs = bbs_setting_data("view", $set_where);
	if ($set_bbs["total_num"] > 0)
	{
	// 관리자일 경우
		$set_bbs["auth_yn"] = "N";
		If ($code_level >= 1 && $code_level <= 11 && $code_mem != "")
		{
			$set_bbs["auth_yn"] = "Y";
		}

	// 게시판설정값
		foreach($set_bbs as $key => $value)
		{
			$key  = "set_" . $key;
			$$key = $value;
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode . '&amp;bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;scate=' . $send_scate . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode"  value="' . $send_fmode . '" />
		<input type="hidden" name="smode"  value="' . $send_smode . '" />
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y' && $b_idx != '') // 보기권한
	{
		$form_chk   = 'Y';
		$form_title = '보기';
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
		$where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
		$data = bbs_info_data('view', $where);

	// 파일
		$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
		$file_list = bbs_file_data('list', $file_where, '', '', '');
		$total_file = $file_page['total_num'];

	// 링크
		$link_where = " and bl.bs_idx = '" . $bs_idx . "' and bl.b_idx = '" . $b_idx . "'";
		$link_list = bbs_link_data('list', $link_where, '', '', '');

	// 코멘트
		$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
		$comment_page = bbs_comment_data('page', $comment_where);
		$total_comment = $comment_page['total_num'];
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="b_idx"    id="view_b_idx"    value="<?=$b_idx;?>" />

			<fieldset>
				<legend class="blind">게시물</legend>
				<table class="tinytable view" summary="게시물 대한 상세정보입니다.">
				<caption>게시물</caption>
				<colgroup>
					<col width="80px" />
					<col />
					<col width="80px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>제목</th>
						<td colspan="3">
							<div class="left">
								<strong><?=$data['subject'];?></strong>
								<?=$data['open_img'];?>
				<?
					if ($total_file > 0)
					{
						echo '
								<span class="attach" title="첨부파일">', number_format($total_file), '</span>';
					}
					if ($total_comment > 0)
					{
						echo '
								<span class="cmt" title="댓글">', number_format($total_comment), '</span>';
					}
				?>
							</div>
						</td>
					</tr>
					<tr>
						<th>작성자</th>
						<td>
							<div class="left"><?=$data['writer'];?></div>
						</td>
						<th>작성일</th>
						<td>
							<div class="left"><?=$data['reg_date'];?></div>
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
		<?
			if ($set_file_yn == "Y")
			{
		?>
					<tr>
						<th>첨부파일1</th>
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
									$fsize = $file_data['img_size'];
									$fsize = byte_replace($fsize);

									$btn_str = preview_file($comp_bbs_dir, $file_data['bf_idx'], 'bbs');
					?>
									<li>
										<?=$btn_str;?>
										<a href="<?=$local_diir;?>/bizstory/bbs/bbs_download.php?bf_idx=<?=$file_data['bf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
									</li>
					<?
								}
							}
							$btn_img = preview_images($b_idx, 'bbs');
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
			<?
				}
			?>
			<?
				if ($set_link_yn == "Y")
				{
			?>
					<tr>
						<th>링크주소</th>
						<td colspan="5">
					<?
						foreach ($link_list as $link_k => $link_data)
						{
							if (is_array($link_data))
							{
					?>

							<div class="left">
								<a href="http://<?=$link_data['link_url'];?>" title="<?=$link_data['link_name'];?>" target="<?=$link_data['link_target'];?>"><?=$link_data['link_name'];?> (<?=$link_data['link_url'];?>)</a>
							</div>
					<?
							}
						}
					?>
							</div>
						</td>
					</tr>
			<?
				}
			?>
				</tbody>
				</table>
	<?
	// 등록자만 가능하다.
		if ($data['reg_id'] == $code_mem || $code_level <= 11)
		{
			$btn_modify = '<span class="btn_big_blue"><input type="button" value="수정" onclick="open_data_form(\'' . $b_idx . '\')" /></span>';
			$btn_delete = '<span class="btn_big_red"><input type="button" value="삭제" onclick="check_delete(\'' . $b_idx . '\')" /></span>';
		}
	?>
				<div class="section">
					<div class="fr">
						<?=$btn_reply;?>
						<?=$btn_modify;?>
						<?=$btn_delete;?>
						<?=$btn_write;?>
					</div>
				</div>
			</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	if ($set_comment_yn == 'Y')
	{
		$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
		$comment_list = bbs_comment_data('list', $comment_where, '', '', '');
?>
		<div class="dotted2"></div>

		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="코멘트목록" onclick="comment_view()"></a> 코멘트 <span id="comment_total_value">[<?=number_format($comment_list['total_num']);?>]</span>
				</p>
				<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')" /></div>
			</div>

			<div id="new_comment" title="코멘트쓰기"></div>

			<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="commentlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="commentlist_bs_idx"    name="bs_idx"    value="<?=$bs_idx;?>" />
				<input type="hidden" id="commentlist_b_idx"     name="b_idx"     value="<?=$b_idx;?>" />
				<input type="hidden" id="commentlist_bco_idx"   name="bco_idx"   value="" />
				<?=$form_page;?>
				<div id="comment_list_data"></div>
			</form>
		</div>
<?
	}
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

	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 댓글 관련
	var comment_list = '<?=$local_dir;?>/bizstory/bbs/bbs_view_comment_list.php';
	var comment_form = '<?=$local_dir;?>/bizstory/bbs/bbs_view_comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/bizstory/bbs/bbs_view_comment_ok.php';

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
//]]>
</script>
<?
	}
?>