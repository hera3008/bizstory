<?
/*
	수정 : 2013.05.02
	위치 : 고객관리 > 접수목록 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn = $comp_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ri.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ri.part_idx = '" . $code_part . "'";

	if ($shclass != '' && $shclass != 'all') // 접수분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $shclass . ",%' or ri.receipt_class = '" . $shclass . "')";
	}
	if ($shstatus != '' && $shstatus != 'all') // 접수상태
	{
		if ($shstatus == 'end_no')
		{
			$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
		}
		else
		{
			$where .= " and ri.receipt_status = '" . $shstatus . "'";
		}
	}
	if ($shsgroup != '' && $shsgroup != 'all') // 직원그룹
	{
		$where .= " and mem.csg_idx = '" . $shsgroup . "'";
	}
	if ($shstaff != '' && $shstaff != 'all') // 직원 - 거래처담당자
	{
		$where .= " and ri.charge_mem_idx = '" . $shstaff . "'";
	}
	if ($list_type == 'all_no') // 전체 미처리
	{
		$where .= " and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')";
	}
	if ($sdate1 != "") $where .= " and date_format(ri.reg_date, '%Y-%m-%d') >= '" . $sdate1 . "'";
	if ($sdate2 != "") $where .= " and date_format(ri.reg_date, '%Y-%m-%d') <= '" . $sdate2 . "'";
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'ri.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(ri.tel_num, '-', '') like '%" . $stext . "%' or
				replace(ri.tel_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ri.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	if ($list_type == 'my_no') // 나의 미처리
	{
		$where .= "
			and (ri.receipt_status <> 'RS90' and ri.receipt_status <> 'RS80' and ri.receipt_status <> 'RS60')
			and (
				if (ifnull(rid.mem_idx, '') = ''
					, if (ifnull(ri.charge_mem_idx, '') = ''
						, ci.mem_idx
						, ri.charge_mem_idx)
					, rid.mem_idx) = '" . $code_mem . "')
		";
		$query_page = "
			select
				count(ri.ri_idx)
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' group by ri_idx) rid on rid.ri_idx = ri.ri_idx

				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				ri.del_yn = 'N'
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ri.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url
				, mem.mem_name, mem.del_yn as member_del_yn, mem.mem_idx
				, code.del_yn as class_del_yn
				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join (select ri_idx, mem_idx from receipt_info_detail where del_yn = 'N' group by ri_idx) rid on rid.ri_idx = ri.ri_idx

				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				ri.del_yn = 'N'
				" . $where . "
			order by
				" . $orderby . "
		";
		//echo "<pre>" . $query_string . "</pre><br />";
		$data_sql['query_page']   = $query_page;
		$data_sql['query_string'] = $query_string;
		$data_sql['page_size']    = $page_size;
		$data_sql['page_num']     = $page_num;

		$list = query_list($data_sql);
	}
	else
	{
/*
		$query_page = "
			select
				count(ri.ri_idx)
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = ri.part_idx
				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				" . $where . "
		";
		//echo "<pre>" . $query_page . "</pre><br />";
		$query_string = "
			select
				ri.*
				, ci.client_name, ci.del_yn as client_del_yn, ci.link_url, ci.mem_idx as client_mem_idx
				, part.part_name
				, mem.mem_name, mem.del_yn as member_del_yn, mem.mem_idx
				, code.del_yn as class_del_yn
				, code2.code_name as receipt_status_str, code2.code_bold as receipt_status_bold, code2.code_color as receipt_status_color, code2.code_value as status_value
				, code2.del_yn as status_del_yn, code2.code_value as status_code_value
			from
				receipt_info ri
				left join client_info ci on ci.comp_idx = ri.comp_idx and ci.ci_idx = ri.ci_idx
				left join company_part part on part.del_yn = 'N' and part.part_idx = ri.part_idx
				left join member_info mem on mem.comp_idx = ci.comp_idx and mem.mem_idx = ci.mem_idx
				left join code_receipt_class code on code.comp_idx = ri.comp_idx and code.part_idx = ri.part_idx and code.code_idx = ri.receipt_class
				left join code_receipt_status code2 on code2.comp_idx = ri.comp_idx and code2.part_idx = ri.part_idx and code2.code_value = ri.receipt_status
			where
				" . $where . "
			order by
				" . $orderby . "
		";
		$data_sql['query_page']   = $query_page;
		$data_sql['query_string'] = $query_string;
		$data_sql['page_size']    = $page_size;
		$data_sql['page_num']     = $page_num;
		$data_info = query_list($data_sql);
*/

		$list = receipt_info_data('list', $where, $orderby, $page_num, $page_size);
		//print_r($list);
	}

	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shclass=' . $send_shclass . '&amp;shstatus=' . $send_shstatus . '&amp;shstaff=' . $send_shstaff;
	$f_search  = $f_search . '&amp;shsgroup=' . $send_shsgroup;
	$f_search  = $f_search . '&amp;sdate1=' . $send_sdate1 . '&amp;sdate2=' . $send_sdate2;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="shclass"  value="' . $send_shclass . '" />
		<input type="hidden" name="shstatus" value="' . $send_shstatus . '" />
		<input type="hidden" name="shsgroup"  value="' . $send_shsgroup . '" />
		<input type="hidden" name="shstaff"  value="' . $send_shstaff . '" />
		<input type="hidden" name="sdate1"   value="' . $send_sdate1 . '" />
		<input type="hidden" name="sdate2"   value="' . $send_sdate2 . '" />
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
		$btn_print_sel = '<a href="javascript:void(0);" onclick="list_print_detail()" class="btn_big_violet"><span>상세인쇄</span></a>';
	}
