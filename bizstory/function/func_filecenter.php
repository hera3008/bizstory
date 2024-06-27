<?
/*
	생성 : 2013.01.29
	수정 : 2013.05.28
	파일센터관련 함수
*/

//-------------------------------------- 풀더, 파일권한
	function filecenter_auth($fi_idx, $data, $chk_dir_view, $chk_dir_read, $chk_dir_write)
	{
		global $_SESSION, $sess_str, $set_preview_ext_str, $set_preview_ext_img2, $preview_agent_code, $preview_user_id;

		$chk_code_mem  = $_SESSION[$sess_str . '_mem_idx'];
		$chk_mem_level = $_SESSION[$sess_str . '_ubstory_level'];
        $chk_empowerment_yn = $_SESSION[$sess_str . '_empowerment_yn'];
		$chk_mem_level = 99;

		$set_type   = $data['set_type'];
		$dir_file   = $data['dir_file'];
		$dir_view   = $data['dir_view'];
		$dir_read   = $data['dir_read'];
		$dir_write  = $data['dir_write'];
        $dir_delete = $data['dir_delete'];
		$dir_depth  = $data['dir_depth'];
		$up_fi_idx  = $data['up_fi_idx'];
		$file_name  = $data['file_name'];
		$file_ext   = $data['file_ext'];
		$change_idx = $data['change_idx'];
		$history_cnt = $data['cnt'];

		$next_up    = $up_fi_idx . ',' . $fi_idx;
		
		//프로젝트 인지 확인
		$is_project_root = false;
		if ($data['file_path'] == '/Project' && ($dir_depth == 2 || $data['menu_code'] != '')) {
			$is_project_root = true;
		}

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if ($dir_file == 'folder') // 폴더일 경우
		{
			if ($chk_mem_level <= 21) // 관리자 모든 권한
			{
				$dir_view_auth  = 'Y'; $dir_read_auth  = 'Y'; $dir_write_auth = 'Y'; $dir_delete_auth = 'Y'; $dir_empowerment_auth = 'Y';
			}
			else
			{
				if ($dir_view  == '1') $dir_view_auth  = 'Y'; else $dir_view_auth  = 'N';
				if ($dir_read  == '1') $dir_read_auth  = 'Y'; else $dir_read_auth  = 'N';
				if ($dir_write == '1') $dir_write_auth = 'Y'; else $dir_write_auth = 'N';
                if ($dir_delete == '1') $dir_delete_auth = 'Y'; else $dir_delete_auth = 'N';
				if ($dir_depth == '1') $dir_view_auth  = 'Y';
                if ($chk_empowerment_yn == 'Y') $dir_empowerment_auth = 'Y'; else $dir_empowerment_auth = 'N';
			}
            /*
            echo "dir_view_auth : " . $dir_view_auth . "<BR>";
            echo "dir_read_auth : " . $dir_read_auth . "<BR>";
            echo "dir_write_auth : " . $dir_write_auth . "<BR>";
            echo "dir_delete_auth : " . $dir_delete_auth . "<BR>";
            echo "dir_depth : " . $dir_depth . "<BR>";
            echo "set_type : " . $set_type . "<BR>";
            echo "is_project_root : " . $is_project_root . "<BR>";
            echo "chk_empowerment_yn : " . $chk_empowerment_yn . "<BR>";
            */
			$check_modify = ''; $check_delete = ''; $btn_modify = ''; $btn_delete = ''; $btn_empowerment = '';
			$btn_folder1 = ''; $btn_folder2 = ''; $btn_file_copy = ''; $btn_file_move = ''; $btn_file = '';  $btn_sel_down = ''; $btn_sel_del = '';

			if ($dir_depth > 1)
			{
				//프로젝트인 경우 해당 프로젝트의 생성 권한이 있을 경우 수정, 삭제가 가능하도록 수정
				if ($set_type == 'nofix' || $is_project_root)
				{
					$check_modify = "popup_folder('" . $up_fi_idx . "', '" . $dir_depth . "', '" . $fi_idx . "')";
					$check_delete = "folder_delete('" . $fi_idx . "')";
					
					if ($is_project_root) $check_modify = ""; //일단 수정은 보류
				}
				$check_empowerment = "empowerment_folder('" . $fi_idx . "')";

				if ($dir_write_auth == 'Y' && $check_modify != '') // 쓰기일 경우
				{
					$btn_modify = '<a href="javascript:void(0);" onclick="' . $check_modify . '" class="btn_con_blue"><span>수정</span></a>';
				}
                
                if ($dir_delete_auth == 'Y' && $check_delete != '')
                {
                    $btn_delete = '<a href="javascript:void(0);" onclick="' . $check_delete . '" class="btn_con_red"><span>삭제</span></a>';
                }
				
                if ($dir_empowerment_auth == 'Y')
                {
                    $btn_empowerment = '<a href="javascript:void(0);" onclick="' . $check_empowerment . '"><img src="/bizstory/images/filecenter/icon_w.png" alt="권한위임" title="권한위임" /></a>';
                }
                
			// 하위값 확인
				$down_where = " and fi.up_fi_idx = '" . $next_up . "'";
				$down_data = filecenter_info_data('page', $down_where);
				$down_total = $down_data['total_num'];

			}

		// 현위치의 권한
			if ($set_type == 'nofix') // 수동생성 경우
			{
				if ($dir_write_auth == 'Y') // 쓰기일 경우
				{
					$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
					$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
					$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
					$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
				}
                
                if ($dir_delete_auth == 'Y')
                {
                    $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
                }
                
                
				if ($dir_read_auth == 'Y') // 읽기일 경우
				{
					$btn_sel_down = '<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
				}
			}
			else // 자동생성 경우
			{
				if ($dir_depth >= 2)
				{
					
					if ($dir_write_auth == 'Y') // 쓰기일 경우
					{
						if ($dir_depth >= 2 && $dir_depth < 5)
						{
							$btn_folder2 = '<a href="javascript:void(0);" onclick="popup_folder_auto(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big2"><span>폴더자동생성</span></a>';
						}
						$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
						$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
						$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
						$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
					}

                    if ($dir_delete_auth == 'Y')
                    {
                        $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
                    }

					if ($dir_read_auth == 'Y') // 읽기일 경우
					{
						$btn_sel_down = '<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
					}
				}

				//프로젝트인 경우 해당 프로젝트의 생성 권한이 있을 경우 수정, 삭제가 가능하도록 수정
				if (!$is_project_root) {
					if ($dir_depth < 4)
					{
						$btn_modify = ''; $btn_delete = '';
					}	
				}
				
			}

			$str['dir_view_auth']  = $dir_view_auth;
			$str['dir_read_auth']  = $dir_read_auth;
			$str['dir_write_auth'] = $dir_write_auth;
            $str['dir_delete_auth'] = $dir_delete_auth;
            $str['dir_empowerment_auth'] = $dir_empowerment_auth;

			$str['btn_folder1']   = $btn_folder1;
			$str['btn_folder2']   = $btn_folder2;
			$str['btn_file']      = $btn_file;
			$str['btn_file_copy'] = $btn_file_copy;
			$str['btn_file_move'] = $btn_file_move;
			$str['btn_sel_down']  = $btn_sel_down;
			$str['btn_sel_del']   = $btn_sel_del;
			
			$str['btn_modify']  = $btn_modify;
			$str['btn_delete']  = $btn_delete;
            $str['btn_empowerment'] = $btn_empowerment;
		}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		else
		{
			$check_preview = ''; $check_history = ''; $check_modify = ''; $btn_modify = ''; $btn_history = ''; $btn_preview = '';

			if ($chk_mem_level <= 21) // 관리자 모든 권한
			{
				$dir_view_auth  = 'Y'; $dir_read_auth  = 'Y'; $dir_write_auth = 'Y'; $dir_delete_auth = 'Y';
			}
			else
			{
				if ($chk_dir_view  == 'Y') $dir_view_auth  = 'Y'; else $dir_view_auth  = 'N';
				if ($chk_dir_read  == 'Y') $dir_read_auth  = 'Y'; else $dir_read_auth  = 'N';
				if ($chk_dir_write == 'Y') $dir_write_auth = 'Y'; else $dir_write_auth = 'N';
                if ($chk_dir_delete == 'Y') $dir_delete_auth = 'Y'; else $dir_delete_auth = 'Y';
			}
            /*
            echo "dir_view_auth : " . $dir_view_auth . "<BR>";
            echo "dir_read_auth : " . $dir_read_auth . "<BR>";
            echo "dir_write_auth : " . $dir_write_auth . "<BR>";
            echo "dir_delete_auth : " . $dir_delete_auth . "<BR>";
            echo "dir_depth : " . $dir_depth . "<BR>";
            echo "set_type : " . $set_type . "<BR>";
            echo "is_project_root : " . $is_project_root . "<BR>";
             * 
             */
		// 미리보기
			if ($file_ext != '')
			{
				if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0) // 파일변환한 경우
				{
					if ($change_idx != '0') $check_preview = 'file_preview_result(\'' . $preview_agent_code . '\', \'' . $preview_user_id . '\', \'' . $change_idx . '\', \'' . $file_name . '\')';
					else $check_preview = "alert('미리보기가 가능하지 않습니다.')";
				}
				else
				{
					if (strlen(stristr($set_preview_ext_img2, $file_ext)) > 0) // 이미지일 경우
					{
						$check_preview = "filecenter_preview_images('" . $fi_idx . "')";
					}
				}
			}

			$check_history = "file_history('" . $fi_idx . "')";
			$check_modify  = "file_modify('" . $fi_idx . "')";

			if ($dir_write_auth == 'Y' && $check_modify  != '') // 쓰기일 경우
			{
				$btn_modify  = '<a href="javascript:void(0);" onclick="' . $check_modify . '" class="btn_con_blue"><span>수정</span></a>';
			}
			
            if ($dir_read_auth == 'Y' && $check_history != '') // 읽기일 경우
			{
				$btn_history = '<a href="javascript:void(0);" onclick="' . $check_history . '"><img src="' . $local_dir . '/bizstory/images/filecenter/icon_history.png" width="16px" height="16px" alt="이력" title="이력" /></a>';				
			}
			
            if ($dir_view_auth == 'Y' && $check_preview != '') // 보기일 경우
			{
				$btn_preview = '<a href="javascript:void(0);" onclick="' . $check_preview . '"><img src="' . $local_dir . '/bizstory/images/filecenter/icon_preview.png" width="16px" height="16px" alt="미리보기" title="미리보기" /></a>';
			}
            
            if ($dir_delete_auth == 'Y')
            {
                $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
            }
			
			if ($history_cnt > 0) {
				$btn_history .= sprintf('<span class="vcenter_num"><em>%d</em></span>', $history_cnt);
			}

			$str['dir_view_auth']  = $dir_view_auth;
			$str['dir_read_auth']  = $dir_read_auth;
			$str['dir_write_auth'] = $dir_write_auth;
            $str['dir_delete_auth'] = $dir_delete_auth;

			$str['btn_modify']  = $btn_modify;
			$str['btn_delete']  = $btn_delete;
			$str['btn_history'] = $btn_history;
			$str['btn_preview'] = $btn_preview;
		}

		Return $str;
	}

