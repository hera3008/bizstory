<?
	$set_file_class   = $comp_set_data['file_class'];
	if ($set_file_class == 'OUT') // 파일 외부전송일 경우
	{
		$chk_comp = $_SESSION[$sess_str . '_comp_idx'];
		$chk_part = $_SESSION[$sess_str . '_part_idx'];
		$chk_mem  = $_SESSION[$sess_str . '_mem_idx'];
?>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 업체수정일 경우
	function filecenter_company_folder(idx)
	{
<?
	if ($set_filecneter_url != '') {
?>
		$.ajax({
			type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/company_ok.php', jsonp : 'callback',
			data: {
				'sub_type' : 'comp_post', 'chk_comp' : '<?=$chk_comp;?>', 'chk_part' : '<?=$chk_part;?>', 'chk_mem' : '<?=$chk_mem;?>',
				'idx' : idx },
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
<?
	}
?>
	}

//------------------------------------ 지사 등록, 수정시 폴더생성
	function filecenter_part_folder(idx)
	{
<?
	if ($set_filecneter_url != '') {
?>
		$.ajax({
			type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/part_ok.php', jsonp : 'callback',
			data: {
				'sub_type' : 'part_post', 'chk_comp' : '<?=$chk_comp;?>', 'chk_part' : '<?=$chk_part;?>', 'chk_mem' : '<?=$chk_mem;?>',
				'idx' : idx },
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
<?
	}
?>
	}

//------------------------------------ 직원 등록, 수정시 폴더생성
	function filecenter_member_folder(idx)
	{
<?
	if ($set_filecneter_url != '') {
?>
		$.ajax({
			type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/member_ok.php', jsonp : 'callback',
			data: {
				'sub_type' : 'mem_post', 'chk_comp' : '<?=$chk_comp;?>', 'chk_part' : '<?=$chk_part;?>', 'chk_mem' : '<?=$chk_mem;?>',
				'idx' : idx },
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
<?
	}
?>
	}

//------------------------------------ 프로젝트 등록시 폴더생성
	function filecenter_project_folder(idx)
	{
<?
	if ($set_filecneter_url != '') {
?>
		$.ajax({
			type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/biz/project_ok.php', jsonp : 'callback',
			data: {
				'sub_type' : 'project_post', 'chk_comp' : '<?=$chk_comp;?>', 'chk_part' : '<?=$chk_part;?>', 'chk_mem' : '<?=$chk_mem;?>',
				'idx' : idx },
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
<?
	}
?>
	}

//------------------------------------ 파일등록후 보여주기
	function filecenter_complete(response)
	{
		// 디비에서 데이타를 가지고 와서 보여주기
		var chk_num = $('#filecenter_upload_num').val();
		var fup_name = 'filecenter_fname_' + chk_num;

		var str_array = response.split('|');
		var view_html = '<li id="' + fup_name + '_liview">' + str_array[0] + '-' + chk_num + '(' + str_array[2] + ' Byte)';
			view_html += '<a href="javascript:void(0);" class="btn_con" onclick="filecenter_delete(\'filecenter_fname\', \'' + str_array[2] + '\', \'' + chk_num + '\')"><span>삭제</span></a>';
			view_html += '<input type="hidden" name="' + fup_name + '_file_name" value="' + str_array[0] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_save_name" value="' + str_array[1] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_size" value="' + str_array[2] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_type" value="' + str_array[3] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_file_ext"  value="' + str_array[4] + '" />';
			view_html += '<input type="hidden" name="' + fup_name + '_idx_common" value="' + str_array[5] + '" />';
			view_html += '</li>';

		$('#filecenter_fname_view').append(view_html);
		chk_num++;
		$('#filecenter_upload_num').val(chk_num);
	}

//------------------------------------ 파일등록후 선택파일삭제
	function filecenter_delete(upload_name, save_name, sort)
	{
		if (confirm("선택한 파일을 삭제하시겠습니까?"))
		{
			$.ajax({
				type: 'post', dataType: 'xml', url:'<?=$set_filecneter_url;?>/biz/project_ok.php',
				data:{'upload_name':upload_name,'save_name':save_name},
				success:function(msg) {
					var success_chk = $(msg).find('success_chk').text();
					var file_view = $(msg).find('file_view').text();

					if (success_chk == "Y")
					{
						$('#' + upload_name + sort + '_liview').html('');
					}
					else check_auth_popup('정상적으로 처리가 되지 않았습니다.');
				}
			});
		}
		return false;
	}







//]]>
</script>
<?
	}
?>