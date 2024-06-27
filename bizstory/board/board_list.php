<?
/*
	생성 : 2012.06.07
	수정 : 2012.09.10
	위치 : 게시판폴더
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$set_part_yn    = $company_set_data['part_yn'];
	$set_table_name = 'board_biz_' . $company_id;

// 게시판 설정
	$set_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$set_bbs = board_set_data("view", $set_where);
	if ($set_bbs["total_num"] > 0)
	{
	// 관리자일 경우
		$set_bbs["auth_yn"] = "N";
		If ($code_level >= 1 && $code_level <= 11 && $code_mem != "")
		{
			$set_bbs["auth_yn"] = "Y";
		}

	// 게시판설정값
		foreach($set_bbs as $key => $value)
		{
			$key  = "set_" . $key;
			$$key = $value;
		}
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = $add_where . " and b.comp_idx = '" . $code_comp . "' and b.bs_idx = '" . $bs_idx . "'";
	if ($set_part_yn == 'N') $where .= " and b.part_idx = '" . $code_part . "'";
	if ($scate != '' && $scate != 'all') $where .= " and b.bc_idx = '" . $scate . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'b.reg_date';
	if ($sorder2 == '') $sorder2 = 'desc';
	$orderby = $sorder1 . ' ' . $sorder2;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 목록, 페이지관련
	$list = board_info_data('list',  $set_table_name, $where, $orderby, $page_num, $page_size);
	$page_num = $list['page_num'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode . '&amp;bs_idx=' . $send_bs_idx;
	$f_search  = $f_default . '&amp;scate=' . $send_scate . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode"  value="' . $send_fmode . '" />
		<input type="hidden" name="smode"  value="' . $send_smode . '" />
		<input type="hidden" name="bs_idx" value="' . $send_bs_idx . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="scate"  value="' . $send_scate . '" />
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
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big fr"><span>등록</span></a>';
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

	$set_col = 5;
?>
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
		<col width="30px" />
		<col width="50px" />
<?
	if ($set_category_yn == 'Y')
	{
		$set_col++;
?>
		<col width="100px" />
<?
	}
?>
		<col />
		<col width="80px" />
		<col width="80px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="bidx" onclick="check_all('bidx', this);" /></th>
			<th class="nosort"><h3>번호</h3></th>
<?
	if ($set_category_yn == 'Y')
	{
?>
			<th class="nosort"><h3>말머리</h3></th>
<?
	}
?>
			<th class="nosort"><h3>제목</h3></th>
			<th class="nosort"><h3>작성자</h3></th>
			<th class="nosort"><h3>작성일</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="<?=$set_col;?>">등록된 데이타가 없습니다.</td>
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

			// 첨부파일
				$file_where = " and bf.bs_idx = '" . $data['bs_idx'] . "' and bf.b_idx = '" . $data['b_idx'] . "'";
				$file_list = board_file_data('page', $file_where);
				$total_file = $file_list['total_num'];

			// 코멘트
				$comment_where = " and bco.bs_idx='" . $data['bs_idx'] . "' and bco.b_idx='" . $data['b_idx'] . "'";
				$comment_data = board_comment_data('page', $comment_where);
				$total_comment = $comment_data['total_num'];

			// 공개/비공개
				if ($data['secret_yn'] == 'Y')
				{
					$open_span = '<span class="private"></span>';
					if ($data['mem_idx'] == $code_mem)
					{
						$subject_string = '<a href="javascript:void(0);" onclick="' . $btn_view . '">' . $data['subject'] . '</a>';
					}
					else
					{
						$subject_string = $data['subject'];
					}
				}
				else
				{
					$open_span = '';
					$subject_string = '<a href="javascript:void(0);" onclick="' . $btn_view . '">' . $data['subject'] . '</a>';
				}
?>
		<tr>
			<td><input type="checkbox" id="bidx_<?=$i;?>" name="chk_b_idx[]" value="<?=$data["b_idx"];?>" title="선택" /></td>
			<td><span class="num"><?=$num;?></span></td>
<?
	if ($set_category_yn == 'Y')
	{
?>
			<td><?=$data['cate_name'];?></td>
<?
	}
?>
			<td>
				<div class="left">
					<?=$subject_string;?>
					<?=$open_span;?>
	<?
		if ($total_file > 0)
		{
			echo '
					<span class="attach" title="첨부파일">', number_format($total_file), '</span>';
		}
		if ($total_comment > 0)
		{
			echo '
					<span class="cmt" title="코멘트">', number_format($total_comment), '</span>';
		}
	?>
				</div>
			</td>
			<td><?=$data['writer'];?></td>
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