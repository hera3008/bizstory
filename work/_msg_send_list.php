<?
/*
	수정 : 2013.04.03
	위치 : 업무관리 > 나의 업무 > 쪽지 > 보낸쪽지 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "and ms.comp_idx = '" . $code_comp . "' and ms.mem_idx = '" . $code_mem . "' and ms.send_save = 'Y' and ms.send_del = 'N'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = message_send_data('list', $where, '', $page_num, $page_size);
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

	if ($auth_menu['del'] == "Y") // 삭제버튼
	{
		$btn_delete = '<a href="javascript:void(0);" onclick="select_delete()" class="btn_big_red"><span>선택삭제</span></a>';
	}
	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big_green"><span>쪽지작성</span></a>';
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<?=$btn_delete;?>
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="130px" />
		<col />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="msidx" onclick="check_all('msidx', this);" /></th>
			<th class="nosort"><h3>받는사람</h3></th>
			<th class="nosort"><h3>내용</h3></th>
			<th class="nosort"><h3>보낸일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="4">등록된 데이타가 없습니다.</td>
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
				$remark = strip_tags($data["remark"]);
				$remark = string_cut($remark, 70);

				$total_receive = '';
				$receive_where = " and mr.ms_idx = '" . $data["ms_idx"] . "'";
				$receive_list = message_receive_data('list', $receive_where, 'mr.mem_name asc', '', '', 2);

				$total_receive = '';
				foreach ($receive_list as $receive_k => $receive_data)
				{
					if (is_array($receive_data))
					{
						if ($receive_k == 0)
						{
							$charge_str = staff_layer_form($receive_data['mem_idx'], '', 'N', $set_color_list2, 'msgsendlist', $data['ms_idx'], '');

							$total_receive .= $charge_str;

						}
					}
				}
				if ($receive_list['total_num'] > 1)
				{
					$remain_person = $receive_list['total_num'] - 1;
					$total_receive .= ' 외 ' . $remain_person . '명';
				}

			// 첨부파일
				$file_where = " and msgf.ms_idx = '" . $data["ms_idx"] . "'";
				$file_list = message_file_data('page', $file_where);
				$total_file = $file_list['total_num'];
				if ($total_file > 0) $file_str = '<span class="attach" title="첨부파일">' . number_format($total_file) . '</span>';
				else $file_str = '';
?>
		<tr>
			<td><input type="checkbox" id="msidx_<?=$i;?>" name="chk_ms_idx[]" value="<?=$data["ms_idx"];?>" /></td>
			<td><?=$total_receive;?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="view_open('<?=$data["ms_idx"];?>')"><?=$remark;?></a>
					<?=$file_str;?>
				</div>
			</td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y.m.d');?></span></td>
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
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 선택삭제
	function select_delete()
	{
		var chk_num = chk_checkbox_num('msidx');
		if (chk_num == 0)
		{
			check_auth_popup('삭제할 데이타를 선택하세요.');
		}
		else
		{
			if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
			{
				$('#list_sub_type').val('delete_send_select');

				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: 'post', dataType: 'json', url: link_ok,
					data: $('#listform').serialize(),
					success: function(msg) {
						if (msg.success_chk == "Y")
						{
							view_open();
							list_data();
						}
						else
						{
							$("#loading").fadeOut('slow');
							$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
							check_auth_popup(msg.error_string);
						}
					}
				});
			}
		}
	}
//]]>
</script>