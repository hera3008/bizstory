<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include "./process/no_direct.php";
	include "./header.php";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$ci_idx    = $idx;

// 업체설정
	$comp_set_where = " and cs.comp_idx = '" . $code_comp . "'";
	$comp_set_data  = company_set_data('view', $comp_set_where);

	$set_tax_yn = $company_set_data['tax_yn']; // 세금계산서여부
	
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
	
	$view_yn = '사용안함';
	if ($data['view_yn'] == 'Y') {
		$view_yn = '사용함';
	}
	
	$tel_num = '';
	if ($data['tel_num'] != null && $data['tel_num'] != "") {
		$tel_num = '<a href="tel:' . $data['tel_num'] . '" class="tel">' . $data['tel_num'] . '</a>';
	}
	
	$ip_yn = '사용안함';
	if ($data["ip_yn"] == 'Y') {
		$ip_yn = '사용함';
	}
?>
<!-- <script type="text/javascript" src="<?=$mobile_dir;?>/js/_myScroll.js" charset="utf-8"></script> -->
<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article class="mt_4">
			<h2>고객목록</h2>
		</article>
		<div id="wrapper" class="receipt">
			<div id="scroller">

				<div class="work_area">
					<div class="title">
						<strong class="s_title"><?=$data['client_name'];?> (<?=$view_yn?>)</strong>
					</div>
					<div class="work_inner">
						<table border="1" cellspacing="0" summary="고객목록" class="table02">
							<tr>
								<th class="w76">연락처</th>
								<td><?=$tel_num?></td>
							</tr>
							<tr>
								<th class="w76">팩스번호</th>
								<td><?=$data['fax_num'];?></td>
							</tr>
							<tr>
								<th class="w76">담당자</th>
								<td>
					<?
						if (is_array($charge_info_arr))
						{
							$total_len = count($charge_info_arr);
							foreach ($charge_info_arr as $arr_k => $arr_v)
							{
								$info_str = explode('/', $arr_v);
								
								$info_tel_no = '';
								if ($info_str[1] != null && $info_str[1] != "") {
									$info_tel_no = '<a href="tel:' . $info_str[1] . '" class="tel">' . $info_str[1] . '</a>';
								}
								
								echo '담당자명 : ', $info_str[0], ', 연락처 : ', $info_tel_no, '<br />';
							}
						}
					?>
								</td>
							</tr>
							<tr>
								<th class="w76">이메일</th>
								<td><?=$data['client_email'];?></td>
							</tr>
							<tr>
								<th class="w76">거래처 그룹</th>
								<td><?=$data['group_name'];?></td>
							</tr>
							<tr>
								<th class="w76">아이피차단</th>
								<td><?=$ip_yn?></td>
							</tr>
							<tr>
								<th class="w76">주소</th>
								<td>[<?=$data['zip_code'];?>] <?=$data['address'];?></td>
							</tr>
							<tr>
								<th class="w76">링크주소</th>
								<td>
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
									
								</td>
							</tr>
							<tr>
								<th class="w76">접속정보</th>
								<td><?=nl2br($data['memo1']);?></td>
							</tr>
							<tr>
								<th class="w76">간단한 메모</th>
								<td><?=nl2br($data['remark']);?></td>
							</tr>
							<tr>
								<th class="w76">담당</th>
								<td><?=$data['part_name'];?> - <?=$data['mem_name'];?></td>
							</tr>
						</table>
					</div>
				</div>

			</div>
		</div>
	</div>
<?
	include "./footer.php";
?>
