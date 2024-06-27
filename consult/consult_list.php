<?
/*
	생성 : 2012.09.25
	수정 : 2012.09.27
	위치 : 설정관리 > 에이전트관리 > 상담게시판 > 상담게시판 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
	$code_part   = search_company_part($code_part);
	$code_mem    = $_SESSION[$sess_str . '_mem_idx'];
	$set_part_yn = $company_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and cons.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and cons.part_idx = '" . $code_part . "'";

	if ($shclass != '' && $shclass != 'all') // 분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $shclass . ",%' or cons.consult_class = '" . $shclass . "')";
	}
	if ($shstaff != '' && $shstaff != 'all') // 직원
	{
		$where .= " and concat(',', cons.charge_idx, ',') like '%," . $shstaff . ",%'";
	}
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'cons.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(cons.tel_num, '-', '') like '%" . $stext . "%' or
				replace(cons.tel_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

// 자기상담만
	if ($smode == 'my_consult')
	{
		$where .= " and concat(',', cons.charge_idx, ',') like '%," . $code_mem . ",%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'cons.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = consult_info_data('list', $where, $orderby, $page_num, $page_size);

	$page_num = $list['page_num'];

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;sconsclass=' . $send_sconsclass . '&amp;shstaff=' . $send_shstaff;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"     value="' . $send_swhere . '" />
		<input type="hidden" name="stext"      value="' . $send_stext . '" />
		<input type="hidden" name="sconsclass" value="' . $send_sconsclass . '" />
		<input type="hidden" name="shstaff"    value="' . $send_shstaff . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_down;?>
	<?=$btn_print;?>
	<?=$btn_print_sel;?>
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="160px" />
		<col width="100px" />
		<col />
		<col width="100px" />
		<col width="100px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="considx" onclick="check_all('considx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'cons.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록자', 'cons.writer');?></h3></th>
			<th class="nosort"><h3>담당자</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="8">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["cons_idx"] . "')";
				else $btn_view = "check_auth_popup('view')";

				$list_data = consult_list_data($data['cons_idx'], $data);
?>
		<tr>
			<td><input type="checkbox" id="considx_<?=$i;?>" name="chk_cons_idx[]" value="<?=$list_data["cons_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$list_data['client_search'];?></td>
			<td><?=$list_data['class_str']['first_class'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$list_data['subject'];?></a>
					<?=$list_data['important_str'];?>
					<?=$list_data['total_file_str'];?>
					<?=$list_data['total_comment_str'];?>
					<?=$list_data['read_consult_str'];?>
				</div>
			</td>
			<td><span class="num"><?=$list_data['consult_name'];?></span></td>
			<td><?=$list_data['charge_list'];?></td>
			<td>
				<span class="num"><?=date_replace($list_data['reg_date'], 'Y.m.d');?><?=$list_data['end_date_str'];?></span>
			</td>
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
