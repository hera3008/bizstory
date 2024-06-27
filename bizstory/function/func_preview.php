<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 첨부파일 미리보기 관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

//-------------------------------------- 문서 미리보기 파일
	function preview_file($f_path, $f_idx, $f_class)
	{
		global $preview_agent_code, $preview_user_id, $set_preview_ext_str, $set_preview_ext_other, $set_viewer_yn;

		if ($set_viewer_yn == 'Y')
		{
			if ($f_class == 'bbs') // 게시판
			{
				$file_where = " and bf.bf_idx = '" . $f_idx . "'";
				$file_data = bbs_file_data('view', $file_where);
			}
			else if ($f_class == 'comp_bbs') // 게시판
			{
				$file_where = " and bf.bf_idx = '" . $f_idx . "'";
				$file_data = comp_bbs_file_data('view', $file_where);
			}
			else if ($f_class == 'client_memo') // 거래처메모
			{
				$file_where = " and cimf.cimf_idx = '" . $f_idx . "'";
				$file_data = client_memo_file_data('view', $file_where);
			}
			else if ($f_class == 'company') // 회사정보
			{
				$file_where = " and cf.cf_idx = '" . $f_idx . "'";
				$file_data = company_file_data('view', $file_where);
			}
			else if ($f_class == 'member') // 직원
			{
				$file_where = " and mf.mf_idx = '" . $f_idx . "'";
				$file_data = member_file_data('view', $file_where);
			}
			else if ($f_class == 'message') // 쪽지
			{
				$file_where = " and msgf.msgf_idx = '" . $f_idx . "'";
				$file_data = message_file_data('view', $file_where);
			}
			else if ($f_class == 'receipt') // 접수관련
			{
				$file_where = " and rf.rf_idx = '" . $f_idx . "'";
				$file_data = receipt_file_data('view', $file_where);
			}
			else if ($f_class == 'receipt_comment') // 접수댓글관련
			{
				$file_where = " and rcf.rcf_idx = '" . $f_idx . "'";
				$file_data = receipt_comment_file_data('view', $file_where);
			}
			else if ($f_class == 'receipt_end') // 접수완료관련
			{
				$file_where = " and ref.ref_idx = '" . $f_idx . "'";
				$file_data = receipt_end_file_data('view', $file_where);
			}
			else if ($f_class == 'work') // 업무관련
			{
				$file_where = " and wf.wf_idx = '" . $f_idx . "'";
				$file_data = work_file_data('view', $file_where);
			}
			else if ($f_class == 'work_report') // 업무보고관련
			{
				$file_where = " and wrf.wrf_idx = '" . $f_idx . "'";
				$file_data = work_report_file_data('view', $file_where);
			}
			else if ($f_class == 'consult') // 상담관련
			{
				$file_where = " and consf.consf_idx = '" . $f_idx . "'";
				$file_data = consult_file_data('view', $file_where);
			}
			else if ($f_class == 'consult_comment') // 상담댓글관련
			{
				$file_where = " and conscf.conscf_idx = '" . $f_idx . "'";
				$file_data = consult_comment_file_data('view', $file_where);
			}
			else if ($f_class == 'bnotice') // 알림게시판
			{
				$file_where = " and abnf.abnf_idx = '" . $f_idx . "'";
				$file_data = agent_bnotice_file_data('view', $file_where);
			}

			$ext = $file_data['img_ext'];
			$str = '';
			if (strlen(stristr($set_preview_ext_str, $ext)) > 0)
			{
				if ($file_data['change_idx'] > 0)
				{
					$str = '
						<a href="javascript:void(0);" onclick="file_preview_result(\'' . $preview_agent_code . '\', \'' . $preview_user_id . '\', \'' . $file_data['change_idx'] . '\', \'' . $file_data['img_fname'] . '\')" class="btn_sml2_green"><span>미리보기</span></a>&nbsp;';
				}
			}
			if (strlen(stristr($set_preview_ext_other, $ext)) > 0)
			{
				$str = '
					<a href="javascript:void(0);" onclick="file_preview_other(\'' . $f_class . '\', \'' . $f_idx . '\', \'' . $ext . '\')" class="btn_sml2_green"><span>미리보기</span></a>&nbsp;';
			}
		}
		else $str = '';

		Return $str;
	}