//-------------------------------------- 폴더권한
	function filecenter_open_check($fi_idx, $code_comp, $code_part)
	{
		if ($fi_idx == '') {
			$query = "
			select t1.fi_idx as project_fi_idx
	                , t2.fi_idx as vdrive_fi_idx
		    from (select fi_idx from filecenter_info where del_yn='0' and comp_idx=" . $code_comp . " and dir_depth=1 and file_name='Project' order by part_idx asc) t1
			        join (select fi_idx from filecenter_info where del_yn='0' and comp_idx=" . $code_comp . " and part_idx=" . $code_part . " and dir_depth=1 and file_name='V-Drive' order by reg_date desc) t2
			";
			$chk_data = query_view($query);
			
			$result = array("chk_up"=>""
							, "project_fi_idx"=>$chk_data['project_fi_idx']
							, "vdrive_fi_idx"=>$chk_data['vdrive_fi_idx']);
			
		} else {
			$query = "
			select
					fi.*                
					, reg.mem_name as reg_name
					, pro.project_code
	                , t1.fi_idx as project_fi_idx
	                , t2.fi_idx as vdrive_fi_idx
				from
					filecenter_info fi
					left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
					left join member_info reg on reg.del_yn = 'N' and reg.mem_idx = fi.reg_id
			        join (select fi_idx from filecenter_info where del_yn='0' and comp_idx=" . $code_comp . " and dir_depth=1 and file_name='Project' order by part_idx asc) t1
			        join (select fi_idx from filecenter_info where del_yn='0' and comp_idx=" . $code_comp . " and part_idx=" . $code_part . " and dir_depth=1 and file_name='V-Drive' order by reg_date desc) t2
				where
					fi.del_yn = '0' and fi.fi_idx = " . $fi_idx . "
				order by
					fi.reg_date desc;
			";
			
			//$chk_where = " and fi.fi_idx = '" . $fi_idx . "'";
			//$chk_data = filecenter_info_data('view', $chk_where);
			
			$chk_data = query_view($query);
			
			if ($chk_data['up_fi_idx'] == '') $navi_up = $chk_data['fi_idx'];
			else $navi_up = $chk_data['up_fi_idx'] . ',' . $chk_data['fi_idx'];
	
			$navi_up_arr = explode(',', $navi_up);
			foreach ($navi_up_arr as $navi_up_k => $navi_up_v)
			{
				if ($navi_up_k == 0) $chk_up = $navi_up_v;
				else $chk_up .= '_' . $navi_up_v;
			}
			
			$result = array("chk_up"=>$chk_up
							, "project_fi_idx"=>$chk_data['project_fi_idx']
							, "vdrive_fi_idx"=>$chk_data['vdrive_fi_idx']);
		}
		
		
		Return $result;
	}

