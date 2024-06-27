<?
	$set_file_class = $comp_set_data['file_class'];
	if ($set_file_class == 'OUT') // 파일 외부전송일 경우
	{
		$code_mem = $_SESSION[$sess_str . '_mem_idx'];
?>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 프로젝트 등록시 폴더생성
	function filecenter_project_folder(pro_idx)
	{
		$.ajax({
			type: 'get', dataType: 'jsonp', url: 'http://<?=$filecneter_url;?>/filemanage/folder_ok.php', jsonp : 'callback',
			data: {
				'sub_type' : 'project_post',
				'pro_idx'  : pro_idx,
				'mem_idx'  : '<?=$code_mem;?>' },
			success: function(msg) {
				if (msg.success_chk == "Y")
				{ }
				else
				{
					$("#loading").fadeOut('slow');
					check_auth_popup(msg.error_string);
				}
			},
			complete: function(){ $("#loading").fadeOut('slow'); }
		});
	}

//------------------------------------ 직원 등록시 폴더생성
	function filecenter_staff_folder(mem_idx)
	{
		$.ajax({
			type: 'get', dataType: 'jsonp', url: 'http://<?=$filecneter_url;?>/filemanage/folder_ok.php', jsonp : 'callback',
			data: {
				'sub_type'  : 'staff_post',
				'mem_idx'   : mem_idx,
				'code_comp' : '<?=$code_comp;?>',
				'code_part' : '<?=$code_part;?>',
				'code_mem'  : '<?=$code_mem;?>' },
			success: function(msg) {
				if (msg.success_chk == "Y")
				{ }
				else
				{
					$("#loading").fadeOut('slow');
					check_auth_popup(msg.error_string);
				}
			},
			complete: function(){ $("#loading").fadeOut('slow'); }
		});
	}

//------------------------------------ 지사 등록시 폴더생성
	function filecenter_part_folder(mem_idx)
	{
		$.ajax({
			type: 'get', dataType: 'jsonp', url: 'http://<?=$filecneter_url;?>/filemanage/folder_ok.php', jsonp : 'callback',
			data: {
				'sub_type' : 'part_post',
				'part_idx' : part_idx,
				'code_mem' : '<?=$code_mem;?>' },
			success: function(msg) {
				if (msg.success_chk == "Y")
				{ }
				else
				{
					$("#loading").fadeOut('slow');
					check_auth_popup(msg.error_string);
				}
			},
			complete: function(){ $("#loading").fadeOut('slow'); }
		});
	}
//]]>
</script>
<?
	}
?>