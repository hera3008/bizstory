<?
/*
	생성 : 2012.09.05
	수정 : 2013.03.27
	위치 : 설정폴더 > 거래처관리 > 삭제거래처 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$ci_idx     = $idx;
	$set_tax_yn = $comp_set_data['tax_yn'];

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
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$form_chk = 'N';
	if ($auth_menu['view'] == 'Y') // 보기권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>';
		exit;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		$where = " and ci.ci_idx = '" . $ci_idx . "'";
		$data = client_info_data("view", $where, '', '', '', 2);

		$address = $data['address'];
		$data['address'] = str_replace('||', ' ', $address);

		$tax_address = $data['tax_address'];
		$data['tax_address'] = str_replace('||', ' ', $tax_address);

		$link_url = $data['link_url'];
		$link_url_arr = explode(',', $link_url);

		$charge_info = $data['charge_info'];
		$charge_info_arr = explode('||', $charge_info);

		$agent_type = $data['agent_type'];
		$agent_type_arr = explode(',', $agent_type);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<fieldset>
			<legend class="blind">거래처정보 상세보기</legend>

			<div class="sub_frame"><h4>거래처정보</h4></div>
			<table class="tinytable view" summary="거래처정보 상세보기입니다.">
			<caption>거래처정보</caption>
			<colgroup>
				<col width="100px" />
				<col width="250px" />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>지사</th>
					<td><div class="left"><?=$data['part_name'];?></div></td>
					<th>담당직원</th>
					<td><div class="left"><?=$data['mem_name'];?></div></td>
				</tr>
				<tr>
					<th>거래처명</th>
					<td><div class="left"><strong><?=$data['client_name'];?></strong></div></td>
					<th>거래처분류</th>
					<td><div class="left"><?=$data['group_name'];?></div></td>
				</tr>
				<tr>
					<th>담당자</th>
					<td colspan="3">
						<div class="left">
				<?
					if (is_array($charge_info_arr))
					{
						$total_len = count($charge_info_arr);
						foreach ($charge_info_arr as $arr_k => $arr_v)
						{
							$info_str = explode('/', $arr_v);
							echo '담당자명 : ', $info_str[0], ', 연락처 : ', $info_str[1], ', 메일주소 : ', $info_str[2], '<br />';
						}
					}
				?>
						</div>
					</td>
				</tr>
				<tr>
					<th>팩스번호</th>
					<td colspan="3"><div class="left"><?=$data['fax_num'];?></div></td>
				</tr>
				<tr>
					<th>주소</th>
					<td colspan="3"><div class="left">[<?=$data['zip_code'];?>] <?=$data['address'];?></div></td>
				</tr>
				<tr>
					<th>링크주소</th>
					<td colspan="3">
						<div class="left">
				<?
					if (is_array($link_url_arr))
					{
						foreach ($link_url_arr as $arr_k => $arr_v)
						{
							$arr_v = str_replace('http://', '', $arr_v);
							if ($arr_k > 0)
							{
								echo ', ';
							}
							echo '<a href="http://', $arr_v, '" target="_blank">', $arr_v, '</a>';
						}
					}
				?>
						</div>
					</td>
				</tr>
				<tr>
					<th>IP차단여부</th>
					<td><div class="left"><?=$data["ip_yn"];?></div></td>
					<th>IP허용</th>
					<td><div class="left"><?=$data['ip_info'];?></div></td>
				</tr>
				<tr>
					<th>접속정보</th>
					<td colspan="3"><div class="left"><?=nl2br($data['memo1']);?></div></td>
				</tr>
				<tr>
					<th>메모</th>
					<td colspan="3"><div class="left"><?=nl2br($data['remark']);?></div></td>
				</tr>
				<tr>
					<th>사용여부</th>
					<td colspan="3"><div class="left"><?=$data["view_yn"];?></div></td>
				</tr>
				<tr>
					<th>접수 SMS</th>
					<td><div class="left"><?=$data['receipt_sms_yn'];?></div></td>
					<th>접수 Email</th>
					<td><div class="left"><?=$data['receipt_email_yn'];?></div></td>
				</tr>
				<tr>
					<th>접수 Push</th>
					<td><div class="left"><?=$data['receipt_push_yn'];?></div></td>
					<th>에이전트타입</th>
					<td><div class="left"><?=$set_agent_type[$data['agent_type']];?></div></td>
				</tr>
				<tr>
					<th><label for="post_report_type">점검보고서타입</label></th>
					<td>
						<div class="left">
					<?
						foreach ($agent_type_arr as $arr_k => $arr_v)
						{
							if ($arr_k > 0)
							{
								echo ', ';
							}
							echo $set_agent_type[$arr_v];
						}
					?>
							<?=$agent_string;?>
						</div>
					</td>
					<th><label for="post_view_yn">사용여부</label></th>
					<td>
						<div class="left">
							<?=$data['view_yn'];?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>
<?
	if ($set_tax_yn == 'Y') {
?>
			<div class="sub_frame"><h4>세금계산서정보</h4></div>
			<table class="tinytable view" summary="세금계산서 상세정보입니다.">
			<caption>세금계산서정보</caption>
			<colgroup>
				<col width="140px" />
				<col />
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>상호명</th>
					<td colspan="3"><div class="left"><?=$data['tax_comp_name'];?></div></td>
				</tr>
				<tr>
					<th>대표자명</th>
					<td><div class="left"><?=$data['tax_boss_name'];?></div></td>
					<th>사업자등록번호</th>
					<td colspan="3"><div class="left"><?=$data['tax_comp_num'];?></div></td>
				</tr>
				<tr>
					<th>업종</th>
					<td><div class="left"><?=$data['tax_upjong'];?></div></td>
					<th>업태</th>
					<td><div class="left"><?=$data['tax_uptae'];?></div></td>
				</tr>
				<tr>
					<th>사업장주소</th>
					<td colspan="3">
						<div class="left"><?=$data['tax_zip_code'];?></div>
						<div class="left mt"><?=$data['tax_address'];?></div>
					</td>
				</tr>
				<tr>
					<th>전자계산서 담당자메일</th>
					<td colspan="3"><div class="left"><?=$data['tax_email'];?></div></td>
				</tr>
			</tbody>
			</table>
<?
	}
?>
		</fieldset>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 사용자
	$client_user_where = " and cu.comp_idx = '" . $code_comp . "' and cu.ci_idx = '" . $ci_idx . "'";
	$client_user_list = client_user_data('page', $client_user_where);
?>
		<div id="task_cuser" class="report_box">
			<div class="report_top">
				<p class="count">
					<a id="cuser_gate" class="btn_i_plus" title="사용자목록" onclick="cuser_view()"></a> 사용자정보 <span id="cuser_total_value">[<?=number_format($client_user_list['total_num']);?>]</span>
				</p>
			</div>

			<form id="cuserlistform" name="cuserlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="cuserlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="cuserlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="cuserlist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="cuserlist_cu_idx"    name="cu_idx" />
				<?=$form_page;?>
				<div id="cuser_list_data"></div>
			</form>
		</div>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 계약
	$contract_where = " and con.comp_idx = '" . $code_comp . "' and con.ci_idx = '" . $ci_idx . "'";
	$contract_list = contract_info_data('page', $contract_where);
?>
		<div class="dotted2"></div>
		<div id="task_contract" class="report_box">
			<div class="report_top">
				<p class="count">
					<a id="contract_gate" class="btn_i_plus" title="계약목록" onclick="contract_view()"></a> 계약정보 <span id="contract_total_value">[<?=number_format($contract_list['total_num']);?>]</span>
				</p>
			</div>

			<form id="contractlistform" name="contractlistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="contractlist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="contractlist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="contractlist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="contractlist_con_idx"   name="con_idx" />
				<?=$form_page;?>
				<div id="contract_list_data"></div>
			</form>
		</div>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 메모
	$memo_where = " and cim.comp_idx = '" . $code_comp . "' and cim.ci_idx = '" . $ci_idx . "'";
	$memo_list = client_memo_data('page', $memo_where);
?>
		<div class="dotted2"></div>
		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_plus" title="메모목록" onclick="memo_view()"></a> 메모 <span id="memo_total_value">[<?=number_format($memo_list['total_num']);?>]</span>
				</p>
			</div>

			<form id="memolistform" name="memolistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="memolist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="memolist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="memolist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="memolist_cim_idx"   name="cim_idx"   value="" />
				<?=$form_page;?>
				<div id="memo_list_data"></div>
			</form>
		</div>

		<div class="section">
			<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
		</div>

	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 사용자 관련
	var cuser_list    = '<?=$local_dir;?>/bizstory/comp_set/client_user.php';
	var cuser_chk_val = 'open';

//------------------------------------ 사용자목록
	function cuser_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: cuser_list,
			data: $('#cuserlistform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success: function(msg) {
				$('#cuser_list_data').html(msg);
			}
		});
	}

//------------------------------------ 사용자 열기/닫기
	function cuser_view()
	{
		if (cuser_chk_val == 'close')
		{
			cuser_chk_val = 'open';
			$('#cuser_list_data').html('');
			$("#cuser_gate").removeClass('btn_i_minus');
			$("#cuser_gate").addClass('btn_i_plus');
		}
		else
		{
			cuser_chk_val = 'close';
			cuser_list_data();
			$("#cuser_gate").removeClass('btn_i_plus');
			$("#cuser_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 계약 관련
	var contract_list    = '<?=$local_dir;?>/bizstory/comp_set/client_contract.php';
	var contract_viewl   = '<?=$local_dir;?>/bizstory/comp_set/client_contract_view.php';
	var contract_chk_val = 'open';

//------------------------------------ 계약목록
	function contract_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: contract_list,
			data: $('#contractlistform').serialize(),
			beforeSubmit: function(){
				$("#loading").fadeIn('slow').fadeOut('slow');
			},
			success: function(msg) {
				$('#contract_list_data').html(msg);
			}
		});
	}

//------------------------------------ 계약 열기/닫기
	function contract_view()
	{
		if (contract_chk_val == 'close')
		{
			contract_chk_val = 'open';
			$('#contract_list_data').html('');
			$("#contract_gate").removeClass('btn_i_minus');
			$("#contract_gate").addClass('btn_i_plus');
		}
		else
		{
			contract_chk_val = 'close';
			contract_list_data();
			$("#contract_gate").removeClass('btn_i_plus');
			$("#contract_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 메모 관련
	var memo_list    = '<?=$local_dir;?>/bizstory/work/client_view_memo_list.php';
	var memo_chk_val = 'open';

//------------------------------------ 메모 열기/닫기
	function memo_view()
	{
		if (memo_chk_val == 'close')
		{
			memo_chk_val = 'open';
			$('#memo_list_data').html('');
			$("#comment_gate").removeClass('btn_i_minus');
			$("#comment_gate").addClass('btn_i_plus');
		}
		else
		{
			memo_chk_val = 'close';
			memo_list_data();
			$("#comment_gate").removeClass('btn_i_plus');
			$("#comment_gate").addClass('btn_i_minus');
		}
	}

//------------------------------------ 메모 목록
	function memo_list_data()
	{
		$.ajax({
			type: "get", dataType: 'html', url: memo_list,
			data: $('#memolistform').serialize(),
			success: function(msg) {
				$('#memo_list_data').html(msg);
			}
		});
	}
//]]>
</script>
<?
	}
?>