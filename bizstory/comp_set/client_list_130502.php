<?
/*
	수정 : 2013.03.37
	위치 : 설정폴더 > 거래처관리 > 거래처등록/수정 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp    = $_SESSION[$sess_str . '_comp_idx'];
	$code_part    = search_company_part($code_part);
	$set_client_cnt = $comp_set_data['client_cnt'];
	$set_agent_yn   = $comp_set_data['agent_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	if ($shgroup != '' && $shgroup != 'all') // 거래처분류
	{
		$where .= " and (concat(ccg.up_ccg_idx, ',') like '%" . $shgroup . ",%' or ci.ccg_idx = '" . $shgroup . "')";
	}
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = client_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
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
		$page_where = " and ci.comp_idx = '" . $code_comp . "'";
		$page_data = client_info_data('page', $page_where);
		if ($page_data['total_num'] >= $set_client_cnt) // 거래처수확인
		{
			$btn_write = '<a href="javascript:void(0);" onclick="alert(\'더이상 등록할 수 없습니다.\')" class="btn_big_green"><span>등록</span></a>';
		}
		else
		{
			$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
		}
	}

// 월유지보수 합계
	$con_where = " and con.comp_idx = '" . $code_comp . "' and con.part_idx = '" . $code_part . "'";
	$con_list = contract_info_data('list', $con_where, '', '', '');
	$total_contract_price = 0;
	$total_month_price = 0;
	$total_price = 0;
	foreach ($con_list as $con_k => $con_data)
	{
		if (is_array($con_data))
		{
		// 계약금액
			if ($con_data['con_price_chk'] == 'Y')
			{
				$total_contract_price += $con_data['con_price'];
			}
		// 월유지보수금액
			if ($con_data['month_price_chk'] == 'Y')
			{
				$total_month_price += $con_data['month_price'];
			}
		// 유지보수금액
			if ($con_data['month_price'] > 0)
			{
				$total_price += $con_data['con_price'];
			}
		}
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom">
	<strong class="etc_info">계약금액 : <?=number_format($total_contract_price);?> 원</strong>,
	<strong class="etc_info">유지보수 : <?=number_format($total_price);?> 원</strong>,
	<strong class="etc_info">월유지보수 : <?=number_format($total_month_price);?> 원</strong>
	<?=$btn_write;?>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="100px" />
		<col />
		<col width="70px" />
		<col width="100px" />
		<col width="145px" />
		<col width="45px" />
		<col width="55px" />
		<col width="45px" />
		<col width="45px" />
		<col width="45px" />
		<col width="55px" />
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="ciidx" onclick="check_all('ciidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처코드', 'ci.client_code');?></h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('담당자', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('그룹', 'ccg.sort');?></h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3>사용</h3></th>
			<th class="nosort"><h3>IP차단</h3></th>
			<th class="nosort"><h3>SMS</h3></th>
			<th class="nosort"><h3>email</h3></th>
			<th class="nosort"><h3>push</h3></th>
			<th class="nosort"><h3>
				<img src="bizstory/images/icon/receipt.gif" alt="접수페이지로 이동합니다." />
				<img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." />
			</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="15">등록된 데이타가 없습니다.</td>
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
				$ci_idx      = $data['ci_idx'];
				$user_link   = $local_dir . '/bizstory/comp_set/client_user.php';
				$report_link = $local_dir . '/bizstory/comp_set/client_report.php';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $ci_idx . "', '" . $data["view_yn"] . "')";
					$btn_ip     = "check_code_data('check_yn', 'ip_yn', '" . $ci_idx . "', '" . $data["ip_yn"] . "')";
					$btn_sms    = "check_code_data('check_yn', 'receipt_sms_yn', '" . $ci_idx . "', '" . $data["receipt_sms_yn"] . "')";
					$btn_email  = "check_code_data('check_yn', 'receipt_email_yn', '" . $ci_idx . "', '" . $data["receipt_email_yn"] . "')";
					$btn_push   = "check_code_data('check_yn', 'receipt_push_yn', '" . $ci_idx . "', '" . $data["receipt_push_yn"] . "')";
					$btn_modify = "open_data_form('" . $ci_idx . "')";
				}
				else
				{
					$btn_view   = "check_auth('modify')";
					$btn_ip     = "check_auth('modify')";
					$btn_sms    = "check_auth('modify')";
					$btn_email  = "check_auth('modify')";
					$btn_push   = "check_auth('modify')";
					$btn_modify = "check_auth('modify')";
				}
				if ($auth_menu['down'] == "Y") $btn_report = "popupsub_open('" . $ci_idx . "', '" . $report_link . "')";
				else $btn_report = "check_auth('modify')";

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $ci_idx . "')";
				else $btn_delete = "check_auth('delete');";

			// 연락처
				$charge_info = $data['charge_info'];
				$charge_info_arr = explode('||', $charge_info);
				$info_str = explode('/', $charge_info_arr[0]);

				if ($data['tel_num'] != '--' && $data['tel_num'] != '-' && $data['tel_num'] != '') $tel_num_str = '<br /><span class="eng">(' . $data['tel_num'] . ')</span>';
				else $tel_num_str = '';

				if ($data['client_email'] != '@' && $data['client_email'] != '') $client_email_str = '<br /><span class="eng">' . $data['client_email'] . '</span>';
				else $client_email_str = '';

			// 링크주소
				$link_url = $data['link_url'];
				$link_url_arr = explode(',', $link_url);
				if ($link_url_arr[0] != '')
				{
					$link_string = str_replace('http://', '', $link_url_arr[0]);
					$link_html = '<a href="http://' . $link_string . '" target="_blank"><img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></a>';
				}
				else
				{
					$link_html = '';
				}

			// 거래처그룹 2단계까지만
				$group_view = client_group_view($data['ccg_idx']);
				$group_name = $group_view['group_level1'];
				if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];

			// 사용자수
				$user_where = " and cu.ci_idx = '" . $data['ci_idx'] . "'";
				$user_page = client_user_data('page', $user_where);
				$total_user = $user_page['total_num'];

			// 계약수
				$con_where = " and con.ci_idx = '" . $data['ci_idx'] . "'";
				$con_page = contract_info_data('page', $con_where);
				$total_con = $con_page['total_num'];

			// 메모수
				$sub_where = " and cim.ci_idx='" . $data['ci_idx'] . "'";
				$sub_data = client_memo_data('page', $sub_where);
				$data['total_memo'] = $sub_data['total_num'];
?>
		<tr>
			<td><input type="checkbox" id="ciidx_<?=$i;?>" name="chk_ci_idx[]" value="<?=$data["ci_idx"];?>" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><span class="num"><?=$data['client_code'];?></span></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_modify;?>"><?=$data['client_name'];?></a>
	<?
		if ($total_user > 0)
		{
			echo '
					<span class="client_user" title="사용자">', number_format($total_user), '</span>';
		}
		if ($total_con > 0)
		{
			echo '
					<span class="client_con" title="계약">', number_format($total_con), '</span>';
		}
		if ($data['total_memo'] > 0)
		{
			echo '
					<span class="cmt" title="메모">', number_format($data['total_memo']), '</span>';
		}
	?>
				</div>
			</td>
			<td><?=$data['mem_name'];?></td>
			<td><?=$group_name;?></td>
			<td><?=$info_str[0];?> <?=$tel_num_str;?> <?=$client_email_str;?></td>
			<td><img src="bizstory/images/icon/<?=$data['view_yn'];?>.gif" alt="<?=$data['view_yn'];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data['ip_yn'];?>.gif" alt="<?=$data['ip_yn'];?>" class="pointer" onclick="<?=$btn_ip;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data['receipt_sms_yn'];?>.gif" alt="<?=$data['receipt_sms_yn'];?>" class="pointer" onclick="<?=$btn_sms;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data['receipt_email_yn'];?>.gif" alt="<?=$data['receipt_email_yn'];?>" class="pointer" onclick="<?=$btn_email;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data['receipt_push_yn'];?>.gif" alt="<?=$data['receipt_push_yn'];?>" class="pointer" onclick="<?=$btn_push;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="client_receipt_move('<?=$data['ci_idx'];?>')"><img src="bizstory/images/icon/receipt.gif" alt="접수페이지로 이동합니다." /></a>
				<?=$link_html;?>
			</td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
<?
	if (($set_agent_yn == 'Y') && ($auth_menu['down'] == "Y"))
	{
	// 파일 존재여부 확인
		$file_path = $local_path . '/agent/data/' . $data['comp_idx'] . '/BizstorySetup_' . $data['client_code'] . '.exe';
		$file_dir = $local_dir . '/bizstory/comp_set/client_agent_download.php?client_idx=' . $data['ci_idx'];

		if (file_exists($file_path) == true)
		{
?>
				<br /><a href="<?=$file_dir;?>" class="btn_con_violet"><span>에이전트</span></a>
<?
		}
		else
		{
			$error_msg = '파일이 존재하지 않습니다.<br />요청해주세요.';
?>
				<br /><a href="javascript:void(0)" onclick="check_auth_popup('<?=$error_msg;?>');" class="btn_con_violet"><span>에이전트</span></a>
<?
		}
?>
<? } ?>
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
//------------------------------------ 거래처사용자목록
	function user_list(ci_idx)
	{
		$('#list_ci_idx').val(ci_idx);
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/comp_set/client_user.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 500;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 거래처사용자등록
	function user_form(ci_idx, idx)
	{
		$('#list_ci_idx').val(ci_idx);
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/comp_set/client_user_form.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				var maskHeight = $(document).height() + 500;
				var maskWidth  = $(window).width();
				$("#data_form").slideDown("slow");
				$("#loading").fadeIn('slow').fadeOut('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':maskWidth,'height':maskHeight}).fadeIn("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 삭제하기
	function check_form_delete(ci_idx, idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$('#other_sub_type').val('delete');
			$('#other_idx').val(idx);
			$.ajax({
				type: "post", dataType: 'json', url: link_ok,
				data: $('#otherform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						user_list(ci_idx);
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}

//------------------------------------ 목록 처리
	function check_form_code(sub_type, sub_action, ci_idx, idx, post_value)
	{
		$('#list_sub_type').val(sub_type)
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);
		$('#list_ci_idx').val(ci_idx);

		$.ajax({
			type: "post", dataType: 'json', url: link_ok,
			data: $('#listform').serialize(),
			success: function(msg) {
				if (msg.success_chk == "Y") user_list(ci_idx);
				else check_auth_popup(msg.error_string);
			}
		});
	}
//]]>
</script>