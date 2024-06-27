<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$sole_idx = $idx;

	$where = " and sole.sole_idx = '" . $sole_idx . "'";
	$data = sole_info_data("view", $where);

	$where = " and comp.sole_idx = '" . $sole_idx . "'";
	$list = company_info_data('list', $where, '', '', '');

	$menu_url = $local_dir . '/bizstory/maintain/sole_company.php';
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong><?=$data['comp_name'];?></strong> 업체목록입니다.
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>

	<div class="ajax_frame">

		<table class="tinytable">
			<colgroup>
				<col width="40px" />
				<col />
				<col width="100px" />
				<col width="80px" />
				<col width="50px" />
				<col width="60px" />
				<col width="50px" />
				<col width="70px" />
				<col width="60px" />
				<col width="80px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort">번호</th>
					<th class="nosort"><h3>업체명</h3></th>
					<th class="nosort"><h3>대표자</h3></th>
					<th class="nosort"><h3>만료일</h3></th>
					<th class="nosort"><h3>지사</th>
					<th class="nosort"><h3>거래처</th>
					<th class="nosort"><h3>직원</h3></th>
					<th class="nosort"><h3>저장공간</h3></th>
					<th class="nosort"><h3>에이전트</h3></th>
					<th class="nosort"><h3>가격</h3></th>
				</tr>
			</thead>
			<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
				<tr>
					<td colspan="12">등록된 데이타가 없습니다.</td>
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
			// 남은일
				if ($data['end_date'] == '') $data['end_date'] = '0000-00-00';
				$data_date = query_view("select datediff('" . $data['end_date'] . "', '" . date("Y-m-d") . "') as remain_days");
				$remain_days = $data_date['remain_days'];
				if ($remain_days == '') $remain_days = 0;

				$tel_num = $data["tel_num"];
				$tel_num_str = substr($tel_num, 0, 1);
				if ($tel_num == '-' || $tel_num == '--')
				{
					$tel_num = '';
				}
				else if ($tel_num_str == '-')
				{
					$tel_num = substr($tel_num, 1, strlen($tel_num));
				}

			// 설정값
				$chk_where = " and cs.comp_idx = '" . $data['comp_idx'] . "'";
				$chk_data = company_setting_data('view', $chk_where);

			// 지사총수
				$part_cnt = number_format($chk_data['part_cnt']); // 등록가능 지사
				$part_where = " and part.comp_idx = '" . $data["comp_idx"] . "'";
				$part_page = company_part_data('page', $part_where);
				$total_part = number_format($part_page['total_num']);

			// 거래처총수
				$client_cnt = number_format($chk_data['client_cnt']); // 등록가능 거래처
				$client_where = " and ci.comp_idx = '" . $data["comp_idx"] . "'";
				$client_page = client_info_data('page', $client_where);
				$total_client = number_format($client_page['total_num']);

			// 직원수
				$staff_cnt = number_format($chk_data['staff_cnt']); // 등록가능 직원수
				$mem_where = " and mem.comp_idx = '" . $data["comp_idx"] . "'";
				$mem_page = member_info_data('page', $mem_where);
				$total_staff = number_format($mem_page['total_num']);

			// 에이전트수
				$agent_where = " and ad.comp_idx = '" . $data["comp_idx"] . "' and ci.del_yn = 'N'";
				$agent_page = agent_data_data('page', $agent_where);
				$total_agent = number_format($agent_page['total_num']);

			//사용데이터 - /data/company/comp_idx/* 값구해서
				$volume_num = number_format($chk_data['volume_num']); // 등록가능 용량
				$volume_path = $comp_path . '/' . $data["comp_idx"];
				$volume_data = server_volume($volume_path);

				if ($remain_days <= 0)
				{
					$end_class = ' style="color:#0000FF;"';
				}
				else if ($remain_days <= 15)
				{
					$end_class = ' style="color:#FF0000;"';
				}
				else
				{
					$end_class = "";
				}

				$total_price  += $chk_data['use_price'];
				$part_total   += $part_page['total_num'];
				$client_total += $client_page['total_num'];
				$staff_total  += $mem_page['total_num'];
				$agent_total  += $agent_page['total_num'];
				$volume_total += $volume_data;
?>
				<tr>
					<td><span class="eng"><?=$i;?></span></td>
					<td>
						<div class="left"<?=$end_class;?>><?=$data["comp_name"];?></div>
					</td>
					<td>
						<?=$data["boss_name"];?><br />
						<span class="eng"><?=$tel_num;?></span>
					</td>
					<td><span class="eng"><?=date_replace($data["end_date"], 'Y-m-d');?><br /><?=number_format($remain_days);?></span></td>
					<td>
						<span class="eng right">
							<strong style="color:#ff6c00;"><?=$total_part;?></strong><br />
							<strong style="color:#0075c8;"><?=$part_cnt;?></strong>
						</span>
					</td>
					<td>
						<span class="eng right">
							<strong style="color:#ff6c00;"><?=$total_client;?></strong><br />
							<strong style="color:#0075c8;"><?=$client_cnt;?></strong>
						</span>
					</td>
					<td>
						<span class="eng right">
							<strong style="color:#ff6c00;"><?=$total_staff;?></strong><br />
							<strong style="color:#0075c8;"><?=$staff_cnt;?></strong>
						</span>
					</td>
					<td>
						<span class="eng right">
							<strong style="color:#ff6c00;"><?=byte_replace($volume_data);?></strong><br />
							<strong style="color:#0075c8;"><?=$volume_num;?>GB</strong>
						</span>
					</td>
					<td><span class="eng right"><?=$total_agent;?></span></td>
					<td><span class="eng right"><?=number_format($chk_data['use_price']);?></span></td>
				</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
				<tr>
					<td colspan="4"></td>
					<td><span class="eng right"><?=number_format($part_total);?></span></td>
					<td><span class="eng right"><?=number_format($client_total);?></span></td>
					<td><span class="eng right"><?=number_format($staff_total);?></span></td>
					<td><span class="eng right"><?=byte_replace($volume_total);?></span></td>
					<td><span class="eng right"><?=number_format($agent_total);?></span></td>
					<td><span class="eng right"><?=number_format($total_price);?></span></td>
				</tr>
			</tbody>
		</table>

		<div class="section">
			<div class="fr">
				<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupform_close()" /></span>
			</div>
		</div>

	</div>
</div>