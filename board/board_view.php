<?
/*
	생성 : 2012.06.07
	수정 : 2012.09.10
	위치 : 게시판폴더
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$set_part_yn    = $company_set_data['part_yn'];
	$set_table_name = 'board_biz_' . $company_id;

	$b_idx = $idx;

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
		$where = " and b.bs_idx = '" . $bs_idx . "' and b.b_idx = '" . $b_idx . "'";
		$data = board_info_data('view', $set_table_name, $where);

		$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
		$file_list = board_file_data('list', $file_where, '', '', '');

	// 첨부파일
		$file_where = " and bf.bs_idx = '" . $bs_idx . "' and bf.b_idx = '" . $b_idx . "'";
		$file_page = board_file_data('page', $file_where);
		$total_file = $file_page['total_num'];

	// 코멘트
		$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
		$comment_page = board_comment_data('page', $comment_where);
		$total_comment = $comment_page['total_num'];
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
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
				?>
								<li>
									<a href="<?=$local_diir;?>/bizstory/board/download.php?bf_idx=<?=$file_data['bf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?></a>
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
	if ($data['reg_id'] == $code_mem || $code_level <= 11)
	{
		$btn_modify = '<span class="btn_big"><input type="button" value="수정" onclick="data_form_open(\'' . $b_idx . '\')" /></span>';
		$btn_delete = '<span class="btn_big"><input type="button" value="삭제" onclick="check_delete(\'' . $b_idx . '\')" /></span>';
	}
?>
			<div class="section">
				<?=$btn_write;?>
				<?=$btn_reply;?>
				<?=$btn_modify;?>
				<?=$btn_delete;?>
			</div>
		</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	$comment_where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
	$comment_list = board_comment_data('list', $comment_where, '', '', '');
?>
<!--//
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
				<input type="hidden" id="commentlist_b_idx"     name="b_idx"     value="<?=$b_idx;?>" />
				<input type="hidden" id="commentlist_bco_idx"   name="bco_idx"   value="" />
				<?=$form_page;?>
				<div id="comment_list_data"></div>
			</form>
		</div>
		<div class="section">
			<span class="btn_big"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>
// -->
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
<?
	}
?>