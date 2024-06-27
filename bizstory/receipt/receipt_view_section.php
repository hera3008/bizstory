<?
/*
	생성 : 2012.07.10
	위치 : 고객관리 > 접수목록 - 보기 - 접수구분
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

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
		include $local_path . "/bizstory/receipt/receipt_view_section_single.php";

		include $local_path . "/bizstory/receipt/receipt_view_section_plural.php";
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
			include $local_path . "/bizstory/receipt/receipt_view_section_plural.php";
		}
		else if ($sub_type == 'plural_list') // 다수접수 목록
		{
			include $local_path . "/bizstory/receipt/receipt_view_section_plural.php";
		}
		else if ($sub_type == 'singular_view') // 단일 보기
		{
			include $local_path . "/bizstory/receipt/receipt_view_section_single.php";
		}
		else if ($sub_type == 'singular_form') // 단일 수정
		{
			include $local_path . "/bizstory/receipt/receipt_view_section_single.php";
		}
	}
?>

<script type="text/javascript">
//<![CDATA[
	part_information('<?=$code_part;?>', 'receipt_class', 'detail_receipt_class', '<?=$detail_data['receipt_class'];?>', '');
	part_information('<?=$code_part;?>', 'staff_info', 'detail_mem_idx', '<?=$detail_data['mem_idx'];?>', '');

	var chk_id = document.getElementById("detail_remark");
	if(chk_id)
	{
		var oEditors = [];
		nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditors,
			elPlaceHolder: "detail_remark",
			sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
			htParams : {bUseToolbar : true,
				fOnBeforeUnload : function(){ }
			},
			fCreator: "createSEditor2"
		});
	}

	//var file_chk_num = 1;
	//file_multi_setting('view_file_fname', '<?=$file_multi_size;?>', 'receipt_end', '');

	$(".datepicker").datepicker();
//]]>
</script>