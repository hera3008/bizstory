<?
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////
///// 지사별 정보관련 함수
/////
////////////////////////////////////////////////////////////////////////////////////////////////////////

// 지사관련정보
	class part_information
	{
		var $code_comp;
		var $code_part;
		var $shsgroup;

		public function part_info_view() // 지사정보
		{
			$part_where = " and part.comp_idx = '" . $this->code_comp . "' and part.part_idx = '" . $this->code_part . "'";
			$part_view = company_part_data('view', $part_where);

			return $part_view;
		}

		public function part_duty() // 직책목록
		{
			$part_where = " and cpd.comp_idx = '" . $this->code_comp . "' and cpd.part_idx = '" . $this->code_part . "' and cpd.view_yn = 'Y'";
			$part_order = "cpd.sort asc";
			$part_list = company_part_duty_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_staff_group() // 직원그룹목록
		{
			$part_where = " and csg.comp_idx = '" . $this->code_comp . "' and csg.part_idx = '" . $this->code_part . "' and csg.view_yn = 'Y'";
			$part_order = "csg.sort asc";
			$part_list = company_staff_group_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_staff_info() // 직원목록
		{
			$part_where = " and mem.comp_idx = '" . $this->code_comp . "' and mem.part_idx = '" . $this->code_part . "' and mem.login_yn = 'Y'";
			$part_order = "mem.mem_name asc";
			$part_list = member_info_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_staff_info_group() // 직원그룹별 직원
		{
			$part_where = " and mem.comp_idx = '" . $this->code_comp . "' and mem.part_idx = '" . $this->code_part . "' and mem.login_yn = 'Y'";
			if ($shsgroup != '') $part_where .= " and mem.csg_idx = '" . $this->shsgroup . "'";
			$part_order = "mem.mem_name asc";
			$part_list = member_info_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_client_group() // 거래처그룹목록
		{
			$part_where = " and ccg.comp_idx = '" . $this->code_comp . "' and ccg.part_idx = '" . $this->code_part . "' and ccg.view_yn = 'Y'";
			$part_order = "ccg.sort asc";
			$part_list = company_client_group_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_client_info() // 거래처목록
		{
			$part_where = " and ci.comp_idx = '" . $this->code_comp . "' and ci.part_idx = '" . $this->code_part . "'";
			$part_order = "ci.client_name asc";
			$part_list = client_info_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_receipt_class() // 접수분류목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_receipt_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_receipt_status() // 접수상태목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_receipt_status_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_work_class() // 업무분류목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_work_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_work_status() // 업무상태목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_work_status_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_consult_class() // 상담분류목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_consult_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_bnotice_class() // 알림분류목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_bnotice_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_account_class() // 계정과목목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_account_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_account_gubun() // 회계구분목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_account_gubun_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_account_bank() // 통장목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_account_bank_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_account_card() // 카드목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_account_card_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_report_class() // 점검보고서타입
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y' and code.menu_depth = '1'";
			$part_order = "code.sort asc";
			$part_list = code_report_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}







		public function part_sche_class() // 일정종류목록
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_sche_class_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}


		public function project_info() // 프로젝트목록
		{
			$part_where = " and pro.comp_idx = '" . $this->code_comp . "'";
			$part_order = "pro.reg_date desc";
			$part_list = project_info_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_dili_status() // 출근부상태
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'";
			$part_order = "code.sort asc";
			$part_list = code_dili_status_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}

		public function part_dili_status2() // 출근부상태(출근, 퇴근, 지각, 야근, 자동, 기타 제외)
		{
			$part_where = " and code.comp_idx = '" . $this->code_comp . "' and code.part_idx = '" . $this->code_part . "' and code.view_yn = 'Y'
				and (code.code_value != '11' and code.code_value != '21' and code.code_value != '31' and code.code_value != '41' and code.code_value != '51' and code.code_value != '90')";
			$part_order = "code.sort asc";
			$part_list = code_dili_status_data('list', $part_where, $part_order, '', '');

			return $part_list;
		}
	}

// 접수정보관련
	class receipt_info
	{
		var $ri_idx;
		var $ri_where;
		var $ri_order;
		var $data_path;
		var $data_dir;

		var $local_dir;
		var $set_receipt_status;

	// 접수정보
		public function receipt_info_view()
		{
			global $set_color_list2;

			$idx = $this->ri_idx;

			$receipt_where = " and ri.ri_idx = '" . $idx . "'";
			$receipt_data = receipt_info_data('view', $receipt_where);

		// 지사
			$sub_where = " and part.part_idx = '" . $receipt_data['part_idx'] . "'";
			$sub_data = company_part_data('view', $sub_where);

			$receipt_data['part_name'] = $sub_data['part_name'];

		// 접수분류
			$receipt_class = receipt_class_view($receipt_data['receipt_class']);
			$receipt_data['receipt_class_str'] = $receipt_class['code_name'];

		// 지사별 접수상태
			$sub_where = " and code.comp_idx = '" . $receipt_data['comp_idx'] . "' and code.part_idx = '" . $receipt_data['part_idx'] . "'";
			$sub_list = code_receipt_status_data('list', $sub_where, '', '', '');
			$receipt_data['code_status'] = $sub_list;

		// 거래처정보
			$client_where = " and ci.ci_idx = '" . $receipt_data['ci_idx'] . "'";
			$client_data = client_info_data('view', $client_where);

			$receipt_data['client_memo1'] = $client_data['memo1']; // 접속정보
			$receipt_data['client_memo1'] = str_replace('"', '&quot;', $receipt_data['client_memo1']);

		// 중요도
			if ($receipt_data['important'] == 'RI02') $important_img = '<span class="btn_level_1"><span>상</span></span>';
			else if ($receipt_data['important'] == 'RI03') $important_img = '<span class="btn_level_2"><span>중</span></span>';
			else if ($receipt_data['important'] == 'RI04') $important_img = '<span class="btn_level_3"><span>하</span></span>';
			else $important_img = '';
			$receipt_data['important_img'] = $important_img;

		// 홈페이지주소
			$link_url = $receipt_data['link_url'];
			$link_url_arr = explode(',', $link_url);
			if ($link_url_arr[0] != '')
			{
				$link_string = str_replace('http://', '', $link_url_arr[0]);
				$link_html = '<a href="http://' . $link_string . '" target="_blank" id="client_memo" title="' . nl2br($receipt_data['client_memo1']) . '">' . $receipt_data['client_name'] . ' <img src="' . $local_dir . '/bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></a>';
			}
			else
			{
				$link_html = '<a id="client_memo" title="' . nl2br($receipt_data['client_memo1']) . '">' . $receipt_data['client_name'] . '</a>';
			}
			$receipt_data['link_html'] = $link_html;

		// 담당자
			if ($receipt_data['charge_mem_idx'] == '' || $receipt_data['charge_mem_idx'] == '0')
			{
				$receipt_data['charge_mem_idx'] = $client_data['mem_idx'];
			}
			$mem_where = " and mem.mem_idx = '" . $receipt_data['charge_mem_idx'] . "'";
			$mem_data = member_info_data('view', $mem_where);

			$receipt_data['charge_mem_name'] = $mem_data['mem_name'];

			$charge_str = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#ff6c00">' . $mem_data['mem_name'] . '</strong>';
			$receipt_data['charge_str'] = $charge_str;

			$charge_mobile_str = '[<span class="c_blue">' . $mem_data['part_name'] . '</span>:<span class="c_green">' . $mem_data['group_name'] . '</span>] <a href="javascript:void(0)" onclick="viewMemInfo(\'' . $mem_data['mem_idx'] . '\');" class="md-trigger" data-modal="modal"><strong style="color:#ff6c00">' . $mem_data['mem_name'] . '</span></a>';
			$receipt_data['charge_mobile_str'] = $charge_mobile_str;

		// 첨부파일
			$file_where = " and rf.ri_idx = '" . $idx . "'";
			$file_page = receipt_file_data('page', $file_where);
			$receipt_data['total_file'] = $file_page['total_num'];

		// 코멘트
			$comment_where = " and rc.ri_idx='" . $idx . "'";
			$comment_page = receipt_comment_data('page', $comment_where);
			$receipt_data['total_comment'] = $comment_page['total_num'];

			return $receipt_data;
		}

	// 접수정보 목록
		public function receipt_info_list()
		{
			$where     = $this->ri_where;
			$order     = $this->ri_order;
			$page_num  = $this->ri_page_num;
			$page_size = $this->ri_page_size;

			$list = receipt_info_data('list', $where, $order, $page_num, $page_size);

		}

	// 접수파일
		public function receipt_file()
		{
			$sub_where = " and rf.ri_idx = '" . $this->ri_idx . "'";
			$sub_list = receipt_file_data('list', $sub_where, '', '', '');

			return $sub_list;
		}

	// 접수상태내역
		public function receipt_status_history()
		{
			$sub_where = " and rsh.ri_idx = '" . $this->ri_idx . "'";
			$sub_list = receipt_status_history_data('list', $sub_where, '', '', '');

			return $sub_list;
		}

	// 접수상태내역 - 상태만
		public function receipt_status_only()
		{
			global $set_receipt_status, $comp_dir, $local_dir;

			$ri_idx = $this->ri_idx;

		// 상세값 총수
			$chk_where = " and rid.ri_idx = '" . $ri_idx . "'";
			$chk_data = receipt_info_detail_data('view', $chk_where);
			//echo 'total_num -> ', $chk_data['total_num'], '<br />';
			//echo 'query -> ', $chk_data['query_string'], '<br />';

			$comp_member_dir = $comp_dir . '/' . $chk_data['comp_idx'] . '/member';

			$sub_where = " and rsh.ri_idx = '" . $ri_idx . "' and rsh.status != ''";
			$sub_order = "rsh.ri_idx asc, rsh.rid_idx asc, rsh.reg_date asc";
			$sub_list = receipt_status_history_data('list', $sub_where, $sub_order, '', '');
			foreach ($sub_list as $sub_k => $sub_data)
			{
				if (is_array($sub_data))
				{
				// 상세접수건
					$detail_where = " and rid.rid_idx = '" . $sub_data['rid_idx'] . "'";
					$detail_data = receipt_info_detail_data('view', $detail_where);

					$rid_idx  = $sub_data['rid_idx'];
					$status   = $sub_data['status'];
					$mem_idx  = $detail_data['mem_idx'];
					$mem_name = $detail_data['mem_name'];

				// 담당자 이미지
					$photo_where = " and mf.mem_idx = '" . $mem_idx . "' and mf.sort = '1'";
					$photo_data = member_file_data('view', $photo_where);
					if ($photo_data['total_num'] > 0)
					{
						$photo_img = '<img src="' . $comp_member_dir . '/' . $photo_data['mem_idx'] . '/' . $photo_data['img_sname'] . '" width="26px" height="26px" alt="' . $photo_data['mem_name'] . '" />';
					}
					else
					{
						$photo_img = '<img src="' . $local_dir . '/bizstory/images/common/no_photo.gif" width="26px" height="26px" alt="' . $photo_data['mem_name'] . '" />';
					}

				// 접수상태
					$status_where = " and code.comp_idx = '" . $sub_data['comp_idx'] . "' and code.part_idx = '" . $sub_data['part_idx'] . "' and code.code_value = '" . $status . "'";
					$status_data = code_receipt_status_data('view', $status_where);

					$status_str = '<span style="';
					if ($status_data['code_bold'] == 'Y') $status_str .= 'font-weight:900;';
					if ($status_data['code_color'] != '') $status_str .= 'color:' . $status_data['code_color'] . ';';
					$status_str .= '">' . $status_data['code_name'] . '</span>';

				// 접수자/변경자
					if ($sub_data['mem_name'] == '') $reg_name = $sub_data['reg_id'];
					else $reg_name = $sub_data['mem_name'];

				// 상태별로 표현
					if ($status == 'RS01') // 접수등록
					{
						$status_list[$rid_idx][$status] .= '<div><span class="icon01"></span> ' . $status_str . ' [ ' . $reg_name . ' : ' . $sub_data['reg_date'] . ' ]</div>';
					}
					else
					{
						if ($detail_data['total_num'] > 0)
						{
							$status_list[$rid_idx][$status] .= '<div>';
							if ($chk_data['total_num'] > 0)
							{
								if ($rid_idx != $old_rid_idx)
								{
									$status_list[$rid_idx][$status] .= '
										<div class="mem_user">
											<span class="mem">' . $photo_img . '</span>
											<span class="user"><a class="name_ui">' . $mem_name . '</a></span>
										</div>
									';
								}
								$status_list[$rid_idx][$status] .= '<div><span class="icon03">&nbsp;</span> ' . $status_str . ' [ ' . $reg_name . ' : ' . $sub_data['reg_date'] . ' ]</div>';
							}
							else
							{
								$status_list[$rid_idx][$status] .= '<div><span class="icon01"></span> ' . $status_str . ' [ ' . $reg_name . ' : ' . $sub_data['reg_date'] . ' ]</div>';
							}
							$status_list[$rid_idx][$status] .= '</div>';
						}
					}
					if ($status == 'RS90') // 완료일 경우
					{
						$status_list[$rid_idx][$status] .= '<div class="status_str"><span class="icon02"></span> ' . nl2br($detail_data['remark_end']) . '</div>';
					}
					$old_rid_idx = $rid_idx;
				}
			}
			ksort($status_list);

			return $status_list;
		}

	// 접수상태내역 - 상태만
		public function receipt_status_only_mobile()
		{
			global $set_receipt_status, $comp_dir, $local_dir;

			$ri_idx = $this->ri_idx;

		// 상세값 총수
			$chk_where = " and rid.ri_idx = '" . $ri_idx . "'";
			$chk_data = receipt_info_detail_data('view', $chk_where);
			//echo 'total_num -> ', $chk_data['total_num'], '<br />';
			//echo 'query -> ', $chk_data['query_string'], '<br />';

			$comp_member_dir = $comp_dir . '/' . $chk_data['comp_idx'] . '/member';

			$sub_where = " and rsh.ri_idx = '" . $ri_idx . "' and rsh.status != ''";
			$sub_order = "rsh.ri_idx asc, rsh.rid_idx asc, rsh.reg_date asc";
			$sub_list = receipt_status_history_data('list', $sub_where, $sub_order, '', '');
			foreach ($sub_list as $sub_k => $sub_data)
			{
				if (is_array($sub_data))
				{
				// 상세접수건
					$detail_where = " and rid.rid_idx = '" . $sub_data['rid_idx'] . "'";
					$detail_data = receipt_info_detail_data('view', $detail_where);

					$rid_idx  = $sub_data['rid_idx'];
					$status   = $sub_data['status'];
					$mem_idx  = $detail_data['mem_idx'];
					$mem_name = $detail_data['mem_name'];

				// 담당자 이미지
					$photo_where = " and mf.mem_idx = '" . $mem_idx . "' and mf.sort = '1'";
					$photo_data = member_file_data('view', $photo_where);
					if ($photo_data['total_num'] > 0)
					{
						$photo_img = '<img src="' . $comp_member_dir . '/' . $photo_data['mem_idx'] . '/' . $photo_data['img_sname'] . '" width="26px" height="26px" alt="' . $photo_data['mem_name'] . '" />';
					}
					else
					{
						$photo_img = '<img src="' . $local_dir . '/bizstory/images/common/no_photo.gif" width="26px" height="26px" alt="' . $photo_data['mem_name'] . '" />';
					}

				// 접수상태
					$status_where = " and code.comp_idx = '" . $sub_data['comp_idx'] . "' and code.part_idx = '" . $sub_data['part_idx'] . "' and code.code_value = '" . $status . "'";
					$status_data = code_receipt_status_data('view', $status_where);

					$status_str = '<span class="';
					if ($status_data['code_bold'] == 'Y') $status_str .= 'fw700 ';
					switch ($status_data['code_color']) {
						case "#ff0000":
							$status_str .= ' c_white';
							break;
						case "#ff6c00":
							$status_str .= ' icon03 c_orange';
							break;
						case "#009e25":
							$status_str .= ' icon03 c_green';
							break;
						case "#518fbb":
							$status_str .= ' icon03 c_blue3';
							break;
							
						default:
						
							break;
					}
						
					$status_str .= '">' . $status_data['code_name'] . '</span>';

				// 접수자/변경자
					if ($sub_data['mem_name'] == '') $reg_name = $sub_data['reg_id'];
					else $reg_name = $sub_data['mem_name'];

				// 상태별로 표현
					if ($status == 'RS01') // 접수등록
					{
						$status_list[$rid_idx][$status] .= '<div class="ico_mem"><span> ' . $status_str . ' [ ' . $reg_name . ' : ' . $sub_data['reg_date'] . ' ]</span></div>';
					}
					else
					{
						if ($detail_data['total_num'] > 0)
						{
							$status_list[$rid_idx][$status] .= '<div clas="mem_regist">';
							if ($chk_data['total_num'] > 0)
							{
								if ($rid_idx != $old_rid_idx)
								{
									$status_list[$rid_idx][$status] .= '
										<div class="mem_user">
											<span>' . $photo_img . '</span>
											<span class="user"><a class="name_ui">' . $mem_name . '</a></span>
										</div>
										
									';
								}
								$status_list[$rid_idx][$status] .= '<ul class="mem_ullist"><li><span class="mem_list">&nbsp;</span> ' . $status_str . ' [ ' . $reg_name . ' : ' . $sub_data['reg_date'] . ' ]</li></ul>';
							}
							else
							{
								$status_list[$rid_idx][$status] .= '<ul class="mem_ullist"><li><span class="mem_list">&nbsp;</span> ' . $status_str . ' [ ' . $reg_name . ' : ' . $sub_data['reg_date'] . ' ]</li></ul>';
							}
							$status_list[$rid_idx][$status] .= '</div>';
						}
					}
					if ($status == 'RS90') // 완료일 경우
					{
						$status_list[$rid_idx][$status] .= '<div class="status_str"><span class="icon02"></span> ' . nl2br($detail_data['remark_end']) . '</div>';
					}
					$old_rid_idx = $rid_idx;
				}
			}
			
			ksort($status_list);

			return $status_list;
		}
	}

// 업무정보관련 - CLASS
	class work_info
	{
		var $wi_idx;
		var $data_path;
		var $data_dir;

		public function work_info_view() // 업무정보
		{
			global $set_work_deadline_txt, $set_color_list2;
			global $_SESSION, $sess_str, $local_dir, $comp_set_data;

			echo 'work_info_view';
			

			$code_mem = $_SESSION[$sess_str . '_mem_idx'];

			$idx = $this->wi_idx;
			print_r($idx);

			$work_where = " and wi.wi_idx = '" . $idx . "'";
			$work_data = work_info_data('view', $work_where);

			$code_comp = $work_data['comp_idx'];
			$code_part = $work_data['part_idx'];
			$set_part_yn      = $comp_set_data['part_yn'];
			$set_part_work_yn = $comp_set_data['part_work_yn'];

		//////////////////////////////////////////////////////////////////////////////////////
		// 담당자, 읽은자 등록 - 알림일 경우
			if ($work_data['work_type'] == 'WT04')
			{
				$charge_idx = $work_data['charge_idx'];
				$charge_arr = explode(',', $charge_idx);
				$charge_num = 0;
				$read_charge_num = 0;
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					$charge_num++;

				// 읽음 표시
					$read_where = " and wre.wi_idx = '" . $idx . "' and wre.mem_idx = '" . $charge_v . "'";
					$read_data = work_read_data('view', $read_where);
					if ($read_data['total_num'] == 0)
					{
						if ($charge_v == $code_mem)
						{
							$insert_query = "
								insert into work_read set
									  comp_idx  = '" . $code_comp . "'
									, part_idx  = '" . $code_part . "'
									, wi_idx    = '" . $idx . "'
									, mem_idx   = '" . $code_mem . "'
									, read_date = '" . date('Y-m-d H:i:s') . "'
									, reg_id    = '" . $code_mem . "'
									, reg_date  = '" . date('Y-m-d H:i:s') . "'
							";
							db_query($insert_query);
							query_history($insert_query, 'work_read', 'insert');

							$read_charge_num++;
						}
					}
					else
					{
						$read_charge_num++;
					}
				}

			// 다 읽으면 업무완료처리
				if ($read_charge_num >= $charge_num)
				{
					if ($work_data['work_status'] != 'WS90')
					{
						$status_query = "
							update work_info set
								  work_status = 'WS90'
								, end_date    = '" . date('Y-m-d H:i:s') . "'
								, mod_id      = '" . $comp_mem . "'
								, mod_date    = '" . date('Y-m-d H:i:s') . "'
							where wi_idx = '" . $idx . "'
						";
						db_query($status_query);
						query_history($status_query, 'work_info', 'update');

					// 히스토리저장
						$history_query = "
							insert into work_status_history set
								  comp_idx    = '" . $code_comp . "'
								, part_idx    = '" . $code_part . "'
								, wi_idx      = '" . $idx . "'
								, mem_idx     = '" . $code_mem . "'
								, status      = 'WS90'
								, status_date = '" . date('Y-m-d H:i:s') . "'
								, status_memo = '업무가 완료되었습니다.'
								, reg_id      = '" . $comp_mem . "'
								, reg_date    = '" . date('Y-m-d H:i:s') . "'
						";
						db_query($history_query);
						query_history($history_query, 'work_status_history', 'insert');
					}
				}
			}
		//////////////////////////////////////////////////////////////////////////////////////

		// 업무정보
			$work_where = " and wi.wi_idx = '" . $idx . "'";
			$work_data = work_info_data('view', $work_where);

		// 지사
			$sub_where = " and part.part_idx = '" . $work_data['part_idx'] . "'";
			$sub_data = company_part_data('view', $sub_where);

			$work_data['part_name'] = $sub_data['part_name'];

		// 담당자, 읽은자 등록
			$charge_idx = $work_data['charge_idx'];
			$charge_arr = explode(',', $charge_idx);
			$total_charge_str = '';
			$charge_num = 0;
			foreach ($charge_arr as $charge_k => $charge_v)
			{
				$mem_where = " and mem.mem_idx = '" . $charge_v . "'";
				$mem_data = member_info_data('view', $mem_where, '', '', '', 2);

				if ($mem_data['total_num'] > 0)
				{
					$charge_name = $mem_data['mem_name'];
					if ($set_part_work_yn == 'Y')
					{
						$part_where = " and part.comp_idx = '" . $code_comp . "'";
						$part_data = company_part_data('page', $part_where);

						if ($part_data['total_num'] > 1)
						{
							if ($mem_data['del_yn'] == 'Y')
							{
								$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</strong>';
							}
							else
							{
								$charge_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span>:' . $mem_data['group_name'] . '] <strong style="color:#ff6c00">' . $mem_data['mem_name'] . '</strong>';
							}
						}
					}
					else
					{
						if ($mem_data['del_yn'] == 'Y')
						{
							$charge_name = '<span style="color:#afafaf;text-decoration:line-through">' . $mem_data['mem_name'] . '</span>';
						}
						else
						{
							$charge_name = $mem_data['mem_name'];
						}
					}

					$total_charge_str .= ', ' . $charge_name;
					$charge_num++;
				}
			}
			$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
			if ($total_charge_str == '') $total_charge_str = '미정';

			$work_data['charge_idx_str'] = $total_charge_str;
			$work_data['charge_num']     = $charge_num;

		// 분류
			$work_class = $work_data['work_class'];
			$class_arr = explode(',', $work_class);
			$total_class_arr = '';
			foreach ($class_arr as $class_k => $class_v)
			{
				$class_where = " and code.code_idx = '" . $class_v . "'";
				$class_data = code_work_class_data('view', $class_where);
				$total_class_arr .= ', ' . $class_data['code_name'];
			}
			$total_class_arr = substr($total_class_arr, 1, strlen($total_class_arr));
			if ($total_class_arr == '') $total_class_arr = '미지정';

			$work_data['work_class_str'] = $total_class_arr;

		// 업무분류
			if ($work_data['work_class'] == '') $work_class_value = '해당없음';
			else $work_class_value = $work_data['work_class_str'];
			$work_data['work_class_str'] = $work_class_value;

		// 업무승인자
			if ($work_data['apply_name'] == '') $apply_value = '해당없음';
			else $apply_value = $work_data['apply_name'];
			$work_data['apply_name'] = $apply_value;

			$work_data = work_list_data($work_data, $idx);

			return $work_data;
		}

		public function work_status_list() // 업무상태
		{
			$idx = $this->wi_idx;

			$work_where = " and wi.wi_idx = '" . $idx . "'";
			$work_view = work_info_data('view', $work_where);

			$code_comp = $work_view['comp_idx'];
			$code_part = $work_view['part_idx'];

			$sub_where = " and code.comp_idx = '" . $code_comp . "' and code.part_idx = '" . $code_part . "'";
			$sub_list = code_work_status_data('list', $sub_where, '', '', '');

			return $sub_list;
		}

		public function work_file_list() // 업무파일
		{
			$idx = $this->wi_idx;

			$sub_where = " and wf.wi_idx = '" . $idx . "'";
			$sub_list = work_file_data('list', $sub_where, '', '', '');

			return $sub_list;
		}

		public function work_file_images() // 업무파일 - 이미지만
		{
			$idx = $this->wi_idx;

			$sub_where = " and wf.wi_idx = '" . $idx . "' and wf.img_sname != ''
				and (wf.img_ext = 'jpg' or wf.img_ext = 'gif' or wf.img_ext = 'png')";
			$sub_list = work_file_data('list', $sub_where, '', '', '');

			$file_num = $sub_list['total_num'];

			return $file_num;
		}

		public function work_status_history() // 업무상태내역
		{
			$idx = $this->wi_idx;

			$sub_where = " and wsh.wi_idx = '" . $idx . "'";
			$sub_list = work_status_history_data('list', $sub_where, '', '', '');

			return $sub_list;
		}
	}
?>