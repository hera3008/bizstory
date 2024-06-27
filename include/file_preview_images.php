<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	$file_comp_idx = $_SESSION[$sess_str . '_comp_idx'];

	if ($f_class == 'bbs') // 게시판
	{
		$bbs_where = " and b.b_idx = '" . $idx . "'";
		$bbs_data = bbs_info_data('view', $bbs_where);

		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (bf.img_ext = '" . $img_v . "'";
			else $img_where .= " or bf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $idx . "' and bf.img_sname != ''" . $img_where;
		$file_list = bbs_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_bbs_path . '/' . $bbs_data['bs_idx'] . '/' . $idx;
		$file_dir_in  = $comp_bbs_dir  . '/' . $bbs_data['bs_idx'] . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/bbs/' . $bbs_data['bs_idx'] . '/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'comp_bbs') // 게시판
	{
		$bbs_where = " and b.b_idx = '" . $idx . "'";
		$bbs_data = comp_bbs_info_data('view', $bbs_where);

		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (bf.img_ext = '" . $img_v . "'";
			else $img_where .= " or bf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $idx . "' and bf.img_sname != ''" . $img_where;
		$file_list = comp_bbs_file_data('list', $file_where, '', '', '');

		$file_path_in = $bbs_path . '/' . $bbs_data['bs_idx'] . '/' . $idx;
		$file_dir_in  = $bbs_dir  . '/' . $bbs_data['bs_idx'] . '/' . $idx;
		$file_dir_out = $bbs_dir  . '/' . $bbs_data['bs_idx'] . '/' . $idx;
		$file_dir_cen = $bbs_dir  . '/' . $bbs_data['bs_idx'] . '/' . $idx;
	}
	else if ($f_class == 'client_memo') // 거래처메모
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (cimf.img_ext = '" . $img_v . "'";
			else $img_where .= " or cimf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and cimf.cim_idx = '" . $idx . "' and cimf.img_sname != ''" . $img_where;
		$file_list = client_memo_file_data('list', $file_where, '', '', '');
		$file_data = client_memo_file_data('view', $file_where);

		$file_path_in = $comp_client_path . '/' . $file_data['ci_idx'];
		$file_dir_in  = $comp_client_dir  . '/' . $file_data['ci_idx'];
		$file_dir_out = $set_filecneter_url  . '/upload/client/' . $file_data['ci_idx'];
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'company') // 회사정보
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (cf.img_ext = '" . $img_v . "'";
			else $img_where .= " or cf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and cf.comp_idx = '" . $idx . "' and cf.img_sname != ''" . $img_where;
		$file_list = company_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_company_path;
		$file_dir_in  = $comp_company_dir;
		$file_dir_out = $set_filecneter_url  . '/upload/company';
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'company_cert') // 회사정보 - 인증서
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (cf.img_ext = '" . $img_v . "'";
			else $img_where .= " or cf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and cf.comp_idx = '" . $idx . "' and cf.img_sname != '' and cf.file_class = 'certificate'" . $img_where;
		$file_list = company_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_company_path;
		$file_dir_in  = $comp_company_dir;
		$file_dir_out = $set_filecneter_url  . '/upload/company';
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'member') // 직원
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (mf.img_ext = '" . $img_v . "'";
			else $img_where .= " or mf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and mf.mem_idx = '" . $idx . "' and mf.img_sname != ''" . $img_where;
		$file_list = member_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_member_path . '/' . $idx;
		$file_dir_in  = $comp_member_dir  . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/member/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'message') // 쪽지
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (msgf.img_ext = '" . $img_v . "'";
			else $img_where .= " or msgf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and msgf.ms_idx = '" . $idx . "' and msgf.img_sname != ''" . $img_where;
		$file_list = message_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_msg_path . '/' . $idx;
		$file_dir_in  = $comp_msg_dir . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/message/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'receipt') // 접수관련
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (rf.img_ext = '" . $img_v . "'";
			else $img_where .= " or rf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and rf.ri_idx = '" . $idx . "' and rf.img_sname != ''" . $img_where;
		$file_list = receipt_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_receipt_path . '/' . $idx;
		$file_dir_in  = $comp_receipt_dir  . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/receipt/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'receipt_comment') // 접수댓글관련
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (rcf.img_ext = '" . $img_v . "'";
			else $img_where .= " or rcf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and rcf.rc_idx = '" . $idx . "' and rcf.img_sname != ''" . $img_where;
		$file_list = receipt_comment_file_data('list', $file_where, '', '', '');
		$file_data = receipt_comment_file_data('view', $file_where);

		$file_path_in = $comp_receipt_path . '/' . $file_data['ri_idx'];
		$file_dir_in  = $comp_receipt_dir  . '/' . $file_data['ri_idx'];
		$file_dir_out = $set_filecneter_url  . '/upload/receipt/' . $file_data['ri_idx'];
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'work') // 업무관련
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (wf.img_ext = '" . $img_v . "'";
			else $img_where .= " or wf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and wf.wi_idx = '" . $idx . "' and wf.img_sname != ''" . $img_where;
		$file_list = work_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_work_path . '/' . $idx;
		$file_dir_in  = $comp_work_dir  . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/work/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'work_report') // 업무보고관련
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (wrf.img_ext = '" . $img_v . "'";
			else $img_where .= " or wrf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and wrf.wr_idx = '" . $idx . "' and wrf.img_sname != ''" . $img_where;
		$file_list = work_report_file_data('list', $file_where, '', '', '');
		$file_data = work_report_file_data('view', $file_where);

		$file_path_in = $comp_work_path . '/' . $file_data['wi_idx'];
		$file_dir_in  = $comp_work_dir  . '/' . $file_data['wi_idx'];
		$file_dir_out = $set_filecneter_url  . '/upload/work/' . $file_data['wi_idx'];
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'consult') // 상담게시판
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (consf.img_ext = '" . $img_v . "'";
			else $img_where .= " or consf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and consf.cons_idx = '" . $idx . "' and consf.img_sname != ''" . $img_where;
		$file_list = consult_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_consult_path . '/' . $idx;
		$file_dir_in  = $comp_consult_dir  . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/consult/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'consult_comment') // 상담게시판 댓글
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (conscf.img_ext = '" . $img_v . "'";
			else $img_where .= " or conscf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and conscf.consc_idx = '" . $idx . "' and conscf.img_sname != ''" . $img_where;
		$file_list = consult_comment_file_data('list', $file_where, '', '', '');
		$file_data = consult_comment_file_data('view', $file_where);

		$file_path_in = $comp_consult_path . '/' . $file_data['cons_idx'];
		$file_dir_in  = $comp_consult_dir  . '/' . $file_data['cons_idx'];
		$file_dir_out = $set_filecneter_url  . '/upload/consult/' . $file_data['cons_idx'];
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'bnotice') // 알림게시판
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (abnf.img_ext = '" . $img_v . "'";
			else $img_where .= " or abnf.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and abnf.abn_idx = '" . $idx . "' and abnf.img_sname != ''" . $img_where;
		$file_list = agent_bnotice_file_data('list', $file_where, '', '', '');

		$file_path_in = $comp_bnotice_path . '/' . $idx;
		$file_dir_in  = $comp_bnotice_dir  . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/bnotice/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';
	}
	else if ($f_class == 'project') // 프로젝트
	{
		foreach ($set_preview_ext_img as $img_k => $img_v)
		{
			if ($img_k == 0) $img_where = " and (prof.img_ext = '" . $img_v . "'";
			else $img_where .= " or prof.img_ext = '" . $img_v . "'";
		}
		if ($img_where != '') $img_where .= ')';

		$file_where = " and prof.pro_idx = '" . $idx . "' and prof.img_sname != ''" . $img_where;
		$file_list = project_file_data('list', $file_where, '', '', '');

		$pro_where = " and pro.pro_idx = '" . $idx . "'";
		$pro_data = project_info_data('view', $pro_where);

		$file_path_in = $comp_project_path . '/' . $idx;
		$file_dir_in  = $comp_project_dir  . '/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/Project/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';

		//echo 'file_path_in -> ', $file_path_in, chr(10);
	}
?>
<div class="content_2">
	<div id="images_preview">
<?
	$file_num = 0;
	if ($file_list['total_num'] > 0)
	{
		echo '
	<ul>';
		$file_idx = 1;
		foreach ($file_list as $k => $data)
		{
			if (is_array($data))
			{
				if ($data['in_out'] == '') $data['in_out'] = 'IN';

				if ($data['in_out'] == 'CENTER') // 외부, 파일센터일 경우
				{
					$center_where = " and fi.pro_idx = '" . $idx . "' and fi.file_sname = '" . $data['img_sname'] . "'";
					$center_data = filecenter_info_data('view', $center_where);

					$img_view_name = $file_dir_cen . $center_data['file_rpath'] . '/' . $center_data['file_sname'];

					echo '
		<li>
			<div class="img_scroll"><a href="', $img_view_name, '" id="img_image_', $file_idx, '" title="', $data['img_fname'], ' No.', $file_idx, '"></a></div>
		</li>';
					$file_idx++;
					$file_num++;
				}
				else if ($data['in_out'] == 'OUT') // 외부일 경우
				{
					$img_view_name = $file_dir_out . $data['img_rpath'] . '/' . $data['img_sname'];
					echo '
		<li>
			<div class="img_scroll"><a href="', $img_view_name, '" id="img_image_', $file_idx, '" title="', $data['img_fname'], ' No.', $file_idx, '"></a></div>
		</li>';
					$file_idx++;
					$file_num++;
				}
				else // 내부일 경우
				{
					$img_path_name = $file_path_in . '/' . $data['img_sname'];
					$img_view_name = $file_dir_in . '/' . $data['img_sname'];

					if (file_exists($img_path_name) == true)
					{
						echo '
		<li>
			<div class="img_scroll"><a href="', $img_view_name, '" id="img_image_', $file_idx, '" title="', $data['img_fname'], ' No.', $file_idx, '"></a></div>
		</li>';
						$file_idx++;
						$file_num++;
					}
				}
			}
		}
		echo '
	</ul>';
	}
?>
	</div>
</div>

<script src="<?=$local_dir;?>/bizstory/js/jquery.mousewheel.min.js"></script>
<script src="<?=$local_dir;?>/bizstory/js/jquery.mCustomScrollbar.js"></script>
<link rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/jquery.mCustomScrollbar.css" type="text/css" media="screen" />
<script type="text/javascript">
	$(".content_2").mCustomScrollbar({
		scrollButtons:{
			enable:true
		}
	});
</script>