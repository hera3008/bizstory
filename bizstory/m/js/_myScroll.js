//<![CDATA[
	var myScroll,
		pullDownEl, pullDownOffset,
		pullUpEl, pullUpOffset,
		generatedCount = 0;

	function pullDownAction ()
	{
		setTimeout(function () { // <-- Simulate network congestion, remove setTimeout from production!

		// 데이타 출력
			$.ajax({
				async : false,
				type: "get", dataType: 'html', url: '/bizstory/m2/process/ajax_list.php',
				data: $('#moreform').serialize(),
				success: function(msg) {
					$("#thelist").append(msg);
					var more_num  = 1;
					$("#morenum").val(parseInt(more_num) + 1);
				}
			});

			myScroll.refresh(); // Remember to refresh when contents are loaded (ie: on ajax completion)
		}, 1000); // <-- Simulate network congestion, remove setTimeout from production!
	}

	function pullUpAction ()
	{
		setTimeout(function () { // <-- Simulate network congestion, remove setTimeout from production!

		// 데이타 출력
			$.ajax({
				async : false,
				type: "get", dataType: 'html', url: '/bizstory/m2/process/ajax_list.php',
				data: $('#moreform').serialize(),
				success: function(msg) {
					$("#thelist").append(msg);
					var more_num  = $("#morenum").val();
					$("#morenum").val(parseInt(more_num) + 1);
				}
			});

			myScroll.refresh(); // Remember to refresh when contents are loaded (ie: on ajax completion)
		}, 1000); // <-- Simulate network congestion, remove setTimeout from production!
	}

////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------ 접수 - 상태변경
	function receipt_change()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '/bizstory/m2/receipt_view_section.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#receipt_section').html(msg);
				myScroll.refresh();
			}
		});
	}

//------------------------------------ 접수 - 상태변경 수정폼
	function receipt_change_modify(idx)
	{
		$("#view_rid_idx").val(idx);
		$("#view_sub_type").val('singular_form');
		receipt_change();
	}

//------------------------------------ 접수 - 상태변경 완료문구
	function receipt_change_end(str, idx)
	{
		$('#end_view_' + idx).css({display:'none'});
		if (str == 'RS90') // 완료일 경우
		{
			$('#end_view_' + idx).css({display:'block'});
			myScroll.refresh();
		}
	}

//------------------------------------ 접수상태변경
	function receipt_change_endok(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('status_modify');
		$("#view_rid_idx").val(idx);

		var status_val = $('#detail_receipt_status').val();
		if (status_val == 'RS90') // 완료일 경우
		{
			chk_value = $('#detail_remark_end').val(); // 완료문구
			chk_title = $('#detail_remark_end').attr('title');
			chk_msg = check_input_value(chk_value);
			if (chk_msg == 'No')
			{
				chk_total = chk_total + chk_title + '<br />';
				action_num++;
			}
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				async : false,
				type: 'post', dataType: 'json', url: '/bizstory/m2/receipt_ok.php',
				data: $('#viewform').serialize(),
				success : function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#view_sub_type').val('');
						$("#view_rid_idx").val('');
						receipt_change();
						receipt_history();

						$('#receipt_status_check').html(msg.receipt_status_check);
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
	}

//------------------------------------ 등록/수정
	function receipt_change_check(idx)
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		$('#view_sub_type').val('singular_post');
		$("#view_rid_idx").val(idx);

		chk_value = $('#detail_receipt_class').val(); // 접수분류
		chk_title = $('#detail_receipt_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_mem_idx').val(); // 담당자
		chk_title = $('#detail_mem_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#detail_end_pre_date').val(); // 완료예정일
		chk_title = $('#detail_end_pre_date').attr('title');
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
				async : false,
				type: 'post', dataType: 'json', url: '/bizstory/mobile/receipt_ok.php',
				data: $('#viewform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#view_sub_type").val('');
						$("#view_rid_idx").val('');
						receipt_change();
						receipt_history();

						$('#receipt_status_check').html(msg.receipt_status_check);
					}
					else check_auth_popup(msg.error_string);
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
	}

//------------------------------------ 접수 - 상태이력
	function receipt_history()
	{
		$.ajax({
			async : false,
			type: "post", dataType: 'html', url: '/bizstory/mobile/receipt_stauts_history.php',
			data: $('#viewform').serialize(),
			success: function(msg) {
				$('#status_history_info').html(msg);
				myScroll.refresh();
			}
		});
	}

//------------------------------------ 접수 - 코멘트
	function receipt_comment()
	{
		$.ajax({
			async : false,
			type: "get", dataType: 'html', url: '/bizstory/mobile/receipt_view_comment_list.php',
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
				myScroll.refresh();
			}
		});
	}

//------------------------------------ 접수 - 코멘트 등록
	function receipt_comment_insert(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_comment").slideUp("slow");
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				async : false,
				type: "post", dataType: 'html', url: '/bizstory/mobile/receipt_view_comment_form.php',
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").html(msg);
					myScroll.refresh();
				}
			});
		}
	}

//------------------------------------ 접수 - 코멘트 등록실행
	function receipt_comment_check()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#commentpost_remark').val(); // 내용
		chk_title = $('#commentpost_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				async : false,
				type: "post", dataType: 'json', url: '/bizstory/mobile/receipt_view_comment_ok.php',
				data: $('#commentform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#comment_total_value').html(msg.total_num);
						receipt_comment_insert('close');
						receipt_comment();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
	}

