<?

//-------------------------------------- 상담분류
	function expert_code_consult_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				expert_code_consult_class code
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
			from
				expert_code_consult_class code
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

//-------------------------------------- 알림분류
	function expert_code_notify_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				expert_code_notify_class code
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
			from
				expert_code_notify_class code
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

//-------------------------------------- 전문가분류
	function expert_code_expert_class_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "code.sort asc";
		if ($del_type == 1) $where = "code.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(code.code_idx)
			from
				expert_code_expert_class code
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				code.*
			from
				expert_code_expert_class code
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

//-------------------------------------- 설정된 항목 표현
	function field_set_form($field_data, $value_data)
	{
		$set_ecsf_idx      = $field_data['ecsf_idx'];
		$set_field_name    = $field_data['field_name'];
		$set_field_type    = $field_data['field_type'];
		$set_field_size    = $field_data['field_size'];
		$set_field_subject = $field_data['field_subject'];
		$set_essential_yn  = $field_data['essential_yn'];

		if ($set_field_size != "" && $set_field_size != "0")
		{
			$set_field_size = ' size="' . $set_field_size . '"';
		}

	// 값이 없을 경우
		if ($value_data == "")
		{
			$chk_where = " and ecsfd.ecsf_idx = '" . $set_ecsf_idx . "' and ecsfd.view_yn = 'Y' and ecsfd.default_yn = 'Y'";
			$chk_data = expert_client_search_field_data_data("view", $chk_where);
			$value_data = $chk_data["ecsfd_idx"];
		}

	// 값의 내용
		$chk_where = " and ecsfd.ecsfd_idx = '" . $value_data . "'";
		$chk_data = expert_client_search_field_data_data("view", $chk_where);
		$value_data_view = $chk_data["code_name"];

	// 구성항목
		$chk_where = " and ecsfd.ecsf_idx = '" . $set_ecsf_idx . "' and ecsfd.view_yn = 'Y'";
		$chk_list = expert_client_search_field_data_data("list", $chk_where, "", "", "");

		$field_form = "";
		$etc_script = "";
		$data_i = 1;

		switch($set_field_type)
		{
		// 라디오버튼
			case "radio":

				$field_label      = $set_field_subject;
				$field_value      = $value_data;
				$field_value_view = $value_data_view;

				foreach ($chk_list as $k => $chk_data)
				{
					if (is_array($chk_data))
					{
						$field_form .= '
							<label for="post_' . $set_field_name . '">
								<input type="radio" name="param[' . $set_field_name . ']" id="post_' . $set_field_name . '_' . $data_i . '" value="' . $chk_data['ecsfd_idx'] . '"' . checked($value_data, $chk_data['ecsfd_idx']) . ' />' . $chk_data['code_name'] . '
							</label>
						';
						$etc_script .= "
							if (f.post_" . $set_field_name . "_" . $data_i . ".checked == true)
							{
								chk_num = chk_num + 1;
							}
						";
						$data_i++;
					}
				}

				if ($set_essential_yn == "Y")
				{
					$field_script = "
						var chk_num = 0;
					" . $etc_script . "
						if (chk_num == 0)
						{
							error_msg += '" . $set_field_subject . "을(를) 선택하세요.<br />';
						}
					";
				}

				break;

		// 체크박스
			case "checkbox":

				$field_label      = $set_field_subject;
				$field_value      = $value_data;
				$field_value_view = $value_data_view;

				$arr_field = explode(',', $value_data);
				foreach ($chk_list as $k => $chk_data)
				{
					if (is_array($chk_data))
					{
						$chk_value = in_array($chk_data['ecsfd_idx'], $arr_field);
						if ($chk_value === True)
						{
							$checked = ' checked="checked"';
						}
						else $checked = '';

						$field_form .= '
							<label for="post_' . $set_field_name . '">
								<input type="checkbox" name="' . $set_field_name . '[]" id="post_' . $set_field_name . '_' . $data_i . '" value="' . $chk_data['ecsfd_idx'] . '"' . $checked . ' />' . $chk_data['code_name'] . '
							</label>';
						$etc_script .= "
							if (f.post_" . $set_field_name . "_" . $data_i . ".checked == true)
							{
								chk_num = chk_num + 1;
							}
						";
						$data_i++;
					}
				}

				if ($set_essential_yn == "Y")
				{
					$field_script = "
						var chk_num = 0;
					" . $etc_script . "
						if (chk_num == 0)
						{
							error_msg += \"" . $set_field_subject . "을(를) 선택하세요.<br />\";
						}
					";
				}

				break;

		// 셀렉트박스
			case "select":

				$field_id = 'post_' . $set_field_name;

				$field_label      = '<label for="' . $field_id . '">' . $set_field_subject . '</label>';
				$field_value      = $value_data;
				$field_value_view = $value_data_view;

				$field_form = '
					<select name="param[' . $set_field_name . ']" id="' . $field_id . '" title="' . $set_field_subject . ' 선택하세요.">
						<option value="">' . $set_field_subject . ' 선택</option>';
				foreach ($chk_list as $k => $chk_data)
				{
					if (is_array($chk_data))
					{
						$field_form .= '
						<option value="' . $chk_data['ecsfd_idx'] . '"' . selected($value_data, $chk_data['ecsfd_idx']) . '>' . $chk_data['code_name'] . '</option>';
					}
				}
				$field_form .= '
					</select>';

				if ($set_essential_yn == "Y")
				{
					$field_script = "
						chk_value = $('#" . $field_id . "').val(); // " . $set_field_subject . "
						chk_title = $('#" . $field_id . "').attr('title');
						chk_msg = check_input_value(chk_value);
						if (chk_msg == 'No')
						{
							chk_total = chk_total + chk_title + '<br />';
							action_num++;
						}
					";
				}

				break;

			default :
		}

		$data_str["field_label"]      = $field_label;
		$data_str["field_form"]       = $field_form;
		$data_str["field_value"]      = $field_value;
		$data_str["field_value_view"] = $field_value_view;
		$data_str["field_script"]     = $field_script;

		Return $data_str;
	}

