<?
/*
	생성 : 2012.05.31
	위치 : 프로젝트게시판 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$set_board['name_db'] = 'pro_board_biz_' . $code_comp;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = $add_where . " and b.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and b.part_idx = '" . $code_part . "'";
	if ($sclient != '' && $sclient != 'all') $where .= " and b.ci_idx = '" . $sclient . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'b.order_idx';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = pro_board_info_data('list', $set_board['name_db'], $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;sclient=' . $send_sclient;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"   value="' . $send_swhere . '" />
		<input type="hidden" name="stext"    value="' . $send_stext . '" />
		<input type="hidden" name="sclient"  value="' . $send_sclient . '" />
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
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big fr"><span>등록</span></a>';
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
	<div class="etc_bottom">
		<?=$btn_write;?>
	</div>
</div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col width="50px" />
		<col width="160px" />
		<col />
		<col width="80px" />
		<col width="80px" />
		<col width="50px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="bidx" onclick="check_all('bidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
			<th class="nosort"><h3><?=field_sort('거래처명', 'ci.client_name');?></h3></th>
			<th class="nosort"><h3><?=field_sort('제목', 'b.subject');?></h3></th>
			<th class="nosort"><h3><?=field_sort('작성자', 'b.writer');?></h3></th>
			<th class="nosort"><h3>등록일</h3></th>
			<th class="nosort"><h3><img src="bizstory/images/icon/receipt.gif" alt="프로젝트게시판로 이동합니다." /></h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="7">등록된 데이타가 없습니다.</td>
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
				if ($auth_menu['view'] == "Y") $btn_view = "view_open('" . $data["b_idx"] . "')";
				else $btn_view = "check_auth('view')";

				$sub_where = " and bco.b_idx = '" . $data['b_idx'] . "'";
				$sub_data = pro_board_comment_data('page', $sub_where);

				$file_where = " and bf.b_idx = '" . $data['b_idx'] . "'";
				$file_data = pro_board_file_data('page', $file_where);
?>
		<tr>
			<td><input type="checkbox" id="bidx_<?=$i;?>" name="chk_b_idx[]" value="<?=$data["b_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
			<td><?=$data['client_name'];?></td>
			<td>
				<div class="left">
					<a href="javascript:void(0);" onclick="<?=$btn_view;?>"><?=$data['subject'];?>
		<?
			if ($file_data['total_num'] > 0)
			{
				echo '<img src="' . $local_dir . '/bizstory/images/icon/file_on.gif" width="14" height="14" alt="첨부파일-' . $file_data['total_num'] . '개" />';
			}
		?>
					<span class="comment">(<?=$sub_data['total_num'];?>)</span></a>

				</div>
			</td>
			<td><?=$data['writer'];?></td>
			<td><span class="num"><?=date_replace($data['reg_date'], 'Y-m-d');?></span></td>
			<td>
				<a href="javascript:void(0);" onclick="client_receipt_move('<?=$data['ci_idx'];?>')"><img src="bizstory/images/icon/receipt.gif" alt="프로젝트게시판로 이동합니다." /></a>
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