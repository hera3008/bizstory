<?
/*
	생성 : 2012.05.16
	위치 : 게시판 - 보기 - 댓글목록
*/
	require_once "../../bizstory/common/setting.php";
	require_once $local_path . "/cms/include/client_chk.php";
	require_once $local_path . "/cms/include/no_direct.php";

// 게시판설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "' and bs.view_yn = 'Y'";
	$set_board = pro_board_set_data("view", $set_where);
	$set_board['name_db'] = 'pro_board_biz_' . $set_board['comp_idx'];

// 게시물정보
	$board_where = " and b.b_idx = '" . $b_idx . "'";
	$board_data = pro_board_info_data('view', $set_board['name_db'], $board_where);

// 해당게시물의 댓글
	$where = " and bco.bs_idx = '" . $bs_idx . "' and bco.b_idx = '" . $b_idx . "'";
	$list = pro_board_comment_data('list', $where, '', '', '');

	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$sub_where = " and mem.mem_idx = '" . $data['mem_idx'] . "'";
			$sub_data = member_info_data('view', $sub_where);
?>
<div class="report" id="comment_list_<?=$data['bco_idx'];?>">
	<div class="report_info">
		<span class="user"><?=$data['writer'];?></span>
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
	if ($data['mem_idx'] == $_SESSION[$sess_str . '_mem_idx'] || $_SESSION[$sess_str . '_ubstory_level'] <= '11')
	{
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="comment_modify_form('open', '<?=$data['bco_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="comment_delete('<?=$data['bco_idx'];?>')"></a>
<?
	}
?>
	</div>

	<div id="comment_modify_<?=$data['bco_idx'];?>" title="댓글수정"></div>

	<div class="report_wrap" id="comment_view_<?=$data['bco_idx'];?>">
		<div class="report_data">
			<div class="user_edit">
				<?=$data['remark'];?>
			</div>
			<div class="file">
<?
	$file_where = " and bcof.bco_idx = '" . $data['bco_idx'] . "'";
	$file_list = pro_board_comment_file_data('list', $file_where, '', '', '');

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
?>
					<li>
						<a href="<?=$local_diir;?>/cms/board_project/comment_download.php?bcof_idx=<?=$file_data['bcof_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
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
//------------------------------------ 댓글 수정
	function comment_modify_form(form_type, bco_idx)
	{
		$("#commentlist_bco_idx").val(bco_idx);
		if (form_type == 'close')
		{
			$("#comment_modify_" + bco_idx).slideUp("slow");
			$("#comment_modify_" + bco_idx).html("");
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
					$("#comment_modify_" + bco_idx).slideUp("slow");
					$("#comment_modify_" + bco_idx).slideDown("slow");
					$("#comment_modify_" + bco_idx).html(msg);
				}
			});
		}
	}

//------------------------------------ 댓글 삭제
	function comment_delete(idx)
	{
		if (confirm("선택하신 보고서를 삭제하시겠습니까?"))
		{
			$('#commentlist_sub_type').val('delete');
			$('#commentlist_bco_idx').val(idx);

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