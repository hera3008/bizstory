<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	if ($set_viewer_yn == 'Y')
	{
		if ($f_class == 'bbs') // 게시판
		{
			$bbs_where = " and b.b_idx = '" . $f_idx . "'";
			$bbs_data = bbs_info_data('view', $bbs_where);

			$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $f_idx . "' and bf.change_idx = '0'";
			$file_list = bbs_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_bbs_dir . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'];

						$file_chk[$file_i]['table_name'] = 'bbs_file';
						$file_chk[$file_i]['table_idx']  = $file_data['bf_idx'];
						$file_chk[$file_i]['idx_name']   = 'bf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'comp_bbs') // 비즈스토리 게시판
		{
			$bbs_where = " and b.b_idx = '" . $f_idx . "'";
			$bbs_data = comp_bbs_info_data('view', $bbs_where);

			$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $f_idx . "' and bf.change_idx = '0'";
			$file_list = comp_bbs_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $bbs_dir . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'];

						$file_chk[$file_i]['table_name'] = 'comp_bbs_file';
						$file_chk[$file_i]['table_idx']  = $file_data['bf_idx'];
						$file_chk[$file_i]['idx_name']   = 'bf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'client_memo') // 거래처메모
		{
			$file_where = " and cimf.cim_idx = '" . $f_idx . "' and cimf.change_idx = '0'";
			$file_list = client_memo_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$file_chk[$file_i]['table_name'] = 'client_memo_file';
						$file_chk[$file_i]['table_idx']  = $file_data['cimf_idx'];
						$file_chk[$file_i]['idx_name']   = 'cimf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $comp_client_dir . '/' . $file_data['ci_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'company') // 업체
		{
			$file_where = " and cf.comp_idx = '" . $f_idx . "' and cf.change_idx = '0'";
			$file_list = company_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/company';

						$file_chk[$file_i]['table_name'] = 'company_file';
						$file_chk[$file_i]['table_idx']  = $file_data['cf_idx'];
						$file_chk[$file_i]['idx_name']   = 'cf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'member') // 직원
		{
			$file_where = " and mf.mem_idx = '" . $f_idx . "' and mf.change_idx = '0'";
			$file_list = member_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/member';

						$file_chk[$file_i]['table_name'] = 'member_file';
						$file_chk[$file_i]['table_idx']  = $file_data['mf_idx'];
						$file_chk[$file_i]['idx_name']   = 'mf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['mem_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'message') // 쪽지
		{
			$file_where = " and msgf.ms_idx = '" . $f_idx . "' and msgf.change_idx = '0'";
			$file_list = message_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$file_chk[$file_i]['table_name'] = 'message_file';
						$file_chk[$file_i]['table_idx']  = $file_data['msgf_idx'];
						$file_chk[$file_i]['idx_name']   = 'msgf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $comp_msg_dir . '/' . $file_data['ms_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'receipt') // 접수관련
		{
			$file_where = " and rf.ri_idx = '" . $f_idx . "' and rf.change_idx = '0'";
			$file_list = receipt_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/receipt';

						$file_chk[$file_i]['table_name'] = 'receipt_file';
						$file_chk[$file_i]['table_idx']  = $file_data['rf_idx'];
						$file_chk[$file_i]['idx_name']   = 'rf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['ri_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'receipt_comment') // 접수댓글관련
		{
			$file_where = " and rcf.rc_idx = '" . $f_idx . "' and rcf.change_idx = '0'";
			$file_list = receipt_comment_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/receipt';

						$file_chk[$file_i]['table_name'] = 'receipt_comment_file';
						$file_chk[$file_i]['table_idx']  = $file_data['rcf_idx'];
						$file_chk[$file_i]['idx_name']   = 'rcf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['ri_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'receipt_end') // 접수완료관련
		{
			$file_where = " and ref.rid_idx = '" . $f_idx . "' and ref.change_idx = '0'";
			$file_list = receipt_end_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/receipt';

						$file_chk[$file_i]['table_name'] = 'receipt_end_file';
						$file_chk[$file_i]['table_idx']  = $file_data['ref_idx'];
						$file_chk[$file_i]['idx_name']   = 'ref_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['ri_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'work') // 업무관련
		{
			$file_where = " and wf.wi_idx = '" . $f_idx . "' and wf.change_idx = '0'";
			$file_list = work_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$file_chk[$file_i]['table_name'] = 'work_file';
						$file_chk[$file_i]['table_idx']  = $file_data['wf_idx'];
						$file_chk[$file_i]['idx_name']   = 'wf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $comp_work_dir . '/' . $file_data['wi_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'work_report') // 업무보고관련
		{
			$file_where = " and wrf.wr_idx = '" . $f_idx . "' and wrf.change_idx = '0'";
			$file_list = work_report_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$file_chk[$file_i]['table_name'] = 'work_report_file';
						$file_chk[$file_i]['table_idx']  = $file_data['wrf_idx'];
						$file_chk[$file_i]['idx_name']   = 'wrf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $comp_work_dir . '/' . $file_data['wi_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'consult') // 상담게시판
		{
			$file_where = " and consf.cons_idx = '" . $f_idx . "' and consf.change_idx = '0'";
			$file_list = consult_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/consult';

						$file_chk[$file_i]['table_name'] = 'consult_file';
						$file_chk[$file_i]['table_idx']  = $file_data['consf_idx'];
						$file_chk[$file_i]['idx_name']   = 'consf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['cons_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'consult_comment') // 상담게시판 댓글
		{
			$file_where = " and conscf.consc_idx = '" . $f_idx . "' and conscf.change_idx = '0'";
			$file_list = consult_comment_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/consult';

						$file_chk[$file_i]['table_name'] = 'consult_comment_file';
						$file_chk[$file_i]['table_idx']  = $file_data['conscf_idx'];
						$file_chk[$file_i]['idx_name']   = 'conscf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['cons_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'bnotice') // 알람게시판
		{
			$file_where = " and abnf.abn_idx = '" . $f_idx . "' and abnf.change_idx = '0'";
			$file_list = agent_bnotice_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/bnotice';

						$file_chk[$file_i]['table_name'] = 'agent_bnotice_file';
						$file_chk[$file_i]['table_idx']  = $file_data['abnf_idx'];
						$file_chk[$file_i]['idx_name']   = 'abnf_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['abn_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}
		else if ($f_class == 'project') // 프로젝트
		{
			$file_where = " and prof.pro_idx = '" . $f_idx . "' and prof.change_idx = '0'";
			$file_list = project_file_data('list', $file_where, '', '', '');
			$file_i = 1;
			foreach ($file_list as $k => $file_data)
			{
				if (is_array($file_data))
				{
					$file_ext = $file_data['img_ext'];
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0)
					{
						$preview_dir = $comp_dir . '/' . $file_data['comp_idx'] . '/project';

						$file_chk[$file_i]['table_name'] = 'project_file';
						$file_chk[$file_i]['table_idx']  = $file_data['prof_idx'];
						$file_chk[$file_i]['idx_name']   = 'prof_idx';

						$file_chk[$file_i]['file_name'] = $file_data['img_fname'];
						$file_chk[$file_i]['file_url']  = 'http://www.bizstory.co.kr' . $preview_dir . '/' . $file_data['pro_idx'] . '/' . $file_data['img_sname'];

						$file_i++;
					}
				}
			}
		}

		echo '<script type="text/javascript">
	//<![CDATA[';
		if (is_array($file_chk))
		{
			foreach ($file_chk as $k => $data)
			{
				$file_name  = $data['file_name'];
				$file_name  = strtolower($file_name);
				$file_url   = $data['file_url'];
				$table_name = $data['table_name'];
				$table_idx  = $data['table_idx'];
				$idx_name   = $data['idx_name'];

				echo "
					$.ajax({
						async : false,
						type: 'get', dataType: 'jsonp', url: 'http://121.88.4.88:8080/convert_request.php',
						jsonp : 'callback',
						data: { 'job_id' : 'demo', 'agent_code' : '" . $preview_agent_code . "', 'user_id' : '" . $preview_user_id . "', 'file_name' : ' " . $file_name . "', 'file_url' : '" . $file_url . "' },
						success: function(msg) {
							if (msg.success == 'Y')
							{
								$.ajax({
									async : false,
									type: 'post', dataType: 'html', url: '/bizstory/include/file_preview_ok.php',
									data: { 'change_idx' : msg.msg, 'table_name' : '" . $table_name . "', 'table_idx' : '" . $table_idx . "', 'idx_name' : '" . $idx_name . "' },
									success: function(msg) { return msg; }
								});
							}
							else
							{
								alert(msg.msg);
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