//-------------------------------------- 문서 미리보기 파일
	function preview_files($f_idx, $f_class)
	{
		global $preview_agent_code, $preview_user_id, $set_preview_ext_str, $set_preview_ext_other, $set_viewer_yn;

		if ($set_viewer_yn == 'Y')
		{
			if ($f_class == 'project') // 프로젝트
			{
				$file_where = " and prof.prof_idx = '" . $f_idx . "'";
				$file_data = project_file_data('view', $file_where);
			}

			$ext = $file_data['img_ext'];
			if (strlen(stristr($set_preview_ext_str, $ext)) > 0)
			{
				if ($file_data['change_idx'] > 0)
				{
					$str = '
						<a href="javascript:void(0);" onclick="file_preview_result(\'' . $preview_agent_code . '\', \'' . $preview_user_id . '\', \'' . $file_data['change_idx'] . '\', \'' . $file_data['img_fname'] . '\')" class="btn_sml2_green"><span>미리보기</span></a>&nbsp;';
				}
			}
			if (strlen(stristr($set_preview_ext_other, $ext)) > 0)
			{
				$str = '
					<a href="javascript:void(0);" onclick="file_preview_other(\'' . $f_class . '\', \'' . $f_idx . '\', \'' . $ext . '\')" class="btn_sml2_green"><span>미리보기</span></a>&nbsp;';
			}
		}
		else $str = '';

		Return $str;
	}

