<?
/*
	생성 : 2012.10.12
	수정 : 2012.10.12
	위치 : 상담게시판 - 보기
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

	$cons_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="comp_idx"    value="' . $client_comp . '" />
		<input type="hidden" name="part_idx"    value="' . $client_part . '" />
		<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
		<input type="hidden" name="client_code" value="' . $client_code . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$chk_where = " and cons.cons_idx = '" . $cons_idx . "'";
	$chk_data = consult_info_data('view', $chk_where);

	$data = consult_list_data($cons_idx, $chk_data);

// 파일목록
	$file_where = " and consf.cons_idx = '" . $cons_idx . "'";
	$file_list = consult_file_data('list', $file_where, '', '', '');

	unset($chk_data);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="cons_idx" id="view_cons_idx" value="<?=$cons_idx;?>" />

		<fieldset>
			<legend class="blind">상담 상세보기</legend>
			<table class="tinytable view" summary="상담 상세보기입니다.">
			<caption>상담 상세보기</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>분류</th>
					<td>
						<div class="left">
				<?
					$consult_class = $data['class_str']['code_name'];
					if (is_array($consult_class))
					{
						foreach ($consult_class as $k => $v)
						{
							if ($k == 1) echo $v;
							else echo ' &gt; ', $v;
						}
					}
				?>
						</div>
					</td>
					<th>등록자</th>
					<td>
						<div class="left1">
							<?=$data['writer'];?> (<a href="tel:<?=$data['tel_num'];?>" class="tel"><?=$data['tel_num'];?></a>)
							- <?=$data['reg_date'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th>담당자</th>
					<td colspan="3">
						<div class="left">
							<?=$data['total_charge_str'];?>
						</div>
					</td>
				</tr>
				<tr>
					<th>제목</th>
					<td colspan="3">
						<div class="left">
							<strong><?=$data['subject'];?></strong>
							<?=$data['important_img'];?>
							<?=$data['total_file_str'];?>
							<?=$data['total_comment_str'];?>
							<?=$data['read_comment_str'];?>
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

								$btn_str = preview_file($consult_dir, $file_data['consf_idx'], 'consult');
				?>
								<li>
									<?=$btn_str;?>
									<a href="<?=$local_dir;?>/agency/consult_download.php?consf_idx=<?=$file_data['consf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
								</li>
				<?
							}
						}
						$btn_img = preview_images($cons_idx, 'consult');
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
					unset($file_data);
					unset($file_list);
				?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
		</fieldset>
		</form>

		<div class="dotted2"></div>

		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="코멘트목록" onclick="comment_view()"></a> 코멘트 <span id="comment_total_value">[<?=number_format($data['total_comment']);?>]</span>
					<?=$data['read_comment_str'];?>
				</p>
				<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_comment.png" alt="코멘트 쓰기" class="pointer" onclick="comment_insert_form('open')" /></div>
			</div>

			<div id="new_comment" title="코멘트쓰기"></div>

			<form id="commentlistform" name="commentlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="commentlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$data['part_idx'];?>" />
				<input type="hidden" id="commentlist_cons_idx"  name="cons_idx"  value="<?=$cons_idx;?>" />
				<input type="hidden" id="commentlist_consc_idx" name="consc_idx" value="" />
				<?=$form_page;?>
				<div id="comment_list_data"></div>
			</form>
		</div>

		<div class="section">
			<div class="fr">
				<span class="btn_big_violet"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 댓글 관련
	var comment_list = '<?=$local_dir;?>/agent/consult_view_comment_list.php';
	var comment_form = '<?=$local_dir;?>/agent/consult_view_comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/agent/consult_view_comment_ok.php';

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
