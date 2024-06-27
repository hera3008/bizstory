<?
/*
	생성 : 2012.12.26
	수정 : 2013.04.12
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무분류/등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$project_where = " and pro.pro_idx = '" . $pro_idx . "'";
	$project_data = project_info_data('view', $project_where);

	if ($project_data['pro_status'] != 'PS01' && $project_data['pro_status'] != 'PS60' && $project_data['pro_status'] != 'PS90') // 대기, 취소, 완료
	{
		$work_class_yn  = 'Y';
	}
	else
	{
		$work_class_yn  = 'N';
	}

	$where = " and proc.pro_idx = '" . $pro_idx . "'";
	$list = project_class_data('list', $where, '', '', '');
?>
<div class="report">
	<div class="report_info">
		<table class="tinytable">
			<colgroup>
				<col width="60px" />
				<col width="60px" />
				<col />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>순서</h3></th>
					<th class="nosort"><h3>상태</h3></th>
					<th class="nosort"><h3>업무분류 업무내용</h3></th>
					<th class="nosort"><h3>책임자</h3></th>
					<th class="nosort"><h3>기한일</h3></th>
					<th class="nosort"><h3>공정률</h3></th>
					<th class="nosort"><h3>관리</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
				<tr>
					<td colspan="7">등록된 데이타가 없습니다.</td>
				</tr>
<?
	}
	else
	{
		$i = 1;
        $sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from project_class where del_yn = 'N' and comp_idx ='" . $code_comp . "' and part_idx ='" . $code_part . "' and pro_idx = '" . $pro_idx . "'");
        
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$pro_status       = $data['class_status'];
				$pro_status_str   = $data['pro_status_str'];
				$pro_status_bold  = $data['pro_status_bold'];
				$pro_status_color = $data['pro_status_color'];

			// 상태
				$status_str = '<span style="';
				if ($pro_status_bold == 'Y') $status_str .= 'font-weight:900;';
				if ($pro_status_color != '') $status_str .= 'color:' . $pro_status_color . ';';
				$status_str .= '">' . $pro_status_str . '</span>';

                if (substr($data['charge_idx'],strlen($data['charge_idx'])-1, 1) == ',') $data['charge_idx'] = substr($data['charge_idx'], 0, strlen($data['charge_idx'])-1);

			// 총담당자구하기
				$charge_arr = explode(',', $data['charge_idx']);
				$total_charge_arr = array();                
				$chk_mem_idx = 'N';
                $idx = 0;
                $cnt = sizeof($charge_arr);
                $tmp_top_member = '';
                
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($charge_v != '')
					{
					    $chk_first = false;
                        
						if ($charge_v == $project_data['apply_idx'])
						{
							$pm_img = '<img src="' . $local_dir . '/bizstory/images/icon/ico_user_pm.gif" alt="프로젝트 책임자" /> ';
						}
						else $pm_img = '';
                        
                        if ($charge_v == $data['apply_idx'])
                        {
                            $pm_img .= '<img src="' . $local_dir . '/bizstory/images/icon/ico_user_pl.gif" alt="분류 책임자" /> ';
                        }                       

						$mem_view = staff_layer_form($charge_v, $pm_img, $set_part_work_yn, $set_color_list2, 'proclassstaff', $data['proc_idx'], '');

						if ($charge_v == $code_mem)
						{
							$chk_mem_idx = 'Y';
						}

                        if ($idx == 0) {
                            $charge_pm = '<span class="pro_member_' . $data['proc_idx'] . '_top" style="">' . $mem_view .'</span>';
                            $chk_first = true;
                            $tmp_top_member = $mem_view;
                        }

                        //프로젝트 책임자 이거나 분류 책임자인 경우 상단으로 뺀다
                        if ($charge_v == $project_data['apply_idx'] || $charge_v == $data['apply_idx']) {
                            if ($charge_v == $project_data['apply_idx']) {
                                $icon_type = "pm";
                            } else {
                                $icon_type = "pl";
                            }
						    $charge_pm = '<span class="pro_member_' . $data['proc_idx'] . '_' . $icon_type . '" style="">' . $mem_view .'</span>';
                            $chk_first = true;
                            
                            // 상단에 등록 된 멤버가 있는 경우 하단에 추가시긴다.
                            if ($tmp_top_member != '' && $idx > 0) array_push($total_charge_arr, '<span>' . $tmp_top_member . '</span>');
                            
                        }   
                        
                        if (!$chk_first) {
                        	array_push($total_charge_arr, '<span>' . $mem_view . '</span>');
                        }
                        
                        $idx++;
					}
				}
                if ($idx == 1) {
                    $total_charge_str = $charge_pm;
                } else {
                    $total_charge_str = $charge_pm . '<span id="besides_' . $data['proc_idx'] . '">외 ' . sizeof($total_charge_arr) . '명&nbsp;</span><img class="class_list" id="member_list_' 
                                . $data['proc_idx'] . '_btn" src="../../common/images/icon/icon_p.png" onclick="chk_show(' . $data['proc_idx'] . ')" style="cursor:pointer" /><div class="pro_member_' . $data['proc_idx'] . '" style="display:none">' . join(", ", $total_charge_arr) . '</div>';    
                }
                
				//$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));
                
            // 책임자
                $apply_name = staff_layer_form($data['apply_idx'], '', $set_part_work_yn, $set_color_list2, 'proapplystaff', $data['proc_idx'], '');
                $btn_up   = "check_auth_popup('modify')";
                $btn_down = "check_auth_popup('modify')";

			// 수정, 삭제 권한 - 등록자, 책임자 가능함
				if ($work_class_yn == 'Y')
				{
					if ($chk_mem_idx == 'Y') // 업무등록 - 담당자도 가능
					{
                        $btn_up   = "sort_proc('sort_up', '" . $data['proc_idx'] . "')";
                        $btn_down = "sort_proc('sort_down', '" . $data['proc_idx'] . "')";
						$btn_work = "work_insert('" . $data['pro_idx'] . "', '" . $data['proc_idx'] . "');";
					}
					else
					{
						$btn_work = "check_auth_popup('');";
					}
					if ($data['mem_idx'] == $code_mem || $code_level <= '11' || $project_data['apply_idx'] == $code_mem || $data['apply_idx'] == $code_mem)
					{
                        $btn_up     = "sort_proc('sort_up', '" . $data['proc_idx'] . "')";
                        $btn_down   = "sort_proc('sort_down', '" . $data['proc_idx'] . "')";
						$btn_work   = "work_insert('" . $data['pro_idx'] . "', '" . $data['proc_idx'] . "');";
						$btn_modify = "class_modify_form('open', '" . $data['proc_idx'] . "')";
						$btn_delete = "class_delete('" . $data['proc_idx'] . "')";
					}
				}
				else
				{
					$btn_work   = "check_auth_popup('');";
					$btn_modify = "check_auth_popup('modify');";
					$btn_delete = "check_auth_popup('delete');";
				}

			// 업무
				$work_where = " and wi.pro_idx = '" . $data['pro_idx'] . "' and wi.proc_idx = '" . $data['proc_idx'] . "'";
				$work_list = work_info_data('list', $work_where, '', '', '');
                
                $class_name = "";

				if ($pro_status == 'PS90')
				{
					$end_str = date_replace($data['end_date'], 'Y-m-d');
				}
				else
				{
					if ($work_list['total_num'] == 0)
					{
						$end_str = 0 . '%';
                        $class_name = "node_blank";
					}
					else
					{
						$pwork_where = " and wi.pro_idx = '" . $data['pro_idx'] . "' and wi.proc_idx = '" . $data['proc_idx'] . "'
							and (wi.work_status = 'WS90' or wi.work_status = 'WS99' or wi.work_status = 'WS60')";
						$pwork_page = work_info_data('page', $pwork_where);

						$end_str = round($pwork_page['total_num'] / $work_list['total_num'] * 100) . '%';
                        
                        $class_name = "collapsed";
					}
				}
				
?>
				<tr>
                    <td>
                        <div class="sort">
<?
                if ($sort_data["min_sort"] != $data["sort"] && $sort_data["min_sort"] != "")
                {
                    echo '<img src="bizstory/images/icon/up.gif" alt="위로" class="pointer" onclick="', $btn_up, '" />';
                }
                if($sort_data["max_sort"] != $data["sort"] && $sort_data["max_sort"] != "")
                {
                    echo '<img src="bizstory/images/icon/down.gif" alt="아래로" class="pointer" onclick="', $btn_down, '" />';
                }
?>
                        </div>
                    </td>
					<td><?=$status_str;?></td>
					<td>
						<div class="left pro_subject"><strong><?=$data['subject'];?></strong>
						<?if ($work_list['total_num'] != 0) {?>
						<img class="class_list" id="class_list_<?=$data['proc_idx']?>_btn" src="../../common/images/icon/icon_p.png" onclick="class_list_chk(this)" style="cursor:pointer" />
						<?}?>						    
						</div>						
						<div class="left pro_charge"><?=$total_charge_str;?></div>
				<?
					$work_num = 1;
					if ($work_list['total_num'] > 0)
					{
						echo '
						<div class="left workList_area" id="class_list_' . $data['proc_idx'] . '" style="display:none"">
							<table class="sub_table">';
						foreach ($work_list as $work_k => $work_data)
						{
							if (is_array($work_data))
							{
								$list_data = work_list_data($work_data, $work_data['wi_idx']);

								echo '
								<tr>
									<td><span class="pro_st">&nbsp;</span><img src="' . $local_dir . '/bizstory/images/icon/num_' . $work_num . '.png" alt="' . $work_num . '" /></td>
									<td class="left">[기한 : ' . $list_data['deadline_date_str'] . ']</td>									
									<td class="left">' . $list_data['charge_str'] . '</td>
								</tr>
								<tr>
								    <td>&nbsp;</td>
								    <td class="left">' . $list_data['project_status'] . '</td>
                                    <td class="left">:
                                        <a href="javascript:void(0);" onclick="location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $work_data['wi_idx'] . '\'" class="aproclass"><span class="aproclass">', $list_data['subject'], '</span></a>
                                        ' . $list_data['file_str'] . '
                                        ' . $list_data['report_str'] . '
                                        ' . $list_data['comment_str'] . '
                                    </td>
                                </tr>
								';
								unset($list_data);
								$work_num++;
							}
						}
						echo '
							</table>
						</div>';
					}
					unset($work_data);
					unset($work_list);
				?>
					</td>
					<td><span class="num"><?=$apply_name;?></span></td>
					<td><span class="num"><?=date_replace($data['deadline_date'], 'Y-m-d');?></span></td>
					<td><span class="num"><?=$end_str;?></span></td>
					<td>
				<?
					if ($work_class_yn == 'Y')
					{
				?>
						<a href="javascript:void(0);" onclick="<?=$btn_work;?>" class="btn_con_green"><span>등록</span></a><br />
						<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a><br />
						<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a><br />
				<?
					}
				?>
					</td>
				</tr>
				<div class="class_modify" id="class_modify_<?=$data['proc_idx'];?>" title="작업수정"></div>
<?
			}
		}
	}
?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
<?
	if (strlen(stristr($mybrowser_val_val, 'IE')) > 0)
	{
?>
		var work_form = '<?=$local_dir;?>/bizstory/project/project_view_work_form_activex.php'; // 파일업로드-active
<?
	}
	else
	{
?>
		var work_form = '<?=$local_dir;?>/bizstory/project/project_view_work_form.php';
<?
	}
?>

//------------------------------------ 작업 열기
	function work_insert(idx1, idx2)
	{
		$('#classlist_pro_idx').val(idx1);
		$('#classlist_proc_idx').val(idx2);
		
        $('.class_modify').html('');
        $('#new_class').html('');
        
        $('#list_sub_type').val('');
        $('#class_new_btn').show();

		$.ajax({
			type: "post", dataType: 'html', url: work_form,
			data: $('#classlistform').serialize(),
			success: function(msg) {
				//$("#nwe_class_work").slideUp("slow");
				//$("#nwe_class_work").slideDown("slow");
				$("#nwe_class_work").html(msg).show();
				$('#workpost_subject').focus();
			}
		});
	}

//------------------------------------ 작업 닫기
	function work_insert_close()
	{
		//$("#nwe_class_work").slideUp("slow");
		$("#nwe_class_work").html('');
	}

//------------------------------------ 작업 수정
	function class_modify_form(form_type, proc_idx)
	{
	    $('#nwe_class_work').html('').hide();
	    
	    //work_insert_close();
	    
		//class_insert_form('close');
		//class_list_data('');
		$('.class_modify').html('');
		$('#new_class').html('');
		
        $('#list_sub_type').val('');
        $('#classlist_proc_idx').val('');
        $('#class_new_btn').show();
        
        $("#classlist_proc_idx").val(proc_idx);
    
        if (form_type == 'close')
        {
            //$("#class_modify_" + proc_idx).slideUp("slow");
            $("#class_modify_" + proc_idx).html("");
        }
        else
        {
            $.ajax({
                type: "post", dataType: 'html', url: class_form,
                data: $('#classlistform').serialize(),
                success: function(msg) {
                    //$("#class_modify_" + proc_idx).slideUp("slow");
                    //$("#class_modify_" + proc_idx).slideDown("slow");                 
                    $("#class_modify_" + proc_idx).html(msg).show();
                    $("#classpost_subject").focus();
                    
                }
            });
        }

		
	}

//------------------------------------ 작업 삭제
	function class_delete(idx)
	{
		if (confirm("선택하신 작업을 삭제하시겠습니까?"))
		{
			$('#classlist_sub_type').val('delete');
			$('#classlist_proc_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: class_ok,
				data: $('#classlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						class_modify_form('close');
						class_list_data();
						//list_data();
                        $("#backgroundPopup").fadeOut("slow");
                        $("#loading").fadeOut('slow');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}

    function sort_proc(sub_type, idx)
    {
        $("#loading").fadeIn('slow');
        $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
        $.ajax({
            type: "post", dataType: 'json', url: link_ok,
            data: {
                'idx' : idx,
                'pro_idx' : $("#classlist_pro_idx").val(),
                'sub_type': sub_type
            },
            success: function(msg) {
                if (msg.success_chk == "Y")
                {
                    if (msg.error_string != '')
                    {
                        check_auth_popup(msg.error_string);
                    }
                    
                    //폴더번호가 넘어오면 해당 폴더를 삭제한다.
                    class_modify_form('close');
                    //list_data();
                    $("#backgroundPopup").fadeOut("slow");
                    $("#loading").fadeOut('slow');                 
                }
                else
                {
                    check_auth_popup(msg.error_string);
                }
            }
        });
    }

    function class_list_chk(obj) {
        try {
        
        var class_list = $(obj).parent().next().next();
        class_list.toggle();
        
        if (class_list.is(':visible')) {
            $(obj).attr('src', '../../common/images/icon/icon_m.png');
        } else {
            $(obj).attr('src', '../../common/images/icon/icon_p.png');
        }
        
        }catch(e) { }
        
    }

    function chk_show(idx) {
        var obj = $("#member_list_" + idx + "_btn");
        
        //alert(obj.attr('src'));
        if (obj.attr('src') == '../../common/images/icon/icon_p.png') {
            obj.attr('src', '../../common/images/icon/icon_m.png');
        } else {
            obj.attr('src', '../../common/images/icon/icon_p.png');
        }
        
        $(".pro_member_" + idx).toggle();
        $("#besides_" + idx).toggle();
    }

//]]>
</script>