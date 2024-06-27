<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$mem_idx   = $idx;

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

	if ($form_chk == 'Y')
	{
		$where = " and mem.mem_idx = '" . $mem_idx . "'";
		$data = member_info_data("view", $where);

		$data['address'] = str_replace('||', '&nbsp;', $data['address']);

		$duty_where = " and cpd.cpd_idx = '" . $data['cpd_idx'] . "'";
		$duty_data = company_part_duty_data("view", $duty_where);

		$group_where = " and csg.csg_idx = '" . $data['csg_idx'] . "'";
		$group_data = company_staff_group_data("view", $group_where);
?>
<div class="ajax_write" id="form_view">
	<div class="ajax_frame">
		<table class="tinytable view" summary="직원정보를 등록/수정합니다.">
		<caption>직원정보</caption>
		<colgroup>
			<col width="100px" />
			<col width="250px" />
			<col width="100px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th>지사</th>
				<td colspan="3"><div class="left"><?=$data['part_name'];?></div></td>
			</tr>
			<tr>
				<th>이름</th>
				<td><div class="left"><?=$data['mem_name'];?></div></td>
				<th>이메일</th>
				<td><div class="left"><?=$data['mem_email'];?></div></td>
			</tr>
			<tr>
				<th>직책</th>
				<td><div class="left"><?=$duty_data['duty_name'];?></div></td>
				<th>직원그룹</th>
				<td><div class="left"><?=$group_data['group_name'];?></div></td>
			</tr>
			<tr>
				<th>주소</th>
				<td colspan="3"><div class="left">[<?=$data['zip_code'];?>] <?=$data['address'];?></div></td>
			</tr>
			<tr>
				<th>전화번호</th>
				<td><div class="left"><?=$data['tel_num'];?></div></td>
				<th>핸드폰 번호</th>
				<td><div class="left"><?=$data['hp_num'];?></div></td>
			</tr>
		</table>
		<div class="section">
			<div class="fr">
				<span class="btn_big_gray"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
<?
	}
?>
