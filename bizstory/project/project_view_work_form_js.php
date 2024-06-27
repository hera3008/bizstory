
<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();

	var oEditors = []; // 에디터관련
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "workpost_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'work', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#workpost_subject').val(); // 제목
		chk_title = $('#workpost_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#workpost_charge_idx').val(); // 담당자
		chk_title = $('#workpost_charge_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 오늘날짜 이전은 안됨
		chk_value = $('#post_deadline_date2').val();
		chk_value = chk_value.replace(/-/g, '');
		if (chk_value < <?=date('Ymd');?>)
		{
			chk_total = chk_total + '이전 날짜는 선택하실 수 없습니다.<br />';
			action_num++;
		}

		var start_date1 = $('#project_class_start_date').val();
		var start_date  = $('#project_class_start_date').val().replace(/-/g, '');

		var end_date1   = $('#project_class_end_date').val();
		var end_date    = $('#project_class_end_date').val().replace(/-/g, '');

		var chk_date    = $('#post_deadline_date2').val().replace(/-/g, '');

		if (chk_date < start_date)
		{
			chk_total = chk_total + '기한은 시작일 ' + start_date1 + ' 보다 커야합니다.<br />';
			action_num++;
		}
		if (chk_date > end_date)
		{
			chk_total = chk_total + '기한은 종료일 ' + end_date1 + ' 보다 작아야합니다.<br />';
			action_num++;
		}

		oEditors.getById["workpost_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#workpost_remark').val(); // 내용
		chk_title = $('#workpost_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/project/project_view_work_ok.php',
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#project_wi_idx').val(msg.wi_idx);
						project_work_file_check();
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

// 업무종류선택
	function work_type_select()
	{
		var chk_value = $('#workpost_work_type').val();

		$("#workpost_apply_view").css({"display": "none"});
		$("#workpost_charge_view").css({"display": "none"});

		if (chk_value == 'WT03') // 승인일 경우
		{
			$("#workpost_apply_view").css({"display": "block"});
		}

		if (chk_value != 'WT01') // 본인제외
		{
			$("#workpost_charge_view").css({"display": "block"});
		}

		if (chk_value == 'WT01')
		{
			$("#workpost_charge_idx").val('<?=$code_mem;?>');
		}
		else // 요청, 알림, 승인일 경우
		{
			$("#workpost_charge_idx").val('');
		}

		charge_member_list(chk_value);
	}

// 담당자목록, 승인자목록
	function charge_member_list(work_type)
	{
		var apply_idx  = $("#workpost_apply_idx").val();
		var charge_idx = $("#workpost_charge_idx").val();
		var proc_idx   = $("#workpost_proc_idx").val();

		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/project/select_charge_member.php',
			data: {'work_type':work_type, 'apply_idx':apply_idx, 'charge_idx':charge_idx, 'proc_idx':proc_idx},
			success: function(msg) {
				$("#workpost_charge_view").html(msg);
			}
		});

		$.ajax({
			type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/project/select_apply_member.php',
			data: {'work_type':work_type, 'apply_idx':apply_idx, 'charge_idx':charge_idx, 'proc_idx':proc_idx},
			success: function(msg) {
				$("#workpost_apply_view").html(msg);
			}
		});
	}

	charge_member_list('');
//]]>
</script>