//-------------------------------------- 거래처정보
	function expert_client_info_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ecs_view_yn asc, comp.comp_idx asc, ci.client_name asc";
		if ($del_type == 1) $where = "comp.del_yn = 'N' and ci.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ci.ci_idx)
			from
				client_info ci
				left join expert_client_search ecs on ecs.del_yn = 'N' and ecs.ci_idx = ci.ci_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = ci.comp_idx
				left join company_setting cs on cs.del_yn = 'N' and cs.comp_idx = ci.comp_idx
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ci.*
				, comp.comp_name
				, ecs.view_yn, ecs.ecs_idx
				, if(ecs.ecs_idx != '', 'Y', 'N') as ecs_view_yn
			from
				client_info ci
				left join expert_client_search ecs on ecs.del_yn = 'N' and ecs.ci_idx = ci.ci_idx
				left join company_info comp on comp.del_yn = 'N' and comp.comp_idx = ci.comp_idx
				left join company_setting cs on cs.del_yn = 'N' and cs.comp_idx = ci.comp_idx
			where
				" . $where . "
				and cs.expert_yn = 'Y'
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

//-------------------------------------- 거래처검색
	function expert_client_search_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ecs.reg_date desc";
		if ($del_type == 1) $where = "ecs.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ecs.ecs_idx)
			from
				expert_client_search ecs
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ecs.*
			from
				expert_client_search ecs
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

//-------------------------------------- 거래처검색필드 구성항목
	function expert_client_search_field_data_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ecsfd.sort asc";
		if ($del_type == 1) $where = "ecsfd.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ecsfd.ecsfd_idx)
			from
				expert_client_search_field_data ecsfd
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ecsfd.*
			from
				expert_client_search_field_data ecsfd
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

//-------------------------------------- 거래처검색필드
	function expert_client_search_field_data($query_type, $where = '', $orderby = '', $page_num = '', $page_size = '', $del_type = 1)
	{
		if ($orderby == '') $orderby = "ecsf.sort asc";
		if ($del_type == 1) $where = "ecsf.del_yn = 'N'" . $where;
		else $where = "1" . $where;

		$query_page = "
			select
				count(ecsf.ecsf_idx)
			from
				expert_client_search_field ecsf
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ecsf.*
			from
				expert_client_search_field ecsf
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