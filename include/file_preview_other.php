<?
	include "../common/setting.php";
	include "../common/no_direct.php";

	if ($f_class == 'bbs') // 게시판
	{
		$file_where = " and bf.bf_idx = '" . $idx . "'";
		$file_data = bbs_file_data('view', $file_where);

		$file_path = $comp_bbs_path . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'comp_bbs') // 게시판
	{
		$file_where = " and bf.bf_idx = '" . $idx . "'";
		$file_data = comp_bbs_file_data('view', $file_where);

		$file_path = $bbs_path . '/' . $file_data['bs_idx'] . '/' . $file_data['b_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'client_memo') // 거래처메모
	{
		$file_where = " and cimf.cimf_idx = '" . $idx . "'";
		$file_data = client_memo_file_data('view', $file_where);

		$file_path = $comp_client_path . '/' . $file_data['ci_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'company') // 회사정보
	{
		$file_where = " and cf.cf_idx = '" . $idx . "'";
		$file_data = company_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/company';
		$file_path    = $preview_path . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'company_cert') // 회사정보 - 인증서
	{
		$file_where = " and cf.cf_idx = '" . $idx . "'";
		$file_data = company_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/company';
		$file_path    = $preview_path . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'member') // 직원
	{
		$file_where = " and mf.mf_idx = '" . $idx . "'";
		$file_data = member_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/member';
		$file_path    = $preview_path . '/' . $file_data['mem_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'message') // 쪽지
	{
		$file_where = " and msgf.msgf_idx = '" . $idx . "'";
		$file_data = message_file_data('view', $file_where);

		$file_path = $comp_msg_path . '/' . $file_data['ms_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'receipt') // 접수관련
	{
		$file_where = " and rf.rf_idx = '" . $idx . "'";
		$file_data = receipt_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/receipt';
		$file_path    = $preview_path . '/' . $file_data['ri_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'receipt_comment') // 접수댓글관련
	{
		$file_where = " and rcf.rcf_idx = '" . $idx . "'";
		$file_data = receipt_comment_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/receipt';
		$file_path    = $preview_path . '/' . $file_data['ri_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'receipt_end') // 접수완료관련
	{
		$file_where = " and ref.ref_idx = '" . $idx . "'";
		$file_data = receipt_end_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/receipt';
		$file_path    = $preview_path . '/' . $file_data['ri_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'work') // 업무관련
	{
		$file_where = " and wf.wf_idx = '" . $idx . "'";
		$file_data = work_file_data('view', $file_where);

		$file_path = $comp_work_path . '/' . $file_data['wi_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'work_report') // 업무보고관련
	{
		$file_where = " and wrf.wrf_idx = '" . $idx . "'";
		$file_data = work_report_file_data('view', $file_where);

		$file_path = $comp_work_path . '/' . $file_data['wi_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'consult') // 상담게시판
	{
		$file_where = " and consf.consf_idx = '" . $idx . "'";
		$file_data = consult_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/consult';
		$file_path    = $preview_path . '/' . $file_data['cons_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'consult_comment') // 상담게시판 댓글
	{
		$file_where = " and conscf.conscf_idx = '" . $idx . "'";
		$file_data = consult_comment_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/consult';
		$file_path    = $preview_path . '/' . $file_data['cons_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'bnotice') // 알림게시판
	{
		$file_where = " and abnf.abnf_idx = '" . $idx . "'";
		$file_data = agent_bnotice_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/bnotice';
		$file_path    = $preview_path . '/' . $file_data['abn_idx'] . '/' . $file_data['img_sname'];
	}
	else if ($f_class == 'project') // 프로젝트
	{
		$file_where = " and prof.prof_idx = '" . $idx . "'";
		$file_data = project_file_data('view', $file_where);

		$preview_path = $comp_path . '/' . $file_data['comp_idx'] . '/project';
		$file_path    = $preview_path . '/' . $file_data['pro_idx'] . '/' . $file_data['img_sname'];
	}

	if (is_file($file_path))
	{
		$fp = fopen($file_path, "r");
		$txt_str = fread($fp, filesize($file_path));
		$txt_str = str_replace('<', '&lt;', $txt_str);
		$txt_str = str_replace('>', '&gt;', $txt_str);
		//$txt_str = han_utf($txt_str);
		$txt_str = nl2br($txt_str);
		fclose($fp);
	}
	$document_str = $txt_str;

	$file_name = $file_data['img_fname'];
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<div class="document_preview">
	<div class="document_title">
		<div class="btnClose">
			<a href="javascript:void(0)" onclick="popupform_close();"><img src="<?=$local_dir;?>/bizstory/images/lightbox/lightbox-btn-close.png"></a>
		</div>
		<div class="document_file_name">
			<?=$file_name;?>
		</div>
	</div>
	<div class="document_frame">
		<div class="document_view">
			<?=$document_str;?>
		</div>
	</div>
</div>
