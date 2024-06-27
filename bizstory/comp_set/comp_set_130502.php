<?
/*
	수정 : 2013.03.22
	위치 : 설정관리 > 회사관리 > 회사설정
*/
	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$set_tax_yn = $comp_set_data['tax_yn'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default;
	$f_page    = $f_search;
	$f_all     = $f_page;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_ok = $local_dir . "/bizstory/comp_set/comp_set_ok.php"; // 저장

	$where = " and cs.comp_idx = '" . $code_comp . "'";
	$data = company_set_data('view', $where);

	$charge_idx = $data['receipt_charge'];
	$charge_idx_arr = explode(',', $charge_idx);
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
			<?=$form_all;?>
			<input type="hidden" name="sub_type" value="modify" />
			<input type="hidden" name="cs_idx"   value="<?=$data['cs_idx'];?>" />

			<fieldset>
				<legend class="blind">회사설정</legend>
				<table class="tinytable write" summary="회사설정를 수정합니다.">
				<caption>회사설정</caption>
				<colgroup>
					<col width="120px" />
					<col />
				</colgroup>
				<tbody>
					<tr>
						<th><label for="post_receipt_charge">접수알림 직원</label></th>
						<td>
							<input type="hidden" name="param[receipt_charge]" id="post_receipt_charge" value="<?=$data['receipt_charge'];?>" title="접수알림 직원 선택하세요." />
							<div class="left">
				<?
				// 지사별
					$part_where = " and part.comp_idx = '" . $code_comp . "' and part.view_yn = 'Y'";
					$part_list = company_part_data('list', $part_where, '', '', '');
					foreach ($part_list as $part_k => $part_data)
					{
						if (is_array($part_data))
						{
							$chk_str = 'partidx' . $part_data['part_idx'];
				?>
								<ul>
									<li class="first">
										<label for="<?=$chk_str;?>">
											<input type="checkbox" class="type_checkbox" title="<?=$part_dcata['part_name'];?>" name="<?=$chk_str;?>" id="<?=$chk_str;?>" onclick="check_all2('<?=$chk_str;?>', this, '1');" />
											<span style="color:<?=$set_color_list2[$part_data['sort']];?>"><?=$part_data['part_name'];?></span>
										</label>
									</li>
								</ul>
				<?
						// 그룹별
							$group_where = " and csg.comp_idx = '" . $code_comp . "' and csg.part_idx = '" . $part_data['part_idx'] . "'";
							$group_list = company_staff_group_data('list', $group_where, '', '', '');
							foreach ($group_list as $group_k => $group_data)
							{
								if (is_array($group_data))
								{
									$chk_strg = $chk_str . '-' . $group_data['csg_idx'];

								// 지사별 직원
									$sub_where2 = " and mem.comp_idx = '" . $code_comp . "' and mem.part_idx = '" . $part_data['part_idx'] . "' and mem.csg_idx = '" . $group_data['csg_idx'] . "' and mem.auth_yn = 'Y' and mem.login_yn = 'Y'";
									$sub_order2 = "csg.sort asc, cpd.sort asc, mem.mem_name asc";
									$mem_list = member_info_data('list', $sub_where2, $sub_order2, '', '');
									if ($mem_list['total_num'] > 0)
									{
				?>
								<ul>
									<li class="second">
										<label for="<?=$chk_strg;?>">
											<input type="checkbox" class="type_checkbox" title="<?=$group_data['group_name'];?>" name="<?=$chk_strg;?>" id="<?=$chk_strg;?>" onclick="check_all2('<?=$chk_strg;?>', this, '0');" />
											<span><?=$group_data['group_name'];?></span>
										</label>
										<ul>
				<?
										foreach ($mem_list as $mem_k => $mem_data)
										{
											if (is_array($mem_data))
											{
												$checkbox_str = $chk_strg . '_' . $mem_data['mem_idx'];
												$checked = '';
												if (is_array($charge_idx_arr))
												{
													foreach ($charge_idx_arr as $charge_k => $charge_v)
													{
														if ($mem_data['mem_idx'] == $charge_v)
														{
															$checked = 'checked="checked"';
															break;
														}
													}
												}
												$total_member++;
				?>
											<li class="mem_name">
												<label for="<?=$checkbox_str;?>">
													<input type="checkbox" name="check_member_idx[]" id="<?=$checkbox_str;?>" value="<?=$mem_data['mem_idx'];?>" class="type_checkbox" <?=$checked;?><?=$disabled;?> title="<?=$mem_data['mem_name'];?>" />
													<?=$mem_data['mem_name'];?>
												</label>
											</li>
				<?
											}
										}
				?>
										</ul>
									</li>
								</ul>
				<?
									}
									unset($mem_data);
									unset($mem_list);
								}
							}
							unset($group_data);
							unset($group_list);
						}
					}
					unset($part_data);
					unset($part_list);
				?>
							</div>
						</td>
					</tr>
					<tr>
						<th><label for="post_receipt_inform_yn">접수알리미여부</label></th>
						<td>
							<div class="left">
								<?=code_radio($set_use, "param[receipt_inform_yn]", "post_receipt_inform_yn", $data["receipt_inform_yn"]);?>
							</div>
						</td>
					</tr>
				</tbody>
				</table>

				<div class="section">
					<div class="fr">
						<span class="btn_big_blue"><input type="submit" value="수정" /></span>
						<span class="btn_big_blue"><input type="button" value="취소" onclick="location.href='<?=$this_page;?>?<?=$f_all;?>'"/></span>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_ok = '<?=$link_ok;?>';

//------------------------------------ Save
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		var mem_idx = document.getElementsByName('check_member_idx[]');
		var i = 0, j = 0;
		var total_member_idx = '';

		while(mem_idx[i])
		{
			if (mem_idx[i].type == 'checkbox' && mem_idx[i].disabled == false && mem_idx[i].checked == true)
			{
				if (j == 0)
				{
					total_member_idx = mem_idx[i].value;
				}
				else
				{
					total_member_idx += ',' + mem_idx[i].value;
				}
				j++;
			}
			i++;
		}
		$('#post_receipt_charge').val(total_member_idx);

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
						check_auth_popup('정상적으로 처리되었습니다.');
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		return false;
	}
//]]>
</script>