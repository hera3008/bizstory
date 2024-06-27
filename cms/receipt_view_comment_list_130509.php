<?
/*
	생성 : 2013.01.21
	수정 : 2013.01.21
	위치 : 접수목록 - 보기 - 댓글목록
*/
	require_once "../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

	$where = " and rc.ri_idx = '" . $ri_idx . "'";
	$list = receipt_comment_data('list', $where, '', $m_page_num, $m_page_size);

	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
<div class="comment" id="comment_list_<?=$data['rc_idx'];?>">
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
<?
	if ($_SESSION[$sess_str . '_ubstory_level'] <= '11') {
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="comment_modify_form('open', '<?=$data['rc_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="comment_delete('<?=$data['rc_idx'];?>')"></a>
<?
	}
?>
	</div>

	<div id="comment_modify_<?=$data['rc_idx'];?>" title="댓글수정"></div>

	<div class="comment_wrap" id="comment_view_<?=$data['rc_idx'];?>">
		<div class="comment_data">
			<div class="user_edit">
				<?=$data['remark'];?>
			</div>
			<div class="file">
<?
	$file_where = " and rcf.rc_idx = '" . $data['rc_idx'] . "'";
	$file_list = receipt_comment_file_data('list', $file_where, '', '', '');

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

				$btn_str = preview_file($comp_receipt_dir, $file_data['rcf_idx'], 'receipt_comment');
?>
					<li>
						<?=$btn_str;?>
						<a href="<?=$local_diir;?>/bizstory/receipt/receipt_view_comment_download.php?rcf_idx=<?=$file_data['rcf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
					</li>
<?
			}
		}
?>
				</ul>
<?
		$btn_img = preview_images($data['rc_idx'], 'receipt_comment');
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
		}
	}
?>
<input type="hidden" id="comment_new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div class="tablefooter_m">
	<?=page_view_comment($m_page_size, $m_page_num, $list['total_page'], 'comment');?>
</div>
<div class ="clear mb01"></div>
<hr />
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 댓글 수정
	function comment_modify_form(form_type, rc_idx)
	{
		$("#commentlist_rc_idx").val(rc_idx);
		if (form_type == 'close')
		{
			$("#comment_modify_" + rc_idx).slideUp("slow");
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
					$("#comment_modify_" + rc_idx).slideUp("slow");
					$("#comment_modify_" + rc_idx).slideDown("slow");
					$("#comment_modify_" + rc_idx).html(msg);
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
			$('#commentlist_rc_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentlistform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
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