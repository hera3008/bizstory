<?
/*
	생성 : 2012.09.05
	수정 : 2013.03.27
	위치 : 설정폴더 > 거래처관리 > 삭제거래처 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$set_client_cnt = $comp_set_data['client_cnt'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and ci.del_yn = 'Y' and ci.comp_idx = '" . $code_comp . "' and ci.part_idx = '" . $code_part . "'";
	if ($shgroup != '' && $shgroup != 'all') // 거래처분류
	{
		$where .= " and (concat(ccg.up_ccg_idx, ',') like '%" . $shgroup . ",%' or ci.ccg_idx = '" . $shgroup . "')";
	}
	if ($stext != '' && $swhere != '')
	{
		$where .= " and " . $swhere . " like '%" . $stext . "%'";
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'ci.del_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = client_info_data('list', $where, $orderby, $page_num, $page_size, 2);
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
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$page_where = " and ci.comp_idx = '" . $code_comp . "'";
	$page_data = client_info_data('page', $page_where);
	$remain_client = $set_client_cnt - $page_data['total_num'];
?>
<div class="info_text">
	<ul>
		<li>거래처는 <?=$set_client_cnt;?>개까지만 등록이 가능합니다.</li>
		<li>현재 <?=$page_data['total_num'];?>개의 거래처가 등록이 되었습니다.</li>
		<li>거래처 복구는 <?=$remain_client;?>개까지 가능합니다.</li>
	</ul>
</div>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col />
		<col width="80px" />
		<col width="100px" />
		<col width="140px" />
		<col width="40px" />
		<col width="90px" />
		<col width="90px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="ciidx" onclick="check_all('ciidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('담당자', 'mem.mem_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('그룹', 'ccg.sort');?></h3></th>
			<th class="nosort"><h3>연락처</h3></th>
			<th class="nosort"><h3><img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></h3></th>
			<th class="nosort"><h3><?=field_sort('삭제일', 'ci.del_date');?></h3></th>
			<th class="nosort"><h3>관리</h3></th>
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
				$ci_idx = $data['ci_idx'];

			// 연락처
				$charge_info = $data['charge_info'];
				$charge_info_arr = explode('||', $charge_info);
				$info_str = str_replace('/', '<br />', $charge_info_arr[0]);

			// 링크주소
				$link_url = $data['link_url'];
				$link_url_arr = explode(',', $link_url);
				if ($link_url_arr[0] != '')
				{
					$link_string = str_replace('http://', '', $link_url_arr[0]);
					$link_html = '<a href="http://' . $link_string . '" target="_blank"><img src="bizstory/images/icon/home.gif" alt="홈페이지로 이동합니다." /></a>';
				}
				else
				{
					$link_html = '';
				}

			// 거래처그룹 2단계까지만
				$group_view = client_group_view($data['ccg_idx']);
				$group_name = $group_view['group_level1'];
				if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];

			// 사용자수
				$user_where = " and cu.ci_idx = '" . $data['ci_idx'] . "'";
				$user_page = client_user_data('page', $user_where);
				$total_user = $user_page['total_num'];

			// 계약수
				$con_where = " and con.ci_idx = '" . $data['ci_idx'] . "'";
				$con_page = contract_info_data('page', $con_where);
				$total_con = $con_page['total_num'];

			// 메모수
				$sub_where = " and cim.ci_idx='" . $data['ci_idx'] . "'";
				$sub_data = client_memo_data('page', $sub_where);
				$data['total_memo'] = $sub_data['total_num'];

				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $ci_idx . "')";
				else $btn_view = "check_auth_popup('view')";

				if ($auth_menu['mod'] == "Y")
				{
					if ($remain_client > 0)
					{
						$btn_return = "check_return('" . $ci_idx . "')";
					}
					else
					{
						$btn_return = "check_auth_popup('거래처복구기능을 사용할 수 없습니다.')";
					}
				}
				else
				{
					$btn_return = "check_auth_popup('modify')";
				}
?>
		<tr>
			<td><input type="checkbox" id="ciidx_<?=$i;?>" name="chk_ci_idx[]" value="<?=$data["ci_idx"];?>" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$data['client_name'];?></a>
	<?
		if ($total_user > 0)
		{
			echo '
					<span class="client_user" title="사용자">', number_format($total_user), '</span>';
		}
		if ($total_con > 0)
		{
			echo '
					<span class="client_con" title="계약">', number_format($total_con), '</span>';
		}
		if ($data['total_memo'] > 0)
		{
			echo '
					<span class="cmt" title="메모">', number_format($data['total_memo']), '</span>';
		}
	?>
				</div>
			</td>
			<td><?=$data['mem_name'];?></td>
			<td><?=$group_name;?></td>
			<td><?=$info_str;?></td>
			<td><?=$link_html;?></td>
			<td><span class="eng"><?=date_replace($data['del_date'], 'Y-m-d');?></span></td>
			<td>
				<a href="javascript:void(0);" onclick="<?=$btn_view;?>" class="btn_con_violet"><span>보기</span></a>
				<a href="javascript:void(0);" onclick="<?=$btn_return;?>" class="btn_con_violet"><span>복구</span></a>
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
