<?
	include "../../common/setting.php";
	include "../../common/no_direct.php";

	//$set_file_class    = 'OUT';
	//$set_filecenter_yn = '0';
	//$set_file_class    = 'IN';

	if ($set_viewer_yn == 'Y')
	{
		if ($table_name == 'project_file') // 프로젝트파일
		{
			$file_where = " and prof.pro_idx = '" . $table_idx . "' and prof.change_idx = '0'";
			$file_list = project_file_data('list', $file_where, '', '', '');
			$file_i = 0;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					$in_out   = $file_data['in_out'];
					if ($file_ext != '')
					{
						if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
						{
							if ($in_out == 'CENTER') // 파일센터일 경우
							{
								$filecenter_where = " and fi.pro_idx = '" . $table_idx . "' and fi.data_idx = '" . $file_data['prof_idx'] . "'";
								$filecenter_list = filecenter_info_data('list', $filecenter_where, '', '', '');
								foreach ($filecenter_list as $filecenter_k => $filecenter_data)
								{
									if (is_array($filecenter_data))
									{
										$preview_dir = $set_filecneter_url . '/upload' . $filecenter_data['file_rpath'] . '/' . $filecenter_data['file_sname'];

										$file_i++;
										$file_chk[$file_i]['table_name'] = 'project_file';
										$file_chk[$file_i]['table_idx']  = $file_data['prof_idx'];
										$file_chk[$file_i]['idx_name']   = 'prof_idx';

										$file_chk[$file_i]['table_name2'] = 'filecenter_info';
										$file_chk[$file_i]['table_idx2']  = $filecenter_data['fi_idx'];
										$file_chk[$file_i]['idx_name2']   = 'fi_idx';

										$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
										$file_chk[$file_i]['file_url']  = $preview_dir;
									}
								}
							}
							else if ($in_out == 'OUT') // 외부서버일 경우
							{
								$file_i++;
								$preview_dir = $set_filecneter_url . '/upload/Project/' . $file_data['pro_idx'] . '/' . $file_data['img_sname'];

								$file_chk[$file_i]['table_name'] = 'project_file';
								$file_chk[$file_i]['table_idx']  = $file_data['prof_idx'];
								$file_chk[$file_i]['idx_name']   = 'prof_idx';

								$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
								$file_chk[$file_i]['file_url']  = $preview_dir;
							}
							else if ($in_out == 'IN') // 내부서버일 경우
							{
								$file_i++;
								$preview_dir = 'http://' . $site_url . $comp_project_dir . '/' . $file_data['pro_idx'] . '/' . $file_data['img_sname'];

								$file_chk[$file_i]['table_name'] = 'project_file';
								$file_chk[$file_i]['table_idx']  = $file_data['prof_idx'];
								$file_chk[$file_i]['idx_name']   = 'prof_idx';

								$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
								$file_chk[$file_i]['file_url']  = $preview_dir;
							}
						}
					}
				}
			}
		}
		else if ($table_name == 'work_file') // 업무
		{
			$file_where = " and wf.wi_idx = '" . $table_idx . "' ";
			$file_list = work_file_data('list', $file_where);
			
			$file_i = 0;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					$in_out   = $file_data['in_out'];
					if ($file_ext != '')
					{
						if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
						{
							if ($in_out == 'CENTER') // 파일센터일 경우
							{
								$filecenter_where = " and fi.wi_idx = '" . $table_idx . "' and fi.data_idx = '" . $file_data['wf_idx'] . "'";
								$filecenter_list = filecenter_info_data('list', $filecenter_where, '', '', '');
								foreach ($filecenter_list as $filecenter_k => $filecenter_data)
								{
									if (is_array($filecenter_data))
									{
										$preview_dir = $set_filecneter_url . '/upload' . $filecenter_data['file_rpath'] . '/' . $filecenter_data['file_sname'];

										$file_i++;
										$file_chk[$file_i]['table_name'] = 'work_file';
										$file_chk[$file_i]['table_idx']  = $file_data['wf_idx'];
										$file_chk[$file_i]['idx_name']   = 'wf_idx';

										$file_chk[$file_i]['table_name2'] = 'filecenter_info';
										$file_chk[$file_i]['table_idx2']  = $filecenter_data['fi_idx'];
										$file_chk[$file_i]['idx_name2']   = 'fi_idx';

										$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
										$file_chk[$file_i]['file_url']  = $preview_dir;
									}
								}
							}
							else if ($in_out == 'OUT') // 외부서버일 경우
							{
								$file_i++;
								$preview_dir = $set_filecneter_url . '/upload/work/' . $file_data['comp_idx'] . '/work/' . $file_data['wi_idx'] . '/' . $file_data['img_sname'];

								$file_chk[$file_i]['table_name'] = 'work_file';
								$file_chk[$file_i]['table_idx']  = $file_data['wf_idx'];
								$file_chk[$file_i]['idx_name']   = 'wf_idx';

								$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
								$file_chk[$file_i]['file_url']  = $preview_dir;
							}
							else if ($in_out == 'IN') // 내부서버일 경우
							{
							
								$file_i++;
								$preview_dir = 'http://' . $site_url . $comp_work_dir . '/' . $file_data['wi_idx'] . '/' . $file_data['img_sname'];

								$file_chk[$file_i]['table_name'] = 'work_file';
								$file_chk[$file_i]['table_idx']  = $file_data['wf_idx'];
								$file_chk[$file_i]['idx_name']   = 'wf_idx';

								$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
								$file_chk[$file_i]['file_url']  = $preview_dir;
							}
						}
					}
				}
			}
		}
		else if ($table_name == 'work_report_file') // 업무보고
		{
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
				$file_url   = $data['file_url'];

				$table_name2 = $data['table_name2'];
				$table_idx2  = $data['table_idx2'];
				$idx_name2   = $data['idx_name2'];

				echo "
					$.ajax({
						async : false, type: 'get', dataType: 'jsonp', url: '" . $set_preview_url . "/convert_request.php', jsonp : 'callback',
						data: { 'job_id' : 'demo', 'agent_code' : '" . $preview_agent_code . "', 'user_id' : '" . $preview_user_id . "', 'file_name' : ' " . $file_name . "', 'file_url' : '" . $file_url . "' },
						success: function(msg) {
							if (msg.success == 'Y')
							{
								$.ajax({
									type: 'post', dataType: 'html', url: '" . $local_dir . "/bizstory/filecenter/biz/file_preview_ok.php',
									data: {
										'change_idx' : msg.msg,
										'table_name' : '" . $table_name . "', 'table_idx' : '" . $table_idx . "', 'idx_name' : '" . $idx_name . "',
										'table_name2' : '" . $table_name2 . "', 'table_idx2' : '" . $table_idx2 . "', 'idx_name2' : '" . $idx_name2 . "',
										'comp_idx' : '" . $code_comp . "', 'part_idx' : '" . $code_part . "', 'mem_idx' : '" . $code_mem . "'
									},
									success: function(msg) {
										return msg;
									}
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