//-------------------------------------- 폴더권한
	function filecenter_auth_folder($fi_idx)
	{
		global $_SESSION, $sess_str;

		if ($fi_idx == '')
		{
			$dir_view_auth  = 'Y';
		}
		else
		{
			$chk_mem_level = $_SESSION[$sess_str . '_ubstory_level'];
			$chk_mem_level = 99;
			$chk_code_mem  = $_SESSION[$sess_str . '_mem_idx'];

			$chk_query = "
				select
					fi.*,
					fa.dir_view, fa.dir_read, fa.dir_write, fa.dir_delete
				from
					filecenter_info fi
					left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $chk_code_mem . "'
				where
					fi.del_yn = '0' and fi.fi_idx = '" . $fi_idx . "'
			";
			$chk_data = query_view($chk_query);

			$set_type   = $chk_data['set_type'];
			$dir_depth  = $chk_data['dir_depth'];
			$up_fi_idx  = $chk_data['up_fi_idx'];
			$file_name  = $chk_data['file_name'];
			$dir_view   = $chk_data['dir_view'];
			$dir_read   = $chk_data['dir_read'];
			$dir_write  = $chk_data['dir_write'];
            $dir_delete = $chk_data['dir_delete'];

			$next_depth = $dir_depth + 1;
			$next_up    = $up_fi_idx . ',' . $fi_idx;

			if ($chk_mem_level <= 21) // 관리자 모든 권한
			{
				$dir_view_auth  = 'Y'; $dir_read_auth  = 'Y'; $dir_write_auth = 'Y'; $dir_delete_auth = 'Y';
			}
			else
			{
				if ($dir_view  == '1') $dir_view_auth  = 'Y'; else $dir_view_auth  = 'N';
				if ($dir_read  == '1') $dir_read_auth  = 'Y'; else $dir_read_auth  = 'N';
				if ($dir_write == '1') $dir_write_auth = 'Y'; else $dir_write_auth = 'N';
				if ($dir_delete == '1') $dir_delete_auth = 'Y'; else $dir_delete_auth = 'N';
				if ($dir_depth == '1') $dir_view_auth  = 'Y';
			}

			$check_modify = ''; $check_delete = ''; $btn_modify = ''; $btn_delete = '';
			$btn_folder1 = ''; $btn_folder2 = ''; $btn_file_copy = ''; $btn_file_move = ''; $btn_file = '';  $btn_sel_down = ''; $btn_sel_del = '';

			if ($dir_depth > 1)
			{
				if ($set_type == 'nofix')
				{
					$check_modify  = "popup_folder('" . $up_fi_idx . "', '" . $dir_depth . "', '" . $fi_idx . "')";
					$check_delete  = "folder_delete('" . $fi_idx . "')";
				}

				if ($dir_write_auth == 'Y' && $check_modify != '') // 쓰기일 경우
				{
					$btn_modify = '<a href="javascript:void(0);" onclick="' . $check_modify . '" class="btn_con_blue"><span>수정</span></a>';					
				}
                
                if ($dir_delete_auth == 'Y' && $check_delete != '')
                {
                    $btn_delete = '<a href="javascript:void(0);" onclick="' . $check_delete . '" class="btn_con_red"><span>삭제</span></a>';
                }

			// 하위값 확인
				$down_where = " and fi.up_fi_idx = '" . $next_up . "'";
				$down_data = filecenter_info_data('page', $down_where);
				$down_total = $down_data['total_num'];

				if ($down_total > 0) $btn_delete = '';
			}

		// 현위치의 권한
			if ($set_type == 'nofix') // 수동생성 경우
			{
				if ($dir_write_auth == 'Y') // 쓰기일 경우
				{
					$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
					$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
					$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
					$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
				}
                
                if ($dir_delete_auth == 'Y')
                {
                    $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
                }
                
				if ($dir_read_auth == 'Y') // 읽기일 경우
				{
                    // $btn_sel_down = '<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
                    $btn_sel_down = '<a href="javascript:void(0);" class="btn_big_violet" onclick="select_download()"><span>다운로드</span></a>';
				}
			}
			else // 자동생성 경우
			{
				if ($dir_depth >= 2)
				{
					if ($dir_write_auth == 'Y') // 쓰기일 경우
					{
						if ($dir_depth >= 2 && $dir_depth < 5)
						{
							$btn_folder2 = '<a href="javascript:void(0);" onclick="popup_folder_auto(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big2"><span>폴더자동생성</span></a>';
						}
						$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
						$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
						$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
						$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
					}
					
					if ($dir_delete_auth == 'Y')
					{
					    $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
					}
					
					if ($dir_read_auth == 'Y') // 읽기일 경우
					{
						// $btn_sel_down = '<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
                        $btn_sel_down = '<a href="javascript:void(0);" class="btn_big_violet" onclick="select_download()"><span>다운로드</span></a>';
					}
				}

				if ($file_name == 'V-Drive' && $dir_depth == 1)
				{
					$btn_folder2 = '';
					if ($dir_write_auth == 'Y') // 쓰기일 경우
					{
						$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
						$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
						$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
						$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
					}
                    
                    if ($dir_delete_auth == 'Y')
                    {
                        $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
                    }
                    
					if ($dir_read_auth == 'Y') // 읽기일 경우
					{
						// $btn_sel_down = '<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
                        $btn_sel_down = '<a href="javascript:void(0);" class="btn_big_violet" onclick="select_download()"><span>다운로드</span></a>';
					}
				}

				if ($dir_depth < 3)
				{
					$btn_modify = ''; $btn_delete = '';
				}
			}
		}

		$str['dir_view_auth']  = $dir_view_auth;
		$str['dir_read_auth']  = $dir_read_auth;
		$str['dir_write_auth'] = $dir_write_auth;
        $str['dir_delete_auth'] = $dir_delete_auth;

		$str['btn_folder1']   = $btn_folder1;
		$str['btn_folder2']   = $btn_folder2;
		$str['btn_file']      = $btn_file;
		$str['btn_file_copy'] = $btn_file_copy;
		$str['btn_file_move'] = $btn_file_move;
		$str['btn_sel_down']  = $btn_sel_down;
		$str['btn_sel_del']   = $btn_sel_del;

		$str['btn_modify']  = $btn_modify;
		$str['btn_delete']  = $btn_delete;

		Return $str;
	}

