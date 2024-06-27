<?
/*
	수정 : 2012.10.09
	위치 : 설정관리 > 에이전트관리 > 상담게시판 > 상담게시판 - 보기 - 댓글목록
*/
	require_once "../common/set_info.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$where = " and consc.cons_idx = '" . $cons_idx . "'";
	$list = consult_comment_data('list', $where, '', '', '');

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지

			$left_margin_css = ' reply_margin' . $data['tgno']; // 답변글 들여쓰기 0부터 시작 0일 경우 원글
?>
<div class="comment <?=$left_margin_css;?>" id="comment_list_<?=$data['consc_idx'];?>">
	<div class="comment_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user"><a class="name_ui" id="camp_member_243560"><?=$data['writer'];?></a></span>
		<span class="date">
<?
			$chk_date = date_replace($data['reg_date'], 'Y-m-d');
			if ($chk_date == date('Y-m-d'))
			{
				echo '<strong>', $data['reg_date'] , '</strong>';
			}
			else
			{
				echo $data['reg_date'];
			}
?>
		</span>
		<a class="btn_i_reply" href="javascript:void(0)" onclick="comment_reply_form('open', '<?=$data['consc_idx'];?>')"></a>
<?
	if ($code_level <= '11') {
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="comment_modify_form('open', '<?=$data['consc_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="comment_delete('<?=$data['consc_idx'];?>')"></a>
<?
	}
?>
	</div>

	<div id="comment_modify_<?=$data['consc_idx'];?>" title="댓글수정"></div>

	<div class="comment_wrap" id="comment_view_<?=$data['consc_idx'];?>">
		<div class="comment_data">
			<div class="user_edit">
				<?=$data['remark'];?>
			</div>
			<div class="file">
<?
	$file_where = " and conscf.consc_idx = '" . $data['consc_idx'] . "'";
	$file_list = consult_comment_file_data('list', $file_where, '', '', '');

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

				$btn_str = preview_file($comp_consult_dir, $file_data['conscf_idx'], 'consult_comment');
?>
					<li>
						<?=$btn_str;?>
						<a href="<?=$local_diir;?>/bizstory/consult/consult_view_comment_download.php?conscf_idx=<?=$file_data['conscf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
					</li>
<?
			}
		}
?>
				</ul>
<?
		$btn_img = preview_images($data['consc_idx'], 'consult_comment');
		if ($btn_img != '')
		{
			echo '
				<div>' . $btn_img . '</div>
			';
		}
	}
?>
			</div>
		</div>
	</div>

</div>
<?
			$num--;
		}
	}
?>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 댓글 답변
	function comment_reply_form(form_type, consc_idx)
	{
		$("#commentlist_consc_idx").val(consc_idx);
		$("#commentlist_sub_type").val('reply_form');

		if (form_type == 'close')
		{
			$("#comment_modify_" + consc_idx).slideUp("slow");
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#commentlistform').serialize(),
				success: function(msg) {
					$("#comment_modify_" + consc_idx).slideUp("slow");
					$("#comment_modify_" + consc_idx).slideDown("slow");
					$("#comment_modify_" + consc_idx).html(msg);
				}
			});
		}
	}

//------------------------------------ 댓글 수정
	function comment_modify_form(form_type, consc_idx)
	{
		$("#commentlist_consc_idx").val(consc_idx);
		if (form_type == 'close')
		{
			$("#comment_modify_" + consc_idx).slideUp("slow");
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#commentlistform').serialize(),
				success: function(msg) {
					$("#comment_modify_" + consc_idx).slideUp("slow");
					$("#comment_modify_" + consc_idx).slideDown("slow");
					$("#comment_modify_" + consc_idx).html(msg);
				}
			});
		}
	}

//------------------------------------ 댓글 삭제
	function comment_delete(idx)
	{
		if (confirm("선택하신 댓글을 삭제하시겠습니까?"))
		{
			$('#commentlist_sub_type').val('delete');
			$('#commentlist_consc_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#comment_total_value').html(msg.total_num);
						comment_modify_form('close')
						comment_list_data();
						//list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>
<?
	db_close();
?>