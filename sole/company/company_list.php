<?
/*
	생성 : 2012.12.11
	수정 : 2012.12.11
	위치 : 총판관리 > 업체목록 - 목록
*/
	require_once "../../bizstory/common/setting.php";
	require_once "../../bizstory/common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and comp.sole_idx = '" . $code_sole . "'";
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'comp.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$where .= " and (
				replace(comp.tel_num, '-', '') like '%" . $stext . "%' or
				replace(comp.fax_num, '-', '') like '%" . $stext . "%' or
				replace(comp.hp_num, '-', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'comp.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = company_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

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
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
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
?>
<div class="info_text">
	<ul>
		<li>지사, 거래처, 직원, 저장공간은 <strong style="color:#ff6c00;">등록수</strong>/<strong style="color:#0075c8;">제한수</strong> 입니다.</li>
		<li><span style="color:#FF0000">업체명</span>은 만료 15일전입니다. </li>
		<li><span style="color:#0000FF">업체명</span>은 만료된 업체입니다. </li>
	</ul>
</div>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="80px" />
		<col />
		<col width="100px" />
		<col width="80px" />
		<col width="80px" />
		<col width="80px" />
		<col width="80px" />
		<col width="60px" />
		<col width="60px" />
		<col width="90px" />
		<col width="60px" />
		<col width="90px" />
		<col width="40px" />
		<col width="70px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="compidx" onclick="check_all('compidx', this);" /></th>
			<th class="nosort"><h3><?=field_sort('분류', 'code.code_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('업체명', 'comp.comp_name');?></h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3><?=field_sort('대표자', 'comp.boss_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('담당자', 'comp.charge_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('만료일', 'comp.end_date');?></h3></th>
			<th class="nosort"><h3>지사</th>
			<th class="nosort"><h3>거래처</th>
			<th class="nosort"><h3>직원</h3></th>
			<th class="nosort"><h3>저장공간</h3></th>
			<th class="nosort"><h3>에이전트</h3></th>
			<th class="nosort"><h3><?=field_sort('가격', 'cs.use_price');?></h3></th>
			<th class="nosort"><h3>승인</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="15">등록된 데이타가 없습니다.</td>
		</tr>
<?
	}
	else
	{
		$i = 1;
		$num = $list["total_num"] - ($page_num - 1) * $page_size;
		$total_price = 0;
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

			// 설정값 - 나중에 수정
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

				$total_price += $chk_data['use_price'];
?>
		<tr>
			<td><?=$num;?></td>
			<td><?=$data["comp_class_str"];?></td>
			<td>
				<div class="left"<?=$end_class;?>>
					<?=$data["comp_name"];?>
				</div>
			</td>
			<td><span class="eng"><?=$tel_num;?></span></td>
			<td><?=$data["boss_name"];?></td>
			<td><?=$data["charge_name"];?></td>
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
			<td><img src="../bizstory/images/icon/<?=$data['auth_yn'];?>.gif" alt="<?=$data['auth_yn'];?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="view_open('<?=$data["comp_idx"];?>')" class="btn_con"><span>상세보기</span></a>
			</td>
		</tr>
<?
				$num--;
				$i++;
			}
		}
	}
?>
		<tr>
			<td colspan="12"></td>
			<td colspan="3"><span class="eng right"><?=number_format($total_price);?></span></td>
		</tr>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>