//-------------------------------------- 파일권한
	function filecenter_auth_file($up_idx, $file_data)
	{
		global $_SESSION, $sess_str, $set_preview_ext_str, $preview_agent_code, $preview_user_id;

		$chk_mem_level = $_SESSION[$sess_str . '_ubstory_level'];
		$chk_mem_level = 99;
		$chk_code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	// 상위권한
		$chk_query = "
			select
				fa.dir_view, fa.dir_read, fa.dir_write, fa.dir_delete
			from
				filecenter_info fi
				left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $chk_code_mem . "'
			where
				fi.del_yn = '0' and fi.fi_idx = '" . $up_idx . "'
		";
		$chk_data = query_view($chk_query);

		$dir_view  = $chk_data['dir_view'];
		$dir_read  = $chk_data['dir_read'];
		$dir_write = $chk_data['dir_write'];
        $dir_delete = $chk_data['dir_delete'];

		if ($chk_mem_level <= 21) // 관리자 모든 권한
		{
			$dir_view_auth  = 'Y'; $dir_read_auth  = 'Y'; $dir_write_auth = 'Y'; $dir_delete_auth = 'Y';
		}
		else
		{
			if ($dir_view  == '1') $dir_view_auth  = 'Y'; else $dir_view_auth  = 'N';
			if ($dir_read  == '1') $dir_read_auth  = 'Y'; else $dir_read_auth  = 'N';
			if ($dir_write == '1') $dir_write_auth = 'Y'; else $dir_write_auth = 'N';
            if ($dir_delete == '1') $dir_delete_auth = 'Y'; else $dir_delete_auth = 'N';
		}

	// 선택값
		$sel_fi_idx     = $file_data['fi_idx'];
		$sel_dir_depth  = $file_data['dir_depth'];
		$sel_file_ext   = $file_data['file_ext'];
		$sel_file_name  = $file_data['file_name'];
		$sel_change_idx = $file_data['change_idx'];

		$check_preview = ''; $check_history = ''; $check_modify = '';
		$btn_modify = ''; $btn_history = ''; $btn_preview = '';
	// 미리보기
		if ($sel_file_ext != '')
		{
			if (strlen(stristr($set_preview_ext_str, $sel_file_ext)) > 0) // 파일변환한 경우
			{
				if ($sel_change_idx != '0')
				{
					$check_preview = 'file_preview_result(\'' . $preview_agent_code . '\', \'' . $preview_user_id . '\', \'' . $sel_change_idx . '\', \'' . $sel_file_name . '\')';
				}
				else
				{
					$check_preview = "alert('미리보기가 가능하지 않습니다.')";
				}
			}
			else
			{
				if ($sel_file_ext == 'gif' || $sel_file_ext == 'jpg' || $sel_file_ext == 'jpeg' || $sel_file_ext == 'png' || $sel_file_ext == 'bmp') // 이미지일 경우
				{
					$check_preview = "filecenter_preview_images('" . $sel_fi_idx . "')";
				}
			}
		}

		$check_history = "file_history('" . $sel_fi_idx . "')";
		$check_modify  = "file_modify('" . $sel_fi_idx . "')";

		if ($dir_write_auth == 'Y' && $check_modify  != '') // 쓰기일 경우
		{
			$btn_modify  = '<a href="javascript:void(0);" onclick="' . $check_modify . '" class="btn_con_blue"><span>수정</span></a>';
		}
		
		if ($dir_read_auth == 'Y' && $check_history != '') // 읽기일 경우
		{
			$btn_history = '<a href="javascript:void(0);" onclick="' . $check_history . '"><img src="' . $local_dir . '/bizstory/images/filecenter/icon_history.png" width="16px" height="16px" alt="이력" title="이력" /></a>';
		}
		
		if ($dir_view_auth == 'Y' && $check_preview != '') // 보기일 경우
		{
			$btn_preview = '<a href="javascript:void(0);" onclick="' . $check_preview . '"><img src="' . $local_dir . '/bizstory/images/filecenter/icon_preview.png" width="16px" height="16px" alt="미리보기" title="미리보기" /></a>';
		}

		$str['dir_view_auth']  = $dir_view_auth;
		$str['dir_read_auth']  = $dir_read_auth;
		$str['dir_write_auth'] = $dir_write_auth;

		$str['btn_modify']  = $btn_modify;
		$str['btn_delete']  = $btn_delete;
		$str['btn_history'] = $btn_history;
		$str['btn_preview'] = $btn_preview;

		Return $str;
	}

//-------------------------------------- Project 폴더목록
	function filecenter_folder_left_project($comp_idx, $mem_idx, $dir_depth, $up_idx, $pro_end = 'N', $ul_id_str = 'fsubmenu')
	{
		global $local_dir;

		$common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.dir_file = 'folder'";

		if ($pro_end == 'N') $common_where .= " and ifnull(pro.pro_status, 'PS01') != 'PS90'";

		$where = $common_where . "
			and fi.dir_depth = '" . $dir_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
			and (fa.dir_view = '1' or fa.dir_read = '1' or fa.dir_write = '1')";

		$file_query['query_string'] = "
			select
				fi.fi_idx, fi.up_fi_idx, fi.dir_depth, fi.file_name
				, fa.dir_view, fa.dir_read, fa.dir_write, fa.dir_delete
			from
				filecenter_info fi
				left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $mem_idx . "'
				left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
			where
				fi.del_yn = 'N'
				" . $where . "
			order by
				fi.file_path asc, fi.file_name asc
		";
		$file_query['page_size'] = '';
		$file_query['page_num'] = '';
        //echo $file_query['query_string'];
		$info_list = query_list($file_query);

		if ($info_list['total_num'] > 0)
		{
			$left_str = '
			<ul id="[ui_id_str]">';
			$sort = 1;
			foreach ($info_list as $info_k => $info_data)
			{
				if (is_array($info_data))
				{
					$fi_idx      = $info_data['fi_idx'];
					$file_name   = $info_data['file_name'];
					$file_depth  = $info_data['dir_depth'];
					$file_up_idx = $info_data['up_fi_idx'];
					$next_depth  = $file_depth + 1;
					$next_up     = $file_up_idx . ',' . $fi_idx;

					$chk_up_arr = explode(',', $file_up_idx);
					foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
					{
						if ($chk_up_k == 0) $chk_up = $chk_up_v;
						else $chk_up .= '_' . $chk_up_v;
					}
					$li_id_str = 'fleft_' . $chk_up . '_' . $sort;
					$left_str  = str_replace('[ui_id_str]', $ul_id_str . '_' . $chk_up, $left_str);

					$li_class = '';
					$icon_img = '';
					if ($file_depth == 2)
					{
						if ($sort == 1)
						{
							$li_class = ' class="frist"';
						}
						else if ($sort == $info_list['total_num'])
						{
							$li_class = ' class="end"';
						}

						if ($sort == 1 && $sort == $info_list['total_num'])
						{
							$li_class = ' class="frist end"';
						}
					}

					if ($ul_id_str == 'ffsubmenu')
					{
						if ($info_data['dir_write'] == '1') $up_type = 'Y';
						else $up_type = 'N';
						$btn_click = "open_dir_change('" . $fi_idx . "', '" . $next_depth . "', '" . $up_type . "');";
					}
					else
					{
						$btn_click = "file_list_view('" . $fi_idx . "', '" . $next_depth . "');";
					}

					$left_str .= '
				<li' . $li_class . ' id="' . $li_id_str . '">
					<a href="javascript:void(0);" onclick="' . $btn_click . '">' . $file_name . '</span></a>';

				// 하위메뉴
					$down_query = "
						select
							count(fi.fi_idx)
						from
							filecenter_info fi
							left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
						where
							fi.del_yn = 'N'
							" . $common_where . "
							and fi.dir_depth = '" . $next_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $next_up . ",%'
					";
					$down_menu = query_page($down_query);
					if ($down_menu['total_num'] > 0)
					{
						$left_str .= filecenter_folder_left_project($comp_idx, $mem_idx, $next_depth, $next_up, $pro_end, $ul_id_str);
					}

					$left_str .= '
				</li>';
					$sort++;
				}
			}
			$left_str .= '
			</ul>';
		}

		Return $left_str;
	}

