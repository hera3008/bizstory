<?
/*
	수정 : 2012.05.14
	위치 : 업무폴더 > 거래처목록 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	if ($shgroup != '' && $shgroup != 'all') // 거래처분류
	{
		$where .= " and (concat(ccg.up_ccg_idx, ',') like '%" . $shgroup . ",%' or ci.ccg_idx = '" . $shgroup . "')";
	}
	if ($stext != '' && $swhere != '')
	{
		if ($swhere == 'ci.tel_num')
		{
			$stext = str_replace('-', '', $stext);
			$stext = str_replace('.', '', $stext);
			$where .= " and (
				replace(ci.tel_num, '-', '') like '%" . $stext . "%' or
				replace(ci.tel_num, '.', '') like '%" . $stext . "%'or
				replace(ci.fax_num, '-', '') like '%" . $stext . "%'or
				replace(ci.fax_num, '.', '') like '%" . $stext . "%'
			)";
		}
		else $where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = client_info_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1"   value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2"   value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col />
		<col width="70px" />
		<col width="100px" />
		<col width="150px" />
		<col width="45px" />
		<col width="55px" />
		<col width="70px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="ciidx" onclick="check_all('ciidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('담당자', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('분류', 'ccg.sort');?></h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3>사용</h3></th>
			<th class="nosort"><h3>IP차단</h3></th>
			<th class="nosort"><h3><img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="9">등록된 데이타가 없습니다.</td>
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
				$charge_info = $data['charge_info'];
				$charge_info_arr = explode('||', $charge_info);
				$info_str = explode('/', $charge_info_arr[0]);

				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["ci_idx"] . "')";
				else $btn_view = "check_auth('view')";

				$link_url = $data['link_url'];
				$link_url_arr = explode(',', $link_url);

				$tel_num = $info_str[1];
				if ($tel_num != '--' && $tel_num != '-' && $tel_num != '') $tel_num_str = '<br /><span class="eng">(' . $tel_num . ')</span>';
				else $tel_num_str = '';

				$client_email = $info_str[2];
				if ($client_email != '@' && $client_email != '') $client_email_str = '<br /><span class="eng">' . $client_email . '</span>';
				else $client_email_str = '';

			// 거래처분류 2단계까지만
				$group_view = client_group_view($data['ccg_idx']);
				$group_name = $group_view['group_level1'];
				if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];

			// 메모수
				$sub_where = " and cim.ci_idx='" . $data['ci_idx'] . "'";
				$sub_data = client_memo_data('page', $sub_where);
				$data['total_memo'] = $sub_data['total_num'];

				$charge_str = staff_layer_form($data['mem_idx'], '', $set_part_work_yn, $set_color_list2, 'clientliststaff', $data['ci_idx'], '');
?>
		<tr>
			<td><input type="checkbox" id="ciidx_<?=$i;?>" name="chk_ci_idx[]" value="<?=$data["ci_idx"];?>" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$data['client_name'];?></a>
<?
		if ($data['total_memo'] > 0)
		{
			echo '
					<span class="cmt" title="메모">', number_format($data['total_memo']), '</span>';
		}
?>
				</div>
			</td>
			<td><?=$charge_str;?></td>
			<td><?=$group_name;?></td>
			<td>
				<?=$info_str[0];?> <?=$tel_num_str;?>
				<?=$client_email_str;?>
			</td>
			<td><img src="bizstory/images/icon/<?=$data['view_yn'];?>.gif" alt="<?=$data['view_yn'];?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data['ip_yn'];?>.gif" alt="<?=$data['ip_yn'];?>" /></td>
			<td>
				<a href="javascript:void(0);" onclick="client_receipt_move('<?=$data['ci_idx'];?>')"><img src="bizstory/images/icon/receipt.gif" alt="접수페이지로 이동합니다." /></a>
	<?
		if ($link_url_arr[0] != '')
		{
			$link_url_arr[0] = str_replace('http://', '', $link_url_arr[0]);
	?>
				<a href="http://<?=$link_url_arr[0];?>" target="_blank"><img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></a>
	<?
		}
	?>
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
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />