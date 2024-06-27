<?
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

	$ri_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = '';
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shstatus=' . $send_shstatus;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="comp_idx"    value="' . $client_comp . '" />
		<input type="hidden" name="part_idx"    value="' . $client_part . '" />
		<input type="hidden" name="client_idx"  value="' . $client_idx . '" />
		<input type="hidden" name="client_code" value="' . $client_code . '" />
		<input type="hidden" name="macaddress"  value="' . $macaddress . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="shstatus"  value="' . $send_shstatus . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $receipt_path;
	$receipt_info->data_dir = $receipt_dir;

	$data        = $receipt_info->receipt_info_view();
	$file_list   = $receipt_info->receipt_file();
	$status_list = $receipt_info->receipt_status_only();
?>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="viewform" name="viewform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="ri_idx"   id="view_ri_idx"   value="<?=$ri_idx;?>" />

		<fieldset>
			<legend class="blind">접수정보 상세보기</legend>
			<table class="tinytable view" summary="접수정보를 상세하게 봅니다.">
			<caption>접수정보</caption>
			<colgroup>
				<col width="100px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
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
						<div class="left1"><?=$data['writer'];?> (<a href="tel:<?=$data['tel_num'];?>" class="tel"><?=$data['tel_num'];?></a>)</div>
					</td>
				</tr>
				<tr>
					<th>제목</th>
					<td colspan="3">
						<div class="left"><strong><?=$data['subject'];?></strong></div>
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

								$btn_str = preview_file($receipt_dir, $file_data['rf_idx'], 'receipt');
				?>
								<li>
									<?=$btn_str;?>
									<a href="<?=$local_dir;?>/agent/receipt_download.php?rf_idx=<?=$file_data['rf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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
							<div>
						<?
							include "receipt_view_section.php";
						?>
							</div>
							<div class="dotted2"></div>
							<div class="status_info">
					<?
						foreach ($status_list as $status_k => $status_v)
						{
							foreach ($status_v as $status_k1 => $status_data)
							{
								echo $status_data;
							}
						}
					?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 코멘트
	$comment_where = " and rc.ri_idx = '" . $ri_idx . "'";
	$comment_list = receipt_comment_data('list', $comment_where, '', '', '');

	$rc_data['chk_ci']  = $client_idx;
	$rc_data['chk_mac'] = $macaddress;
	$rc_data['ri_idx']  = $ri_idx;
	$read_chk = receipt_read_check($rc_data);

	$data['read_comment'] = $read_chk['read_comment'];
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
				<input type="hidden" id="commentlist_code_part" name="code_part" value="<?=$data['part_idx'];?>" />
				<input type="hidden" id="commentlist_ri_idx"    name="ri_idx"    value="<?=$ri_idx;?>" />
				<input type="hidden" id="commentlist_rc_idx"    name="rc_idx"    value="" />
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
	var comment_list = '<?=$local_dir;?>/agent/receipt_view_comment_list.php';
	var comment_form = '<?=$local_dir;?>/agent/receipt_view_comment_form.php';
	var comment_ok   = '<?=$local_dir;?>/agent/receipt_view_comment_ok.php';

//------------------------------------ 분리폼
	function plural_remark(str)
	{
		$('#plural_remark_' + str).css({"display":"block"});
	}

//------------------------------------ 댓글 등록
	function comment_insert_form(form_type)
	{
		$('#comment_writer').val($.cookie('login_writer_save'));

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
