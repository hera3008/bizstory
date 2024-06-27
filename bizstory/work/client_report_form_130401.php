<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
/*
	생성 : 2012.04.09
	수정 : 2013.04.01
	위치 : 고객관리 > 점검보고서 - 등록, 수정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$rr_idx    = $idx;

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y' && $rr_idx == '') // 등록권한
	{
		$form_chk   = 'Y';
		$form_title = '등록';
	}
	else if ($auth_menu['mod'] == 'Y' && $rr_idx != '') // 수정권한
	{
		$form_chk   = 'Y';
		$form_title = '수정';
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
		$where = " and rr.rr_idx = '" . $rr_idx . "'";
		$data = receipt_report_data('view', $where);

		if ($data["part_idx"] == '' || $data["part_idx"] == '0') $data["part_idx"] = $code_part;

		if ($data['receipt_date'] == '') $data['receipt_date'] = date('Y-m');
		$receipt_date = explode('-', $data['receipt_date']);
		$data['receipt_year']  = $receipt_date[0];
		$data['receipt_month'] = $receipt_date[1];

		if ($data['receipt_sdate'] == '') $data['receipt_sdate'] = date('Y-m') . '-01';
		if ($data['receipt_edate'] == '') $data['receipt_edate'] = date('Y-m-d');
		if ($data['report_part'] == '')
		{
			$data['report_part'] = $company_info_data['comp_name']; // 점검자 소속 - 회사명
		}
?>
<div class="info_text">
	<ul>
		<li>기간을 먼저 선택하고 난뒤 거래처를 선택하세요. 기간을 변경하게 되면 거래처를 다시 선택하셔야 합니다.</li>
		<li>거래처를 선택을 하면 해당하는 점검항목과 접수목록이 나옵니다.</li>
		<li>수정은 간단한 인수자정보, 점검자 정보만 가능합니다. 그외 점검항목, 접수목록 등을 수정할 경우 기존 보고서를 삭제를 하고 다시 등록해주세요.</li>
	</ul>
</div>

<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">점검보고서 폼</legend>

				<table class="tinytable write" summary="점검보고서 <?=$form_title;?>합니다.">
				<caption>점검보고서</caption>
				<colgroup>
					<col width="120px" />
					<col />
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_part_idx">지사</label></th>
						<td colspan="3">
							<div class="left">
				<?
					if ($rr_idx == '')
					{
						$str_script = "part_information(this.value, 'client_info', 'post_ci_idx', '" . $data['ci_idx'] . "', '');";
						echo company_part_select($data['part_idx'], ' onchange="' . $str_script . '"');
					}
					else
					{
						echo $data['part_name'];
					}
				?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_receipt_year">보고서구분</label></th>
						<td>
							<div class="left">
				<?
					if ($rr_idx == '')
					{
				?>
								<select id="post_receipt_year" name="param[receipt_year]" onchange="for_check()">
						<?
							for ($i = 2012; $i <= date('Y') + 1; $i++)
							{
						?>
									<option value="<?=$i;?>" <?=selected($data['receipt_year'], $i);?>><?=$i;?></option>
						<?
							}
						?>
								</select> 년
								<select id="post_receipt_month" name="param[receipt_month]" onchange="for_check()">
						<?
							for ($i = 1; $i <=12; $i++)
							{
								$ii = str_pad($i, 2, '0', STR_PAD_LEFT);
						?>
									<option value="<?=$ii;?>" <?=selected($data['receipt_month'], $ii);?>><?=$ii;?></option>
						<?
							}
						?>
								</select>월
				<?
					}
					else
					{
						echo $data['receipt_date'];
					}
				?>
							</div>
						</td>
						<th><label for="post_receipt_sdate">기간</label></th>
						<td>
							<div class="left">
				<?
					if ($rr_idx == '')
					{
				?>
								<input type="text" id="post_receipt_sdate" name="param[receipt_sdate]" class="type_text datepicker" title="시작일 입력하세요." size="10" value="<?=$data['receipt_sdate'];?>" onclick="check_client_info()" />
								~
								<input type="text" id="post_receipt_edate" name="param[receipt_edate]" class="type_text datepicker" title="종료일 입력하세요." size="10" value="<?=$data['receipt_edate'];?>" onclick="check_client_info()" />
				<?
					}
					else
					{
						echo $data['receipt_sdate'], ' ~ ', $data['receipt_edate'];
					}
				?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_ci_idx">거래처명</label></th>
						<td colspan="3">
							<div class="left">
				<?
					if ($rr_idx == '')
					{
				?>
								<select name="param[ci_idx]" id="post_ci_idx" title="거래처를 선택하세요" onchange="check_client_info()">
									<option value="">거래처를 선택하세요</option>
								</select>
								<input type="hidden" name="param[client_name]" id="post_client_name" value="<?=$data['client_name'];?>" />
				<?
					}
					else
					{
				?>
								<?=$data['client_name'];?>
								<input type="hidden" name="ci_idx" id="post_ci_idx" value="<?=$data['ci_idx'];?>" />
								<input type="hidden" name="client_name" id="post_client_name" value="<?=$data['client_name'];?>" />
				<?
					}
				?>
							</div>
						</td>
					</tr>
			<?
				if ($rr_idx == '')
				{
			?>
					<tr>
						<th><label for="post_report_class">점검타입</label></th>
						<td>
							<div class="left">
								<select name="report_class" id="post_report_class" title="점검타입을 선택하세요" onchange="check_client_info()">
									<option value="">점검타입을 선택하세요</option>
								</select>
							</div>
						</td>
						<th><label for="post_receipt_class">접수분류</label></th>
						<td>
							<div class="left">
								<select name="receipt_class" id="post_receipt_class" title="접수분류 전체" onchange="check_client_info()">
									<option value="">접수분류 전체</option>
								</select>
							</div>
						</td>
					</tr>
			<?
				}
			?>
					<tr>
						<th><label for="post_client_charge">인수자</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_client_charge" name="param[client_charge]" class="type_text" title="인수자 입력하세요." size="20" value="<?=$data['client_charge'];?>" />
							</div>
						</td>
						<th><label for="post_client_telnum">연락처</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_client_telnum" name="param[client_telnum]" class="type_text" title="연락처 입력하세요." size="20" value="<?=$data['client_telnum'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_client_hpnum">핸드폰</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_client_hpnum" name="param[client_hpnum]" class="type_text" title="핸드폰 입력하세요." size="20" value="<?=$data['client_hpnum'];?>" />
							</div>
						</td>
						<th><label for="post_client_email">이메일</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_client_email" name="param[client_email]" class="type_text" title="이메일 입력하세요." size="30" value="<?=$data['client_email'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_client_address">주소</label></th>
						<td colspan="3">
							<div class="left">
								<input type="text" id="post_client_address" name="param[client_address]" class="type_text" title="주소 입력하세요." size="50" value="<?=$data['client_address'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_report_part">점검자소속</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_report_part" name="param[report_part]" class="type_text" title="점검자소속 입력하세요." size="20" value="<?=$data['report_part'];?>" />
							</div>
						</td>
						<th><label for="post_report_charge">점검자 담당자</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_report_charge" name="param[report_charge]" class="type_text" title="점거자담당자 입력하세요." size="20" value="<?=$data['report_charge'];?>" />
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_report_telnum">점검자 연락처</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_report_telnum" name="param[report_telnum]" class="type_text" title="점거자연락처 입력하세요." size="20" value="<?=$data['report_telnum'];?>" />
							</div>
						</td>
						<th><label for="post_report_email">점검자 이메일</label></th>
						<td>
							<div class="left">
								<input type="text" id="post_report_email" name="param[report_email]" class="type_text" title="점거자이메일 입력하세요." size="30" value="<?=$data['report_email'];?>" />
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="sub_frame"><h4>점검항목</h4></div>
				<div id="sub_receipt_report"></div>

				<div class="sub_frame"><h4>접수목록</h4></div>
				<div id="sub_receipt_list"></div>

				<div class="section">
					<div class="fr">
				<?
					if ($rr_idx == '') {
				?>
						<span class="btn_big_green"><input type="button" value="등록하기" onclick="check_form()" /></span>
						<span class="btn_big_green"><input type="button" value="등록취소" onclick="close_data_form()" /></span>
						<input type="hidden" name="sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="submit" value="수정하기" /></span>
						<span class="btn_big_blue"><input type="button" value="수정취소" onclick="close_data_form()" /></span>
						<input type="hidden" name="sub_type" value="modify" />
						<input type="hidden" id="post_rr_idx" name="rr_idx" value="<?=$rr_idx;?>" />
				<?
					}
				?>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	part_information('<?=$code_part;?>', 'client_info', 'post_ci_idx', '<?=$data['ci_idx'];?>', '');
	part_information('<?=$code_part;?>', 'report_class', 'post_report_class', '', '');
	part_information('<?=$code_part;?>', 'receipt_class', 'post_receipt_class', '', '');

	$(".datepicker").datepicker();

//------------------------------------ 거래처, 점검타입, 접수분류
	function check_client_info()
	{
		var ci_idx = $('#post_ci_idx').val();
		var rr_idx = $('#post_rr_idx').val();
		var sdate  = '';
		var edate  = '';
		var report_class  = '';
		var receipt_class = '';

		if (rr_idx == '')
		{
			sdate  = $('#post_receipt_sdate').val();
			edate  = $('#post_receipt_edate').val();
			report_class  = $('#post_report_class').val();
			receipt_class = $('#post_receipt_class').val();

		// 거래처정보
			$.ajax({
				type: "post", dataType: 'json', url: '<?=$local_dir;?>/bizstory/work/client_report_info.php',
				data: {'ci_idx' : ci_idx},
				success: function(msg) {
					$('#post_client_name').val(msg.client_name);
					$('#post_client_charge').val(msg.client_charge);
					$('#post_client_telnum').val(msg.client_telnum);
					$('#post_client_hpnum').val(msg.client_hpnum);
					$('#post_client_email').val(msg.client_email);
					$('#post_client_address').val(msg.client_address);
					$('#post_report_charge').val(msg.report_charge);
					$('#post_report_telnum').val(msg.report_telnum);
					$('#post_report_email').val(msg.report_email);
				}
			});
		}

	// 점검항목
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/work/client_report_check.php',
			data: {'rr_idx' : rr_idx, 'report_class' : report_class},
			success: function(msg) {
				$('#sub_receipt_report').html(msg);
			}
		});

	// 접수항목
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/work/client_report_receipt.php',
			data: {'ci_idx' : ci_idx, 'rr_idx' : rr_idx, 'sdate' : sdate, 'edate' : edate, 'receipt_class' : receipt_class},
			success: function(msg) {
				$('#sub_receipt_list').html(msg);
			}
		});
	}

//------------------------------------ 보고서구분에 대해서 기간설정
	function for_check()
	{
		var receipt_day = '';

		var receipt_year  = $('#post_receipt_year').val();
		var receipt_month = $('#post_receipt_month').val();

		if (receipt_month == '02') receipt_day = '28';
		if (receipt_month == '01' || receipt_month == '03' || receipt_month == '05' || receipt_month == '07' || receipt_month == '08' || receipt_month == '10' || receipt_month == '12') receipt_day = '31';
		if (receipt_month == '04' || receipt_month == '06' || receipt_month == '09' || receipt_month == '11') receipt_day = '30';

		var receipt_sdate = receipt_year + '-' + receipt_month + '-01';
		var receipt_edate = receipt_year + '-' + receipt_month + '-' + receipt_day;

		$('#post_receipt_sdate').val(receipt_sdate);
		$('#post_receipt_edate').val(receipt_edate);
		check_client_info();
	}

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val(); // 지사
		chk_title = $('#post_part_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_ci_idx').val(); // 거래처
		chk_title = $('#post_ci_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
/*
		chk_value = $('#post_report_class').val(); // 점검타입
		chk_title = $('#post_report_class').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}
*/
		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
						list_data();
					}
					else
					{
						if (msg.error_string != '')
						{
							check_auth_popup(msg.error_string);
							$("#loading").fadeOut('slow');
						}
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}

	check_client_info();
//]]>
</script>


<?
	}
?>
