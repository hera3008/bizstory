<?
/*
	생성 : 2013.01.17
	수정 : 2013.01.17
	위치 : 전문가코너 > 코드설정 > 거래처검색분류 - 구성항목
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$ecsf_idx  = $idx;
	$ecsfd_idx = $idx_sub;

	$field_where = " and ecsf.ecsf_idx = '" . $ecsf_idx . "'";
	$field_data = expert_client_search_field_data('view', $field_where);

	$where = " and ecsfd.ecsf_idx = '" . $ecsf_idx . "'";
	$list = expert_client_search_field_data_data('list', $where, '', '', '');

	$sub_list = $local_dir . '/bizstory/expert/search_field_data.php';
	$sub_ok   = $local_dir . '/bizstory/expert/search_field_data_ok.php';

	if ($auth_menu['int'] == "Y") // 등록버튼
	{
		$btn_write  = "check_form('')";
	}
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<div class="info_frame">
			<span><strong><?=$field_data['field_subject'];?></strong> 구성항목</span>
		</div>

		<form id="otherform" name="otherform" action="<?=$this_page;?>" method="post" onsubmit="return check_form('');">
			<input type="hidden" id="other_ecsf_idx" name="ecsf_idx" value="<?=$ecsf_idx;?>" />
			<input type="hidden" id="other_sub_type" name="sub_type" value="" />

			<input type="hidden" id="other_idx"        name="idx"        value="" />
			<input type="hidden" id="other_sub_action" name="sub_action" value="" />
			<input type="hidden" id="other_post_value" name="post_value" value="" />

			<table class="tinytable view">
			<colgroup>
				<col width="80px" />
				<col />
				<col width="60px" />
				<col width="60px" />
				<col width="115px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>순서</h3></th>
					<th class="nosort"><h3>구성항목명</h3></th>
					<th class="nosort"><h3>보기</h3></th>
					<th class="nosort"><h3>기본</h3></th>
					<th class="nosort"><h3>관리</h3></th>
				</tr>
			</thead>
			<tbody>
<?
// 등록
	if ($ecsfd_idx == '')
	{
?>
				<tr>
					<td>&nbsp;</td>
					<td>
						<div class="left">
							<input type="text" name="post_code_name" id="post_code_name" class="type_text" title="구성항목 입력하세요." />
						</div>
					</td>
					<td><input type="checkbox" name="post_view_yn" id="post_view_yn" value="Y" checked="checked" /></td>
					<td><input type="checkbox" name="post_default_yn" id="post_default_yn" value="Y" /></td>
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
					<td colspan="5">등록된 데이타가 없습니다.</td>
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
				$sort_data = query_view("select min(sort) as min_sort, max(sort) as max_sort from expert_client_search_field_data where del_yn = 'N' and ecsf_idx = '" . $data['ecsf_idx'] . "'");

				if ($auth_menu['mod'] == "Y")
				{
					$btn_up        = "other_open_check_code('sort_up', '', '" . $data['ecsfd_idx'] . "', '', '" . $sub_list . "', '" . $sub_ok . "')";
					$btn_down      = "other_open_check_code('sort_down', '', '" . $data['ecsfd_idx'] . "', '', '" . $sub_list . "', '" . $sub_ok . "')";
					$btn_view      = "other_open_check_code('check_yn', 'view_yn', '" . $data['ecsfd_idx'] . "', '" . $data["view_yn"] . "', '" . $sub_list . "', '" . $sub_ok . "')";
					$btn_default   = "other_open_check_code('check_yn', 'default_yn', '" . $data['ecsfd_idx'] . "', '" . $data["default_yn"] . "', '" . $sub_list . "', '" . $sub_ok . "')";
					$btn_modify    = "other_open_data_form('" . $sub_list . "', '" . $ecsf_idx . "', '" . $data['ecsfd_idx'] . "')";
					$btn_modify_ok = "check_form('" . $data['ecsfd_idx'] . "')";
				}
				else
				{
					$btn_up        = "check_auth_popup('modify')";
					$btn_down      = "check_auth_popup('modify')";
					$btn_view      = "check_auth_popup('modify')";
					$btn_default   = "check_auth_popup('modify')";
					$btn_modify    = "check_auth_popup('modify')";
					$btn_modify_ok = "check_auth_popup('modify')";
				}

				if ($auth_menu['del'] == "Y") $btn_delete = "delete_sub('" . $data['ecsfd_idx'] . "')";
				else $btn_delete = "check_auth_popup('delete');";

				if ($ecsfd_idx == $data['ecsfd_idx'])
				{
					$sub_where = " and ecsfd.ecsfd_idx = '" . $ecsfd_idx . "'";
					$sub_data = expert_client_search_field_data_data('view', $sub_where);
?>
				<tr>
					<td>&nbsp;</td>
					<td><div class="left"><input type="text" name="edit_code_name" id="edit_code_name" value="<?=$sub_data['code_name'];?>" class="type_text" title="구성항목 입력하세요." /></div></td>
					<td><input type="checkbox" name="edit_view_yn" id="edit_view_yn" value="Y"<?=checked($sub_data['view_yn'], 'Y');?> /></td>
					<td><input type="checkbox" name="edit_default_yn" id="edit_default_yn" value="Y"<?=checked($sub_data['default_yn'], 'Y');?> /></td>
					<td>
						<a href="javascript:void(0);" onclick="<?=$btn_modify_ok;?>" class="btn_con_blue"><span>수정</span></a>
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
					<td><div class="left"><?=$data["code_name"];?></div></td>
					<td><img src="bizstory/images/icon/<?=$data["view_yn"];?>.gif" alt="<?=$data["view_yn"];?>" class="pointer" onclick="<?=$btn_view;?>" /></td>
					<td><img src="bizstory/images/icon/<?=$data["default_yn"];?>.gif" alt="<?=$data["default_yn"];?>" class="pointer" onclick="<?=$btn_default;?>" /></td>
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
	function check_form(ecsfd_idx)
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		if (ecsfd_idx == '')
		{
			$('#other_sub_type').val('post');
			chk_value = $('#post_code_name').val();
			chk_title = $('#post_code_name').attr('title');
		}
		else
		{
			$('#other_sub_type').val('modify');
			$('#other_idx').val(ecsfd_idx);
			chk_value = $('#edit_code_name').val();
			chk_title = $('#edit_code_name').attr('title');
		}

		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$sub_ok;?>',
				data: $('#otherform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						other_open_data_form('<?=$sub_list;?>', '<?=$ecsf_idx;?>', '');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

//------------------------------------ 삭제하기
	function delete_sub(idx)
	{
		if (confirm("선택하신 데이타를 삭제하시겠습니까?"))
		{
			$('#other_sub_type').val('delete');
			$('#other_idx').val(idx);
			$.ajax({
				type: 'post', dataType: 'json', url: '<?=$sub_ok;?>',
				data: $('#otherform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						other_open_data_form('<?=$sub_list;?>', '<?=$ecsf_idx;?>', '');
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>