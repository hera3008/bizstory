<?
/*
	생성 : 2012.12.14
	수정 : 2012.12.14
	위치 : 총설정폴더 > 컨텐츠관리 > 게시판관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 검색
	$where = " ";
	if ($stext != '' && $swhere != '') $where .= " and " . $swhere . " like '%" . $stext . "%'";

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 페이지관련
	$list = comp_bbs_setting_data('list', $where, '', $page_num, $page_size);
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
		$btn_write = '<a href="javascript:void(0);" onclick="open_data_form(\'\')" class="btn_big_green"><span>등록</span></a>';
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
		<col width="50px" />
		<col width="50px" />
		<col width="50px" />
		<col width="50px" />
		<col width="140px" />
	</colgroup>
	<thead>
		<tr>
			<th class="nosort"><input type="checkbox" name="bsidx" onclick="check_all('bsidx', this);" /></th>
			<th class="nosort"><h3>게시판명(개수)</h3></th>
			<th class="nosort"><h3>스킨</h3></th>
			<th class="nosort"><h3>말머리</h3></th>
			<th class="nosort"><h3>사용</h3></th>
			<th class="nosort"><h3>답변</h3></th>
			<th class="nosort"><h3>댓글</h3></th>
			<th class="nosort"><h3>링크</h3></th>
			<th class="nosort"><h3>파일</h3></th>
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
				$url_cate = $local_dir . '/bizstory/maintain_bbs/bbs_cate.php';

				if ($auth_menu['mod'] == "Y")
				{
					$btn_view    = "check_code_data('check_yn', 'view_yn', '" . $bs_idx . "', '" . $data["view_yn"] . "')";
					$btn_reply   = "check_code_data('check_yn', 'reply_yn', '" . $bs_idx . "', '" . $data["reply_yn"] . "')";
					$btn_comment = "check_code_data('check_yn', 'comment_yn', '" . $bs_idx . "', '" . $data["comment_yn"] . "')";
					$btn_link    = "check_code_data('check_yn', 'link_yn', '" . $bs_idx . "', '" . $data["link_yn"] . "')";
					$btn_file    = "check_code_data('check_yn', 'file_yn', '" . $bs_idx . "', '" . $data["file_yn"] . "')";
					$btn_modify  = "open_data_form('" . $bs_idx . "')";
					$btn_cate    = "other_open_data_form('" . $url_cate . "', '" . $bs_idx . "', '')";
				}
				else
				{
					$btn_view    = "check_auth_popup('modify')";
					$btn_reply   = "check_auth_popup('modify')";
					$btn_comment = "check_auth_popup('modify')";
					$btn_link    = "check_auth_popup('modify')";
					$btn_file    = "check_auth_popup('modify')";
					$btn_modify  = "check_auth_popup('modify')";
					$btn_cate    = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "check_delete('" . $bs_idx . "')";
				else $btn_delete = "check_auth_popup('delete');";

			// 게시물수
				$sub_where = " and b.bs_idx='" . $bs_idx . "'";
				$sub_data = comp_bbs_info_data('page', $sub_where);
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
			<td><img src="bizstory/images/icon/cate_off.gif" alt="말머리 사용하지 않음" /></td>
		<?
			}
		?>
			<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["reply_yn"];?>.gif" alt="<?=$data["reply_yn"];?>" class="pointer" onclick="<?=$btn_reply;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["comment_yn"];?>.gif" alt="<?=$data["comment_yn"];?>" class="pointer" onclick="<?=$btn_comment;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["link_yn"];?>.gif" alt="<?=$data["link_yn"];?>" class="pointer" onclick="<?=$btn_link;?>" /></td>
			<td><img src="bizstory/images/icon/<?=$data["file_yn"];?>.gif" alt="<?=$data["file_yn"];?>" class="pointer" onclick="<?=$btn_file;?>" /></td>
			<td>
				<a href="<?=$local_dir;?>/index.php?fmode=maintain_bbs&amp;smode=bbs&amp;bs_idx=<?=$data["bs_idx"];?>" class="btn_con_violet"><span>보기</span></a>
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
