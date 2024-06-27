
/*
	수정 : 2013.01.22
	위치 : 업로드파일 관련 자바스크립트
*/
	$(document).ready(function() {
	// File Style
		$(".type_basic").filestyle({
			image: "/common/upload/file_submit.gif",
			imagewidth : 82,
			imageheight : 29
		});
	});

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------ 업로드파일설정
	function file_setting(upload_name, add_name, sort, max_size, upload_ext)
	{
		$('#' + upload_name).uploadify({
			'uploader'  : '/common/upload/uploadify.swf',
			'cancelImg' : '/common/upload/cancel.gif',
			'buttonImg' : '/common/upload/file_submit.gif',
			'wmode'     : 'transparent',
			'auto'      : true,
			'multi'     : false,
			'script'    : '/common/upload/uploadify.php',
			'folder'    : '/data/tmp',
			'fileExt'   : upload_ext,
			'scriptData': {
				'upload_name' : upload_name,
				'add_name'    : add_name,
				'sort'        : sort,
				'file_max'    : max_size,
				'upload_ext'  : upload_ext
			},
			'onSelect': function(event, ID, fileObj) {
				if (fileObj.size > max_size)
				{
					check_auth_popup(fileObj.name + '은 ' + max_size + 'Byte보다 크기 때문에 올릴 수 없습니다.');
					$('#' + upload_name).uploadifyCancel($('.uploadifyQueueItem').first().attr('id').replace('#' + upload_name,''))
				}
			},
			'onComplete': function(event, gueueID, fileObj, response, data) {
				file_complete(response, upload_name, add_name, sort, upload_ext, max_size);
			},
			'onError': function (event, ID, fileObj, errorObj) {
				alert(errorObj.type + ' Error: ' + errorObj.info);
			}
		});
	}

//------------------------------------ 파일저장이후
	function file_complete(response, upload_name, add_name, sort, upload_ext, max_size)
	{
		var str_array = response.split('|');
		if (str_array[0] == 'N')
		{
			alert('지원하지 않는 확장자입니다.');
		}
		else
		{
			var view_html  = '<ul>';
				view_html += '	<li>' + str_array[0] + '(' + str_array[1] + ' Byte)';
				view_html += '		<a href="javascript:void(0);" class="btn_con" onclick="file_delete(\'' + upload_name + '\', \'' + str_array[2] + '\', \'' + add_name + '\', \'' + sort + '\', \'' + upload_ext + '\', \'' + max_size + '\')"><span>삭제</span></a>';
				view_html += '		<input type="hidden" name="' + upload_name + '_save_name" value="' + str_array[2] + '" />';
				view_html += '		<input type="hidden" name="' + upload_name + '_file_name" value="' + str_array[0] + '" />';
				view_html += '		<input type="hidden" name="' + upload_name + '_file_size" value="' + str_array[1] + '" />';
				view_html += '		<input type="hidden" name="' + upload_name + '_file_type" value="' + str_array[3] + '" />';
				view_html += '		<input type="hidden" name="' + upload_name + '_file_ext"  value="' + str_array[4] + '" />';
				view_html += '	</li>';
				view_html += '</ul>';

			$('#' + upload_name + '_view').html(view_html);
		}
	}

//------------------------------------ 선택파일삭제
	function file_delete(upload_name, save_name, add_name, sort, upload_ext, max_size)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'xml', url:'/bizstory/include/file_delete.php',
				data:{'upload_name':upload_name,'save_name':save_name},
				success:function(msg) {
					var success_chk = $(msg).find('success_chk').text();
					var file_view = $(msg).find('file_view').text();

					if (success_chk == "Y")
					{
						$('#' + upload_name + '_view').html(file_view);
						file_setting(upload_name, add_name, sort, max_size, upload_ext);
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}

//------------------------------------ 폼에서 파일삭제
	function file_form_delete(idx, sort)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: {'sub_type':'file_delete', 'idx':idx, 'sort':sort},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						check_auth_popup('정상적으로 처리되었습니다.');
						$('#file_fname' + sort + '_view').html('');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}