//-------------------------------------- V-Drive 폴더목록
	function filecenter_folder_left_vdrive($comp_idx, $part_idx, $mem_idx, $dir_depth, $up_idx, $ul_id_str = 'fsubmenu')
	{
		global $local_dir;

		$common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.dir_file = 'folder'";
		$where = $common_where . "
			and fi.dir_depth = '" . $dir_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
			and (fa.dir_view = '1' or fa.dir_read = '1' or fa.dir_write = '1')";

		$file_query['query_string'] = "
			select
				fi.fi_idx, fi.up_fi_idx, fi.dir_depth, fi.file_name
				, fa.dir_view, fa.dir_read, fa.dir_write, fa.dir_delete
			from
				filecenter_info fi
				left join filecenter_auth fa on fa.del_yn = 'N' and fa.fi_idx = fi.fi_idx and fa.mem_idx = '" . $mem_idx . "'
			where
				fi.del_yn = 'N'
				" . $where . "
			order by
				fi.file_path asc, fi.file_name asc
		";
		$file_query['page_size'] = '';
		$file_query['page_num'] = '';
		$info_list = query_list($file_query);

		if ($info_list['total_num'] > 0)
		{
			$left_str = '
			<ul id="[ui_id_str]">';
			$sort = 1;
			foreach ($info_list as $info_k => $info_data)
			{
				if (is_array($info_data))
				{
					$fi_idx      = $info_data['fi_idx'];
					$file_name   = $info_data['file_name'];
					$file_depth  = $info_data['dir_depth'];
					$file_up_idx = $info_data['up_fi_idx'];
					$next_depth  = $file_depth + 1;
					$next_up     = $file_up_idx . ',' . $fi_idx;

					$chk_up_arr = explode(',', $file_up_idx);
					foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
					{
						if ($chk_up_k == 0) $chk_up = $chk_up_v;
						else $chk_up .= '_' . $chk_up_v;
					}
					$li_id_str = 'fleft_' . $chk_up . '_' . $sort;
					$left_str  = str_replace('[ui_id_str]', $ul_id_str . '_' . $chk_up, $left_str);

					$li_class = '';
					$icon_img = '';
					if ($file_depth == 2)
					{
						if ($sort == 1)
						{
							$li_class = ' class="frist"';
						}
						else if ($sort == $info_list['total_num'])
						{
							$li_class = ' class="end"';
						}

						if ($sort == 1 && $sort == $info_list['total_num'])
						{
							$li_class = ' class="frist end"';
						}
					}

					if ($ul_id_str == 'ffsubmenu')
					{
						if ($info_data['dir_write'] == '1') $up_type = 'Y';
						else $up_type = 'N';
						$btn_click = "open_dir_change('" . $fi_idx . "', '" . $next_depth . "', '" . $up_type . "');";
					}
					else
					{
						$btn_click = "file_list_view('" . $fi_idx . "', '" . $next_depth . "');";
					}

					$left_str .= '
						<li' . $li_class . ' id="' . $li_id_str . '">
					<a href="javascript:void(0);" onclick="' . $btn_click . '">' . $file_name . '</a>';

				// 하위메뉴
					$down_query = "
						select
							count(fi.fi_idx)
						from
							filecenter_info fi
						where
							fi.del_yn = 'N'
							" . $common_where . "
							and fi.dir_depth = '" . $next_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $next_up . ",%'
					";
					$down_menu = query_page($down_query);
					if ($down_menu['total_num'] > 0)
					{
						$left_str .= filecenter_folder_left_vdrive($comp_idx, $part_idx, $mem_idx, $next_depth, $next_up, $ul_id_str);
					}

					$left_str .= '
				</li>';
					$sort++;
				}
			}
			$left_str .= '
			</ul>';
		}

		Return $left_str;
	}