?>
<div class="info_text">
	<ul>
		<li>상세인쇄시 인쇄하고자 하는 접수를 선택하고 난뒤 클릭해주세요.</li>
	</ul>
</div>

<div class="details">
	<div>Records <span class="total_num"><?=$list['total_num'];?></span> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_down;?>
	<?=$btn_print;?>
	<?=$btn_print_sel;?>
	<a href="javascript:void(0);" onclick="list_search('my_no')" class="btn_big_violet"><span>나의 미처리</span></a>
	<a href="javascript:void(0);" onclick="list_search('all_no')" class="btn_big_violet"><span>전체 미처리</span></a>
	<a href="javascript:void(0);" onclick="list_search('all')" class="btn_big_violet"><span>전체목록</span></a>
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
		<col width="70px" />
		<col width="90px" />
		<col width="80px" />
		<col width="35px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="riidx" onclick="check_all('riidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3>분류</h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'ri.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록자', 'ri.writer');?></h3></th>
			<th class="nosort"><h3><?=field_sort('상태', 'code2.sort');?></h3></th>
			<th class="nosort"><h3><?=field_sort('담당직원', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3>등록일</h3></th>
			<th class="nosort"><h3><img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="10">등록된 데이타가 없습니다.</td>
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
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["ri_idx"] . "')";
				else $btn_view = "check_auth_popup('view')";

				$list_data = receipt_list_data($data['ri_idx'], $data);
?>
		<tr>
			<td><input type="checkbox" id="riidx_<?=$i;?>" name="chk_ri_idx[]" value="<?=$list_data["ri_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$list_data['client_name_str'];?></td>
			<td><?=$list_data['receipt_class_str']['first_class'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$list_data['subject'];?></a>
					<?=$list_data['important_str'];?>
	<?
		if ($list_data['total_file'] > 0)
		{
			echo '
					<span class="attach" title="첨부파일">', number_format($list_data['total_file']), '</span>';
		}
		if ($list_data['total_comment'] > 0)
		{
			echo '
					<span class="cmt" title="코멘트">', number_format($list_data['total_comment']), '</span>';
		}

		if ($read_work > 0)
		{
			echo '
					<span class="today_num" title="읽을 코멘트"><em>', number_format($read_work), '</em></span>';
		}
	?>
				</div>
			</td>
			<td><span class="num"><?=$list_data['receipt_name'];?></span></td>
			<td><?=$list_data['receipt_status_str'];?></td>
			<td><?=$list_data['member_str'];?></td>
			<td>
				<span class="num"><?=date_replace($list_data['reg_date'], 'Y.m.d');?><?=$list_data['end_date_str'];?></span>
			</td>
			<td>
				<?=$list_data['link_html'];?>
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