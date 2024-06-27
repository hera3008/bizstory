<?
/*
	생성 : 2012.05.03
	수정 : 2012.05.08
	위치 : 업무폴더 > 나의업무 > 업무 - 보기 - 업무보고서 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	if($wr_idx != ""){
		$where = " and wr.wr_idx = '" . $wr_idx . "'";
		$data = work_report_data('view', $where);

		$file_where = " and wrf.wr_idx = '" . $data['wr_idx'] . "'";
		$file_list = work_report_file_data('list', $file_where, '', '', '');

		$file_query = "select max(sort) as sort from work_report_file where wr_idx = '" . $wr_idx . "'";
		$file_chk = query_view($file_query);
		$file_sort = ($file_chk['sort'] == '') ? '0' : $file_chk['sort'];

		$file_upload_num = $file_chk['sort'];
		$file_chk_num    = $file_upload_num + 1;
	}else{
		$file_list = array();
		$file_upload_num = 0;
		$file_chk_num = 1;
	}
?>

<div class="new_report">
	<form name="reportform" id="reportform" method="post" action="<?=$this_page;?>" onsubmit="return check_report_form()">
		<input type="hidden" name="wi_idx" value="<?=$wi_idx;?>" />
		<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
		<div class="form">
			<textarea name="param[remark]" id="reportpost_remark" cols="50" rows="10" title="업무보고서내용을 입력하세요."><?=$data['remark'];?></textarea>
			<!--div class="left"><a href="javascript:void(0);" onclick="popup_file()" class="btn_big_green"><span>파일업로드</span></a></div-->
			<div class="filewrap">
				<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
				<div class="file">
					<ul id="file_fname_add_view">
<?
	foreach ($file_list as $file_k => $file_data)
	{
		if (is_array($file_data))
		{
			$file_chk = $file_data['sort'];
			$fsize = $file_data['img_size'];
			$fsize = byte_replace($fsize);
?>
					<li id="file_fname_<?=$file_chk;?>_view" class="org_file">
						<a href="<?=$local_diir;?>/bizstory/work/work_report_download.php?wrf_idx=<?=$file_data['wrf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
						<a href="javascript:void(0);" class="btn_con" onclick="sub_file_delete('<?=$file_data['wrf_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
					</li>
<?
		}
	}
?>
					</ul>
				</div>
			</div>
		</div>
		<div class="action">
	<?
		if ($wr_idx == '') {
	?>
			<span class="btn_big_green"><input type="submit" value="등록" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="report_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		} else {
	?>
			<span class="btn_big_blue"><input type="submit" value="수정" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="report_modify_form('close', '<?=$wr_idx;?>')" /></span>

			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="wr_idx"   value="<?=$wr_idx;?>" />
	<?
		}
	?>
		</div>
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	//file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'work_report', '');
	file_setting('file_fname', 'work_report', '', '<?=$file_multi_size;?>', '');

	// 에디터관련
	var oEditors_report = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_report,
		elPlaceHolder: "reportpost_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
			}
		},
		fCreator: "createSEditor2"
	});

//------------------------------------ 업무보고서등록/수정
	function check_report_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		oEditors_report.getById["reportpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#reportpost_remark').val(); // 내용
		chk_title = $('#reportpost_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: report_ok,
				data: $('#reportform').serialize(),
				success: function(msg) {
					$("#loading").fadeIn('slow').fadeOut('slow');
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환

						$('#report_total_value').html(msg.total_num);
	<?
		if ($wr_idx == '') {
	?>
						report_insert_form('close');
	<?
		} else {
	?>
						report_modify_form('close','');
	<?
		}
	?>
						report_list_data();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 폼에서 파일삭제
	function sub_file_delete(idx, sort)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: report_ok,
				data: {'sub_type':'file_delete', 'idx':idx},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup('정상적으로 처리되었습니다.');
						$('#file_fname_' + sort + '_view').html('');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}

	//------------------------------------ 파일업로드 폼
	function popup_file()
	{
<?
		$link_file = $local_dir . "/bizstory/filecenter/biz/file_html.php";        // 파일업로드
?>
	
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$link_file;?>',
			data: $('#postform').serialize(),
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form2").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form2").html(msg);
			}
		});
	}
//]]>
</script>