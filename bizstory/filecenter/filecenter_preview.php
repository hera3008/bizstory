<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	if ($set_viewer_yn == 'Y')
	{
		$code_comp = $_SESSION[$sess_str . '_comp_idx'];
		$code_part = search_company_part($code_part);
		$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

		$history_where = "
			and fi.del_yn = 'N'
			and fh.comp_idx = '" . $code_comp . "' and fh.part_idx = '" . $code_part . "'
			and fh.dir_file = 'file' and fh.change_idx = '0'
			and fh.reg_type != 'delete' and fh.reg_type != 'update'";
		$history_order = "fh.fi_idx asc, fh.reg_date desc";
		$history_list = filecenter_history_data('list', $history_where, $history_order, '', '');
		$file_i = 1;
		foreach ($history_list as $k => $file_data)
		{
			if (is_array($file_data))
			{
				$fi_idx = $file_data['fi_idx'];
				$file_sname_history = $file_data['file_sname'];

				$info_where = " and fi.fi_idx = '" . $fi_idx . "'";
				$info_data = filecenter_info_data('view', $info_where);

				$file_ext   = $info_data['file_ext'];
				$file_rpath = $info_data['file_rpath'];

				if ($file_ext != '' && $file_sname_history != '')
				{
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $set_filecneter_url . '/upload' . $file_rpath . '/' . $file_sname_history;

					// 파일이력
						$file_chk[$file_i]['table_name'] = 'filecenter_history';
						$file_chk[$file_i]['table_idx']  = $file_data['fh_idx'];
						$file_chk[$file_i]['idx_name']   = 'fh_idx';

						$file_chk[$file_i]['file_name'] = $file_data['new_subject'];
						$file_chk[$file_i]['file_url']  = $preview_dir;

					// 파일정보
						if ($fi_idx != $old_fi_idx)
						{
							$file_sname = $info_data['file_sname'];
							$preview_dir = $set_filecneter_url . '/upload' . $file_rpath . '/' . $file_sname;

							$file_info_chk[$file_i]['table_name'] = 'filecenter_info';
							$file_info_chk[$file_i]['table_idx']  = $info_data['fi_idx'];
							$file_info_chk[$file_i]['idx_name']   = 'fi_idx';

							$file_info_chk[$file_i]['file_name'] = $info_data['file_name'];
							$file_info_chk[$file_i]['file_url']  = $preview_dir;
						}
						$old_fi_idx = $fi_idx;
						$file_i++;
					}
					$file_i++;
				}
			}
		}

		echo '<script type="text/javascript">
	//<![CDATA[';
		if (is_array($file_chk))
		{
			foreach ($file_chk as $k => $data)
			{
				$table_name = $data['table_name'];
				$table_idx  = $data['table_idx'];
				$idx_name   = $data['idx_name'];

				$file_name  = $data['file_name'];
				$file_name  = strtolower($file_name);
				$file_name  = str_replace(' ', '_', $file_name);
				$file_url   = $data['file_url'];

				echo "
					$.ajax({
						async : false, type: 'get', dataType: 'jsonp', url: '" . $set_preview_url . "/convert_request.php', jsonp : 'callback',
						data: { 'job_id' : 'demo', 'agent_code' : '" . $preview_agent_code . "', 'user_id' : '" . $preview_user_id . "', 'file_name' : '11" . $file_name . "', 'file_url' : '" . $file_url . "' },
						success: function(msg) {
							if (msg.success == 'Y')
							{
								$.ajax({
									type: 'post', dataType: 'html', url: '/bizstory/include/file_preview_ok.php',
									data: { 'change_idx' : msg.msg, 'table_name' : '" . $table_name . "', 'table_idx' : '" . $table_idx . "', 'idx_name' : '" . $idx_name . "' },
									success: function(msg) { return msg; }
								});
							}
						}
					});
				";
			}
		}
		if (is_array($file_info_chk))
		{
			foreach ($file_info_chk as $k => $data)
			{
				$table_name = $data['table_name'];
				$table_idx  = $data['table_idx'];
				$idx_name   = $data['idx_name'];

				$file_name  = $data['file_name'];
				$file_name  = strtolower($file_name);
				$file_name  = str_replace(' ', '_', $file_name);
				$file_url   = $data['file_url'];

				echo "
					$.ajax({
						async : false, type: 'get', dataType: 'jsonp', url: '" . $set_preview_url . "/convert_request_hcms.php', jsonp : 'callback',
						data: { 'job_id' : 'demo', 'agent_code' : '" . $preview_agent_code . "', 'user_id' : '" . $preview_user_id . "', 'file_name' : '22" . $file_name . "', 'file_url' : '" . $file_url . "' },
						success: function(msg) {
							if (msg.success == 'Y')
							{
								$.ajax({
									type: 'post', dataType: 'html', url: '/bizstory/include/file_preview_ok.php',
									data: { 'change_idx' : msg.msg, 'table_name' : '" . $table_name . "', 'table_idx' : '" . $table_idx . "', 'idx_name' : '" . $idx_name . "' },
									success: function(msg) { return msg; }
								});
							}
						}
					});
				";
			}
		}
		echo '//]]>
	</script>';
	}
?>