<?
/*
	수정 : 2012.04.27
	위치 : 업무폴더 > 나의 업무 > 쪽지 > 받은쪽지 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "and mr.comp_idx = '" . $code_comp . "' and mr.mem_idx = '" . $code_mem . "' and mr.recv_keep = 'N'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = message_receive_data('list', $where, '', $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big fr"><span>쪽지작성</span></a>';
	}
?>
<div class="info_text">
	<ul>
		<li>왼쪽 직원을 선택하면 오른쪽 선택한 쪽지를 볼수 있습니다.</li>
	</ul>
</div>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom"><?=$btn_write;?></div>
</div>
<hr />

<div class="message_area">
	<div class="message_list">
		<table class="tinytable">
			<colgroup>
				<col width="30px"/>
				<col />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"></th>
					<th class="nosort"><h3>직원</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$sub_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
	$part_list = company_part_data('list', $sub_where, '', '', '');
	foreach ($part_list as $part_k => $part_data)
	{
		if (is_array($part_data))
		{
?>
				<tr>
					<td>&nbsp;</td>
					<td><div class="left"><strong><?=$part_data['part_name'];?></strong></div></td>
				</tr>
<?
			$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
			$mem_list = member_info_data('list', $sub_where2, 'mem.mem_name', '', '');
			if ($mem_list['total_num'] > 0)
			{
				foreach ($mem_list as $mem_k => $mem_data)
				{
					if (is_array($mem_data))
					{
?>
				<tr>
					<td>&nbsp;</td>
					<td><div class="left">&nbsp;&nbsp;&nbsp;&nbsp;<a href=""><?=$mem_data['mem_name'];?></a></div></td>
				</tr>
<?
					}
				}
			}
		}
	}
?>
			</tbody>
		</table>
	</div>

	<div class="message_comment">
		<div class="wrap_comment" id="msg_data_list"></div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 목록
	function msg_list_data()
	{
		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/work/msg_receive_list_list.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				$('#msg_data_list').html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}
	msg_list_data();
//]]>
</script>