//-------------------------------------- 현위치
	function filecenter_folder_path($up_idx)
	{
		$chk_where = " and fi.fi_idx = '" . $up_idx . "'";
		$chk_data = filecenter_info_data('view', $chk_where);
		if ($chk_data['dir_depth'] == '1')
		{
			$file_path = $chk_data['fi_idx'];
		}
		else
		{
			$file_path = $chk_data['up_fi_idx'] . ',' . $chk_data['fi_idx'];
		}
        
		/*if ($up_idx != '')
		{
			$file_path_arr = explode(',', $file_path);
			$navi_path = '';
			$navi_num = 1;
			foreach ($file_path_arr as $k => $v)
			{
				$navi_where = " and fi.fi_idx = '" . $v . "'";
				$navi_data = filecenter_info_data('view', $navi_where);
				$navi_level = $navi_data['dir_depth'] + 1;
				$navi_path .= '/<a href="javascript:void(0)" onclick="file_list_move(\'' . $navi_data['fi_idx'] . '\', \'' . $navi_level . '\')">' . $navi_data['file_name'] . '</a>';

				$path_up_idx[$navi_num]   = $navi_data['fi_idx'];
				$path_up_level[$navi_num] = $navi_level;
				$path_up_name[$navi_num]  = $navi_data['file_sname'];
				$path_set_type[$navi_num] = $navi_data['set_type'];

				$navi_num++;
			}
		}
        print_r($path_up_name);
        */
        if ($up_idx != '')
        {
            $navi_path = '';
            $navi_num = 1;
            
            $navi_where = " and fi.fi_idx in (" . $file_path . ") ";
            $orderby = "fi.dir_depth asc";
            $navi_list_data = filecenter_info_data('list', $navi_where, $orderby);
            
            foreach ($navi_list_data as $k => $navi_data)
            {
                if (is_array($navi_data)) {
                $navi_level = $navi_data['dir_depth'] + 1;
                $navi_path .= '/<a href="javascript:void(0)" onclick="file_list_move(\'' . $navi_data['fi_idx'] . '\', \'' . $navi_level . '\')">' . $navi_data['file_name'] . '</a>';

                $path_up_idx[$navi_num]   = $navi_data['fi_idx'];
                $path_up_level[$navi_num] = $navi_level;
                $path_up_name[$navi_num]  = $navi_data['file_sname'];
                $path_set_type[$navi_num] = $navi_data['set_type'];

                $navi_num++;
                }
            }
        }
        //print_r($path_up_name);

		$str['navi_path']     = $navi_path;
		$str['set_type']      = $chk_data['set_type'];
		$str['path_up_idx']   = $path_up_idx;
		$str['path_up_level'] = $path_up_level;
		$str['path_up_name']  = $path_up_name;
		$str['path_set_type'] = $path_set_type;

		Return $str;
	}

//-------------------------------------- 파일권한
	function filecenter_auth_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "fa.reg_date desc";
		if ($del_type == 1) $where = "fa.del_yn = '0'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(fa.fa_idx)
			from
				filecenter_auth fa
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				fa.*
			from
				filecenter_auth fa
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 파일이력
	function filecenter_history_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "fh.reg_date desc";
		if ($del_type == 1) $where = "fh.del_yn = '0'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(fh.fh_idx)
			from
				filecenter_history fh
				left join filecenter_info fi on fi.del_yn = '0' and fi.fi_idx = fh.fi_idx
				left join member_info mem on mem.mem_idx = fh.reg_id
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				fh.*
				, mem.del_yn as mem_del_yn, mem.mem_name as reg_name
				, fi.file_name, fi.file_ext, fi.file_path, fi.file_rpath
			from
				filecenter_history fh
				left join filecenter_info fi on fi.del_yn = '0' and fi.fi_idx = fh.fi_idx
				left join member_info mem on mem.mem_idx = fh.reg_id
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}
	
//-------------------------------------- 파일센터정보
	function filecenter_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1, $join = '')
	{
		if ($orderby == '') $orderby = "fi.reg_date desc";
		if ($del_type == 1) $where = "fi.del_yn = '0'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(fi.fi_idx)
			from
				filecenter_info fi
				left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
				left join member_info reg on reg.del_yn = 'N' and reg.mem_idx = fi.reg_id 
			     " . $join . "			
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				fi.*
				, reg.mem_name as reg_name
				, pro.project_code
			from
				filecenter_info fi
				left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
				left join member_info reg on reg.del_yn = 'N' and reg.mem_idx = fi.reg_id
				" . $join . "
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}
    
//-------------------------------------- 파일센터정보
    function filecenter_level_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
    {
        if ($orderby == '') $orderby = "fi.reg_date desc";
        if ($del_type == 1) $where = "fi.del_yn = '0'" . $where;
        else $where = "1" . $where;

        $query_page = "
            select
                count(fi.fi_idx)
            from
                filecenter_info fi
                left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
                left join member_info reg on reg.del_yn = 'N' and reg.mem_idx = fi.reg_id
            where
                " . $where . "
        ";
        //echo "<pre>" . $query_page . "</pre><br />";
        $query_string = "
            select
                fi.*
                , reg.mem_name as reg_name
                , pro.project_code
                , pro.pro_status
                , (select count(*) from filecenter_info where del_yn='0' and comp_idx=fi.comp_idx and part_idx=fi.part_idx and dir_file='folder' and fi.file_name != '' and dir_depth=(fi.dir_depth+1)
                  and concat(',', up_fi_idx, ',') like concat('%,', fi.fi_idx, ',%')
                  ) cnt
            from
                filecenter_info fi
                left join project_info pro on pro.del_yn = 'N' and pro.pro_idx = fi.pro_idx
                left join member_info reg on reg.del_yn = 'N' and reg.mem_idx = fi.reg_id
            where
                " . $where . "
            order by
                " . $orderby . "
        ";
        //echo "<pre>" . $query_string . "</pre><br />";

        if ($query_type == 'view') $data_info = query_view($query_string);
        else if ($query_type == 'page') $data_info = query_page($query_page);
        else
        {
            $data_sql['query_page']   = $query_page;
            $data_sql['query_string'] = $query_string;
            $data_sql['page_size']    = $page_size;
            $data_sql['page_num']     = $page_num;

            $data_info = query_list($data_sql);
        }

        Return $data_info;
    }
//-------------------------------------- 파일타입
	function filecenter_code_type_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = '0'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				filecenter_code_type code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
				, part.part_name
			from
				filecenter_code_type code
				left join company_part part on part.del_yn = 'N' and part.part_idx = code.part_idx
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}

//-------------------------------------- 파일타입별 권한설정
	function filecenter_code_type_auth_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "codea.reg_date desc";
		if ($del_type == 1) $where = "codea.del_yn = '0'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(codea.codea_idx)
			from
				filecenter_code_type_auth codea
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				codea.*
			from
				filecenter_code_type_auth codea
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";

		if ($query_type == 'view') $data_info = query_view($query_string);
		else if ($query_type == 'page') $data_info = query_page($query_page);
		else
		{
			$data_sql['query_page']   = $query_page;
			$data_sql['query_string'] = $query_string;
			$data_sql['page_size']    = $page_size;
			$data_sql['page_num']     = $page_num;

			$data_info = query_list($data_sql);
		}

		Return $data_info;
	}
?>
<?

