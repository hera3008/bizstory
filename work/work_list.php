<?
/*
	생성 : 2012.04.19
	수정 : 2013.04.30
	위치 : 업무폴더 > 나의업무 > 업무 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$code_part    = search_company_part($code_part);
	$code_mem     = $_SESSION[$sess_str . '_mem_idx'];
	$code_level   = $_SESSION[$sess_str . '_ubstory_level'];
	$code_ubstory = $_SESSION[$sess_str . '_ubstory_yn'];
	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 보고, 코멘트 있는 경우
	if ($sview == 'today')
	{
		$work_check = work_read_check('');
		$add_where = $work_check['work_where'];
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = $add_where . " and wi.comp_idx = '" . $code_comp . "'";
	if ($set_part_work_yn == 'Y')
	{ }
	else
	{
		if ($set_part_yn == 'N') $where .= " and wi.part_idx = '" . $code_part . "'";
	}
    $file_where = str_replace("wi.", "", $where);
    
	if ($swtype != '' && $swtype != 'all') $where .= " and wi.work_type = '" . $swtype . "'";
	if ($shwstatus != '' && $shwstatus != 'all') $where .= " and wi.work_status = '" . $shwstatus . "'";
	
	if ($smember == 'all') // 전체업무일 경우
	{
		$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "' or wi.part_idx = '" . $code_part . "')";
	}
	else if ($smember != '')
	{
		$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $smember . ",%' or wi.apply_idx = '" . $smember . "' or wi.reg_id = '" . $smember . "')";
	}
	else
	{
		$where .= " and (concat(',', wi.charge_idx, ',') like '%," . $code_mem . ",%' or wi.apply_idx = '" . $code_mem . "' or wi.reg_id = '" . $code_mem . "')";
	}
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '' || $sorder2 == '')
	{
		$today_date = date('Y-m-d');
		$orderby = "
			 if (ws.code_value = 'WS90'
				, if (wi.end_date = '0000-00-00', '9999-12-31', wi.end_date)
				, if (ws.code_value = 'WS80'
					, '9000-12-31'
					, if (ws.code_value = 'WS01'
						, '9001-12-31'
						, '9002-12-31'
					)
				)
			) desc
			, if (datediff('" . $today_date . "', if (wi.deadline_date = '0000-00-00', '9999-12-31', wi.deadline_date)) < 0
				, 0
				, datediff('" . $today_date . "', if (wi.deadline_date = '0000-00-00', '9999-12-31', wi.deadline_date))
			) desc
			, wi.reg_date desc
		";
	}
	else
	{
		$orderby = $sorder1 . ' ' . $sorder2;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = work_list_info('list', $where, $file_where, $code_mem, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;swtype=' . $send_swtype . '&amp;shwstatus=' . $send_shwstatus . '&amp;smember=' . $send_smember;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"    value="' . $send_swhere . '" />
		<input type="hidden" name="stext"     value="' . $send_stext . '" />
		<input type="hidden" name="swtype"    value="' . $send_swtype . '" />
		<input type="hidden" name="shwstatus" value="' . $send_shwstatus . '" />
		<input type="hidden" name="smember"   value="' . $send_smember . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}

// 프로젝트일 경우
//  프로젝트는 프로젝트관리에서 보도록 수정(2017.07.06 고동원)
/*
	$project_where = "
		and (concat(',', pro.charge_idx, ',') like '%," . $code_mem . ",%' or pro.apply_idx = '" . $code_mem . "' or pro.reg_id = '" . $code_mem . "')
		and pro.pro_status != 'PS60' and pro.pro_status != 'PS90'";
	$project_list = project_list_info('list', $project_where, $file_where, '', '', '');
	if ($sview == 'today') $project_list['total_num'] = 0;
 * 
 * $total_num = $list['total_num'] + $project_list['total_num'];
*/
	$total_num = $list['total_num'];
?>
<div class="info_text">
	<?=$work_list_title?>
</div>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_down;?>
	<?=$btn_print;?>
	<?=$btn_print_sel;?>
	<a href="javascript:void(0);" onclick="move_project_list()" class="btn_big_blue" id="btn_proj"><span>프로젝트로 이동</span></a>
<?
	if ($code_ubstory == 'Y' && $code_level <= '21') {
?>
	<a href="javascript:void(0);" onclick="work_list_all('my')" class="btn_big_violet"><span>나의업무</span></a>
	<a href="javascript:void(0);" onclick="work_list_all('all')" class="btn_big_violet"><span>전체업무</span></a>
<?
	}
