<?
/*
	생성 : 2013.02.04
	수정 : 2013.04.08
	위치 : 파일센터 > 권한설정 - 권한
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
	$code_part   = search_company_part($code_part);
	$code_mem    = $_SESSION[$sess_str . '_mem_idx'];
	$set_part_yn = $comp_set_data['part_yn'];
    $type = $_POST['type'];
    $val = $_POST['val'];
    $target = $_POST['target'];
    
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>선택</strong>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
            <li>검색하실 계정을 선택하십시오.</li>
		</ul>
	</div>

	<div class="ajax_frame">

        <input type="hidden" name="target_mem_idx" id="target_mem_idx" value="<?=$val?>"/>
        <input type="hidden" name="target_mem_name" id="target_mem_name" />

	            
			<ul id="auth_folder_list">
<?
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
    // 지사별
    $part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
    if ($set_part_work_yn == 'Y') { }
    else if ($set_part_yn == 'N') $part_where .= " and part.part_idx = '" . $code_part . "'";
    $part_list = company_part_data('list', $part_where, '', '', '');
    
    $click_script = 'select_target();';
    
    if ($type == 'single') {
        $click_script = 'select_target(this.value);';   
    }
    
    $member_target = form_member_view('target_member_idx[]', '', $part_list, $click_script, '', '02');
?>

        <fieldset>
            <legend class="blind"><?=$target;?> 폼</legend>

            <table class="tinytable write" summary="계정을 선택합니다.">
            <caption><?=$form_title;?></caption>
            <colgroup>
                <col width="120px" />
                <col />
            </colgroup>
            <tbody>
                <tr>
                    <th><label for="post_menu_depth">선택 계정</label></th>
                    <td>
                        <div class="left">
                            <?=$member_target['change_view']?>
                        </div>
                    </td>
                </tr>
            </tbody>
            </table>

            <div class="section">
                <div class="fr">

                    <span class="btn_big_blue"><input type="button" value="선택" onclick="chk_submit()" /></span>
                    <span class="btn_big_gray"><input type="button" value="취소" onclick="cancel_it()" /></span>

                </div>
            </div>

        </fieldset>
			    
			</ul>
			
	</div>
</div>
<script type="text/javascript">
function chk_submit() {
    
    $("#search_s<?=$target?>").val($("#target_mem_idx").val());
    
    switch('<?=$target?>') {
        case 'charge':
            $("#charge_member").html('선택된 담당자 : ' + $("#target_mem_name").val());
        break;
        case 'apply':
            $("#apply_member").html('선택된 책임자 : ' + $("#target_mem_name").val());
        break;
        case 'reg':
            $("#reg_member").html('선택된 등록자 : ' + $("#target_mem_name").val());
        break;
    }
    
    popupform_close();
    
}

function cancel_it() {
    
    $("#search_s<?=$target?>").val('');
    $("#<?=$target?>_member").html('');
    
    popupform_close();
    
}

<? if ($type == 'multi') { ?>
function select_target() {
    //console.log("target");
    var target_mem_idx = [];
    var target_mem_name = [];
    
    $("input[name='target_member_idx[]']:checkbox:checked").each(function() {
        
        target_mem_idx.push($(this).val());
        target_mem_name.push($(this).attr('title')); 
    });
    
    $("#target_mem_idx").val(target_mem_idx.join(','));
    $("#target_mem_name").val(target_mem_name.join(','));
}
<? } else { ?>
function select_target(new_mem_idx) {
    //console.log("source");
    var target_mem_idx = [];
    var target_mem_name = [];
    
    $("input[name='target_member_idx[]']:checkbox:checked").each(function() {
        
        if ($(this).val() != new_mem_idx) {
            $(this).prop('checked', false);            
        } else {
            target_mem_idx.push($(this).val());
            target_mem_name.push($(this).attr('title')); 
        }
    });
    
    $("#target_mem_idx").val(target_mem_idx.join(','));
    $("#target_mem_name").val(target_mem_name.join(','));
}
<? } ?>
<?=$member_target['change_script']?>

$(function() {

    var selected = [<?=$val?>];
    var target_mem_name = [];
    
    $.each(selected, function(i, value) {
    
        $("input[name='target_member_idx[]']").each(function() {
    
            if ($(this).val() == value) {
                $(this).attr('checked', true);
                target_mem_name.push($(this).attr('title'));
            }
        });
    });
    
     $("#target_mem_name").val(target_mem_name.join(','));
    /*
    $("input[name='target_member_idx[]']").each(function() {
        if ($(this).val(
    });
    */
});
</script>
<?
    
