<?
	$set_file_class = $comp_set_data['file_class'];
	if ($set_file_class == 'OUT') // 파일 외부전송일 경우
	{
?>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 프로젝트 등록시 폴더생성
	function filecenter_project_folder(pro_idx, add_name)
	{
	// 디비저장
		$.ajax({
			type: 'post', dataType: 'json', url: '<?=$local_dir;?>/bizstory/filecenter/xupload/out_project_ok.php',
			data: { 'sub_type':'folder_post', 'pro_idx':pro_idx },
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
				// 외부서버저장
					$.ajax({
						type: 'post', dataType: 'jsonp', url: 'http://<?=$filecneter_url;?>/filemanage/folder_ok.php',
						jsonp : 'callback',
						data: {
							'sub_type':'folder_post',
							'file_path':msg.file_path,
							'file_name':msg.file_name,
							'add_name':msg.add_name,
							'up_idx':msg.up_idx,
							'up_level':msg.up_level
						},
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