////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------ 업무보고서 열기/닫기
	function report_view()
	{
		if (report_chk_val == 'close')
		{
			report_chk_val = 'open';
			$('#report_list_data').html('');
			$("#report_gate").removeClass('btn_i_minus');
			$("#report_gate").addClass('btn_i_plus');
			myScroll.refresh();
		}
		else
		{
			report_chk_val = 'close';
			report_list_data();
			$("#report_gate").removeClass('btn_i_plus');
			$("#report_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 업무보고서 목록
	function report_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/mobile/work_my_view_report_list.php',
			data: $('#reportlistform').serialize(),
			success: function(msg) {
				$('#report_list_data').html(msg);
				myScroll.refresh();
			}
		});
	}

//------------------------------------ 업무보고서 등록
	function report_insert_form(form_type)
	{
		report_chk_val = 'close';
		report_view();

		if (form_type == 'close')
		{
			$("#new_report").html('');
			$('#report_new_btn').css({'display':'block'});
			myScroll.refresh();
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: '/bizstory/mobile/work_my_view_report_form.php',
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#report_new_btn').css({'display':'none'});
					$("#new_report").html(msg);
					myScroll.refresh();
				}
			});
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------ 댓글 열기/닫기
	function comment_view()
	{
		if (comment_chk_val == 'close')
		{
			comment_chk_val = 'open';
			$('#comment_list_data').html('');
			$("#comment_gate").removeClass('btn_i_minus');
			$("#comment_gate").addClass('btn_i_plus');
			myScroll.refresh();
		}
		else
		{
			comment_chk_val = 'close';
			comment_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 댓글 목록
	function comment_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: '/bizstory/mobile/work_my_view_comment_list.php',
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
				myScroll.refresh();
			}
		});
	}

//------------------------------------ 댓글 등록
	function comment_insert_form(form_type)
	{
		comment_chk_val = 'close';
		comment_view();

		if (form_type == 'close')
		{
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
			myScroll.refresh();
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: '/bizstory/mobile/work_my_view_comment_form.php',
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").html(msg);
					myScroll.refresh();
				}
			});
		}
	}

////////////////////////////////////////////////////////////////////////////////////////////////////
//------------------------------------ 상담 - 코멘트
	function consult_comment()
	{
		$.ajax({
			async : false,
			type: "get", dataType: 'html', url: '/bizstory/mobile/consult_view_comment_list.php',
			data: $('#commentlistform').serialize(),
			success: function(msg) {
				$('#comment_list_data').html(msg);
				myScroll.refresh();
			}
		});
	}

//------------------------------------ 상담 - 코멘트 등록
	function consult_comment_insert(form_type)
	{
		if (form_type == 'close')
		{
			$("#new_comment").slideUp("slow");
			$("#new_comment").html('');
			$('#comment_new_btn').css({'display':'block'});
		}
		else
		{
			$.ajax({
				async : false,
				type: "post", dataType: 'html', url: '/bizstory/mobile/consult_view_comment_form.php',
				data: $('#viewform').serialize(),
				success: function(msg) {
					$('#comment_new_btn').css({'display':'none'});
					$("#new_comment").html(msg);
					myScroll.refresh();
				}
			});
		}
	}

//------------------------------------ 상담 - 코멘트 등록실행
	function consult_comment_check()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#commentpost_remark').val(); // 내용
		chk_title = $('#commentpost_remark').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				async : false,
				type: "post", dataType: 'json', url: '/bizstory/mobile/consult_view_comment_ok.php',
				data: $('#commentform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#comment_total_value').html(msg.total_num);
						consult_comment_insert('close');
						consult_comment();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
	}


////////////////////////////////////////////////////////////////////////////////////////////////////
	function loaded()
	{
		if ($.find('#pullUp') != '')
		{
			pullUpEl     = document.getElementById('pullUp');
			pullUpOffset = pullUpEl.offsetHeight;

			myScroll = new iScroll('wrapper', {
				useTransition: true,
				topOffset: pullDownOffset,
				onRefresh: function () {
					if (pullUpEl.className.match('loading'))
					{
						pullUpEl.className = '';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
					}
				},
				onScrollMove: function () {
					if (this.y < (this.maxScrollY - 5) && !pullUpEl.className.match('flip'))
					{
						pullUpEl.className = 'flip';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Release to refresh...';
						this.maxScrollY = this.maxScrollY;
					}
					else if (this.y > (this.maxScrollY + 5) && pullUpEl.className.match('flip'))
					{
						pullUpEl.className = '';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Pull up to load more...';
						this.maxScrollY = pullUpOffset;
					}
				},
				onScrollEnd: function () {
					if (pullUpEl.className.match('flip'))
					{
						pullUpEl.className = 'loading';
						pullUpEl.querySelector('.pullUpLabel').innerHTML = 'Loading...';
						pullUpAction(); // Execute custom function (ajax call?)
					}
				}
			});
		}

		setTimeout(function () { document.getElementById('wrapper').style.left = '0'; }, 800);
	}

	document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	document.addEventListener('DOMContentLoaded', function () { setTimeout(loaded, 200); }, false);

//]]>