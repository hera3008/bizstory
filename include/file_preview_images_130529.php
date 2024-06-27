<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	if ($f_class == 'bbs') // 게시판
	{
		$bbs_where = " and b.b_idx = '" . $idx . "'";
		$bbs_data = bbs_info_data('view', $bbs_where);

		$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $idx . "' and bf.img_sname != ''
			and (bf.img_ext = 'jpg' or bf.img_ext = 'jpeg' or bf.img_ext = 'gif' or bf.img_ext = 'png' or bf.img_ext = 'bmp' or bf.img_ext = 'tif')";

		$file_list = bbs_file_data('list', $file_where, '', '', '');
		$file_data = bbs_file_data('view', $file_where);

		$file_path = $comp_bbs_path . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'];
		$file_dir  = $comp_bbs_dir . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'];

	}
	else if ($f_class == 'comp_bbs') // 게시판
	{
		$bbs_where = " and b.b_idx = '" . $idx . "'";
		$bbs_data = comp_bbs_info_data('view', $bbs_where);

		$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $idx . "' and bf.img_sname != ''
			and (bf.img_ext = 'jpg' or bf.img_ext = 'jpeg' or bf.img_ext = 'gif' or bf.img_ext = 'png' or bf.img_ext = 'bmp' or bf.img_ext = 'tif')";

		$file_list = comp_bbs_file_data('list', $file_where, '', '', '');
		$file_data = comp_bbs_file_data('view', $file_where);

		$file_path = $bbs_path . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'];
		$file_dir  = $bbs_dir . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'];

	}
	else if ($f_class == 'client_memo') // 거래처메모
	{
		$file_where = " and cimf.cim_idx = '" . $idx . "' and cimf.img_sname != ''
			and (cimf.img_ext = 'jpg' or cimf.img_ext = 'jpeg' or cimf.img_ext = 'gif' or cimf.img_ext = 'png' or cimf.img_ext = 'bmp' or cimf.img_ext = 'tif')";

		$file_list = client_memo_file_data('list', $file_where, '', '', '');
		$file_data = client_memo_file_data('view', $file_where);

		$file_path = $comp_client_path . '/' . $file_data['ci_idx'];
		$file_dir  = $comp_client_dir . '/' . $file_data['ci_idx'];
	}
	else if ($f_class == 'company') // 회사정보
	{
		$file_where = " and cf.comp_idx = '" . $idx . "' and cf.img_sname != ''
			and (cf.img_ext = 'jpg' or cf.img_ext = 'jpeg' or cf.img_ext = 'gif' or cf.img_ext = 'png' or cf.img_ext = 'bmp' or cf.img_ext = 'tif')";

		$file_list = company_file_data('list', $file_where, '', '', '');
		$file_data = company_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/company';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/company';
		$file_path    = $preview_path;
		$file_dir     = $preview_dir;
	}
	else if ($f_class == 'company_cert') // 회사정보 - 인증서
	{
		$file_where = " and cf.comp_idx = '" . $idx . "' and cf.img_sname != '' and cf.file_class = 'certificate'
			and (cf.img_ext = 'jpg' or cf.img_ext = 'jpeg' or cf.img_ext = 'gif' or cf.img_ext = 'png' or cf.img_ext = 'bmp' or cf.img_ext = 'tif')";

		$file_list = company_file_data('list', $file_where, '', '', '');
		$file_data = company_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/company';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/company';
		$file_path    = $preview_path;
		$file_dir     = $preview_dir;
	}
	else if ($f_class == 'member') // 직원
	{
		$file_where = " and mf.mem_idx = '" . $idx . "' and mf.img_sname != ''
			and (mf.img_ext = 'jpg' or mf.img_ext = 'jpeg' or mf.img_ext = 'gif' or mf.img_ext = 'png' or mf.img_ext = 'bmp' or mf.img_ext = 'tif')";

		$file_list = member_file_data('list', $file_where, '', '', '');
		$file_data = member_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/member';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/member';
		$file_path    = $preview_path . '/' . $file_data['mem_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['mem_idx'];
	}
	else if ($f_class == 'message') // 쪽지
	{
		$file_where = " and msgf.ms_idx = '" . $idx . "' and msgf.img_sname != ''
			and (msgf.img_ext = 'jpg' or msgf.img_ext = 'jpeg' or msgf.img_ext = 'gif' or msgf.img_ext = 'png' or msgf.img_ext = 'bmp' or msgf.img_ext = 'tif')";

		$file_list = message_file_data('list', $file_where, '', '', '');
		$file_data = message_file_data('view', $file_where);

		$file_path = $comp_msg_path . '/' . $file_data['ms_idx'];
		$file_dir  = $comp_msg_dir . '/' . $file_data['ms_idx'];
	}
	else if ($f_class == 'receipt') // 접수관련
	{
		$file_where = " and rf.ri_idx = '" . $idx . "' and rf.img_sname != ''
			and (rf.img_ext = 'jpg' or rf.img_ext = 'jpeg' or rf.img_ext = 'gif' or rf.img_ext = 'png' or rf.img_ext = 'bmp' or rf.img_ext = 'tif')";

		$file_list = receipt_file_data('list', $file_where, '', '', '');
		$file_data = receipt_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/receipt';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/receipt';
		$file_path    = $preview_path . '/' . $file_data['ri_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['ri_idx'];
	}
	else if ($f_class == 'receipt_comment') // 접수댓글관련
	{
		$file_where = " and rcf.rc_idx = '" . $idx . "' and rcf.img_sname != ''
			and (rcf.img_ext = 'jpg' or rcf.img_ext = 'jpeg' or rcf.img_ext = 'gif' or rcf.img_ext = 'png' or rcf.img_ext = 'bmp' or rcf.img_ext = 'tif')";

		$file_list = receipt_comment_file_data('list', $file_where, '', '', '');
		$file_data = receipt_comment_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/receipt';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/receipt';
		$file_path    = $preview_path . '/' . $file_data['ri_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['ri_idx'];
	}
	else if ($f_class == 'receipt_end') // 접수완료관련
	{
		$file_where = " and ref.rid_idx = '" . $idx . "' and ref.img_sname != ''
			and (ref.img_ext = 'jpg' or ref.img_ext = 'jpeg' or ref.img_ext = 'gif' or ref.img_ext = 'png' or ref.img_ext = 'bmp' or ref.img_ext = 'tif')";

		$file_list = receipt_end_file_data('list', $file_where, '', '', '');
		$file_data = receipt_end_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/receipt';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/receipt';
		$file_path    = $preview_path . '/' . $file_data['ri_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['ri_idx'];
	}
	else if ($f_class == 'work') // 업무관련
	{
		$file_where = " and wf.wi_idx = '" . $idx . "' and wf.img_sname != ''
			and (wf.img_ext = 'jpg' or wf.img_ext = 'jpeg' or wf.img_ext = 'gif' or wf.img_ext = 'png' or wf.img_ext = 'bmp' or wf.img_ext = 'tif')";

		$file_list = work_file_data('list', $file_where, '', '', '');
		$file_data = work_file_data('view', $file_where);

		$file_path = $comp_work_path . '/' . $file_data['wi_idx'];
		$file_dir  = $comp_work_dir . '/' . $file_data['wi_idx'];
	}
	else if ($f_class == 'work_report') // 업무보고관련
	{
		$file_where = " and wrf.wr_idx = '" . $idx . "' and wrf.img_sname != ''
			and (wrf.img_ext = 'jpg' or wrf.img_ext = 'jpeg' or wrf.img_ext = 'gif' or wrf.img_ext = 'png' or wrf.img_ext = 'bmp' or wrf.img_ext = 'tif')";

		$file_list = work_report_file_data('list', $file_where, '', '', '');
		$file_data = work_report_file_data('view', $file_where);

		$file_path = $comp_work_path . '/' . $file_data['wi_idx'];
		$file_dir  = $comp_work_dir . '/' . $file_data['wi_idx'];
	}
	else if ($f_class == 'consult') // 상담게시판
	{
		$file_where = " and consf.cons_idx = '" . $idx . "' and consf.img_sname != ''
			and (consf.img_ext = 'jpg' or consf.img_ext = 'jpeg' or consf.img_ext = 'gif' or consf.img_ext = 'png' or consf.img_ext = 'bmp' or consf.img_ext = 'tif')";

		$file_list = consult_file_data('list', $file_where, '', '', '');
		$file_data = consult_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/consult';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/consult';
		$file_path    = $preview_path . '/' . $file_data['cons_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['cons_idx'];
	}
	else if ($f_class == 'consult_comment') // 상담게시판 댓글
	{
		$file_where = " and conscf.consc_idx = '" . $idx . "' and conscf.img_sname != ''
			and (conscf.img_ext = 'jpg' or conscf.img_ext = 'jpeg' or conscf.img_ext = 'gif' or conscf.img_ext = 'png' or conscf.img_ext = 'bmp' or conscf.img_ext = 'tif')";

		$file_list = consult_comment_file_data('list', $file_where, '', '', '');
		$file_data = consult_comment_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/consult';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/consult';
		$file_path    = $preview_path . '/' . $file_data['cons_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['cons_idx'];
	}
	else if ($f_class == 'bnotice') // 알림게시판
	{
		$file_where = " and abnf.abn_idx = '" . $idx . "' and abnf.img_sname != ''
			and (abnf.img_ext = 'jpg' or abnf.img_ext = 'jpeg' or abnf.img_ext = 'gif' or abnf.img_ext = 'png' or abnf.img_ext = 'bmp' or abnf.img_ext = 'tif')";

		$file_list = agent_bnotice_file_data('list', $file_where, '', '', '');
		$file_data = agent_bnotice_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/bnotice';
		$preview_dir  = $comp_dir . '/' . $file_data['comp_idx'] . '/bnotice';
		$file_path    = $preview_path . '/' . $file_data['abn_idx'];
		$file_dir     = $preview_dir . '/' . $file_data['abn_idx'];
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

		$file_path_in = $comp_path . '/' . $pro_data['comp_idx'] . '/project/' . $idx;
		$file_dir_in  = $comp_dir  . '/' . $pro_data['comp_idx'] . '/project/' . $idx;
		$file_dir_out = $set_filecneter_url  . '/upload/Project/' . $idx;
		$file_dir_cen = $set_filecneter_url  . '/upload';

		echo 'file_path_in -> ', $file_path_in, chr(10);
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
				if ($data['in_out'] == 'IN') // 내부일 경우
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
				else if ($data['in_out'] == 'OUT') // 외부일 경우
				{
					$img_view_name = $file_dir_out . '/' . $data['img_sname'];
					echo '
		<li>
			<div class="img_scroll"><a href="', $img_view_name, '" id="img_image_', $file_idx, '" title="', $data['img_fname'], ' No.', $file_idx, '"></a></div>
		</li>';
					$file_idx++;
					$file_num++;
				}
				else // 외부, 파일센터 일 경우
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