<?
/*
	생성 : 2012.04.07
	수정 : 2012.09.27
	위치 : 업무폴더 > 나의업무 > 업무 - 보기 - 댓글목록
*/
	require_once "../common/set_info.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$where = " and wc.wi_idx = '" . $wi_idx . "'";
	$list = work_comment_data('list', $where, '', $m_page_num, $m_page_size);

	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
		// 업무보고 읽음으로 표시 - 자기것은 제외
			work_report_comment_check($wi_idx, '', $data['wc_idx']);

			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
<div class="comment" id="comment_list_<?=$data['wc_idx'];?>">
	<div class="comment_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user">
			<span class="relative">
				<a href="javascript:void(0)" onclick="viewMemInfo(<?=$data['mem_idx']?>);" data-modal="modal" class="md-trigger name_ui"><?=$data['writer'];?></a>
				<div id="objcomment_<?=$data['mem_idx'];?>_<?=$wi_idx;?>_<?=$data['wc_idx'];?>" class="none"></div>
			</span>
		</span>
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
	if ($data['mem_idx'] == $_SESSION[$sess_str . '_mem_idx'] || $_SESSION[$sess_str . '_ubstory_level'] <= '11') {
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="comment_modify_form('open', '<?=$data['wc_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="comment_delete('<?=$data['wc_idx'];?>')"></a>
<?
	}
?>
	</div>

	<div id="comment_modify_<?=$data['wc_idx'];?>" title="댓글수정"></div>

	<div class="comment_wrap" id="comment_view_<?=$data['wc_idx'];?>">
		<div class="comment_data">
			<div class="user_edit">
				<?=$data['remark'];?>
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
<div class ="clear"></div>
<hr />

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 댓글 수정
	function comment_modify_form(form_type, wc_idx)
	{
		$("#commentlist_wc_idx").val(wc_idx);
		if (form_type == 'close')
		{
			$("#comment_modify_" + wc_idx).slideUp("slow");
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: comment_form,
				data: $('#commentlistform').serialize(),
				beforeSubmit: function(){
					//$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					$("#comment_modify_" + wc_idx).slideUp("slow");
					$("#comment_modify_" + wc_idx).slideDown("slow");
					$("#comment_modify_" + wc_idx).html(msg);
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
			$('#commentlist_wc_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentlistform').serialize(),
				beforeSubmit: function(){
					//$("#loading").fadeIn('slow').fadeOut('slow');
				},
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
