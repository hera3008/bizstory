
//------------------------------------ 리스트 수정
	function check_code_modify(sub_type, sub_action, idx, post_value)
	{
		$("#popup_notice_view").hide();

		$('#list_sub_type').val(sub_type)
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);

		$.ajax({
			type     : "post",
			dataType :'html',
			url      : link_list,
			data     : $('#listform').serialize(),
			success  : function(msg) {
				$('#data_list').html(msg);
			}
		});
	}