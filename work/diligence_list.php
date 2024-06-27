<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	if ($sdate == '')
	{
		$sdate = date('Ymd');
		$send_sdate = $sdate;
		$recv_sdate = $sdate;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $code_part . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.reg_date';
	if ($sorder2 == '') $sorder2 = 'asc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = member_info_data('list', $where, $orderby, '', '');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$syear  = substr($sdate, 0, 4);
	$smonth = substr($sdate, 4, 2);
	$sday   = substr($sdate, 6, 2);

	$data_date = query_view("
		select
			date_format(date_sub('" . $sdate . "',interval 1 day),'%Y%m%d') as prev_date,
			date_format(date_add('" . $sdate . "',interval 1 day),'%Y%m%d') as next_date
	");
	$p_date = $data_date["prev_date"];
	$n_date = $data_date["next_date"];
?>
<div>
	<div>
		<a href="javascript:void(0)" onclick="$('#list_sdate').val('<?=$p_date;?>'); list_data()">&#x2190; </a>
		<strong><?=$syear;?>년 <?=$smonth;?>월 <?=$sday;?>일</strong>
		<a href="javascript:void(0)" onclick="$('#list_sdate').val('<?=$n_date;?>'); list_data()"> &#x2192;</a>
	</div>
	<div>
		<span>지금 시각</span><span id="clock_content"></span>
<?
	if ($sdate == date('Ymd') && $code_mem != '')
	{
	// 출근(지각)확인
		$start_where = " and di.comp_idx = '" . $code_comp . "' and di.mem_idx = '" . $code_mem . "' and di.start_date = '" . $sdate . "' and (di.dili_status = '11' or di.dili_status = '31')";
		$start_data = diligence_info_data('view', $start_where);

	// 퇴근확인
		$end_where = " and di.comp_idx = '" . $code_comp . "' and di.mem_idx = '" . $code_mem . "' and di.start_date = '" . $sdate . "' and di.dili_status = '21'";
		$end_data = diligence_info_data('view', $end_where);

	// 오늘일 경우만 출근, 퇴근
		if ($start_data['total_num'] == 0)
		{
?>
			<a href="javascript:void(0);" class="btn_big fr" onclick="form_open('<?=$sdate;?>', 'start')"><span>출근확인</span></a>
<?
		}
		else if ($end_data['total_num'] == 0)
		{
?>
			<a href="javascript:void(0);" class="btn_big fr" onclick="form_open('<?=$sdate;?>', 'end')"><span>퇴근확인</span></a>
<?
		}
	}
?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col />
		<col width="120px" />
		<col width="120px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3><?=field_sort('이름', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3>출근시간</h3></th>
			<th class="nosort"><h3>퇴근시간</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="3">등록된 데이타가 없습니다.</td>
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
				$dili_where = " and di.comp_idx = '" . $code_comp . "' and di.mem_idx = '" . $data['mem_idx'] . "' and di.start_date = '" . $sdate . "'";
				$start_where = $dili_where . " and (di.dili_status = '11' or di.dili_status = '31')";
				$start_data = diligence_info_data('view', $start_where);

				$end_where = $dili_where . " and di.dili_status = '21'";
				$end_data = diligence_info_data('view', $end_where);
?>
		<tr>
			<td><?=$data['mem_name'];?></td>
			<td><?=$start_data['start_time'];?></td>
			<td><?=$end_data['start_time'];?></td>
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

<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$('#clock_content').epiclock({format: 'H:i:s'}); // Clock
	});

//------------------------------------ 출근, 퇴근 열기
	function form_open(sdate, dili_status)
	{
		$("#popup_notice_view").hide();
		$('#list_sdate').val(sdate);
		$('#list_dili_status').val(dili_status);
		
		$.ajax({
			type     : "get", dataType : 'html', url : link_form,
			data     : $('#listform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success  : function(msg) {
				var maskHeight = $(document).height() + 500;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('width', "300px");
				$('.popupform').css('top', "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		})
	}
//]]>
</script>