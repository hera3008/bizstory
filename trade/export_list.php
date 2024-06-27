<?
/*
	생성 : 2012.11.21
	위치 : 무역업무 > 수출신고 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp   = $_SESSION[$sess_str . '_comp_idx'];
	$code_part   = search_company_part($code_part);
	$code_mem    = $_SESSION[$sess_str . '_mem_idx'];
	$set_part_yn = $company_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = $add_where . " and ei.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ei.part_idx = '" . $code_part . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ei.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = export_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
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
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col />
		<col width="100px" />
		<col width="110px" />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="100px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="eiidx" onclick="check_all('eiidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('신고자상호', 'ei.declare_company');?></h3></th>
			<th class="nosort"><h3><?=field_sort('신고자성명', 'ei.declare_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('통관고유부호', 'ei.customs_mark');?></h3></th>
			<th class="nosort"><h3><?=field_sort('수출자구분', 'ei.export_section');?></h3></th>
			<th class="nosort"><h3><?=field_sort('구매자부호', 'ei.buyer_mark');?></h3></th>
			<th class="nosort"><h3><?=field_sort('신고구분', 'ei.report_section');?></h3></th>
			<th class="nosort"><h3><?=field_sort('거래구분', 'ei.deal_section');?></h3></th>
			<th class="nosort"><h3><?=field_sort('등록일', 'ei.reg_date');?></h3></th>
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
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["ei_idx"] . "')";
				else $btn_view = "check_auth_popup('view')";
?>
		<tr>
			<td><input type="checkbox" id="eiidx_<?=$i;?>" name="chk_ei_idx[]" value="<?=$data["ei_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><div class="left"><a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$data['declare_company'];?></a></div></td>
			<td><?=$data['declare_name'];?></td>
			<td><?=$data['customs_mark'];?></td>
			<td><?=$data['export_section'];?></td>
			<td><?=$data['buyer_mark'];?></td>
			<td><?=$data['report_section'];?></td>
			<td><?=$data['deal_section'];?></td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y-m-d');?></span></td>
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