<?
/*
	생성 : 2013.05.22
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체별거래처 - 보기
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ci_idx    = $idx;

// 업체설정
	$comp_set_where = " and cs.comp_idx = '" . $code_comp . "'";
	$comp_set_data  = company_set_data('view', $comp_set_where);

	$set_tax_yn = $company_set_data['tax_yn']; // 세금계산서여부

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
		$where = " and ci.ci_idx = '" . $ci_idx . "'";
		$data = client_info_data("view", $where);

		if ($data['part_idx'] == '') $data['part_idx'] = $code_part;

		$address = $data['address'];
		$data['address'] = str_replace('||', ' ', $address);

		$tax_address = $data['tax_address'];
		$data['tax_address'] = str_replace('||', ' ', $tax_address);

		$link_url = $data['link_url'];
		$link_url_arr = explode(',', $link_url);

		$charge_info = $data['charge_info'];
		$charge_info_arr = explode('||', $charge_info);
?>
<div class="ajax_write" id="form_view">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" id="view_sub_type" />
			<input type="hidden" name="part_idx" id="view_part_idx" value="<?=$data['part_idx'];?>" />
			<input type="hidden" name="ci_idx"   id="view_ci_idx"   value="<?=$ci_idx;?>" />
			<input type="hidden" name="cim_idx"  id="view_cim_idx"  value="" />

			<fieldset>
				<legend class="blind">거래처정보 상세보기</legend>

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
						<th>거래처그룹</th>
						<td><div class="left"><?=$data['group_name'];?></div></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><div class="left"><?=$data['tel_num'];?></div></td>
						<th>팩스번호</th>
						<td><div class="left"><?=$data['fax_num'];?></div></td>
					</tr>
					<tr>
						<th>이메일</th>
						<td colspan="3"><div class="left"><?=$data['client_email'];?></div></td>
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
				</table>
	<?
	// 세금계산서를 사용할 경우 나옴
		if ($set_tax_yn == 'Y')
		{
	?>
				<div class="sub_frame"><h4>세금계산서관련 정보</h4></div>
				<table class="tinytable view" summary="세금계산서관련정보 상세보기입니다.">
				<caption>세금계산서관련정보</caption>
				<colgroup>
					<col width="100px" />
					<col width="250px" />
					<col width="100px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th>상호명</th>
						<td><div class="left"><?=$data['tax_comp_name'];?></div></td>
						<th>담당자메일주소</th>
						<td><div class="left"><?=$data['tax_email'];?></div></td>
					</tr>
					<tr>
						<th>대표자명</th>
						<td><div class="left"><?=$data['tax_boss_name'];?></div></td>
						<th>사업자등록번호</th>
						<td><div class="left"><?=$data['tax_comp_num'];?></div></td>
					</tr>
					<tr>
						<th>업종</th>
						<td><div class="left"><?=$data['tax_upjong'];?></div></td>
						<th>업태</th>
						<td><div class="left"><?=$data['tax_uptae'];?></div></td>
					</tr>
					<tr>
						<th>사업장주소</th>
						<td colspan="3"><div class="left">[<?=$data['tax_zip_code'];?>] <?=$data['tax_address'];?></div></td>
					</tr>
				</table>
	<?
		}
	?>
			</fieldset>
		</form>
<?
////////////////////////////////////////////////////////////////////////////////////////
// 메모
	$memo_where = " and cim.ci_idx = '" . $ci_idx . "'";
	$memo_list = client_memo_data('list', $memo_where, '', '', '');
?>
		<div class="dotted2"></div>

		<div id="task_comment" class="comment_box">
			<div class="comment_top">
				<p class="count">
					<a id="comment_gate" class="btn_i_minus" title="메모목록" onclick="memo_view()"></a> 메모 <span id="memo_total_value">[<?=number_format($memo_list['total_num']);?>]</span>
				</p>
				<div class="new" id="comment_new_btn"><img src="<?=$lcoal_dir;?>/bizstory/images/btn/btn_memo.png" alt="메모 쓰기" class="pointer" onclick="memo_insert_form('open')" /></div>
			</div>

			<div id="new_memo" title="메모쓰기"></div>

			<form id="memolistform" name="memolistform" method="post" action="<?=$this_page;?>">
				<input type="hidden" id="memolist_comp_idx"  name="comp_idx"  value="<?=$code_comp;?>" />
				<input type="hidden" id="memolist_part_idx"  name="part_idx"  value="<?=$code_part;?>" />
				<input type="hidden" id="memolist_sub_type"  name="sub_type"  value="" />
				<input type="hidden" id="memolist_code_part" name="code_part" value="<?=$code_part;?>" />
				<input type="hidden" id="memolist_ci_idx"    name="ci_idx"    value="<?=$ci_idx;?>" />
				<input type="hidden" id="memolist_cim_idx"   name="cim_idx"   value="" />
				<?=$form_page;?>
				<div id="memo_list_data"></div>
			</form>
		</div>

		<div class="section">
			<div class="fr">
				<span class="btn_big_violet"><input type="button" value="닫기" onclick="view_close()" /></span>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
	memo_view();
//]]>
</script>
<?
	}
?>
