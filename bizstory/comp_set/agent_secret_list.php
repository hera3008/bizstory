<?
/*
	생성 : 2012.07.03
	위치 : 설정관리 > 접수관리 > 에이전트관리 > 알림관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = $add_where . " and abn.comp_idx = '" . $code_comp . "' and abn.part_idx = '" . $code_part . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'abn.order_idx';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = agent_bnotice_data('list', $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big fr"><span>알림등록</span></a>';
	}
	if ($auth_menu['down'] == "Y") // 다운로드버튼
	{
		//$btn_down = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_excel()"><span>엑셀</span></a>';
	}
	if ($auth_menu['print'] == "Y") // 인쇄버튼
	{
		//$btn_print     = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print()"><span><em class="print"></em>인쇄</span></a>';
		//$btn_print_sel = '<a href="javascript:void(0);" class="btn_sml fl" onclick="list_print_detail()"><span><em class="print"></em>상세인쇄</span></a>';
	}
?>
<div class="info_text">
	<ul>
		<li>거래처그룹을 선택하게 되면 '거래처전체' 선택해제됩니다.</li>
	</ul>
</div>

<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom">
		<?=$btn_down;?>
		<?=$btn_print;?>
		<?=$btn_print_sel;?>
		<?=$btn_write;?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="50px" />
		<col />
		<col width="150px" />
		<col width="200px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3>제목</h3></th>
			<th class="nosort"><h3>거래처그룹</h3></th>
			<th class="nosort"><h3>거래처</h3></th>
			<th class="nosort"><h3>등록일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="5">등록된 데이타가 없습니다.</td>
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
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["abn_idx"] . "')";
				else $btn_view = "check_auth('view')";

			// 중요도
				if ($data['import_type'] == '1') $important_span = '<span class="btn_level_1"><span>상</span></span>';
				else if ($data['import_type'] == '2') $important_span = '<span class="btn_level_2"><span>중</span></span>';
				else if ($data['import_type'] == '3') $important_span = '<span class="btn_level_3"><span>하</span></span>';
				else $important_span = '';

			// 거래처분류 2단계까지만
				$group_view = client_group_view($data['ccg_idx']);
				$group_name = $group_view['group_level1'];
				if ($group_view['group_level2'] != '') $group_name .= '<br />' . $group_view['group_level2'];
				if ($group_name == '') $group_name = '거래처그룹전체';

			// 첨부파일
				$file_where = " and abnf.abn_idx = '" . $data['abn_idx'] . "'";
				$file_list = agent_bnotice_file_data('page', $file_where);
				$total_file = $file_list['total_num'];

			// 거래처
				if ($data['client_all'] == 'Y')
				{
					$client_view = '모든거래처';
				}
				else
				{
					$client_view = '';
					$client_idx = $data['ci_idx'];
					$client_idx_arr = explode(',', $client_idx);
					foreach ($client_idx_arr as $client_k => $client_v)
					{
						if ($client_k > 0)
						{
							$client_where = " and ci.ci_idx = '" . $client_v . "'";
							$client_data = client_info_data('view', $client_where);

							if ($client_k == 1)
							{
								$client_view = $client_data['client_name'];
							}
							else
							{
								$client_view .= ', ' . $client_data['client_name'];
							}
						}
					}
				}
?>
		<tr>
			<td><span class="num"><?=$num;?></span></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$data['subject'];?></a>
					<?=$important_span;?>
	<?
		if ($total_file > 0)
		{
			echo '
					<span class="attach" title="첨부파일">', number_format($total_file), '</span>';
		}
	?>
				</div>
			</td>
			<td><?=$group_name;?></td>
			<td><div class="left"><?=$client_view;?></div></td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y-m-d');?></span></td>
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