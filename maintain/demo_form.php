<?
/*
	생성 : 2012.12.17
	수정 : 2012.12.17
	위치 : 설정폴더(관리자) > 설정관리 > 데모신청 - 등록, 수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$demo_idx = $idx;

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
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
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

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $demo_idx == '') || ($auth_menu['mod'] == 'Y' && $demo_idx != '')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
?>
		<script type="text/javascript">
		//<![CDATA[
			check_auth_popup('');
		//]]>
		</script>
<?
	}

	if ($form_chk == 'Y')
	{
		$where = " and demo.demo_idx = '" . $demo_idx . "'";
		$data = demo_info_data("view", $where);

		$tel_num = $data['tel_num'];
		$tel_num_arr = explode('-', $tel_num);
		$data['tel_num1'] = $tel_num_arr[0];
		$data['tel_num2'] = $tel_num_arr[1];
		$data['tel_num3'] = $tel_num_arr[2];

		$mem_email = $data['mem_email'];
		$mem_email_arr = explode('@', $mem_email);
		$data['mem_email1'] = $mem_email_arr[0];
		$data['mem_email2'] = $mem_email_arr[1];
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

		<fieldset>
			<legend class="blind">데모신청 폼</legend>
			<table class="tinytable write" summary="데모신청을 위한 상호명, 담당자, 연락처, 이메일을 입력합니다.">
			<caption>데모신청</caption>
			<colgroup>
				<col width="125px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_comp_name">상호명</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[comp_name]" id="post_comp_name" class="type_text" title="상호명을 입력하세요." size="20" maxlength="50" value="<?=$data['comp_name'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_charge_name">담당자</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[charge_name]" id="post_charge_name" class="type_text" title="담당자를 입력하세요." size="20" maxlength="20" value="<?=$data['charge_name'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_tel_num1">연락처</label></th>
					<td>
						<div class="left">
							<?=code_select($set_telephone, 'param[tel_num1]', 'post_tel_num1', $data['tel_num1'], '전화번호 앞자리를 선택하세요.', '없음', '', '');?>
							-
							<input type="text" name="param[tel_num2]" id="post_tel_num2" class="type_text" title="전화번호를 입력하세요." size="4" maxlength="4" value="<?=$data['tel_num2'];?>" />
							-
							<input type="text" name="param[tel_num3]" id="post_tel_num3" class="type_text" title="전화번호를 입력하세요." size="4" maxlength="4" value="<?=$data['tel_num3'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_mem_email1">이메일</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[mem_email1]" id="post_mem_email1" class="type_text" title="이메일 아이디를 입력하세요." size="12" maxlength="50" value="<?=$data['mem_email1'];?>" />
							@
							<input type="text" name="param[mem_email2]" id="post_mem_email2" class="type_text" title="이메일 주소를 입력하세요." size="20" maxlength="50" value="<?=$data['mem_email2'];?>" />
							<?=code_select($set_email_domain, 'post_mem_email3', 'post_mem_email3', '', '이메일 선택하세요', '이메일 선택하세요', '', '', 'onchange="email_input(\'post_mem_email2\', \'post_mem_email3\');"');?>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($demo_idx == '') {
			?>
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="popupform_close()" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="demo_idx" value="<?=$demo_idx;?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>
<? include "../include/find_address.php"; ?>
<script type="text/javascript">
//<![CDATA[
	function check_demo()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_comp_name').val(); // 상호명
		chk_title = $('#post_comp_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_charge_name').val(); // 담당자
		chk_title = $('#post_charge_name').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_tel_num2').val() + $('#post_tel_num3').val(); // 연락처
		chk_title = $('#post_tel_num2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_mem_email1').val(); // 이메일
		chk_title = $('#post_mem_email1').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_mem_email2').val(); // 이메일
		chk_title = $('#post_mem_email2').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		$('#post_sub_type').val('reg_post');
		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type : 'post', dataType: 'json', url: link_ok,
				data : $('#popup_demoform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$("#loading").fadeOut('slow');
						check_auth_popup('정상적으로 데모신청이 되었습니다.');
						window.close();
					}
					else
					{
						check_auth_popup(msg.error_string);
						$("#loading").fadeOut('slow');
					}
				},
				complete: function(){
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
	}
//]]>
</script>
<?
	}
?>