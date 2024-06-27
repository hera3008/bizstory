<?
/*
	생성 : 2012.12.14
	수정 : 2013.05.21
	위치 : 게시판 - 보기 - 댓글 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$where = " and bco.bco_idx = '" . $bco_idx . "'";
	$data = comp_bbs_comment_data('view', $where);
?>
<div class="new_report">
	<form name="commentform" id="commentform" method="post" action="<?=$this_page;?>" onsubmit="return check_comment_form()">
		<input type="hidden" name="bs_idx" value="<?=$bs_idx;?>" />
		<input type="hidden" name="b_idx"  value="<?=$b_idx;?>" />
		<div class="form">
			<textarea name="param[remark]" id="commentpost_remark" cols="50" rows="10" title="댓글내용을 입력하세요."><?=$data['remark'];?></textarea>
		</div>
		<div class="action">
	<?
		if ($bco_idx == '') {
	?>
			<span class="btn_big_green"><input type="submit" value="등록" /></span>
			<span class="btn_big_green"><input type="button" value="취소" onclick="comment_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		} else {
	?>
			<span class="btn_big_blue"><input type="submit" value="수정" /></span>
			<span class="btn_big_blue"><input type="button" value="취소" onclick="comment_modify_form('close', '<?=$bco_idx;?>')" /></span>

			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="bco_idx"  value="<?=$bco_idx;?>" />
	<?
		}
	?>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
// 에디터관련
	var oEditors_comment = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_comment,
		elPlaceHolder: "commentpost_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
			}
		},
		fCreator: "createSEditor2"
	});

//------------------------------------ 댓글등록/수정
	function check_comment_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		oEditors_comment.getById["commentpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#commentpost_remark').val(); // 내용
		chk_title = $('#commentpost_remark').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: comment_ok,
				data: $('#commentform').serialize(),
				success: function(msg) {
					$("#loading").fadeIn('slow').fadeOut('slow');
					if (msg.success_chk == "Y")
					{
						$('#comment_total_value').html(msg.total_num);
	<?
		if ($bco_idx == '') {
	?>
						comment_insert_form('close')
	<?
		} else {
	?>
						comment_modify_form('close','')
	<?
		}
	?>
						comment_list_data();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>