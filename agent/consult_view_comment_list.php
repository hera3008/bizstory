<?
/*
	생성 : 2012.10.12
	수정 : 2012.10.12
	위치 : 상담게시판 - 보기 - 댓글목록
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/agent/include/agent_chk.php";
	require_once $local_path . "/bizstory/common/no_direct.php";

	$where = " and consc.cons_idx = '" . $cons_idx . "'";
	$list = consult_comment_data('list', $where, '', '', '');

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 읽음으로 표시 - 자기것은 제외
			$cc_data['chk_mac']   = $macaddress;
			$cc_data['cons_idx']  = $cons_idx;
			$cc_data['consc_idx'] = $data['consc_idx'];
			consult_data_read($cc_data);

			$mem_img = member_img_view($data['mem_idx'], $staff_dir); // 등록자 이미지

			$left_margin_css = ' reply_margin' . $data['tgno']; // 답변글 들여쓰기 0부터 시작 0일 경우 원글
?>
<div class="comment <?=$left_margin_css;?>" id="comment_list_<?=$data['consc_idx'];?>">
	<div class="comment_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user"><a class="name_ui"><?=$data['writer'];?></a></span>
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

				$btn_str = preview_file($consult_dir, $file_data['conscf_idx'], 'consult_comment');
?>
					<li>
						<?=$btn_str;?>
						<a href="<?=$local_diir;?>/agent/consult_view_comment_download.php?conscf_idx=<?=$file_data['conscf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>