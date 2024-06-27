<?
/*
	수정 : 2012.05.15
	위치 : 설정폴더 > 컨텐츠관리 > 게시판관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn    = $company_set_data['part_yn'];
	$set_table_name = 'board_biz_' . $company_id;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " and bs.comp_idx = '" . $code_comp . "'";
	if ($set_part_yn == 'N') $where .= " and bs.part_idx = '" . $code_part . "'";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$list = board_set_data('list', $where, '', $page_num, $page_size);
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
		$btn_write = '<a href="javascript:void(0);" onclick="data_form_open(\'\')" class="btn_big_green"><span>등록</span></a>';
	}
?>
<div class="details">
	<div>Records <?=$list['total_num'];?> / Total Pages <?=$list['total_page'];?></div>
</div>
<div class="etc_bottom"><?=$btn_write;?></div>
<hr />

<table class="tinytable">
	<colgroup>
		<col width="30px" />
		<col />
		<col width="100px" />
		<col width="80px" />
		<col width="50px" />
		<col width="160px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="bsidx" onclick="check_all('bsidx', this);" /></th>
			<th class="nosort"><h3>게시판명(개수)</h3></th>
			<th class="nosort"><h3>스킨</h3></th>
			<th class="nosort"><h3>말머리</h3></th>
			<th class="nosort"><h3>사용</h3></th>
			<th class="nosort"><h3>관리</h3></th>
		</tr>
	</thead>
	<tbody>
<?
	$i = 0;
	if ($list["total_num"] == 0) {
?>
		<tr>
			<td colspan="6">등록된 데이타가 없습니다.</td>
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
				$bs_idx   = $data['bs_idx'];
				$url_cate = $local_dir . '/bizstory/comp_set/board_cate.php';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $bs_idx . "', '" . $data["view_yn"] . "')";
					$btn_modify = "data_form_open('" . $bs_idx . "')";
					$btn_cate   = "popup_page('" . $url_cate . "', '" . $bs_idx . "', '')";
				}
				else
				{
					$btn_view   = "check_auth('modify')";
					$btn_modify = "check_auth('modify')";
					$btn_cate   = "check_auth('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $bs_idx . "')";
				else $btn_delete = "check_auth('delete');";

			// 게시물수
				$sub_where = " and b.bs_idx='" . $data['bs_idx'] . "'";
				$sub_data = board_info_data('page', $set_table_name, $sub_where);
				$total_board = number_format($sub_data['total_num']);
?>
		<tr>
			<td><input type="checkbox" id="bsidx_<?=$i;?>" name="chk_bs_idx[]" value="<?=$data["bs_idx"];?>" /></td>
			<td><div class="left"><?=$data['subject'];?><span class="comment">(<?=$total_board;?>)</span></div></td>
			<td><?=$data["skin_name"];?></td>
		<?
			if ($data["category_yn"] == "Y") {
		?>
			<td><img src="bizstory/images/icon/cate_on.gif" alt="말머리" class="pointer" onclick="<?=$btn_cate;?>" /></td>
		<?
			} else {
		?>
			<td><img src="bizstory/images/icon/cate_off.gif" alt="말머리설정이 안되어 있습니다." /></td>
		<?
			}
		?>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td>
				<a href="<?=$local_dir;?>/index.php?fmode=board&amp;smode=board&amp;bs_idx=<?=$data["bs_idx"];?>" class="btn_con" target="_blank"><span>보기</span></a>
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
	</tbody>
</table>
<input type="hidden" id="new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div id="tablefooter">
	<?=page_view($page_size, $page_num, $list['total_page']);?>
</div>
<hr />
