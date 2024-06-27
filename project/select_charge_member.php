<?
/*
	생성 : 2013.02.05
	수정 : 2013.02.05
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무 - 등록/수정폼 - 담당자선택
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];

	$project_class_where = " and proc.proc_idx = '" . $proc_idx . "'";
	$project_class_data = project_class_data('view', $project_class_where);

	$pro_charge_idx     = $project_class_data['charge_idx'];
	$pro_charge_idx_arr = explode(',', $pro_charge_idx);
	$total_member   = 0;

	if ($work_type != 'WT03') $apply_idx = '';

	$charge_idx_arr = explode(',', $charge_idx);
?>
<div class="charge_view_box">
	<ul>
		<li class="part_name">ㆍ담당자선택</li>
	</ul>
<?
    $pro_charge_idx = str_replace(',,,', ',', $pro_charge_idx);
    $pro_charge_idx = str_replace(',,', ',', $pro_charge_idx);

    if (substr($pro_charge_idx, strlen($pro_charge_idx)-1, 1) == ',') {
        $pro_charge_idx = substr($pro_charge_idx, 1, strlen($pro_charge_idx)-2);
    }
    $sub_where = " and part.comp_idx='" . $code_comp . "' and part.view_yn='Y' and m.mem_idx in (" . $pro_charge_idx . ")";

    if ($set_part_work_yn == 'Y')
    { }
    else if ($set_part_yn == 'N') 
    {
        $sub_where .= " and part.part_idx = '" . $code_part . "'";
    }
    $part_list = project_part_data($sub_where, '', '', '');
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
                    $sub_where2 = " and mem.part_idx = '" . $part_idx . "' and mem.csg_idx = '" . $group_idx . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y' and mem.mem_idx in (" . $pro_charge_idx . ")";
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
                                        <input type="checkbox" name="workcheck_member_idx[]" id="' . $mem_check . '" value="' . $mem_idx . '" class="type_checkbox"' . $checked . $disabled . ' title="' . $mem_name . '" onclick="popup_member_select();" /> ' . $mem_name . '
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

<script type="text/javascript">
//<![CDATA[
// 담당자 - 선택
	function popup_member_select()
	{
		var mem_idx  = document.getElementsByName('workcheck_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = [];

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
	<?
		if ($work_type == 'WT01') // 본인업무일 경우
		{
	?>
				if (j >= 1)
				{
					mem_idx[i].checked = false;
					check_auth_popup('본인업무일 경우 한명의 직원만 선택할 수 있습니다.');
				}
	<?
		}
	?>
                total_member_idx.push(mem_idx[i].value);
                j++;
			}
			i++;
		}
<?
	if ($work_type == 'WT03') // 승인업무일 경우
	{
?>
		if (j >= <?=$total_member;?>)
		{
			check_auth_popup('승인업무일 경우 직원모두를 선택할 수 없습니다.');
		}
<?
	}
	else if ($work_type == 'WT01') // 본인업무일 경우
	{
?>
		if (j >= 2)
		{
			mem_idx[i].checked == true
			check_auth_popup('본인업무일 경우 한명의 직원만 선택할 수 있습니다.');
		}
<?
	}
	else
	{
?>
		if (j = 0)
		{ }
<?
	}
?>
		else
		{
			i = 0, j = 0;
			while(mem_idx[i])
			{
				if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
				{
				    if (total_member_idx.indexOf(mem_idx[i].value) < 0)
					   total_member_idx.push(mem_idx[i].value);
					j++;
				}
				i++;
			}
			
			$('#workpost_charge_idx').val(total_member_idx.join(','));
			//charge_member_list('<?=$work_type;?>');
		}
	}
	
//]]>
</script>