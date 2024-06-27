<?
/*
	생성 : 2012.04.09
	수정 : 2013.04.01
	위치 : 고객관리 > 점검보고서 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and rr.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and rr.part_idx = '" . $code_part . "'";
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'rr.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = receipt_report_data('list', $where, $orderby, $page_num, $page_size);
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
<div class="etc_bottom"><?=$btn_write;?></div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="170px" />
		<col />
		<col width="150px" />
		<col width="80px" />
		<col width="70px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="rridx" onclick="check_all('rridx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'rr.client_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'rr.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('기간', 'rr.receipt_sdate');?></h3></th>
			<th class="nosort"><h3>등록일</h3></th>
			<th class="nosort"><h3>보고서</h3></th>
			<th class="nosort"><h3>관리</h3></th>
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
				$print_url = $local_dir . "/bizstory/work/client_report_list_print.php"; // 인쇄
				$down_url  = $local_dir . "/bizstory/work/client_report_list_pdf.php"; // PDF문서

				if ($auth_menu['print'] == "Y") $btn_print = "open_list_print('" . $data["rr_idx"] . "', '" . $print_url . "')";
				else $btn_print = "check_auth_popup('print')";

				if ($auth_menu['down'] == "Y") $btn_down = "open_data_down('" . $data["rr_idx"] . "', '" . $down_url . "')";
				else $btn_down = "check_auth_popup('down')";

				if ($auth_menu['mod'] == "Y") $btn_modify = "open_data_form('" . $data["rr_idx"] . "')";
				else $btn_modify = "check_auth_popup('modify')";

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data["rr_idx"] . "')";
				else $btn_delete = "check_auth_popup('delete');";

				$receipt_date = date_replace($data['receipt_date'], 'Y.m');
?>
		<tr>
			<td><input type="checkbox" id="rridx_<?=$i;?>" name="chk_rr_idx[]" value="<?=$data["rr_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$data['client_name'];?></td>
			<td><div class="left">(<?=$receipt_date;?>) <?=$data['subject'];?></div></td>
			<td>
				<span class="num">
					<?=date_replace($data['receipt_sdate'], 'Y.m.d');?>
					~
					<?=date_replace($data['receipt_edate'], 'Y.m.d');?>
				</span>
			</td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y.m.d');?></span></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_print;?>" class="btn_con_violet"><span>인쇄</span></a>
				<!--// <a href="javascript:void(0);" onclick="<?=$btn_down;?>" class="btn_con_violet"><span>PDF</span></a> //-->
			</td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
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

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 팝업열기
	function popup_open(idx)
	{
		$('#list_idx').val(idx);

		f = document.listform;
		f.target = 'view';
		f.action = link_view;
		window.open('', 'view', 'width=800, height=600, toolbar=no, top=30, left=30, resizable=yes, scrollbars=yes');
		f.submit();
	}
//]]>
</script>