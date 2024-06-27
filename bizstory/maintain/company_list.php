<?
/*
	수정 : 2013.05.22
	위치 : 설정폴더(총관리자용) > 업체관리 > 업체목록 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = "";
	if ($sclass != '' && $sclass != 'all') // 업체분류
	{
		$where .= " and (concat(code.up_code_idx, ',') like '%" . $sclass . ",%' or comp.comp_class = '" . $sclass . "')";
	}
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
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;sclass=' . $send_sclass;
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
		<input type="hidden" name="sclass" value="' . $send_sclass . '" />
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

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="popupform_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="info_text">
	<ul>
		<li>지사, 거래처, 직원, 저장공간은 <strong style="color:#ff6c00;">등록수</strong>/<strong style="color:#0075c8;">제한수</strong> 입니다.</li>
		<li><span style="color:#FF0000">업체명</span>은 만료 15일전입니다. <span style="color:#0000FF">업체명</span>은 만료된 업체입니다.</li>
	</ul>
</div>

<div class="details">[<?=$_SESSION[$sess_str . '_ubstory_level'] ?>]
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom"><?=$btn_write;?></div>

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="80px" />
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
		<col width="110px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="compidx" onclick="check_all('compidx', this);" /></th>
			<th class="nosort"><h3><?=field_sort('총판', 'sole.code_name');?></h3></th>
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
			<td colspan="16">등록된 데이타가 없습니다.</td>
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
				if ($auth_menu['mod'] == "Y")
				{
					$btn_auth   = "check_code_data('check_yn', 'auth_yn', '" . $data["comp_idx"] . "', '" . $data["auth_yn"] . "')";
					$btn_move   = "window.open('" . $local_dir . "/bizstory/maintain/company_chk.php?comp_idx=" . $data['comp_idx'] . "', '_blank')";
					$btn_modify = "popupform_open('" . $data["comp_idx"] . "')";

					$menu_url = $local_dir . '/bizstory/maintain/company_menu.php';
					$btn_menu = "other_page_open('" . $data["comp_idx"] . "', '" . $menu_url . "')";
				}
				else
				{
					$btn_auth   = "check_auth_popup('modify')";
					$btn_move   = "check_auth_popup('modify')";
					$btn_menu   = "check_auth_popup('modify')";
					$btn_modify = "check_auth_popup('modify')";
				}
				if ($data["auth_yn"] == 'Y') $btn_auth = "check_auth_popup('승인했습니다.')";

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $data["comp_idx"] . "')";
				else $btn_delete = "check_auth_popup('delete');";

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

			// 총값들 - 지사, 거래처, 직원, 저장공간, 에이전트, 가격
				$all_set_part   += $chk_data['part_cnt'];
				$all_set_client += $chk_data['client_cnt'];
				$all_set_mem    += $chk_data['staff_cnt'];
				$all_set_volume += $chk_data['volume_num'];

				$all_use_part   += $part_page['total_num'];
				$all_use_client += $client_page['total_num'];
				$all_use_mem    += $mem_page['total_num'];
				$all_use_agent  += $agent_page['total_num'];
				$all_use_volume += $volume_data;

				$total_price += $chk_data['use_price'];
				$use_price = number_format($chk_data['use_price']);
?>
		<tr>
			<td><input type="checkbox" id="compidx_<?=$i;?>" name="chk_comp_idx[]" value="<?=$data["comp_idx"];?>" /></td>
			<td><?=$data["sole_name"];?></td>
			<td><?=$data["comp_class_str"];?></td>
			<td>
				<div class="left"<?=$end_class;?>>
					<?=$data["comp_name"];?>(<?=$data["comp_idx"];?>)
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
			<td><span class="eng right"><?=$use_price;?></span></td>
			<td><img src="bizstory/images/icon/<?=$data['auth_yn'];?>.gif" alt="<?=$data['auth_yn'];?>" class="pointer" onclick="<?=$btn_auth;?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_move;?>" class="btn_con_violet"><span>페이지보기</span></a><br />
				<a href="javascript:void(0);" onclick="<?=$btn_menu;?>" class="btn_con_violet"><span>메뉴설정</span></a><br />
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
		<tr>
			<td colspan="8"></td>
			<td>
				<span class="eng right">
					<strong style="color:#ff6c00;"><?=number_format($all_use_part);?></strong><br />
					<strong style="color:#0075c8;"><?=number_format($all_set_part);?></strong>
				</span>
			</td>
			<td>
				<span class="eng right">
					<strong style="color:#ff6c00;"><?=number_format($all_use_client);?></strong><br />
					<strong style="color:#0075c8;"><?=number_format($all_set_client);?></strong>
				</span>
			</td>
			<td>
				<span class="eng right">
					<strong style="color:#ff6c00;"><?=number_format($all_use_mem);?></strong><br />
					<strong style="color:#0075c8;"><?=number_format($all_set_mem);?></strong>
				</span>
			</td>
			<td>
				<span class="eng right">
					<strong style="color:#ff6c00;"><?=byte_replace($all_use_volume);?></strong><br />
					<strong style="color:#0075c8;"><?=number_format($all_set_volume);?>GB</strong>
				</span>
			</td>
			<td><span class="eng right"><?=number_format($all_use_agent);?></span></td>
			<td><span class="eng right"><?=number_format($total_price);?></span></td>
			<td colspan="2">&nbsp;</td>
		</tr>
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
