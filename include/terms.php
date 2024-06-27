<?PHP
// 서비스약관
	$page_where = " and pi.menu_code = '{$type}'";
	$page_data = page_info_data('view', $page_where);
	$use_rule = $page_data['remark'];

	echo $use_rule;
?>