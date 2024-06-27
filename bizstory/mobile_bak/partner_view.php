<?
	include "../common/setting.php";
	include $local_path . "/bizstory/mobile/process/mobile_setting.php";
	include $mobile_path . "/process/member_chk.php";
	include $mobile_path . "/header.php";

	$client_where = " and ci.ci_idx = '" . $ci_idx . "'";
	$client_data = client_info_data('view', $client_where);

	$part_where = " and part.part_idx = '" . $client_data['part_idx'] . "'";
	$part_data = company_part_data("view", $part_where);

	$address = $client_data['address'];
	$client_data['address'] = str_replace('||', ' ', $address);

	$link_url = $client_data['link_url'];
	$link_url_arr = explode(',', $link_url);

	$ip_info = $client_data['ip_info'];
	$ip_info_arr = explode(',', $ip_info);

	$charge_info = $client_data['charge_info'];
	$charge_info_arr = explode('||', $charge_info);
?>
	<div id="bbs_view" class="homebox full sub view">

		<div class="toolbar han">
			<?=$btn_back;?>
			<h1>
				<a href="javascript:void(0)" onclick="window.location.href='<?=$mobile_dir;?>/index.php'"><?=$client_data['client_name'];?></a>
			</h1>
			<?=$btn_menu;?>
		</div>

		<!-- Contents -->
		<div id="wrapper">
			<div id="scroller">
				<div class="">
					<table border="1" cellspacing="0" class="board-list" summary="사용유무, 거래처명, 연락처, 팩스번호, 담당자명 등">
					<caption>거래처 콘텐츠</caption>
					<col width="100px" />
					<col />
					<tbody>
						<tr>
							<th>거래처명</th>
							<td class="subject">
								<p>
									<?=$client_data['client_name'];?> (<?=$set_use[$client_data["view_yn"]];?>)
								</p>
							</td>
						</tr>
						<tr>
							<th>연락처</th>
							<td><a href="tel:<?=$client_data['tel_num'];?>" class="tel"><?=$client_data['tel_num'];?></a></td>
						</tr>
						<tr>
							<th>팩스번호</th>
							<td><a href="tel:<?=$client_data['fax_num'];?>" class="tel"><?=$client_data['fax_num'];?></a></td>
						</tr>
						<tr>
							<th>담당자명</th>
							<td>
						<?
							if (is_array($charge_info_arr))
							{
								$total_len = count($charge_info_arr);
								foreach ($charge_info_arr as $arr_k => $arr_v)
								{
									$info_str = explode('/', $arr_v);
									echo '담당자명 : ', $info_str[0], ', 연락처 : <a href="tel:', $info_str[1], '" class="tel">', $info_str[1], '</a><br />';
								}
							}
						?>
							</td>
						</tr>
						<tr>
							<th>이메일</th>
							<td><?=$client_data['client_email'];?></td>
						</tr>
						<tr>
							<th>거래처 그룹</th>
							<td><?=$client_data['group_name'];?></td>
						</tr>
						<tr>
							<th>아이피차단</th>
							<td>
								<?=$set_use[$client_data["ip_yn"]];?>
						<?
							if ($client_data["ip_yn"] == 'Y')
							{
						?>
								<p class="ip">
						<?
								if (is_array($ip_info_arr))
								{
									foreach ($ip_info_arr as $arr_k => $arr_v)
									{
										echo $arr_v, ', ';
									}
								}
						?>
								</p>
						<?
							}
						?>
							</td>
						</tr>
						<tr>
							<th>주소</th>
							<td>
								<p>
									[<?=$client_data['zip_code'];?>] <?=$client_data['address'];?>
								</p>
							</td>
						</tr>
						<tr>
							<th>링크주소</th>
							<td>
						<?
							if (is_array($link_url_arr))
							{
								foreach ($link_url_arr as $arr_k => $arr_v)
								{
									$arr_v = str_replace('http://', '', $arr_v);
									if ($arr_k > 0)
									{
										echo '<br />';
									}
									//echo '<a href="http://', $arr_v, '" target="_blank">', $arr_v, '</a>';
									echo $arr_v;
								}
							}
						?>
							</td>
						</tr>
						<tr>
							<th>접속정보</th>
							<td>
								<p>
									<?=nl2br($client_data['memo1']);?>
								</p>
							</td>
						</tr>
						<tr>
							<th>간단한 메모</th>
							<td>
								<p>
									<?=nl2br($client_data['remark']);?>
								</p>
							</td>
						</tr>
						<tr>
							<th>담당</th>
							<td><?=$part_data['part_name'];?> - <?=$client_data['mem_name'];?></td>
						</tr>
					</tbody>
					</table>
				</div>

				<div class="contents_pd pt10">
					<div class="barmenu body">
						<a href="javascript:void(0)" onclick="history.go(-1)" class="loop">목록보기<em></em></a>
					</div>
				</div>
			</div>
		</div>
	<!-- //Contents -->
	<?
		$bottom_btn = '
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/index.php\'" class="icon4"><span>홈</span></a>
			<a href="javascript:void(0)" onclick="check_auth_popup(\'준비중입니다\')" class="icon2"><span>메모작성</span></a>
			<a href="javascript:void(0)" onclick="login_out();" class="icon1"><span class="leave_type">로그아웃</span></a>
			<a href="javascript:void(0)" onclick="window.location.href=\'' . $mobile_dir . '/set_up.php\'" class="icon3"><span>설정</span></a>
		';
	?>
	<? include $mobile_path . "/footer.php"; ?>
</div>