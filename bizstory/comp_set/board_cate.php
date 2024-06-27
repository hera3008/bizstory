<?
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$board_where = " and bs.bs_idx = '" . $bs_idx . "'";
	$board_data = board_set_data('view', $board_where);

	$where = " and bc.bs_idx = '" . $bs_idx . "'";
	$orderby = "bc.sort asc";
	$list = board_category_data('list', $where, $orderby, '', '');

	$cate_list = $local_dir . '/bizstory/comp_set/board_cate.php';
	$cate_ok   = $local_dir . '/bizstory/comp_set/board_cate_ok.php';

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write  = "check_form('" . $bc_idx . "')";
	}
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="info_frame">
			<span><strong><?=$board_data['subject'];?></strong> 말머리관리</span>
		</div>

		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post" onsubmit="return check_form('');">
			<input type="hidden" id="other_comp_idx"   name="comp_idx"   value="<?=$code_comp;?>" />
			<input type="hidden" id="other_part_idx"   name="part_idx"   value="<?=$code_part;?>" />
			<input type="hidden" id="other_bs_idx"     name="bs_idx"     value="<?=$bs_idx;?>" />
			<input type="hidden" id="other_bc_idx"     name="bc_idx"     value="" />
			<input type="hidden" id="other_sub_type"   name="sub_type"   value="" />

			<input type="hidden" id="other_idx"        name="idx"        value="" />
			<input type="hidden" id="other_sub_action" name="sub_action" value="" />
			<input type="hidden" id="other_post_value" name="post_value" value="" />

			<table class="tinytable view">
			<colgroup>
				<col width="80px" />
				<col />
				<col width="60px" />
				<col width="115px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>순서</h3></th>
					<th class="nosort"><h3>말머리명</h3></th>
					<th class="nosort"><h3>보기</h3></th>
					<th class="nosort"><h3>관리</h3></th>
				</tr>
			</thead>
			<tbody>
<?
// 등록
	if ($bc_idx == '')
	{
?>
				<tr>
					<td>&nbsp;</td>
					<td><div class="left"><input type="text" name="post_menu_name" id="post_menu_name" class="type_text" title="말머리 입력하세요." /></div></td>
					<td><input type="checkbox" name="post_view_yn" id="post_view_yn" value="Y" checked="checked" /></td>
					<td>
						<a href="javascript:void(0);" onclick="<?=$btn_write;?>" class="btn_con_green"><span>등록</span></a>
					</td>
				</tr>
<?
	}

	$i = 0;
	if ($list["total_num"] == 0) {
?>
				<tr>
					<td colspan="4">등록된 데이타가 없습니다.</td>
				</tr>
<?
	}
	else
	{
		$i = 1;
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$sort_data = query_view("
					select min(sort) as min_sort, max(sort) as max_sort
					from board_category
					where del_yn = 'N' and comp_idx ='" . $code_comp . "' and bs_idx = '" . $data['bs_idx'] . "'");

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up     = "check_code_data('sort_up', '', '" . $data['bc_idx'] . "', '')";
					$btn_down   = "check_code_data('sort_down', '', '" . $data['bc_idx'] . "', '')";
					$btn_view   = "check_code_data('check_yn', 'view_yn', '" . $data['bc_idx'] . "', '" . $data["view_yn"] . "')";
					$btn_modify = "popup_page('" . $cate_list . "', '" . $data['bs_idx'] . "', '" . $data['bc_idx'] . "')";
				}
				else
				{
					$btn_up     = "check_auth('modify')";
					$btn_down   = "check_auth('modify')";
					$btn_view   = "check_auth('modify')";
					$btn_modify = "check_auth('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "popup_delete('" . $data['bc_idx'] . "')";
				else $btn_delete = "check_auth('delete');";

				if ($bc_idx == $data['bc_idx'])
				{
					$cate_where = " and bc.bc_idx = '" . $bc_idx . "'";
					$cate_data = board_category_data('view', $cate_where);
?>
				<tr>
					<td>&nbsp;</td>
					<td><div class="left"><input type="text" name="edit_menu_name" id="edit_menu_name" value="<?=$cate_data['menu_name'];?>" class="type_text" title="말머리 입력하세요." /></div></td>
					<td><input type="checkbox" name="edit_view_yn" id="edit_view_yn" value="Y"<?=checked($cate_data['view_yn'], 'Y');?> /></td>
					<td>
						<a href="javascript:void(0);" onclick="<?=$btn_write;?>" class="btn_con_blue"><span>수정</span></a>
					</td>
				</tr>
<?
				}
			// 수정시
				else
				{
?>
				<tr>
					<td>
						<div class="sort">
<?
					if ($sort_data["min_sort"] != $data["sort"] && $sort_data["min_sort"] != "")
					{
						echo '<img src="bizstory/images/icon/up.gif" alt="위로" class="pointer" onclick="', $btn_up, '" />';
					}
					if($sort_data["max_sort"] != $data["sort"] && $sort_data["max_sort"] != "")
					{
						echo '<img src="bizstory/images/icon/down.gif" alt="아래로" class="pointer" onclick="', $btn_down, '" />';
					}
?>
						</div>
					</td>
					<td><div class="left"><?=$data["menu_name"];?></div></td>
					<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
					<td>
						<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a>
						<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a>
					</td>
				</tr>
<?
				}
				$i++;
			}
		}
	}
?>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_gray"><input type="button" value="닫기" onclick="popupform_close()" /></span>
				</div>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 등록
	function check_form(bc_idx)
	{
		$("#popup_notice_view").hide();
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		if (bc_idx == '')
		{
			$('#other_sub_type').val('post');
			chk_value = $('#post_menu_name').val();
			chk_title = $('#post_menu_name').attr('title');
		}
		else
		{
			$('#other_sub_type').val('modify');
			$('#other_bc_idx').val(bc_idx);
			chk_value = $('#edit_menu_name').val();
			chk_title = $('#edit_menu_name').attr('title');
		}

		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$cate_ok;?>',
				data: $('#otherform').serialize(),
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popup_page('<?=$cate_list;?>', '<?=$bs_idx;?>', '');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

//------------------------------------ 삭제하기
	function popup_delete(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$('#other_sub_type').val('delete');
			$('#other_bc_idx').val(idx);
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$cate_ok;?>',
				data: $('#otherform').serialize(),
				beforeSubmit: function(){ $("#loading").fadeIn('slow').fadeOut('slow'); },
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popup_page('<?=$cate_list;?>', '<?=$bs_idx;?>', '');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>