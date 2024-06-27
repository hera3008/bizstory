<?
/*
	생성 : 2012.12.26
	수정 : 2012.12.26
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 작업 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];

    $project_where = " and pro.pro_idx = '" . $pro_idx . "'";
    $project_data = project_info_data('view', $project_where);
    $project_charge_arr = explode(',', $project_data['charge_idx']);
    $project_start_date = $project_data['start_date'];
    $project_end_date   = $project_data['deadline_date'];
    
    if ($proc_idx != '') { 
    	$where = " and proc.proc_idx = '" . $proc_idx . "'";
    	$data = project_class_data('view', $where);
        
        //$pro_charge_idx = $data['charge_idx'];
    } 
    
    $pro_charge_idx = $project_data['charge_idx'];
    
    $pro_charge_idx_arr = explode(',', $pro_charge_idx);
    
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
    
	if ($proc_idx == '')
	{
		$member_idx = 'check_member_idx[]';
	}
	else
	{
		$member_idx = 'check_member_idx_' . $proc_idx . '[]';
	}

	if ($data['deadline_date'] == '')
	{
		$chk_start_date = str_replace('-', '', $project_start_date);
		$chk_today_date = date('Ymd');

		if ($chk_start_date >= $chk_today_date)
		{
			$data['deadline_date'] = $project_start_date;
		}
		else
		{
			$data['deadline_date'] = date('Y-m-d');
		}
	}
?>
<div class="new_report">
	<form name="classform" id="classform" method="post" action="<?=$this_page;?>" onsubmit="return check_class_form()">
		<input type="hidden" name="pro_idx" value="<?=$pro_idx;?>" />
		<input type="hidden" name="project_start_date" id="project_start_date" value="<?=$project_start_date;?>" />
		<input type="hidden" name="project_end_date"   id="project_end_date"   value="<?=$project_end_date;?>" />

		<table class="tinytable write" summary="프로젝트 작업을 등록/수정합니다.">
		<caption>프로젝트 작업</caption>
		<colgroup>
			<col width="100px" />
			<col />
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th><label for="classpost_subject">작업명</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[subject]" id="classpost_subject" class="type_text" title="작업명을 입력하세요." size="40" value="<?=$data['subject'];?>" />
					</div>
				</td>
				<th><label for="classpost_deadline_date">기한</label></th>
				<td>
					<div class="left">
						<input type="text" name="param[deadline_date]" id="classpost_deadline_date" class="type_text datepicker" title="기한을 입력하세요." size="10" value="<?=date_replace($data['deadline_date'], 'Y-m-d');?>" />
					</div>
				</td>
			</tr>
            <tr>
                <th><label for="classpost_apply_idx">분류 책임자</label></th>
                <td colspan="3">
                    <div class="left">
                        <select name="param[apply_idx]" id="classpost_apply_idx" title="책임자를 지정하세요.">
                            <option value="">분류 책임자를 지정하세요.</option>
                    <?
                    // 지사별 직원
                        $sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.mem_idx in (" . $pro_charge_idx . ") and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
                        $sub_order2 = "cpd.sort asc, mem.mem_name asc";
                        $mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
                        //print_r($mem_list);
                        
                        foreach ($mem_list as $mem_k => $mem_data)
                        {
                            if (is_array($mem_data))
                            {
                    ?>
                            <option value="<?=$mem_data['mem_idx'];?>" <?if ($data['apply_idx'] == $mem_data['mem_idx']) {?>selected<?}?>>[<?=$mem_data['part_name'];?>] <?=$mem_data['mem_name'];?></option>
                    <?
                                
                            }
                        }
                    ?>
                        </select>
                    </div>
                </td>
            </tr>
			<tr>
				<th><label for="classpost_charge_idx">담당자</label></th>
				<td colspan="3">
					<input type="hidden" name="param[charge_idx]" id="classpost_charge_idx" value="" title="담당자를 선택하세요." />
                    <!--<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" title="담당자를 선택하세요." />-->
                    <input type="hidden" name="post_old_charge_idx" id="post_old_charge_idx" value="<?=$data['charge_idx'];?>" />
					<div class="charge_view_box left">
						<ul>
							<li class="part_name">ㆍ프로젝트 담당자중 선택</li>
						</ul>
						<ul>
				    <?
                    $charge_idx_arr = $project_charge_arr; //explode(',', $data['charge_idx']);
                    $charge_view = form_charge_view('project_member_idx[]', $pro_charge_idx, $part_list, 'select_member();', 'project');
                    
                    echo $charge_view['change_view'];
                    ?>
						</ul>
					</div>
				</td>
			</tr>
		</tbody>
		</table>

		<div class="action">
	<?
		if ($proc_idx == '') {
	?>
			<span class="btn_big_green"><input type="submit" value="등록" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="class_insert_form('close')" /></span>

			<input type="hidden" name="sub_type" value="post" />
	<?
		} else {
	?>
			<span class="btn_big_blue"><input type="submit" value="수정" /></span>
			<span class="btn_big_gray"><input type="button" value="취소" onclick="class_modify_form('close', '<?=$proc_idx;?>')" /></span>

			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="proc_idx" value="<?=$proc_idx;?>" />
			<input type="hidden" name="old_deadline_date" id="old_deadline_date" value="<?=$data['deadline_date'];?>" />
			<input type="hidden" name="old_charge_idx"    id="old_charge_idx"    value="<?=$data['charge_idx'];?>" />
	<?
		}
		unset($project_data);
		unset($data);
	?>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
    $(function() {
        $(".datepicker").datepicker();
        if ($("#old_charge_idx").val() != '' && $("#old_charge_idx").val() != null) {
            var charge_arr = $("#old_charge_idx").val().split(',');
            
            $("input:checkbox[name='project_member_idx[]']").each(function() {
                var mem_idx = $(this).val();
                
                var cnt = charge_arr.length;
                
                for (var idx = 0; idx < cnt; idx++) {
                    if (mem_idx == charge_arr[idx]) {$(this).attr('checked', true);}
                }
            });
        }
    });

//------------------------------------ 작업등록/수정
	function check_class_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#classpost_subject').val(); // 작업명
		chk_title = $('#classpost_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#classpost_deadline_date').val(); // 기한
		chk_title = $('#classpost_deadline_date').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		var start_date1 = $('#project_start_date').val();
		var start_date  = $('#project_start_date').val().replace(/-/g, '');

		var end_date1   = $('#project_end_date').val();
		var end_date    = $('#project_end_date').val().replace(/-/g, '');

		var chk_date    = $('#classpost_deadline_date').val().replace(/-/g, '');

		if (chk_date < start_date)
		{
			chk_total = chk_total + '기한은 시작일 ' + start_date1 + ' 보다 커야합니다.<br />';
			action_num++;
		}
		if (chk_date > end_date)
		{
			chk_total = chk_total + '기한은 종료일 ' + end_date1 + ' 보다 작아야합니다.<br />';
			action_num++;
		}

		select_member();

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: "post", dataType: 'json', url: class_ok,
				data: $('#classform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
	<?
		if ($proc_idx == '') {
	?>
						class_insert_form('close');
	<?
		} else {
	?>
						class_modify_form('close','');
	<?
		}
	?>
						class_list_data();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

// 담당자 - 선택
	function select_member()
	{
		//var mem_idx  = document.getElementsByName('<?=$member_idx;?>');
		var mem_idx  = document.getElementsByName('project_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = ''
		var arr_idx = [];

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
			    arr_idx.push(mem_idx[i].value);
			}
			i++;
		}
		
		total_member_idx = arr_idx.join(',');
		
		$('#classpost_charge_idx').val(total_member_idx);
	}
//]]>
</script>