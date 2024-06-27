
//------------------------------------ 메모 열기/닫기
	function memo_view()
	{
		if (memo_chk_val == 'close')
		{
			memo_chk_val = 'open';
			$('#memo_list_data').html('');
			$("#comment_gate").removeClass('btn_i_minus');
			$("#comment_gate").addClass('btn_i_plus');
		}
		else
		{
			memo_insert_form('close');
			memo_chk_val = 'close';
			memo_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 메모 목록
	function memo_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: memo_list,
			data: $('#memolistform').serialize(),
			success: function(msg) {
				$('#memo_list_data').html(msg);
			}
		});
	}

//------------------------------------ 메모 등록
	function memo_insert_form(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_memo").slideUp("slow");
			$("#new_memo").html('');
			$('#comment_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: memo_form,
				data: $('#postform').serialize(),
				success: function(msg) {
					memo_chk_val = 'close';
					memo_view();

					$('#comment_new_btn').css({'display':'none'});
					$("#new_memo").slideUp("slow");
					$("#new_memo").slideDown("slow");
					$("#new_memo").html(msg);
				}
			});
		}
	}

//------------------------------------ 메모 수정
	function memo_modify_form(form_type, cim_idx)
	{
		if (form_type == 'close')
		{
			$("#memolist_cim_idx").val('');
			memo_chk_val = 'open';
			memo_view();
		}
		else
		{
			$("#memolist_cim_idx").val(cim_idx);
			$.ajax({
				type: "post", dataType: 'html', url: memo_form,
				data: $('#memolistform').serialize(),
				success: function(msg) {
					memo_chk_val = 'close';
					memo_view();

					$("#new_memo").slideUp("slow");
					$("#new_memo").slideDown("slow");
					$("#new_memo").html(msg);
				}
			});
		}
	}

//------------------------------------ 메모등록/수정
	function check_memo_form(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		oEditors_memo.getById["memopost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#memopost_remark').val(); // 내용
		chk_title = $('#memopost_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: "post", dataType: 'json', url: memo_ok,
				data: $('#memoform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환

						$('#memo_total_value').html(msg.total_num);
						if (idx == '')
						{
							memo_insert_form('close');
						}
						else
						{
							memo_modify_form('close','');
						}
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

//------------------------------------ 메모 삭제
	function memo_delete(idx)
	{
		if (confirm("선택하신 메모을 삭제하시겠습니까?"))
		{
			$('#memolist_sub_type').val('delete');
			$('#memolist_cim_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: memo_ok,
				data: $('#memolistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#memo_total_value').html(msg.total_num);
						memo_modify_form('close')
						memo_list_data();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}

//------------------------------------ 폼에서 파일삭제
	function memo_file_delete(idx, sort)
	{
		$("#popup_notice_view").hide();
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			var comp_idx = $('#memolist_comp_idx').val();
			var part_idx = $('#memolist_part_idx').val();
			$.ajax({
				type: 'post', dataType: 'json', url: memo_ok,
				data: {'sub_type':'file_delete', 'idx':idx, 'comp_idx':comp_idx, 'part_idx':part_idx},
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