//------------------------------------ 파일추가
	function file_form_add(add_name, file_max, file_ext)
	{
		var org_num = $('#upload_fnum').val();
		var chk_num = parseInt(org_num) + 1;

		var string_input  = '<tr>';
			string_input += '	<th><label for="file_subject' + chk_num + '">추가파일</label></th>';
			string_input += '	<td>';
			string_input += '		<div class="left file">';
			string_input += '			<input type="text" name="file_subject' + chk_num + '" id="file_subject' + chk_num + '" class="type_text" title="추가파일제목을 입력하세요." />';
			string_input += '			<input type="file" name="file_fname' + chk_num + '" id="file_fname' + chk_num + '" class="type_text type_file type_multi" title="추가파일 선택하기" />';
			string_input += '		</div>';
			string_input += '		<div class="left file" id="file_fname' + chk_num + '_view"></div>';
			string_input += '	</td>';
			string_input += '	<td>&nbsp;</td>';
			string_input += '</tr>';

		$("#file_table").append(string_input);
		$('#upload_fnum').val(chk_num);
		file_setting('file_fname' + chk_num, add_name, chk_num, file_max, file_ext);
	}

/*

//------------------------------------ 파일추가
	function add_file(form_name)
	{
		var org_num = $('#upload_fnum').val();
		var chk_num = parseInt(org_num) + 1;

		var id_val1 = $("#file_table tr:last div input:text").attr("id").replace("file_subject" + org_num, "file_subject" + chk_num);
		var id_val4 = $("#file_table tr:last div input:text").attr("name").replace("file_subject" + org_num, "file_subject" + chk_num);
		var id_val2 = $("#file_table tr:last div input:file").attr("id").replace("file_fname" + org_num, "file_fname" + chk_num);
		var id_val3 = $("#file_table tr:last div ul").attr("id").replace("upload_fname" + org_num, "upload_fname" + chk_num);

		var newitem = $("#file_table tr:last").clone();
		$("#file_table").append(newitem);

		//newitem.find("td:eq(0)").attr("rowspan", "1");

		$("#file_table tr:last div input:text").attr("id", id_val1);
		$("#file_table tr:last div input:text").attr("name", id_val4);
		$("#file_table tr:last div input:file").attr("id", id_val2);
		$("#file_table tr:last div ul").attr("id", id_val3);

		$('#upload_fnum').val(chk_num);
		file_setting_id("file_fname" + chk_num, '<?=$tmp_dir;?>', '<?=$upload_file_size_max;?>', "upload_fname" + chk_num, 'member', '');
	}
*/

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 한꺼번에 여러개 선택을 할 경우
//------------------------------------ 업로드파일설정
	function file_multi_setting(upload_name, max_size, add_name, upload_ext)
	{
		$('#' + upload_name).uploadify({
			'uploader'  : '/common/upload/uploadify.swf',
			'cancelImg' : '/common/upload/cancel.gif',
			'buttonImg' : '/common/upload/file_submit.gif',
			'wmode'     : 'transparent',
			'auto'      : true,
			'multi'     : true,
			'script'    : '/common/upload/uploadify_multi.php',
			'folder'    : '/data/tmp',
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
				file_multi_complete(response, upload_name, add_name);
			},
			'onError' : function (event, ID, fileObj, errorObj) {
				alert(errorObj.type + ' Error: ' + errorObj.info);
			}
		});
	}

//------------------------------------ 파일저장이후
	function file_multi_complete(response, upload_name, add_name)
	{
		var fup_name = upload_name + file_chk_num;
		var str_array = response.split('|');
		var view_html = '<li id="' + fup_name + '_liview">' + str_array[0] + '-' + file_chk_num + '(' + str_array[1] + ' Byte)';
			view_html += '<a href="javascript:void(0);" class="btn_con" onclick="file_multi_delete(\'' + upload_name + '\', \'' + str_array[2] + '\', \'' + add_name + '\', \'' + file_chk_num + '\')"><span>삭제</span></a>';
			view_html += '<input type="hidden" name="' + fup_name + '_save_name" value="' + str_array[2] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_name" value="' + str_array[0] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_size" value="' + str_array[1] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_type" value="' + str_array[3] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_ext"  value="' + str_array[4] + '" />';
			view_html += '</li>';

		$('#' + upload_name + '_view').append(view_html);
		$('#file_upload_num').val(file_chk_num);
		//alert($('#file_upload_num').val() + '\n\n' + file_chk_num);
		file_chk_num++;
	}

//------------------------------------ 선택파일삭제
	function file_multi_delete(upload_name, save_name, add_name, sort)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'xml', url:'/bizstory/include/file_multi_delete.php',
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
	function file_multi_form_delete(idx, sort)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
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











