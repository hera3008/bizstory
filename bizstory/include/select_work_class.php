<?
/*
	생성 : 2012.04.23
	수정 : 2012.05.07
	위치 : 업무폴더 > 나의업무 > 업무 - 분류선택
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
?>

<div id="<?=$view_id;?>" class="work_class">

	<div id="work_class_list"></div>

	<div class="dotted"></div>

	<div class="work_class_form">
		<input type="text" name="work_class_str" id="work_class_str" size="25" title="업무분류를 입력하세요." class="type_text" />
		<a href="javascript:void(0);" onclick="check_work_class_insert();" class="btn_sml"><span>추가</span></a>
	</div>

	<div class="work_class_top">
		<div class="new">
			<a href="javascript:void(0);" onclick="popup_work_class_select('<?=$field_id;?>', '<?=$view_id;?>');" class="btn_sml2"><span>확인</span></a>
			<a href="javascript:void(0);" onclick="$('#<?=$view_id;?>').html('');" class="btn_sml2"><span>닫기</span></a>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
// 업무분류 - 선택
	function popup_work_class_select(field_id, view_id)
	{
		var idx  = document.getElementsByName('check_code_idx');
		var name = document.getElementsByName('check_code_name');
		var i = 0, j = 0;
		var total_code_idx = '', total_code_name = '';

		while(idx[i])
		{
			if (idx[i].type == 'radio' && idx[i].disabled == false && idx[i].checked == true)
			{
				if (j == 0)
				{
					total_code_idx  = idx[i].value;
					total_code_name = name[i].value;
				}
				else
				{
					total_code_idx  += ',' + idx[i].value;
					total_code_name += ',' + name[i].value;
				}
				j++;
			}
			i++;
		}
		$('#' + field_id).val(total_code_idx);
		$('#' + view_id).html('');
		$('#' + view_id + '_select').html(total_code_name);
		$('#' + view_id + '_btn span').html('');
		if (j >= 1)
		{
			$('#' + view_id + '_btn a').html('수정하기');
		}
		$('#' + view_id + '_multi').css({display:'block'});
	}

// 분류등록
	function check_work_class_insert()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#work_class_str').val();
		chk_title = $('#work_class_str').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type:'post', dateType:'json', url: '<?=$local_dir;?>/bizstory/include/select_work_class_ok.php',
				data: {'work_class_str':chk_value, 'sub_type':'post'},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#work_class_str').val('');
						check_work_class_list();
					}
				}
			});
		}
		return false;
	}

// 분류목록
	function check_work_class_list(field_value)
	{
		$.ajax({
			type: "get", dataType: 'html', url: '<?=$local_dir;?>/bizstory/include/select_work_class_list.php',
			data: {'field_value':field_value},
			success: function(msg) {
				$('#work_class_list').html(msg);
			}
		});
	}

	check_work_class_list('<?=$field_value;?>');
//]]>
</script>