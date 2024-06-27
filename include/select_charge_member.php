<?
/*
	생성 : 2012.04.23
	수정 : 2013.04.22
	위치 : 업무관리 > 나의업무 > 업무 - 담당자선택
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp        = $_SESSION[$sess_str . '_comp_idx'];
	$code_part        = search_company_part($code_part);
	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];

	$where = " and wi.wi_idx = '" . $wi_idx . "'";
	$work_data = work_info_data('view', $where);

	$charge_idx_arr = explode(',', $charge_idx);
	$total_member = 0;
?>
<div class="charge_view_box">
	<ul>
		<li class="part_name">ㆍ담당자선택</li>
	</ul>
<?
// 지사별
	$sub_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
	if ($set_part_work_yn == 'Y')
	{ }
	else
	{
		if ($set_part_yn == 'N') $sub_where .= " and part.part_idx = '" . $code_part . "'";
	}
	$part_list = company_part_data('list', $sub_where, '', '', '');
	foreach ($part_list as $part_k => $part_data)
	{
		if (is_array($part_data))
		{
			$part_idx     = $part_data['part_idx'];
			$part_name    = $part_data['part_name'];
			$part_sort    = $part_data['sort'];
			$part_color   = $set_color_list2[$part_sort];
			$part_check   = 'partidx' . $part_idx;
			$part_div_id  = 'part_charge_view_' . $part_idx;
			$part_span_id = 'part_charge_btn_' . $part_idx;

		// 승인이 아닌 경우만 전체가 가능
			if ($work_type != 'WT03') $part_disabled = '';
			else $part_disabled = ' disabled="disabled"';

			echo '
				<ul>
					<li class="first">
						<label for="' . $part_check . '">
							<input type="checkbox" class="type_checkbox" title="' . $part_name . '" name="' . $part_check . '" id="' . $part_check . '" onclick="check_all2(\'' . $part_check . '\', this, \'1\'); popup_member_select();"' . $part_disabled . ' />
							<span style="color:' . $part_color . '">' . $part_name . '</span>
						</label>
						<span onclick="part_charge_chk(\'' . $part_idx . '\')" class="pointer" id="' . $part_span_id . '"><img src="../../common/images/icon/icon_p.png" alt="펼치기" /></span>
					</li>
				</ul>
				<div class="none" id="' . $part_div_id . '">';

		// 그룹별
			$group_where = " and csg.part_idx = '" . $part_idx . "'";
			$group_list = company_staff_group_data('list', $group_where, '', '', '');
			foreach ($group_list as $group_k => $group_data)
			{
				if (is_array($group_data))
				{
					$group_idx   = $group_data['csg_idx'];
					$group_name  = $group_data['group_name'];
					$group_check = $part_check . '-' . $group_idx;

				// 승인이 아닌 경우만 전체가 가능
					if ($work_type != 'WT03') $part_disabled = '';
					else $part_disabled = ' disabled="disabled"';

				// 지사별 직원
					$sub_where2 = " and mem.part_idx = '" . $part_idx . "' and mem.csg_idx = '" . $group_idx . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
					$sub_order2 = "cpd.sort asc, mem.mem_name asc";
					$mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
					if ($mem_list['total_num'] > 0)
					{
						echo '
					<ul>
						<li class="second">
							<label for="' . $group_check . '">
								<input type="checkbox" class="type_checkbox" title="' .$group_name . '" name="' . $group_check . '" id="' . $group_check . '" onclick="check_all2(\'' . $group_check . '\', this, \'0\'); popup_member_select();" /> <span>' . $group_name . '</span>
							</label>
							<ul>';

						foreach ($mem_list as $mem_k => $mem_data)
						{
							if (is_array($mem_data))
							{
								$mem_idx   = $mem_data['mem_idx'];
								$mem_name  = $mem_data['mem_name'];
								$mem_check = $group_check . '_' . $mem_idx;

								$checked = '';
								if (is_array($charge_idx_arr))
								{
									foreach ($charge_idx_arr as $charge_k => $charge_v)
									{
										if ($mem_idx == $charge_v)
										{
											$checked = ' checked="checked"';
											$part_charge_on[$part_idx] = '
												$("#' . $part_div_id . '").css({"display": "block"});
												$("#' . $part_span_id . '").val(\' <img src="../../common/images/icon/icon_p.png" alt="펼치기" /> \');';
											break;
										}
									}
								}
								$total_member++;

							//승인자제외
								if ($mem_idx == $apply_idx)
								{
									$disabled = ' disabled="disabled"';
								}
								else
								{
									$disabled = '';
									if ($wi_idx != '' && $checked != '')
									{
										$data_charge = $work_data['charge_idx'];
										$data_charge_arr = explode(',', $data_charge);
										if (is_array($data_charge_arr))
										{
											foreach ($data_charge_arr as $data_charge_k => $data_charge_v)
											{
												if ($mem_idx == $data_charge_v)
												{
													if ($old_work_type == 'WT01') $disabled = '';
													else $disabled = ' disabled="disabled"';
													break;
												}
											}
										}
									}
								}
								$total_member++;

								echo '
								<li class="mem_name">
									<label for="' . $mem_check . '">
										<input type="checkbox" name="check_member_idx[]" id="' . $mem_check . '" value="' . $mem_idx . '" class="type_checkbox"' . $checked . $disabled . ' title="' . $mem_name . '" onclick="popup_member_select();" /> ' . $mem_name . '
									</label>
								</li>';
							}
						}
						echo '
							</ul>
						</li>
					</ul>';
					}
				}
			}
			echo '
				</div>';
		}
	}
?>
</div>
<?
	if ($old_work_type == 'WT01')
	{
		$work_data['charge_idx'] = '';
	}
?>
	<input type="hidden" name="chk_charge_idx" id="chk_charge_idx" value="<?=$work_data['charge_idx'];?>" title="담당자를 선택하세요." />

<script type="text/javascript">
//<![CDATA[
// 담당자 - 선택
	function popup_member_select()
	{
		var mem_idx  = document.getElementsByName('check_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = ''

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx  = mem_idx[i].value;
				}
				else
				{
					total_member_idx  += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}

		var chk_mem = $('#chk_charge_idx').val();
		if (chk_mem != '')
		{
			total_member_idx += ',' + chk_mem;
		}
<?
	if ($work_type != 'WT03') $total_member = 999999;
?>
		if (j >= <?=$total_member;?>)
		{
			check_auth_popup('승인업무일 경우 직원모두를 선택할 수 없습니다.');
		}
		else
		{
			$('#post_charge_idx').val(total_member_idx);
		}
		charge_member_list('<?=$work_type;?>', '<?=$wi_idx;?>');
	}

// 담당자관련
<?
	if (is_array($part_charge_on))
	{
		foreach ($part_charge_on as $on_k => $on_v)
		{
			echo $on_v;
		}
	}
?>
//]]>
</script>