///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// file_setting_id -> file_setting 으로 변경하여 아래함수들 삭제할것
//------------------------------------ 업로드파일설정
	function file_setting_id(upload_name, upload_folder, max_size, view_name, add_name, upload_ext)
	{
		$('#' + upload_name).uploadify({
			'uploader'  : '/common/upload/uploadify.swf',
			'cancelImg' : '/common/upload/cancel.gif',
			'buttonImg' : '/common/upload/file_submit.gif',
			'wmode'     : 'transparent',
			'auto'      : true,
			'multi'     : false,
			'script'    : '/common/upload/uploadify.php',
			'folder'    : upload_folder,
			'scriptData': {
				'upload_name' : upload_name,
				'add_name'    : add_name,
				'view_name'   : view_name,
				'file_max'    : max_size,
				'upload_ext'  : upload_ext
			},
			'onSelect': function(event, ID, fileObj) {
				if (fileObj.size > max_size)
				{
					check_auth_popup(fileObj.name + '은 ' + max_size + 'Byte보다 크기 때문에 올릴수 없습니다.');
					$('#' + upload_name).uploadifyCancel($('.uploadifyQueueItem').first().attr('id').replace('#' + upload_name,''))
				}
			},
			'onComplete': function(event, gueueID, fileObj, response, data) {
				file_complete_id(response, view_name, upload_name);
			},
			'onError'   : function (event, ID, fileObj, errorObj) {
				alert(errorObj.type + ' Error: ' + errorObj.info);
			}
		});
	}

//------------------------------------ 파일저장이후
	function file_complete_id(response, view_name, upload_name)
	{
		var str_array = response.split('|');
		var view_html = '<li>' + str_array[0] + '(' + str_array[1] + ' Byte)<br />';
			view_html += '<input type="hidden" name="' + upload_name + '_save_name" value="' + str_array[2] + '" />';
			view_html += '<input type="hidden" name="' + upload_name + '_file_name" value="' + str_array[0] + '" />';
			view_html += '<input type="hidden" name="' + upload_name + '_file_size" value="' + str_array[1] + '" />';
			view_html += '<input type="hidden" name="' + upload_name + '_file_type" value="' + str_array[3] + '" />';
			view_html += '<input type="hidden" name="' + upload_name + '_file_ext"  value="' + str_array[4] + '" /></li>';
		$('#' + view_name).append(view_html);
		$('#' + upload_name + '_view').html('');
	}

//------------------------------------ 선택파일삭제
	function file_delete_id(view_name, idx, org_idx)
	{
		$('#file_sub_type').val('file_delete');
		$('#file_idx').val(idx);
		$('#file_org_idx').val(org_idx);
		$.ajax({
			url     : link_ok,
			data    : $('#fileform').serialize(),
			success : function(msg) {
				if (msg.success_chk == "Y")
				{
					$('#' + view_name).html('');
				}
				else check_auth_popup(msg.error_string);
			}
		});
		return false;
	}

//------------------------------------ 파일추가
	function add_file(form_name)
	{
		var org_num = $('#upload_fnum').val();
		var chk_num = parseInt(org_num) + 1;

		var id_val1 = $("#file_table tr:last div input:text").attr("id").replace("file_subject" + org_num, "file_subject" + chk_num);
		var id_val4 = $("#file_table tr:last div input:text").attr("name").replace("file_subject" + org_num, "file_subject" + chk_num);
		var id_val2 = $("#file_table tr:last div input:file").attr("id").replace("file_fname" + org_num, "file_fname" + chk_num);
		var id_val3 = $("#file_table tr:last div ul").attr("id").replace("upload_fname" + org_num, "upload_fname" + chk_num);

		var newitem = $("#file_table tr:last").clone();
		$("#file_table").append(newitem);

		//newitem.find("td:eq(0)").attr("rowspan", "1");

		$("#file_table tr:last div input:text").attr("id", id_val1);
		$("#file_table tr:last div input:text").attr("name", id_val4);
		$("#file_table tr:last div input:file").attr("id", id_val2);
		$("#file_table tr:last div ul").attr("id", id_val3);

		$('#upload_fnum').val(chk_num);
		file_setting_id("file_fname" + chk_num, '<?=$tmp_dir;?>', '<?=$upload_file_size_max;?>', "upload_fname" + chk_num, 'member', '');
	}