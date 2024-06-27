<?
/*
	생성 : 2012.05.07
	수정 : 2012.05.10
	위치 : 설정폴더 > 거래처등록 > 계약정보 - 목록
*/
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

		$where = " and con.comp_idx = '" . $code_comp . "' and con.ci_idx = '" . $ci_idx . "'";
		$list = contract_info_data('list', $where, '', '', '');
?>
<div class="new_report">

	<table class="tinytable">
		<colgroup>
			<col width="50px" />
			<col />
			<col width="120px" />
			<col width="70px" />
			<col width="70px" />
			<col width="70px" />
			<col width="80px" />
			<col width="80px" />
			<col width="70px" />
			<col width="90px" />
		</colgroup>
		<thead>
			<tr>
				<th class="nosort"><h3>번호</h3></th>
				<th class="nosort"><h3>계약명</h3></th>
				<th class="nosort"><h3>계약번호</h3></th>
				<th class="nosort"><h3>계약일</h3></th>
				<th class="nosort"><h3>착수일</h3></th>
				<th class="nosort"><h3>완료일</h3></th>
				<th class="nosort"><h3>계약금액</h3></th>
				<th class="nosort"><h3>유지보수</h3></th>
				<th class="nosort"><h3>구분</h3></th>
				<th class="nosort"><h3>담당자</h3></th>
			</tr>
		</thead>
		<tbody>
	<?
		$i = 0;
		if ($list["total_num"] == 0) {
	?>
			<tr>
				<td colspan="10">등록된 데이타가 없습니다.</td>
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
					if ($auth_menu['view'] == "Y") $btn_view = "contract_view_form('open', '" . $data['con_idx'] . "')";
					else $btn_view = "check_auth_popup('view')";
	?>
			<tr>
				<td><?=$i;?></td>
				<td><div class="left"><a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$data['subject'];?></a></div></td>
				<td><span class="eng"><?=$data['contract_number'];?></span></td>
				<td><span class="eng"><?=date_replace($data['contract_date'], 'y.m.d');?></span></td>
				<td><span class="eng"><?=date_replace($data['begin_date'], 'y.m.d');?></span></td>
				<td><span class="eng"><?=date_replace($data['complete_date'], 'y.m.d');?></span></td>
				<td><div class="right"><span class="eng"><?=number_format($data['con_price']);?></span></div></td>
				<td><div class="right"><span class="eng"><?=number_format($data['month_price']);?></span></div></td>
				<td><?=$set_contract_type[$data['contract_type']];?></td>
				<td><?=$data['charge_name'];?></td>
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
//------------------------------------ 계약보기
	function contract_view_form(form_type, con_idx)
	{
		if (form_type == 'close')
		{
			$("#new_contract").slideUp("slow");
			$("#new_contract").html('');
		}
		else
		{
			$("#contractlist_con_idx").val(con_idx);
			$.ajax({
				type: "post", dataType: 'html', url: contract_viewl,
				data: $('#contractlistform').serialize(),
				success: function(msg) {
					$("#new_contract").slideUp("slow");
					$("#new_contract").slideDown("slow");
					$("#new_contract").html(msg);
				}
			});
		}
	}
//]]>
</script>
<?
	}
?>