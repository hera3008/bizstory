<?
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$where = " and comp.comp_idx = '" . $code_comp . "'";
	$comp_data = company_info_data('view', $where);

	$where = " and cs.comp_idx = '" . $code_comp . "'";
	$comp_set_data = company_set_data('view', $where);

	$where = " and part.comp_idx = '" . $code_comp . "'";
	$part_list = company_part_data('list', $where, '', '', '');
	foreach ($part_list as $part_k => $part_data)
	{
		if (is_array($part_data))
		{
			$member_where = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "'";
			$member_num = member_info_data('page', $member_where, '', '', '');

			$clientg_where = " and ccg.comp_idx = '" . $code_comp . "' and ccg.part_idx = '" . $part_data['part_idx'] . "'";
			$clientg_num = company_client_group_data('page', $clientg_where, '', '', '');

			$client_where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $part_data['part_idx'] . "'";
			$client_num = client_info_data('page', $client_where, '', '', '');

			$part_info[$part_k]['part_idx']    = $part_data['part_idx'];
			$part_info[$part_k]['part_name']   = $part_data['part_name'];
			$part_info[$part_k]['member_num']  = $member_num['total_num'];
			$part_info[$part_k]['clientg_num'] = $clientg_num['total_num'];
			$part_info[$part_k]['client_num']  = $client_num['total_num'];
		}
	}
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="sub_frame"><h4>서비스이용현황</h4></div>
		<table class="tinytable view" summary="서비스이용현황 정보입니다.">
		<caption>서비스이용현황</caption>
		<colgroup>
			<col width="120px" />
			<col />
			<col width="120px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th><span>서비스 시작일</span></th>
				<td>
					<div class="left"><?=$comp_data['start_date'];?></div>
				</td>
				<th><span>서비스 종료일</span></th>
				<td>
					<div class="left"><?=$comp_data['end_date'];?></div>
				</td>
			</tr>
			<tr>
				<th><span>지사명</span></th>
				<td colspan="3">
		<?
			foreach ($part_info as $part_k => $part_data) {
		?>
					<div class="left">
						<ul>
							<li class="first">
								<span><?=$part_data['part_name'];?></span>
							</li>
						</ul>
						<ul>
							<li>직원수 - <?=number_format($part_data['member_num']);?></li>
							<li>거래처그룹수 - <?=number_format($part_data['clientg_num']);?></li>
							<li>거래처수 - <?=number_format($part_data['client_num']);?></li>
						</ul>
					</div>
		<?
			}
		?>
				</td>
			</tr>
		</tbody>
		</table>

		<div class="sub_frame"><h4>기본서비스 이용현황</h4></div>
		<table class="tinytable view" summary="기본서비스 이용현황 정보입니다.">
		<caption>기본서비스</caption>
		<colgroup>
			<col width="120px" />
			<col />
			<col width="120px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th><label for="post_tax_comp_name">서비스현황</label></th>
				<td colspan="3">
					<div class="left">
					</div>
				</td>
			</tr>
		</tbody>
		</table>

		<div class="sub_frame"><h4>부가서비스 이용현황</h4></div>
		<table class="tinytable view" summary="부가서비스 이용현황 정보입니다.">
		<caption>부가서비스</caption>
		<colgroup>
			<col width="120px" />
			<col />
			<col width="120px" />
			<col />
		</colgroup>
		<tbody>
			<tr>
				<th><label for="post_tax_comp_name">서비스현황</label></th>
				<td colspan="3">
					<div class="left">
					</div>
				</td>
			</tr>
		</tbody>
		</table>

	</div>
</div>