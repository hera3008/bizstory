<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

	if ($ci_idx == '')
	{
?>
<div class="info_frame">
	<span>거래처를 먼저 등록하세요.</span>
</div>
<?
	}
	else
	{
		$where = " and ci.ci_idx = '" . $ci_idx . "'";
		$client_data = client_info_data("view", $where);

		$where = " and cu.comp_idx = '" . $code_comp . "' and cu.ci_idx = '" . $ci_idx . "'";
		$list = client_user_data('list', $where, '', '', '');
?>
<div class="new_report">

	<table class="tinytable">
		<colgroup>
			<col width="50px" />
			<col width="150px" />
			<col width="100px" />
			<col />
			<col width="60px" />
			<col width="110px" />
		</colgroup>
		<thead>
			<tr>
				<th class="nosort"><h3>번호</h3></th>
				<th class="nosort"><h3>아이디</h3></th>
				<th class="nosort"><h3>이름</h3></th>
				<th class="nosort"><h3>연락처</th>
				<th class="nosort"><h3>로그인</h3></th>
				<th class="nosort"><h3>관리</h3></th>
			</tr>
		</thead>
		<tbody>
	<?
		$i = 0;
		if ($list["total_num"] == 0) {
	?>
			<tr>
				<td colspan="6">등록된 데이타가 없습니다.</td>
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
					$cu_idx = $data['cu_idx'];

					if ($client_data['del_yn'] == 'N')
					{
						$btn_login  = "check_form_code('sub_check_yn', 'login_yn', '" . $ci_idx . "', '" . $cu_idx . "', '" . $data["login_yn"] . "')";
						$btn_modify = "cuser_modify_form('open', '" . $cu_idx . "')";
						$btn_delete = "cuser_delete('" . $cu_idx . "')";
					}
					else
					{
						$btn_login  = "check_auth_popup('modify')";
						$btn_modify = "check_auth_popup('modify')";
						$btn_delete = "check_auth_popup('delete')";
					}
	?>
			<tr>
				<td><?=$i;?></td>
				<td><?=$data['mem_id'];?></td>
				<td><?=$data['mem_name'];?></td>
				<td><div class="left"><?=$data['tel_num'];?> / <?=$data['mem_email'];?></div></td>
				<td><img src="bizstory/images/icon/<?=$data['login_yn'];?>.gif" alt="<?=$data['login_yn'];?>" class="pointer" onclick="<?=$btn_login;?>" /></td>
				<td>
					<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
					<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
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

</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 사용자 수정
	function cuser_modify_form(form_type, cu_idx)
	{
		if (form_type == 'close')
		{
			$("#new_cuser").slideUp("slow");
			$("#new_cuser").html('');
		}
		else
		{
			$("#cuserlist_cu_idx").val(cu_idx);
			$.ajax({
				type: "post", dataType: 'html', url: cuser_form,
				data: $('#cuserlistform').serialize(),
				success: function(msg) {
					$("#new_cuser").slideUp("slow");
					$("#new_cuser").slideDown("slow");
					$("#new_cuser").html(msg);
				}
			});
		}
	}

//------------------------------------ 사용자 삭제
	function cuser_delete(idx)
	{
		if (confirm("선택하신 사용자를 삭제하시겠습니까?"))
		{
			$('#cuserlist_sub_type').val('delete');
			$('#cuserlist_cu_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: cuser_ok,
				data: $('#cuserlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#cuser_total_value').html(msg.total_num);
						cuser_modify_form('close', '')
						cuser_list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>
<?
	}
?>
