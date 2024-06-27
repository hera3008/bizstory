
	var local_dir = '';

//------------------------------------ 팝업폼 닫기
	function popup_work_close()
	{
		$("#popup_notice_view").hide();
		$("#sub_popupform").slideUp("slow");
		$("#backgroundPopup").fadeOut("slow");
	}

//------------------------------------ 업무분류 - 창
	function popup_work_class(field_id, view_id)
	{
		var form_id = '#' + view_id;
		var field_value = $("#" + field_id).val();

		$.ajax({
			type: "get", dataType: 'html', url: local_dir + '/bizstory/include/select_work_class.php',
			data: {"view_id":view_id, "field_id":field_id, "field_value":field_value},
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success: function(msg) {
				$(form_id).slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$(form_id).html(msg);
			}
		});
	}

//------------------------------------ 기한-날짜 직접선택
	function deadline_date_view(str, view_id)
	{
		if (str == 'select') $("#" + view_id).css({"display": "block"});
		else $("#" + view_id).css({"display": "none"});
	}

//------------------------------------ 기한-덧붙이기 직접선택
	function deadline_str_view(str, view_id)
	{
		if (str == 'select') $("#" + view_id).css({"display": "block"});
		else $("#" + view_id).css({"display": "none"});
	}