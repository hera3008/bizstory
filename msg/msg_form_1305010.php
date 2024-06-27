<?
/*
	수정 : 2013.04.15
	위치 : 업무관리 > 나의 업무 > 쪽지 > 쪽지작성
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$mem_idx   = $idx;

	$set_file_class = $comp_set_data['file_class'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if ($auth_menu['int'] == 'Y') // 등록권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
		$mem_where = " and mem.comp_idx = '" . $code_comp . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
		$mem_order = "part.sort asc, csg.sort asc, cpd.sort asc, mem.mem_name asc";
		$mem_list = member_info_data('list', $mem_where, $mem_order, '', '');
		foreach ($mem_list as $mem_k => $mem_data)
		{
			if (is_array($mem_data))
			{
				$part_idx   = $mem_data['part_idx'];
				$part_name  = $mem_data['part_name'];
				$part_sort  = $mem_data['part_sort'];
				$csg_idx    = $mem_data['csg_idx'];
				$group_name = $mem_data['group_name'];
				$group_sort = $mem_data['group_sort'];
				$mem_idx    = $mem_data['mem_idx'];
				$mem_name   = $mem_data['mem_name'];

				$part_list[$part_idx]['idx']  = $part_idx;
				$part_list[$part_idx]['name'] = $part_name;
				$part_list[$part_idx]['sort'] = $part_sort;

				$group_list[$part_idx][$csg_idx]['idx']  = $csg_idx;
				$group_list[$part_idx][$csg_idx]['name'] = $group_name;
				$group_list[$part_idx][$csg_idx]['sort'] = $group_sort;

				$member_list[$part_idx][$csg_idx][$mem_idx]['idx']  = $mem_idx;
				$member_list[$part_idx][$csg_idx][$mem_idx]['name'] = $mem_name;
			}
		}
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>

			<fieldset>
				<legend class="blind">쪽지작성 폼</legend>

				<table class="tinytable write" summary="쪽지작성을 등록합니다.">
					<caption>쪽지작성</caption>
					<colgroup>
						<col width="100px" />
						<col />
					</colgroup>
					<tbody>
						<tr>
							<th>받는 사람</th>
							<td>
								<div class="left">
									<ul>
					<?
						foreach ($part_list as $part_k => $part_data)
						{
							if (is_array($part_data))
							{
					?>
										<li>
											<label for="<?=$chk_str;?>">
												<input type="checkbox" class="type_checkbox" title="<?=$part_data['name'];?>" name="<?=$chk_str;?>" id="<?=$chk_str;?>" onclick="check_all2('<?=$chk_str;?>', this, '1'); select_member();" />
												<span style="color:<?=$set_color_list2[$part_data['sort']];?>"><?=$part_data['name'];?></span>
											</label>
										</li>
					<?
							}
						}
					?>
									</ul>
								</div>







								<div class="left">
			<?
				$sub_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
				$part_list = company_part_data('list', $sub_where, '', '', '');
				foreach ($part_list as $part_k => $part_data)
				{
					if (is_array($part_data))
					{
						$chk_str = 'partidx' . $part_data['part_idx'];
			?>
									<ul>
										<li class="first">
											<label for="<?=$chk_str;?>">
												<input type="checkbox" class="type_checkbox" title="<?=$part_data['part_name'];?>" name="<?=$chk_str;?>" id="<?=$chk_str;?>" onclick="check_all('<?=$chk_str;?>', this);" />
												<span><?=$part_data['part_name'];?></span>
											</label>
										</li>
									</ul>
			<?
						$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
						$mem_list = member_info_data('list', $sub_where2, 'mem.mem_name', '', '');
						if ($mem_list['total_num'] > 0)
						{
			?>
									<ul>
			<?
							foreach ($mem_list as $mem_k => $mem_data)
							{
								if (is_array($mem_data))
								{
									$checkbox_str = $chk_str . '_' . $mem_data['mem_idx'];
			?>
										<li>
											<label for="<?=$checkbox_str;?>">
												<input type="checkbox" name="receive_idx[]" id="<?=$checkbox_str;?>" value="<?=$mem_data['mem_idx'];?>" class="type_checkbox" title="<?=$mem_data['mem_name'];?>" />
												<span><?=$mem_data['mem_name'];?></span>
											</label>
										</li>
			<?
								}
							}
			?>
									</ul>
			<?
						}
					}
				}
			?>
								</div>
							</td>
						</tr>
						<tr>
							<th><label for="post_remark">내용</label></th>
							<td>
								<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
								<label for="post_send_save">
									<input type="checkbox" class="type_checkbox" title="보낸쪽지함에 저장(해제시 수신확인 불가)" value='Y' name="param[send_save]" id="post_send_save" checked="checked" />
									<span>보낸쪽지함에 저장(해제시 수신확인 불가)</span>
								</label>
							</td>
						</tr>
						<tr>
							<th><label for="file_fname">파일</label></th>
							<td colspan="3">
								<div class="filewrap">
									<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
									<div class="file">
										<ul id="file_fname_view"></ul>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_green"><input type="submit" value="등록하기" /></span>
						<span class="btn_big_green"><input type="button" value="등록취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" value="post" />
					</div>
				</div>

			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
// 에디터관련
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "post_remark",
		sSkinURI: "<?=$local_dir;?>/bizstory/editor/smarteditor/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){ }
		},
		fCreator: "createSEditor2"
	});

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

	// 받는사람확인
		var mem_idx  = document.getElementsByName('receive_idx[]');
		var i = 0, j = 0;

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				j++;
			}
			i++;
		}
		if (j == 0)
		{
			chk_total = chk_total + '받는자를 선택하세요.<br />';
			action_num++;
		}

		oEditors.getById["post_remark"].exec("UPDATE_CONTENTS_FIELD", []);
		chk_value = $('#post_remark').val(); // 내용
		chk_title = $('#post_remark').attr('title');
		if (chk_value == '' || chk_value == '<br>')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
				<?
					if ($set_file_class == 'OUT')
					{
				?>
						filecenter_msg_folder(msg.f_idx);
				<?
					}
					else
					{
				?>
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
				<?
					}
				?>
						close_data_form();
					}
					else
					{
						$("#loading").fadeOut('slow');
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);
		return false;
	}
//]]>
</script>
<?
	}
?>