//-------------------------------------- 이미지 미리보기 파일
	function preview_images($idx, $f_class)
	{
		global $set_viewer_yn, $set_preview_ext_img;

		if ($set_viewer_yn == 'Y')
		{
			if ($f_class == 'bbs') // 게시판
			{
				$bbs_where = " and b.b_idx = '" . $idx . "'";
				$bbs_data = bbs_info_data('view', $bbs_where);

				$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $idx . "' and bf.img_sname != ''
					and (bf.img_ext = 'jpg' or bf.img_ext = 'jpeg' or bf.img_ext = 'gif' or bf.img_ext = 'png' or bf.img_ext = 'bmp' or bf.img_ext = 'tif')";
				$file_page = bbs_file_data('page', $file_where);
			}
			else if ($f_class == 'comp_bbs') // 게시판
			{
				$bbs_where = " and b.b_idx = '" . $idx . "'";
				$bbs_data = comp_bbs_info_data('view', $bbs_where);

				$file_where = " and bf.bs_idx = '" . $bbs_data['bs_idx'] . "' and bf.b_idx = '" . $idx . "' and bf.img_sname != ''
					and (bf.img_ext = 'jpg' or bf.img_ext = 'jpeg' or bf.img_ext = 'gif' or bf.img_ext = 'png' or bf.img_ext = 'bmp' or bf.img_ext = 'tif')";
				$file_page = comp_bbs_file_data('page', $file_where);
			}
			else if ($f_class == 'client_memo') // 거래처메모
			{
				$file_where = " and cimf.cim_idx = '" . $idx . "' and cimf.img_sname != ''
					and (cimf.img_ext = 'jpg' or cimf.img_ext = 'jpeg' or cimf.img_ext = 'gif' or cimf.img_ext = 'png' or cimf.img_ext = 'bmp' or cimf.img_ext = 'tif')";
				$file_page = client_memo_file_data('view', $file_where);
			}
			else if ($f_class == 'company') // 회사정보
			{
				$file_where = " and cf.comp_idx = '" . $idx . "' and cf.img_sname != ''
					and (cf.img_ext = 'jpg' or cf.img_ext = 'jpeg' or cf.img_ext = 'gif' or cf.img_ext = 'png' or cf.img_ext = 'bmp' or cf.img_ext = 'tif')";
				$file_page = company_file_data('view', $file_where);
			}
			else if ($f_class == 'company_cert') // 회사정보 - 인증서
			{
				$file_where = " and cf.comp_idx = '" . $idx . "' and cf.img_sname != '' and cf.file_class = 'certificate'
					and (cf.img_ext = 'jpg' or cf.img_ext = 'jpeg' or cf.img_ext = 'gif' or cf.img_ext = 'png' or cf.img_ext = 'bmp' or cf.img_ext = 'tif')";
				$file_page = company_file_data('page', $file_where);
			}
			else if ($f_class == 'member') // 직원
			{
				$file_where = " and mf.mem_idx = '" . $idx . "' and mf.img_sname != ''
					and (mf.img_ext = 'jpg' or mf.img_ext = 'jpeg' or mf.img_ext = 'gif' or mf.img_ext = 'png' or mf.img_ext = 'bmp' or mf.img_ext = 'tif')";
				$file_page = member_file_data('view', $file_where);
			}
			else if ($f_class == 'message') // 쪽지
			{
				$file_where = " and msgf.ms_idx = '" . $idx . "' and msgf.img_sname != ''
					and (msgf.img_ext = 'jpg' or msgf.img_ext = 'jpeg' or msgf.img_ext = 'gif' or msgf.img_ext = 'png' or msgf.img_ext = 'bmp' or msgf.img_ext = 'tif')";
				$file_page = message_file_data('view', $file_where);
			}
			else if ($f_class == 'receipt') // 접수관련
			{
				$file_where = " and rf.ri_idx = '" . $idx . "' and rf.img_sname != ''
					and (rf.img_ext = 'jpg' or rf.img_ext = 'jpeg' or rf.img_ext = 'gif' or rf.img_ext = 'png' or rf.img_ext = 'bmp' or rf.img_ext = 'tif')";
				$file_page = receipt_file_data('view', $file_where);
			}
			else if ($f_class == 'receipt_comment') // 접수댓글관련
			{
				$file_where = " and rcf.rc_idx = '" . $idx . "' and rcf.img_sname != ''
					and (rcf.img_ext = 'jpg' or rcf.img_ext = 'jpeg' or rcf.img_ext = 'gif' or rcf.img_ext = 'png' or rcf.img_ext = 'bmp' or rcf.img_ext = 'tif')";
				$file_page = receipt_comment_file_data('view', $file_where);
			}
			else if ($f_class == 'receipt_end') // 접수완료관련
			{
				$file_where = " and ref.rid_idx = '" . $idx . "' and ref.img_sname != ''
					and (ref.img_ext = 'jpg' or ref.img_ext = 'jpeg' or ref.img_ext = 'gif' or ref.img_ext = 'png' or ref.img_ext = 'bmp' or ref.img_ext = 'tif')";
				$file_page = receipt_end_file_data('view', $file_where);
			}
			else if ($f_class == 'work') // 업무관련
			{
				$file_where = " and wf.wi_idx = '" . $idx . "' and wf.img_sname != ''
					and (wf.img_ext = 'jpg' or wf.img_ext = 'jpeg' or wf.img_ext = 'gif' or wf.img_ext = 'png' or wf.img_ext = 'bmp' or wf.img_ext = 'tif')";
				$file_page = work_file_data('view', $file_where);
			}
			else if ($f_class == 'work_report') // 업무보고관련
			{
				$file_where = " and wrf.wr_idx = '" . $idx . "' and wrf.img_sname != ''
					and (wrf.img_ext = 'jpg' or wrf.img_ext = 'jpeg' or wrf.img_ext = 'gif' or wrf.img_ext = 'png' or wrf.img_ext = 'bmp' or wrf.img_ext = 'tif')";
				$file_page = work_report_file_data('view', $file_where);
			}
			else if ($f_class == 'consult') // 상담관련
			{
				$file_where = " and consf.cons_idx = '" . $idx . "' and consf.img_sname != ''
					and (consf.img_ext = 'jpg' or consf.img_ext = 'jpeg' or consf.img_ext = 'gif' or consf.img_ext = 'png' or consf.img_ext = 'bmp' or consf.img_ext = 'tif')";
				$file_page = consult_file_data('view', $file_where);
			}
			else if ($f_class == 'consult_comment') // 상담댓글관련
			{
				$file_where = " and conscf.consc_idx = '" . $idx . "' and conscf.img_sname != ''
					and (conscf.img_ext = 'jpg' or conscf.img_ext = 'jpeg' or conscf.img_ext = 'gif' or conscf.img_ext = 'png' or conscf.img_ext = 'bmp' or conscf.img_ext = 'tif')";
				$file_page = consult_comment_file_data('view', $file_where);
			}
			else if ($f_class == 'bnotice') // 알림게시판
			{
				$file_where = " and abnf.abn_idx = '" . $idx . "' and abnf.img_sname != ''
					and (abnf.img_ext = 'jpg' or abnf.img_ext = 'jpeg' or abnf.img_ext = 'gif' or abnf.img_ext = 'png' or abnf.img_ext = 'bmp' or abnf.img_ext = 'tif')";
				$file_page = agent_bnotice_file_data('view', $file_where);
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
				$file_page = project_file_data('view', $file_where);
			}

			$file_num = $file_page['total_num'];
			if ($file_num > 0)
			{
				$str = '<a href="javascript:void(0);" onclick="file_preview_images(\'' . $f_class . '\', \'' . $idx . '\', \'' . $file_num . '\')" class="btn_sml2_violet"><span>이미지 미리보기</span></a>';
			}
			else $str = '';
		}
		else $str = '';

		Return $str;
	}
?>