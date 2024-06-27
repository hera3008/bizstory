<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./process/no_direct.php";
	//include "./header.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($part_idx);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$receipt_info = new receipt_info();
	$receipt_info->ri_idx = $ri_idx;
	$receipt_info->data_path = $comp_receipt_path;
	$receipt_info->data_dir = $comp_receipt_dir;

	$receipt_data = $receipt_info->receipt_info_view();
	$history_list = $receipt_info->receipt_status_history();

// 등록된 하위값
	$where = " and rid.ri_idx = '" . $ri_idx . "'";
	$data = receipt_info_detail_data('page', $where);

	$detail_data['end_pre_date']  = date('Y-m-d');
	$detail_data['receipt_class'] = $receipt_data['receipt_class'];
	$detail_data['mem_idx']       = $receipt_data['charge_mem_idx'];

// 값이 한개도 없을 경우 단일
	if ($data['total_num'] == 0 && $rid_type == '')
	{
		include "./receipt_view_section_single.php";

		include "./receipt_view_section_plural.php";
	}
	else
	{
		if ($sub_type == '')
		{
		// 다수값이 없으면 단일로 인식
			$plural_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '2'";
			$plural_list = receipt_info_detail_data('page', $plural_where);
			if ($plural_list['total_num'] == 0)
			{
				$sub_type = 'singular_view';

				$singular_where = " and rid.ri_idx = '" . $ri_idx . "' and rid.receipt_type = '1'";
				$singular_data = receipt_info_detail_data('view', $singular_where);

				$rid_idx = $singular_data['rid_idx'];
			}
			else
			{
				$sub_type = 'plural_list';
			}
		}

		if ($sub_type == 'plural_form') // 다수접수 등록/수정
		{
			include "./receipt_view_section_plural.php";
		}
		else if ($sub_type == 'plural_list') // 다수접수 목록
		{
			include "./receipt_view_section_plural.php";
		}
		else if ($sub_type == 'singular_view') // 단일 보기
		{
			include "./receipt_view_section_single.php";
		}
		else if ($sub_type == 'singular_form') // 단일 수정
		{
			include "./receipt_view_section_single.php";
		}
	}
?>
<script src="./js/jquery-1.9.1.min.js"></script>

<script type="text/javascript">
//<![CDATA[
	function part_information(code_part, select_class, field_id, field_value, select_type)
	{
		if (code_part == "") code_part = $('#post_part_idx').val();
		var shsgroup = $('#search_shsgroup').val();

		$.ajax({
			type: "post", cache: false, async: true, dataType : "json", url: '../../bizstory/comp_set/part_information.php',
			data: {
				"code_part" : code_part,
				"select_class" : select_class,
				"field_value" : field_value,
				"select_type" : select_type,
				"shsgroup" : shsgroup
			},
			success  : function(msg) {
				$('#' + field_id).empty();
				if (select_type == 'select')
				{
					$('#' + field_id).append('<option value="all">' + $('#' + field_id).attr('title') + '</option>');
				}
				else if (select_type == 'select_allno')
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}
				else
				{
					$('#' + field_id).append('<option value="">' + $('#' + field_id).attr('title') + '</option>');
				}

				if (msg.success_chk == "Y")
				{
					$.each(msg.result_data, function() {

						var empty_str = ''
						for (var ii = 2; ii <= this.menu_dpeth; ii++)
						{
							empty_str = empty_str + '&nbsp;&nbsp;&nbsp;';
						}

						if (this.selected == 'Y')
						{
							$('#' + field_id).append('<option value= ' + this.idx + ' selected="selected">' + empty_str + this.name + '</option>');
						}
						else
						{
							$('#' + field_id).append('<option value= ' + this.idx + '>' + empty_str + this.name + '</option>');
						}
					});
				}
				else
				{
					if (msg.result_data != '')
					{
						check_auth_popup(msg.result_data);
					}
				}
			},
			error: function(e) {
				alert(e);
			}
		});
	}
	try {
		part_information('<?=$code_part;?>', 'receipt_class', 'detail_receipt_class', '<?=$detail_data['receipt_class'];?>', '');
		part_information('<?=$code_part;?>', 'staff_info', 'detail_mem_idx', '<?=$detail_data['mem_idx'];?>', '');	
	} catch(e) {
		alert(e.message);
	}
	
//]]>
</script>