//-------------------------------------- 등록, 수정시 담당자폼
    function form_member_view($input_name, $charge_idx, $part_list, $script_arr, $charge_type = 'default', $opt = '')
    {
        global $set_color_list2, $_SESSION, $sess_str, $type;

        $chk_part = $_SESSION[$sess_str . '_part_idx'] . $opt;
        $chk_mem  = $_SESSION[$sess_str . '_mem_idx'];

        $charge_idx_arr = explode(',', $charge_idx);
        $script_chk_arr = explode(';', $script_arr);
        $script_view    = '';
        
        foreach ($script_chk_arr as $k => $v)
        {
            $script_view .= $v;
        }

        $change_str = '';
    // 지사별
        foreach ($part_list as $part_k => $part_data)
        {
            if (is_array($part_data))
            {
                $part_idx     = $part_data['part_idx'];
                $part_name    = $part_data['part_name'];
                $part_sort    = $part_data['sort'];
                $part_color   = $set_color_list2[$part_sort];
                $part_check   = 'partidx' . $part_idx . $opt;
                $part_ul_id   = 'part_charge_view_' . $part_idx. $opt;
                $part_span_id = 'part_charge_btn_' . $part_idx. $opt;
                $part_charge_func = 'part_charge_chk' . str_replace('-', '_', $opt);
                
                if ($opt != '' && $type == 'multi') {
                    $chk_all_str = '<input type="checkbox" class="type_checkbox" title="' . $part_name . '" name="' . $part_check . '" id="' . $part_check . '" onclick="check_all2(\'' . $part_check . '\', this, \'1\');' . $script_view . '" />';                    
                }

                $change_str .= '
                    <div class="charge_view_box left">
                        <ul>
                            <li class="first">
                                <label for="' . $part_check . '">
                                    ' . $chk_all_str . '
                                    <span style="color:' . $part_color . '">' . $part_name . '</span>
                                </label>
                                <span onclick="' . $part_charge_func . '(\'' . $part_idx . '\')" class="pointer" id="' . $part_span_id . '"><img src="../../common/images/icon/icon_p.png" alt="펼치기" /></span>
                            </li>
                        </ul>
                    </div>
                    <div class="charge_view_box left none" id="' . $part_ul_id . '">';

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

                    // 직원
                        $mem_where = " and mem.part_idx = '" . $part_idx . "' and mem.csg_idx = '" . $group_idx . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
                        $mem_order = "csg.sort asc, cpd.sort asc, mem.mem_name asc";
                        $mem_list = member_info_data('list', $mem_where, $mem_order, '', '');
                        if ($mem_list['total_num'] > 0)
                        {
                            if ($opt != '' && $type == 'multi') {
                                $chk_all_str0 = '<input type="checkbox" class="type_checkbox" title="' .$group_name . '" name="' . $group_check . '" id="' . $group_check . '" onclick="check_all2(\'' . $group_check . '\', this, \'0\');' . $script_view . '" />';
                            }
                            
                            
                            $change_str .= '
                        <ul>
                            <li class="second">
                                <label for="' . $group_check . '">
                                    ' . $chk_all_str0 . '
                                    <span>' . $group_name . '</span>
                                </label>
                                <ul>';

                            foreach ($mem_list as $mem_k => $mem_data)
                            {
                                if (is_array($mem_data))
                                {
                                    $mem_idx   = $mem_data['mem_idx'];
                                    $mem_name  = $mem_data['mem_name'];
                                    $mem_check = $group_check . '_' . $mem_idx;

                                    $checked = ''; $disabled = '';
                                    if (is_array($charge_idx_arr))
                                    {
                                        foreach ($charge_idx_arr as $charge_k => $charge_v)
                                        {
                                            if ($mem_idx == $charge_v)
                                            {
                                                $checked  = ' checked="checked"';
                                                if ($charge_type == 'default') $disabled = ' disabled="disabled"';
                                                else $disabled = '';

                                                $part_charge_on[$part_idx] = '
                                                    $("#' . $part_ul_id . '").css({"display": "block"});
                                                    $("#' . $part_span_id . '").val(" - ");';
                                                break;
                                            }
                                        }
                                    }
                                    $total_member++;
                                    if ($charge_type == 'msg')
                                    {
                                        if ($chk_mem == $mem_idx)
                                        {
                                            $disabled = ' disabled="disabled"';
                                            $checked  = '';
                                        }
                                    }

                                    $change_str .= '
                                    <li class="mem_name">
                                        <label for="' . $mem_check . '">
                                            <input type="checkbox" name="' . $input_name . '" id="' . $mem_check . '" value="' . $mem_idx . '" class="type_checkbox"' . $checked . $disabled . ' title="' . $mem_name . '" onclick="' . $script_view . '" /> ' . $mem_name . '
                                        </label>
                                    </li>';
                                }
                            }
                            $change_str .= '
                                </ul>
                            </li>
                        </ul>';
                        }
                    }
                }
                $change_str .= '
                    </div>';
            }
        }
        if (is_array($part_charge_on))
        {
            foreach ($part_charge_on as $on_k => $on_v)
            {
                $script_charge_on .= $on_v;
            }
        }

        $script = '
            $("#part_charge_btn_' . $chk_part . '").html(" <img src=\'../../common/images/icon/icon_m.png\' alt=\'접기\' /> ");
            $("#part_charge_view_' . $chk_part . '").css({"display": "block"});

            ' . $script_charge_on . '

            function ' . $part_charge_func . '(idx)
            {
                if ($("#part_charge_view_" + idx + "' . $opt . '").is(":visible")) {
                    $("#part_charge_btn_" + idx + "' . $opt . '").html(" <img src=\'../../common/images/icon/icon_p.png\' alt=\'접기\' /> ");
                } else {
                    $("#part_charge_btn_" + idx + "' . $opt . '").html(" <img src=\'../../common/images/icon/icon_m.png\' alt=\'펼치기\'> ");
                }
                $("#part_charge_view_" + idx + "' . $opt . '").toggle();
            }
        ';

        $str['change_view']   = $change_str;
        $str['change_script'] = $script;
        $str['change_total']  = $total_member;

        return $str;
    }
?>