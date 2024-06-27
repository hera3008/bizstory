<?
/*
	수정 : 2013.01.21
	위치 : 파일등록
*/
	require_once "../../common/setting.php";
	include $local_path . "/bizstory/common/member_chk.php";
	include $local_path . "/include/top_upload.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$file_upload_num = 0;
	$file_chk_num    = $file_upload_num + 1;

	$upload_dir         = '/filemanager/bizstory/tmp';
	$upload_file_check  = 'http://220.90.137.171/filemanager/site_file_temp.php';
	$upload_file_delete = 'http://220.90.137.171/filemanager/site_file_delete.php';
	$upload_file_ok     = 'http://220.90.137.171/filemanager/site_file_ok.php';
?>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 업로드파일설정
	function multi_setting(upload_name, max_size, add_name, upload_ext)
	{
		$('#' + upload_name).uploadify({
			'uploader'  : '/common/upload/uploadify.swf',
			'cancelImg' : '/common/upload/cancel.gif',
			'buttonImg' : '/common/upload/file_submit.gif',
			'wmode'     : 'transparent',
			'auto'      : true,
			'multi'     : true,
			'script'    : '<?=$upload_file_check;?>',
			'folder'    : '<?=$upload_dir;?>',
			'scriptData': {
				'upload_name' : upload_name,
				'add_name'    : add_name,
				'file_max'    : max_size,
				'upload_ext'  : upload_ext,
				'sort'        : file_chk_num
			},
			'onSelect': function(event, ID, fileObj) {
				if (fileObj.size > max_size)
				{
					check_auth_popup(fileObj.name + '은 ' + max_size + 'Byte보다 크기 때문에 올릴 수 없습니다.');
					$('#' + upload_name).uploadifyCancel($('.uploadifyQueueItem').first().attr('id').replace('#' + upload_name,''));
				}
			},
			'onComplete': function(event, gueueID, fileObj, response, data) {
				multi_complete(response, upload_name, add_name);
			},
			'onError' : function (event, ID, fileObj, errorObj) {
				alert(errorObj.type + ' Error: ' + errorObj.info);
			}
		});
	}

//------------------------------------ 파일저장이후
	function multi_complete(response, upload_name, add_name)
	{
		var fup_name = upload_name + file_chk_num;
		var str_array = response.split('|');
		var view_html = '<li id="' + fup_name + '_liview">' + str_array[0] + '-' + file_chk_num + '(' + str_array[1] + ' Byte)';
			view_html += '<a href="javascript:void(0);" class="btn_con" onclick="multi_delete(\'' + upload_name + '\', \'' + str_array[2] + '\', \'' + add_name + '\', \'' + file_chk_num + '\')"><span>삭제</span></a>';
			view_html += '<input type="hidden" name="' + fup_name + '_save_name" value="' + str_array[2] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_name" value="' + str_array[0] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_size" value="' + str_array[1] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_type" value="' + str_array[3] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_ext"  value="' + str_array[4] + '" />';
			view_html += '</li>';

		$('#' + upload_name + '_view').append(view_html);
		$('#file_upload_num').val(file_chk_num);
		file_chk_num++;
	}

//------------------------------------ 선택파일삭제
	function multi_delete(upload_name, save_name, add_name, sort)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'xml', url:'<?=$upload_file_delete;?>', // 수정할 곳
				data:{'upload_name':upload_name,'save_name':save_name},
				success:function(msg) {
					var success_chk = $(msg).find('success_chk').text();
					var file_view = $(msg).find('file_view').text();

					if (success_chk == "Y")
					{
						$('#' + upload_name + sort + '_liview').html('');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}

//------------------------------------ 폼에서 파일삭제
	function multi_form_delete(idx, sort)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$upload_file_ok;?>',
				data: {'sub_type':'file_delete', 'idx':idx},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup('정상적으로 처리되었습니다.');
						$('#file_fname_' + sort + '_liview').html('');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}
//]]>
</script>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">접수정보 폼</legend>
			<table class="tinytable write" summary="접수정보를 등록/수정합니다.">
			<caption>접수정보</caption>
			<colgroup>
				<col width="80px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td colspan="3">
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view"></ul>
							</div>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big fl"><input type="button" value="등록하기" onclick="check_form()" /></span>
					<span class="btn_big fl"><input type="button" value="등록취소" onclick="location.href = '<?=$this_page;?>?<?=$f_all;?>'" /></span>

					<input type="hidden" name="sub_type" value="post" />
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var file_chk_num = <?=$file_chk_num;?>;
	multi_setting('file_fname', '<?=$file_multi_size;?>', 'temp_check', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		$.ajax({
			type: 'post', dataType: 'json', url: link_ok,
			data: $('#postform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					list_data();
				}
				else check_auth_popup(msg.error_string);
			}
		});
	}
//]]>
</script>

<?
	include $local_path . "/include/tail.php";
?>
