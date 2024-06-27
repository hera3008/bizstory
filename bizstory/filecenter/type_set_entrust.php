<?
/*
	생성 : 2013.01.29
	수정 : 2013.02.05
	위치 : 파일센터 > 타입설정 - 권한 위임
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_idx  = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' || $auth_menu['mod'] == 'Y') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = "권한 위임";
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
    // 지사별
        $part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
        if ($set_part_work_yn == 'Y') { }
        else if ($set_part_yn == 'N') $part_where .= " and part.part_idx = '" . $code_part . "'";
        $part_list = company_part_data('list', $part_where, '', '', '');
        
        $member_source = form_member_view('source_member_idx[]', '', $part_list, 'select_source(this.value);');
        $member_target = form_member_view('target_member_idx[]', '', $part_list, 'select_target();', '', '02');
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$page_menu_name;?></strong> <?=$form_title;?>
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">
		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return chk_submit()">
		    <input type="hidden" id="sub_type"   name="sub_type" value="auth_entrust" />
		    <input type="hidden" id="sub_action" name="sub_action" />
		    <input type="hidden" name="source_mem_idx" id="source_mem_idx" />
		    <input type="hidden" name="target_mem_idx" id="target_mem_idx" />
			<?=$form_all;?>

			<fieldset>
				<legend class="blind"><?=$form_title;?> 폼</legend>

				<table class="tinytable write" summary="타입설정 등록/수정합니다.">
				<caption><?=$form_title;?></caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">권한 위임 계정</label></th>
						<td>
							<div class="left">
								<?=$member_source['change_view']?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_menu_depth">권한 부여 계정</label></th>
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

						<span class="btn_big_blue"><input type="submit" value="저장" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="popupentrust_close()" /></span>

					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript">
function chk_submit() {
    var err_msg = [];
    
    if ($("#source_mem_idx").val().trim() == '')
    {
        err_msg.push('권한 위임 계정을 선택하십시오.');
    }
    
    if ($("#target_mem_idx").val().trim() == '')
    {
        err_msg.push('권한 부여 계정을 선택하십시오.');
    }
    
    if (err_msg.length == 0) {
        if (confirm("선택한 계정에 권한을 부여하시겠습니까?")) {
            $("#loading").fadeIn('slow');
            $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
            $.ajax({
                type: 'post', dataType: 'json', url: link_ok,
                data: $('#postform').serialize(),
                success: function(msg) {
                    if (msg.success_chk == "Y")
                    {
                        popupentrust_close();
                    }
                    else
                    {
                        $("#loading").fadeOut('slow');
                        $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
                        check_auth_popup(msg.error_string);
                    }
                },
                complete: function(){
                    $("#loading").fadeOut('slow');
                    $("#backgroundPopup").fadeOut("slow");
                }
        });
        }
    } else {
        check_auth_popup(err_msg.join('<br />'));
    }
    
    return false;
}

function select_source(new_mem_idx) {
    var source_mem_idx = [];
    
    $("input[name='source_member_idx[]']:checkbox:checked").each(function() {        
        if ($(this).val() != new_mem_idx)
        {
            $(this).prop('checked', false);
        } else {
            source_mem_idx.push($(this).val());
        }
    });

    $("#source_mem_idx").val(source_mem_idx.join(','));
}

function select_target() {
    //console.log("target");
    var target_mem_idx = [];
    $("input[name='target_member_idx[]']:checkbox:checked").each(function() {
        //console.log($(this));
        //console.log($(this).val());
        target_mem_idx.push($(this).val()); 
    });
    
    $("#target_mem_idx").val(target_mem_idx.join(','));
}

<?=$member_source['change_script']?>
<?=$member_target['change_script']?>
</script>
<?
	}


//-------------------------------------- 등록, 수정시 담당자폼
    function form_member_view($input_name, $charge_idx, $part_list, $script_arr, $charge_type = 'default', $opt = '')
    {
        global $set_color_list2, $_SESSION, $sess_str;

        $chk_part = $_SESSION[$sess_str . '_part_idx'] . $opt;
        $chk_mem  = $_SESSION[$sess_str . '_mem_idx'];

        $charge_idx_arr = explode(',', $charge_idx);
        $script_chk_arr = explode(';', $script_arr);
        $script_view    = '';
        
        foreach ($script_chk_arr as $k => $v)
        {
            $script_view .= $v . ';';
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
                
                if ($opt != '') {
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
                            if ($opt != '') {
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