//-------------------------------------- 파일업로드 위치변경목록
	function filecenter_folder_change($chk_up_idx, $comp_idx, $part_idx, $mem_idx, $part_yn, $dir_depth = 1, $up_idx = '')
	{
		global $local_dir;

		$common_where = " and fi.comp_idx = '" . $comp_idx . "' and fi.part_idx = '" . $part_idx . "' and fi.dir_file = 'folder' and fi.file_name != ''";

		$where = $common_where . " and fi.dir_depth = '" . $dir_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'";
		$order = "fi.file_path asc, fi.file_name asc";
		$info_list = filecenter_info_data('list', $where, $order, '', '');

		if ($dir_depth == 1)
		{
			$left_str = '
			<ul id="ffsub_navi">';
		}
		else
		{
			$left_str .= '
			<ul id="[ui_id_str]">';
		}

		$sort = 1;
		foreach ($info_list as $info_k => $info_data)
		{
			if (is_array($info_data))
			{
				$file_name  = $info_data['file_name'];
				$next_depth = $info_data['dir_depth'] + 1;
				if ($info_data['up_fi_idx'] == '')
				{
					$next_up = $info_data['fi_idx'];
				}
				else
				{
					$next_up = $info_data['up_fi_idx'] . ',' . $info_data['fi_idx'];
				}

			// 하위메뉴
				$down_where = $common_where . " and fi.dir_depth = '" . $next_depth . "' and concat(',', fi.up_fi_idx, ',') like '%," . $next_up . ",%'";
				$down_menu = filecenter_info_data('view', $down_where);

				$chk_up_idx = $info_data['up_fi_idx'];
				$chk_up_arr = explode(',', $chk_up_idx);
				foreach ($chk_up_arr as $chk_up_k => $chk_up_v)
				{
					if ($chk_up_k == 0)
					{
						$chk_up = $chk_up_v;
					}
					else
					{
						$chk_up .= '_' . $chk_up_v;
					}
				}
				if ($chk_up == '')
				{
					$li_id_str  = 'fleft_' . $sort;
				}
				else
				{
					$li_id_str  = 'fleft_' . $chk_up . '_' . $sort;
				}
				$left_str = str_replace('[ui_id_str]', 'ffsubmenu_' . $chk_up, $left_str);

				$li_class = '';
				$icon_img = '';
				if ($info_data['dir_depth'] == 1)
				{
					if ($file_name == 'Project') $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
					else $icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
				}
				else if ($info_data['dir_depth'] == 2)
				{
					if ($sort == 1)
					{
						$li_class = ' class="frist"';
					}
					else if ($sort == $info_list['total_num'])
					{
						$li_class = ' class="end"';
					}

					if ($sort == 1 && $sort == $info_list['total_num'])
					{
						$li_class = ' class="frist end"';
					}
				}

				$dir_auth = filecenter_folder_auth($info_data['fi_idx']); // 권한 - 선택한 폴더에 대한 권한

				if ($dir_auth['dir_view_auth'] == 'Y' || $dir_auth['dir_read_auth'] == 'Y' || $dir_auth['dir_write_auth'] == 'Y')
				{
					$left_str .= '
					<li' . $li_class . ' id="' . $li_id_str . '">';

					if ($dir_auth['dir_write_auth'] == 'Y')
					{
						$up_type = 'Y';
					}
					else
					{
						$up_type = 'N';
					}
					$left_str .= '
						<a href="javascript:void(0);" onclick="open_dir_change(\'' . $info_data['fi_idx'] . '\', \'' . $next_depth . '\', \'' . $up_type . '\')">' . $icon_img . $file_name . '</a>';

					if ($down_menu['total_num'] > 0)
					{
						$left_str .= filecenter_folder_change($chk_up_idx, $comp_idx, $part_idx, $mem_idx, $part_yn, $next_depth, $next_up);
					}

					$left_str .= '
					</li>';
					$sort++;
				}
			}
		}
		$left_str .= '
			</ul>
		';

		Return $left_str;
	}