?>
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="100px" />
		<col width="120px" />
		<col />
		<col width="100px" />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="wiidx" onclick="check_all('wiidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('기한일', 'wi.deadline_date');?></h3></th>
			<th class="nosort"><h3>상태/완료일</h3></th>
			<th class="nosort"><h3>제목</h3></th>
			<th class="nosort"><h3>담당자</h3></th>
			<th class="nosort"><h3><?=field_sort('등록자', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록일', 'wi.reg_date');?></h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($total_num == 0) {
?>
		<tr>
			<td colspan="9">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
	// 프로젝트일 경우 - 알림일 경우 제외
	/*
		if ($sview != 'today')
		{
			$num = $project_list["total_num"];
			foreach ($project_list as $k => $data)
			{
				if (is_array($data))
				{
					$list_data = project_list_data($data, $data['pro_idx'], 'list');
?>
		<tr class="project_list" id="project_list_<?=$i?>" style="display:none">
			<td class="work_project2">&nbsp;</td>
			<td class="work_project2"><span class="num"><?=$num;?></span></td>
			<td class="work_project2"><span class="num"><?=$list_data['deadline_date_str'];?></span></td>
			<td class="work_project2"><?=$list_data['end_date_str'];?></td>
			<td class="work_project2">
				<div class="left">
					<img src="<?=$local_dir;?>/bizstory/images/icon/icon_project.gif" alt="프로젝트" />
					<span class="pro_code">[
                    <?if ($list_data['menu1'] == '') {?><?=$list_data['project_code'];?><?}else{
                        
                       echo $list_data['menu1'] . '-';
                       
                       if ($list_data['menu2'] != '') echo $list_data['menu2'] . '-';
                       
                       echo $list_data['project_code'];
                     
                    }?> 
                    ]</span>
					<a href="javascript:void(0);" onclick="location.href='<?=$local_dir;?>/index.php?fmode=project&smode=project&pro_idx=<?=$list_data['pro_idx'];?>#v<?=time() . $list_data['pro_idx']?>'"><?=$list_data['subject'];?></a>
					<?=$list_data['important_img'];?>
					<?=$list_data['open_img'];?>
					<?=$list_data['file_str'];?>
					<?=$list_data['new_img'];?>
				</div>
			</td>
			<td class="work_project2"><div class="left"><?=$list_data['charge_str'];?></div></td>
			<td class="work_project2"><?=$list_data['reg_name'];?></td>
			<td class="work_project2"><span class="num"><?=date_replace($list_data['reg_date'], 'Y-m-d');?></span></td>
		</tr>
<?
					$num--;
                    $i++;
				}
			}
		}
        */
        
	// 업무일 경우
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
        
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["wi_idx"] . "')";
				else $btn_view = "check_auth_popup('view')";

				$list_data = work_list_data($data, $data['wi_idx'], 'list');
?>
		<tr>
			<td<?=$list_data['work_class'];?>><input type="checkbox" id="wiidx_<?=$i;?>" name="chk_wi_idx[]" value="<?=$data["wi_idx"];?>" title="선택" /></td>
			<td<?=$list_data['work_class'];?>><span class="num"><?=$num;?></span></td>
			<td<?=$list_data['work_class'];?>><span class="num"><?=$list_data['deadline_date_str'];?></span></td>
			<td<?=$list_data['work_class'];?>><?=$list_data['end_date_str'];?></td>
			<td<?=$list_data['work_class'];?>>
				<div class="left">
					<?=$list_data['project_img'];?>
					<?=$list_data['work_img'];?>
					<?=$list_data['part_img'];?>
					<?=$list_data['subject_string'];?>
					<?=$list_data['important_img'];?>
					<?=$list_data['open_img'];?>
					<?=$list_data['file_str'];?>
					<?=$list_data['report_str'];?>
					<?=$list_data['comment_str'];?>
					<?=$list_data['new_img'];?>
					<?=$list_data['read_work_str'];?>
				</div>
			</td>
			<td<?=$list_data['work_class'];?>><div class="left"><?=$list_data['charge_str'];?></div></td>
			<td<?=$list_data['work_class'];?>><?=$list_data['reg_name'];?></td>
			<td<?=$list_data['work_class'];?>><span class="num"><?=date_replace($list_data['reg_date'], 'Y-m-d');?></span></td>
		</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />

<script type="text/javascript">
    function move_project_list() {
        location.href = '/index.php?fmode=project&smode=project';
    }
    
    function show_project() {
        $(".project_list").toggle();
        if ($("#project_list_0").is(":visible")) {
            $("#btn_proj").html('<span>프로젝트 닫힘</span>');
        } else {
            $("#btn_proj").html('<span>프로젝트 펼침</span>');
        }
    }
</script>

