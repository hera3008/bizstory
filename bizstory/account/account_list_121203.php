<?
/*
	수정 : 2012.11.22
	위치 : 회계업무 > 운영비관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	$set_part_yn = $company_set_data['part_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ai.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and ai.part_idx = '" . $code_part . "'";
	if ($astype != '' && $astype != 'all') $where .= " and ai.account_type = '" . $astype . "'";
	if ($asgubun != '' && $asgubun != 'all') $where .= " and ai.gubun_code = '" . $asgubun . "'";
	if ($asclass != '' && $asclass != 'all') $where .= " and ai.class_code = '" . $asclass . "'";
	if ($asbank != '' && $asbank != 'all') $where .= " and ai.bank_code = '" . $asbank . "'";
	if ($ascard != '' && $ascard != 'all') $where .= " and ai.card_code = '" . $ascard . "'";
	if ($asclient != '' && $asclient != 'all') $where .= " and ai.ci_idx = '" . $asclient . "'";
	if ($assdate != '') $where .= " and date_format(ai.account_date, '%Y-%m-%d') >= '" . $assdate . "'";
	if ($asedate != '') $where .= " and date_format(ai.account_date, '%Y-%m-%d') <= '" . $asedate . "'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ai.account_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$list = account_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;assdate=' . $send_assdate . '&amp;asedate=' . $send_asedate;
	$f_search  = $f_search . '&amp;astype=' . $send_astype . '&amp;asgubun=' . $send_asgubun . '&amp;asclass=' . $send_asclass . '&amp;asbank=' . $send_asbank . '&amp;ascard=' . $send_ascards . '&amp;asclient=' . $send_asclient;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="assdate"  value="' . $send_assdate . '" />
		<input type="hidden" name="asedate"  value="' . $send_asedate . '" />
		<input type="hidden" name="astype"   value="' . $send_astype . '" />
		<input type="hidden" name="asgubun"  value="' . $send_asgubun . '" />
		<input type="hidden" name="asclass"  value="' . $send_asclass . '" />
		<input type="hidden" name="asbank"   value="' . $send_asbank . '" />
		<input type="hidden" name="ascard"   value="' . $send_ascard . '" />
		<input type="hidden" name="asclient" value="' . $send_asclient . '" />
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
		$btn_write  = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big fr"><span>등록</span></a>';
		$btn_write2 = '<a href="javascript:void(0);" onclick="open_data_upload(\'\')" class="btn_big fr"><span>일괄등록</span></a>';
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom">
		<?=$btn_write2;?>
		<?=$btn_write;?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="60px" />
		<col width="80px" />
		<col width="80px" />
		<col width="150px" />
		<col width="80px" />
		<col />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="aiidx" onclick="check_all('aiidx', this);" /></th>
			<th class="nosort"><h3>종류</h3></th>
			<th class="nosort"><h3><?=field_sort('날짜', 'ai.account_date');?></h3></th>
			<th class="nosort"><h3>구분</h3></th>
			<th class="nosort"><h3><?=field_sort('계정', 'code1.sort');?></h3></th>
			<th class="nosort"><h3><?=field_sort('금액', 'ai.account_price');?></h3></th>
			<th class="nosort"><h3>적요</h3></th>
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
				$ai_idx = $data['ai_idx'];

				if ($auth_menu['mod'] == "Y")
				{
					$btn_modify = "open_data_form('" . $ai_idx . "')";
				}
				else
				{
					$btn_modify = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $ai_idx . "')";
				else $btn_delete = "check_auth_popup('delete');";

				$add_content = '';
				if ($data['gubun_code'] == 'card') // 카드일 경우
				{
					if ($data['card_code_name'] != '')
					{
						$add_content = '<span class="eng" style="';
						if ($data['card_code_bold'] == 'Y') $add_content .= 'font-weight:900;';
						if ($data['card_code_color'] != '') $add_content .= 'color:' . $data['card_code_color'] . ';';
						$add_content .= '">추가내용-' . $data['card_code_name'] . '(' . $data['card_mem_name'] . ': ' . $data['card_num'] . ')</span>';
					}
				}
				else if ($data['gubun_code'] == 'bank') // 계좌이체일 경우
				{
					if ($data['bank_code_name'] != '')
					{
						$add_content = '<span class="eng" style="';
						if ($data['bank_code_bold'] == 'Y') $add_content .= 'font-weight:900;';
						if ($data['bank_code_color'] != '') $add_content .= 'color:' . $data['bank_code_color'] . ';';
						$add_content .= '">추가내용-' . $data['bank_code_name'] . '(' . $data['bank_num'] . ')</span>';
					}
				}

			// 구분
				$gubun_code_name = '<span style="';
				if ($data['class_code_bold'] == 'Y') $gubun_code_name .= 'font-weight:900;';
				if ($data['class_code_color'] != '') $gubun_code_name .= 'color:' . $data['class_code_color'] . ';';
				$gubun_code_name .= '">' . $data["gubun_code_name"] . '</span>';
?>
		<tr>
			<td><input type="checkbox" id="aiidx_<?=$i;?>" name="chk_ai_idx[]" value="<?=$data["ai_idx"];?>" /></td>
			<td><?=$set_account_type[$data['account_type']];?></td>
			<td><span class="eng"><?=$data['account_date'];?></span></td>
			<td><?=$gubun_code_name;?></td>
			<td><?=$data['class_code_name'];?>(<?=$data['class_code_value'];?>)</td>
			<td><span class="eng right"><?=number_format($data['account_price']);?></span></td>
			<td><div class="left"><?=$data['content'];?> <?=$add_content;?></div></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con"><span>삭제</span></a>
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
//------------------------------------ 파일업로드
	function open_data_upload(idx)
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/account/account_upload.php',
			data: '',
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500 );
				$("#data_view").slideUp("slow");
				$("#data_view").slideDown("slow");
				$("#data_view").html(msg);
				$('#data_list').html('');
			}
		});
	}
//]]>
</script>