//-------------------------------------- 폴더, 파일권한
	function filecenter_folder_auth($fi_idx, $fi_idx2 = '')
	{
		global $_SESSION, $sess_str, $set_preview_ext_str, $preview_agent_code, $preview_user_id;

	// 브라우저 정보
		$mybrowser     = getenv('HTTP_USER_AGENT');
		$mybrowser_arr = explode(';', $mybrowser);
		$mybrowser_val = trim($mybrowser_arr[1]);
		$mybrowser_val_arr = explode(' ', $mybrowser_val);
		$mybrowser_val_val = trim($mybrowser_val_arr[0]);

		if ($fi_idx == '')
		{
			$btn_folder1   = '';
			$btn_folder2   = '';
			$btn_file      = '';
			$btn_file_copy = '';
			$btn_file_move = '';
			$btn_sel_down  = '';
			$btn_sel_del   = '';

			$btn_modify  = '';
			$btn_delete  = '';
			$btn_history = '';
			$btn_preview = '';

			$dir_view_auth  = 'Y';
			$dir_read_auth  = '';
			$dir_write_auth = '';
            $dir_delete_auth = '';
		}
		else
		{
		// 소속 폴더값이거나 현 폴더값
			$chk_where = " and fi.fi_idx = '" . $fi_idx . "'";
			$chk_data = filecenter_info_data('view', $chk_where);

			$set_type   = $chk_data['set_type'];
			$dir_file   = $chk_data['dir_file'];
			$dir_depth  = $chk_data['dir_depth'];
			$next_depth = $chk_data['dir_depth'] + 1;
			$up_fi_idx  = $chk_data['up_fi_idx'];
			$next_up    = $chk_data['up_fi_idx'] . ',' . $chk_data['fi_idx'];
			$file_name  = $chk_data['file_name'];
			$file_ext   = $chk_data['file_ext'];
			$change_idx = $chk_data['change_idx'];

			$code_level = $_SESSION[$sess_str . '_ubstory_level'];
			$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
			$code_level = 99;
			if ($code_level <= 21) // 관리자 모든 권한
			{
				$dir_view_auth  = 'Y';
				$dir_read_auth  = 'Y';
				$dir_write_auth = 'Y';
                $dir_delete_auth = 'Y';
			}
			else
			{
				$auth_where = " and fa.fi_idx = '" . $fi_idx . "' and fa.mem_idx = '" . $code_mem . "'";
				$auth_data = filecenter_auth_data('view', $auth_where);
				if ($auth_data['total_num'] == 0) // 권한이 없을 경우
				{
					$dir_view_auth  = 'N';
					$dir_read_auth  = 'N';
					$dir_write_auth = 'N';
                    $dir_delete_auth = 'Y';
				}
				else // 현권한
				{
					if ($auth_data['dir_view'] == '1') $dir_view_auth = 'Y'; else $dir_view_auth = 'N';
					if ($auth_data['dir_read'] == '1') $dir_read_auth = 'Y'; else $dir_read_auth = 'N';
					if ($auth_data['dir_write'] == '1') $dir_write_auth = 'Y'; else $dir_write_auth = 'N';
                    if ($auth_data['dir_delete'] == '1') $dir_delete_auth = 'Y'; else $dir_delete_auth = 'N';
				}
				if ($dir_depth == 1)
				{
					$dir_view_auth  = 'Y';
				}
			}

			$btn_folder1 = ''; $btn_folder2 = ''; $btn_file = ''; $btn_file_copy = ''; $btn_file_move = ''; $btn_sel_down = ''; $btn_sel_del = '';
			$btn_modify = ''; $btn_delete = ''; $check_modify = ''; $check_delete = '';
			$btn_history = ''; $btn_preview = ''; $check_preview = ''; $check_history = '';

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 폴더, 파일관련 권한
		// 현 파일값
			if ($fi_idx2 != '')
			{
				$chk_where2 = " and fi.fi_idx = '" . $fi_idx2 . "'";
				$chk_data2 = filecenter_info_data('view', $chk_where2);

				$dir_file   = $chk_data2['dir_file'];
				$file_ext   = $chk_data2['file_ext'];
				$file_name  = $chk_data2['file_name'];
				$change_idx = $chk_data2['change_idx'];
				$file_dir_depth = $chk_data2['dir_depth'];
			}

			if ($dir_depth > 1 || $file_dir_depth > 1)
			{
				if ($dir_file == 'file') // 파일일 경우
				{
				// 미리보기
					if (strlen(stristr($set_preview_ext_str, $file_ext)) > 0) // 파일변환한 경우
					{
						if ($change_idx != '0')
						{
							$check_preview = 'file_preview_result(\'' . $preview_agent_code . '\', \'' . $preview_user_id . '\', \'' . $change_idx . '\', \'' . $file_name . '\')';
						}
						else
						{
							$check_preview = "alert('미리보기가 가능하지 않습니다.')";
						}
					}
					else
					{
						if ($file_ext == 'gif' || $file_ext == 'jpg' || $file_ext == 'jpeg' || $file_ext == 'png' || $file_ext == 'bmp') // 이미지일 경우
						{
							$check_preview = "filecenter_preview_images('" . $fi_idx2 . "')";
						}
					}

					$check_history = "file_history('" . $fi_idx2 . "')";
					$check_modify  = "file_modify('" . $fi_idx2 . "')";
				}
				else
				{
					if ($set_type == 'nofix')
					{
						$check_modify  = "popup_folder('" . $up_fi_idx . "', '" . $dir_depth . "', '" . $fi_idx . "')";
						$check_delete  = "folder_delete('" . $fi_idx . "')";
					}
				}

				if ($dir_write_auth == 'Y' && $check_modify != '') // 쓰기일 경우
				{
					$btn_modify = '<a href="javascript:void(0);" onclick="' . $check_modify . '" class="btn_con_blue"><span>수정</span></a>';
				}
				if ($dir_delete_auth == 'Y' && $check_delete != '') // 삭제일 경우
                {
                    $btn_delete = '<a href="javascript:void(0);" onclick="' . $check_delete . '" class="btn_con_red"><span>삭제</span></a>';
                }
				if ($dir_read_auth == 'Y' && $check_history != '') // 읽기일 경우
				{
					$btn_history = '<a href="javascript:void(0);" onclick="' . $check_history . '" class="btn_con_violet"><span>이력</span></a>';
				}
				if ($dir_view_auth == 'Y' && $check_preview != '') // 보기일 경우
				{
					$btn_preview = '<a href="javascript:void(0);" onclick="' . $check_preview . '" class="btn_con_green"><span>미리보기</span></a>';
				}

				if ($fi_idx2 == '') // 폴더일 경우
				{
				// 하위값 확인
					$down_where = " and fi.up_fi_idx = '" . $next_up . "'";
					$down_data = filecenter_info_data('page', $down_where);
					$down_total = $down_data['total_num'];

					if ($down_total > 0) $btn_delete = '';
				}
			}

		//////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 현위치의 권한
			if ($set_type == 'nofix') // 수동생성 경우
			{
				if ($dir_write_auth == 'Y') // 쓰기일 경우
				{
					$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
					$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
					$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
					$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
				}
                
                if ($dir_delete_auth == 'Y')
                {
                    $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
                } 
				
				if ($dir_read_auth == 'Y') // 읽기일 경우
				{
					$btn_sel_down = '
						<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
						//<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_violet"><span>선택다운로드</span></a>
				}
			}
			else // 자동생성 경우
			{
				if ($dir_depth >= 2)
				{
					if ($dir_write_auth == 'Y') // 쓰기일 경우
					{
						if ($dir_depth >= 2 && $dir_depth < 5)
						{
							$btn_folder2 = '<a href="javascript:void(0);" onclick="popup_folder_auto(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big2"><span>폴더자동생성</span></a>';
						}
						$btn_folder1   = '<a href="javascript:void(0);" onclick="popup_folder(\'' . $fi_idx . '\', \'' . $next_depth . '\', \'\')" class="btn_big"><span>폴더생성</span></a>';
						$btn_file_copy = '<a href="javascript:void(0);" onclick="file_copy_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일복사</span></a>';
						$btn_file_move = '<a href="javascript:void(0);" onclick="file_move_popup(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big"><span>파일이동</span></a>';
						$btn_file      = '<a href="javascript:void(0);" onclick="popup_file(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_green"><span>파일업로드</span></a>';
					}

                    if ($dir_delete_auth == 'Y')
                    {
                        $btn_sel_del   = '<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_red"><span>선택삭제</span></a>';
                    }
					
					if ($dir_read_auth == 'Y') // 읽기일 경우
					{
						$btn_sel_down = '
							<a href="javascript:void(0);" class="btn_file_down"><span id="filecenter_download"></span></a>';
							//<a href="javascript:void(0);" onclick="select_delete(\'' . $fi_idx . '\', \'' . $next_depth . '\')" class="btn_big_violet"><span>선택다운로드</span></a>
					}
				}

				if ($dir_depth < 3)
				{
					$btn_modify = '';
					$btn_delete = '';
				}
			}
		}

		$str['dir_view_auth']  = $dir_view_auth;
		$str['dir_read_auth']  = $dir_read_auth;
		$str['dir_write_auth'] = $dir_write_auth;
        $str['dir_delete_auth'] = $dir_delete_auth;

		$str['btn_folder1']   = $btn_folder1;
		$str['btn_folder2']   = $btn_folder2;
		$str['btn_file']      = $btn_file;
		$str['btn_file_copy'] = $btn_file_copy;
		$str['btn_file_move'] = $btn_file_move;
		$str['btn_sel_down']  = $btn_sel_down;
		$str['btn_sel_del']   = $btn_sel_del;

		$str['btn_modify']  = $btn_modify;
		$str['btn_delete']  = $btn_delete;
		$str['btn_history'] = $btn_history;
		$str['btn_preview'] = $btn_preview;

		Return $str;
	}
?>