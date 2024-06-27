<?
/*
	생성 : 2013.01.16
	수정 : 2013.01.16
	위치 : 전문가코너 > 거래처검색관리 - 등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$ci_idx = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;scomp=' . $send_scomp;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scomp"  value="' . $send_scomp . '" />
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

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $ci_idx == '') || ($auth_menu['mod'] == 'Y' && $ci_idx != '')) // 등록, 수정권한
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
		$client_where = " and ci.ci_idx = '" . $ci_idx . "'";
		$client_data = client_info_data("view", $client_where);

		$address = $client_data['address'];
		$client_data['address'] = str_replace('||', ' ', $address);

		$field_where = " and ecsf.view_yn = 'Y'";
		$field_list = expert_client_search_field_data("list", $field_where, '', '', '');

		$where = " and ecs.ci_idx = '" . $ci_idx . "'";
		$data = expert_client_search_data("view", $where);

		if ($data['view_yn'] == '') $data['view_yn'] = 'Y';
		if ($data['ecs_idx'] == '') $sub_type = 'post';
		else $sub_type = 'modify';
?>
<div class="ajax_write">
	<div class="ajax_frame">

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
				<th>거래처명</th>
				<td colspan="3"><div class="left"><strong><?=$client_data['client_name'];?></strong></div></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><div class="left"><?=$client_data['tel_num'];?></div></td>
				<th>팩스번호</th>
				<td><div class="left"><?=$client_data['fax_num'];?></div></td>
			</tr>
			<tr>
				<th>이메일</th>
				<td colspan="3"><div class="left"><?=$client_data['client_email'];?></div></td>
			</tr>
			<tr>
				<th>주소</th>
				<td colspan="3"><div class="left">[<?=$client_data['zip_code'];?>] <?=$client_data['address'];?></div></td>
			</tr>
			<tr>
				<th>업종</th>
				<td><div class="left"><?=$client_data['tax_upjong'];?></div></td>
				<th>업태</th>
				<td><div class="left"><?=$client_data['tax_uptae'];?></div></td>
			</tr>
		</table>

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="comp_idx" id="post_comp_idx" value="<?=$client_data['comp_idx'];?>" />
			<input type="hidden" name="ci_idx"   id="post_ci_idx"   value="<?=$ci_idx;?>" />
			<input type="hidden" name="ecs_idx"  id="post_ecs_idx"  value="<?=$data['ecs_idx'];?>" />
			<input type="hidden" name="sub_type" id="post_sub_type" value="<?=$sub_type;?>" />

		<fieldset>
			<legend class="blind">거래처검색어 폼</legend>
			<table class="tinytable write" summary="거래처검색어를 등록/수정합니다.">
			<caption>거래처검색어</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th>보기여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_use, "param[view_yn]", "post_view_yn", $data["view_yn"]);?>
						</div>
					</td>
				</tr>
<?
	foreach ($field_list as $field_k => $field_data)
	{
		if (is_array($field_data))
		{
			$field_name = $field_data['field_name'];
			$field_str = field_set_form($field_data, $data[$field_name]);
?>
				<tr>
					<th><?=$field_str["field_label"];?></th>
					<td>
						<div class="left">
							<?=$field_str["field_form"];?>
						</div>
					</td>
				</tr>
<?
		}
	}
?>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_blue"><input type="button" value="수정" onclick="check_modify_form()" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>
				</div>
			</div>

		</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 수정
	function check_modify_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						close_data_form();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>
<?
	}
?>