<?
/*
	생성 : 2012.12.20
	수정 : 2013.04.22
	위치 : 업무관리 > 프로젝트관리 - 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_part_yn      = $comp_set_data['part_yn'];
	$set_part_work_yn = $comp_set_data['part_work_yn'];
	$set_file_class   = $comp_set_data['file_class'];
	$pro_idx          = $idx;

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $pro_idx == '') || ($auth_menu['mod'] == 'Y' && $pro_idx != '')) // 등록, 수정권한
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
		$project_info = new project_info();
		$project_info->pro_idx = $pro_idx;

		$data      = $project_info->project_info_view();
		$file_list = $project_info->project_file_list();

		if ($sub_type == "postform")
		{
			$data['open_yn'] = 'Y';
		}

	// 파일
		$file_query = "select max(sort) as sort from project_file where pro_idx = '" . $pro_idx . "'";
		$file_chk = query_view($file_query);
		$file_upload_num = $file_chk['sort'];
		$file_chk_num    = $file_upload_num + 1;

	// 지사별
		$part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
		if ($set_part_work_yn == 'Y') { }
		else
		{
			if ($set_part_yn == 'N') $part_where .= " and part.part_idx = '" . $code_part . "'";
		}
		$part_list = company_part_data('list', $part_where, '', '', '');

		$charge_idx_arr = explode(',', $data['charge_idx']);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<input type="hidden" name="file_upload_num" id="file_upload_num" value="<?=$file_upload_num;?>" />

		<fieldset>
			<legend class="blind">프로젝트정보 폼</legend>
			<table class="tinytable write" summary="프로젝트정보를 등록/수정합니다.">
			<caption>프로젝트정보</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<tr>
					<th><label for="post_subject">제목</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[subject]" id="post_subject" class="type_text" title="제목을 입력하세요." size="50" value="<?=$data['subject'];?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_project_code">프로젝트코드</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[project_code]" id="post_project_code" class="type_text" title="프로젝트코드를 입력하세요." size="20" value="<?=$data['project_code'];?>" />
							<div class="field_help">* 프로젝트코드가 없을 경우 자동으로 생성이 됩니다.</div>
						</div>
					</td>
				</tr>
				<tr>
					<th>공개여부</th>
					<td>
						<div class="left">
							<?=code_radio($set_project_open, 'param[open_yn]', 'post_open_yn', $data['open_yn']);?>
							<div class="field_help">* 관련 첨부자료, 프로젝트분류와 보고, 코멘트의 상태도 공개여부에 따라 전환됩니다.</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_start_date">기한</label></th>
					<td>
						<div class="left">
							<input type="text" name="param[start_date]" id="post_start_date" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=date_replace($data['start_date'], 'Y-m-d');?>" />
							~
							<input type="text" name="param[deadline_date]" id="post_deadline_date" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=date_replace($data['deadline_date'], 'Y-m-d');?>" />
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_apply_idx">책임자</label></th>
					<td>
						<div class="left">
							<select name="param[apply_idx]" id="post_apply_idx" title="책임자를 지정하세요." onchange="select_apply();">
								<option value="">책임자를 지정하세요.</option>
						<?
							foreach ($part_list as $part_k => $part_data)
							{
								if (is_array($part_data))
								{
						?>
								<option value=""><?=$part_data['part_name'];?></option>
						<?
								// 지사별 직원
									$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
									$sub_order2 = "cpd.sort asc, mem.mem_name asc";
									$mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
									foreach ($mem_list as $mem_k => $mem_data)
									{
										if (is_array($mem_data))
										{
						?>
								<option value="<?=$mem_data['mem_idx'];?>" <?=selected($mem_data['mem_idx'], $data['apply_idx']);?>>&nbsp;&nbsp;&nbsp;&nbsp;<?=$mem_data['mem_name'];?></option>
						<?
										}
									}
								}
							}
						?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="post_charge_idx">담당자</label></th>
					<td>
						<input type="hidden" name="param[charge_idx]" id="post_charge_idx" value="<?=$data['charge_idx'];?>" title="담당자를 선택하세요." />
		<?
			$charge_idx_arr = explode(',', $data['charge_idx']);

		// 지사별
			foreach ($part_list as $part_k => $part_data)
			{
				if (is_array($part_data))
				{
					$part_idx     = $part_data['part_idx'];
					$part_name    = $part_data['part_name'];
					$part_sort    = $part_data['sort'];
					$part_color   = $set_color_list2[$part_sort];
					$part_check   = 'partidx' . $part_idx;
					$part_div_id  = 'part_charge_view_' . $part_idx;
					$part_span_id = 'part_charge_btn_' . $part_idx;

					echo '
						<div class="charge_view_box left">
							<ul>
								<li class="first">
									<label for="' . $part_check . '">
										<input type="checkbox" class="type_checkbox" title="' . $part_name . '" name="' . $part_check . '" id="' . $part_check . '" onclick="check_all2(\'' . $part_check . '\', this, \'1\'); select_member();" />
										<span style="color:' . $part_color . '">' . $part_name . '</span>
									</label>
									<span onclick="part_charge_chk(\'' . $part_idx . '\')" class="pointer" id="' . $part_span_id . '"> + </span>
								</li>
							</ul>
						</div>
						<div class="charge_view_box left none" id="' . $part_div_id . '">';

				// 그룹별
					$group_where = " and csg.part_idx = '" . $part_idx . "'";
					$group_list = company_staff_group_data('list', $group_where, '', '', '');
					foreach ($group_list as $group_k => $group_data)
					{
						if (is_array($group_data))
						{
							$group_idx   = $group_data['csg_idx'];
							$group_name  = $group_data['group_name'];
							$group_check = $part_check . '-' . $group_idx;

						// 직원
							$mem_where = " and mem.part_idx = '" . $part_idx . "' and mem.csg_idx = '" . $group_idx . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
							$mem_order = "cpd.sort asc, mem.mem_name asc";
							$mem_list = member_info_data('list', $mem_where, $mem_order, '', '');
							if ($mem_list['total_num'] > 0)
							{
								echo '
							<ul>
								<li class="second">
									<label for="' . $group_check . '">
										<input type="checkbox" class="type_checkbox" title="' .$group_name . '" name="' . $group_check . '" id="' . $group_check . '" onclick="check_all2(\'' . $group_check . '\', this, \'0\'); select_member();" /> <span>' . $group_name . '</span>
									</label>
									<ul>';

								foreach ($mem_list as $mem_k => $mem_data)
								{
									if (is_array($mem_data))
									{
										$mem_idx   = $mem_data['mem_idx'];
										$mem_name  = $mem_data['mem_name'];
										$mem_check = $group_check . '_' . $mem_idx;

										$checked = ''; $disabled = '';
										if (is_array($charge_idx_arr))
										{
											foreach ($charge_idx_arr as $charge_k => $charge_v)
											{
												if ($mem_idx == $charge_v)
												{
													$checked  = ' checked="checked"';
													$disabled = ' disabled="disabled"';
													$part_charge_on[$part_idx] = '
														$("#' . $part_div_id . '").css({"display": "block"});
														$("#' . $part_span_id . '").val(" - ");';
													break;
												}
											}
										}
										$total_member++;

										echo '
										<li class="mem_name">
											<label for="' . $mem_check . '">
												<input type="checkbox" name="project_member_idx[]" id="' . $mem_check . '" value="' . $mem_idx . '" class="type_checkbox"' . $checked . $disabled . ' title="' . $mem_name . '" onclick="select_member();" /> ' . $mem_name . '
											</label>
										</li>';
									}
								}
								echo '
									</ul>
								</li>
							</ul>';
							}
						}
					}
					echo '
						</div>';
				}
			}
		?>
					</td>
				</tr>
				<tr>
					<th><label for="post_remark">내용</label></th>
					<td>
						<div class="left textarea_span">
							<textarea name="param[remark]" id="post_remark" title="내용을 입력하세요." rows="5" cols="50" class="none"><?=$data['remark'];?></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td colspan="3">
						<div class="filewrap">
							<input type="file" name="file_fname" id="file_fname" class="type_text type_file type_multi" title="파일 선택하기" />
							<div class="file">
								<ul id="file_fname_view">
			<?
				foreach ($file_list as $file_k => $file_data)
				{
					if (is_array($file_data))
					{
						$file_chk = $file_data['sort'];
						$fsize = $file_data['img_size'];
						$fsize = byte_replace($fsize);
			?>
									<li id="file_fname_<?=$file_chk;?>_liview" class="org_file">
										<a href="<?=$local_diir;?>/bizstory/project/project_download.php?prof_idx=<?=$file_data['prof_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
										<a href="javascript:void(0);" class="btn_con" onclick="file_multi_form_delete('<?=$file_data['prof_idx'];?>', '<?=$file_chk;?>')"><span>삭제</span></a>
									</li>
			<?
					}
				}
			?>
								</ul>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="file_fname">파일 - 수정중</label></th>
					<td colspan="3">
<?
	if (strlen(stristr($mybrowser_val_val, 'IE')) > 0)
	{
		include $local_path . "/bizstory/filecenter/biz/file_activex.php";
	}
	else
	{
		$link_file = $local_path . "/bizstory/filecenter/biz/file_html.php";
	}
?>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
			<?
				if ($pro_idx == '') {
			?>
					<span class="btn_big_green"><input type="submit" value="등록하기" /></span>
					<span class="btn_big_green"><input type="button" value="등록취소" onclick="close_data_form()" /></span>

					<input type="hidden" name="sub_type" value="post" />
			<?
				} else {
			?>
					<span class="btn_big_blue"><input type="submit" value="수정하기" /></span>
					<span class="btn_big_blue"><input type="button" value="수정취소" onclick="close_data_form()" /></span>

					<input type="hidden" name="sub_type" value="modify" />
					<input type="hidden" name="pro_idx"  value="<?=$pro_idx;?>" />
					<input type="hidden" name="old_deadline_date" id="old_deadline_date" value="<?=$data['deadline_date'];?>" />
					<input type="hidden" name="old_apply_idx"     id="old_apply_idx"     value="<?=$data['apply_idx'];?>" />
					<input type="hidden" name="old_charge_idx"    id="old_charge_idx"    value="<?=$data['charge_idx'];?>" />
					<input type="hidden" name="old_pro_status"    id="old_pro_status"    value="<?=$data['pro_status'];?>" />
			<?
				}
			?>
				</div>
			</div>

		</fieldset>
			<?=$form_all;?>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	$(".datepicker").datepicker();

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

	var file_chk_num = <?=$file_chk_num;?>;
	file_multi_setting('file_fname', '<?=$file_multi_size;?>', 'project', '');

//------------------------------------ 등록, 수정
	function check_form()
	{
		var action_num = 0;
		var chk_msg = '', chk_total = '';
		var chk_value = '', chk_title = '';

		chk_value = $('#post_subject').val(); // 제목
		chk_title = $('#post_subject').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

	// 오늘날짜 이전은 안됨
<?
	if ($pro_idx == '')
	{
?>
		chk_value = $('#post_deadline_date').val();
		chk_value = chk_value.replace('-', '');
		chk_value = chk_value.replace('-', '');
		if (chk_value < <?=date('Ymd');?>)
		{
			chk_total = chk_total + '이전 날짜는 선택하실 수 없습니다.<br />';
			action_num++;
		}
<?
	}
?>
	// 종료일이 시작일보다 크도록
		var chk_start = $('#post_start_date').val();
		chk_start = chk_start.replace('-', '');
		chk_start = chk_start.replace('-', '');

		var chk_end = $('#post_deadline_date').val();
		chk_end = chk_end.replace('-', '');
		chk_end = chk_end.replace('-', '');

		if (chk_start > chk_end)
		{
			chk_total = chk_total + '종료일은 시작일보다 작을 수 없습니다.<br />';
			action_num++;
		}

		chk_value = $('#post_apply_idx').val(); // 책임자
		chk_title = $('#post_apply_idx').attr('title');
		chk_msg = check_input_value(chk_value);
		if (chk_msg == 'No')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		select_member();

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
						close_data_form();
						file_preview(msg.f_class, msg.f_idx); // 미리보기를 위해서 파일변환
				<?
					if ($set_file_class == 'OUT')
					{
				?>
						filecenter_project_folder(msg.f_idx);
				<?
					}
				?>
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

// 담당자 - 선택
	function select_member()
	{
		var mem_idx  = document.getElementsByName('project_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = ''

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx  = mem_idx[i].value;
				}
				else
				{
					total_member_idx  += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}

		var chk_mem = $('#post_charge_idx').val();
		if (chk_mem != '')
		{
			total_member_idx += ',' + chk_mem;
		}

		if (j >= <?=$total_member;?>)
		{
			check_auth_popup('직원모두를 선택할 수 없습니다.');
		}
		else
		{
			$('#post_charge_idx').val(total_member_idx);
		}
	}

// 책임자 - 선택 - 담당자에서 제외
	function select_apply()
	{
		$('#post_charge_idx').val();
	}

// 담당자관련
<?
	if (is_array($part_charge_on))
	{
		foreach ($part_charge_on as $on_k => $on_v)
		{
			echo $on_v;
		}
	}
?>
	function part_charge_chk(idx)
	{
		$("#part_charge_btn_" + idx).html(" - ");
		$("#part_charge_view_" + idx).css({"display": "block"});
	}
//]]>
</script>
<?
	}
?>
