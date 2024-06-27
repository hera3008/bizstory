<?
/*
	생성 : 2012.12.26
	수정 : 2013.03.07
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무분류/등록/수정
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp  = $_SESSION[$sess_str . '_comp_idx'];
	$code_part  = search_company_part($code_part);
	$code_mem   = $_SESSION[$sess_str . '_mem_idx'];
	$code_level = $_SESSION[$sess_str . '_ubstory_level'];

	$project_where = " and pro.pro_idx = '" . $pro_idx . "'";
	$project_data = project_info_data('view', $project_where);

	if ($project_data['pro_status'] != 'PS01' && $project_data['pro_status'] != 'PS60' && $project_data['pro_status'] != 'PS90') // 대기, 취소, 완료
	{
		$work_class_yn  = 'Y';
	}
	else
	{
		$work_class_yn  = 'N';
	}

	$where = " and proc.pro_idx = '" . $pro_idx . "'";
	$list = project_class_data('list', $where, '', '', '');
?>
<div class="report">
	<div class="report_info">
		<table class="tinytable">
			<colgroup>
				<col width="60px" />
				<col />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><h3>상태</h3></th>
					<th class="nosort"><h3>업무분류 업무내용</h3></th>
					<th class="nosort"><h3>기한일</h3></th>
					<th class="nosort"><h3>공정률</h3></th>
					<th class="nosort"><h3>관리</h3></th>
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
		foreach($list as $k => $data)
		{
			if (is_array($data))
			{
				$pro_status       = $data['class_status'];
				$pro_status_str   = $data['pro_status_str'];
				$pro_status_bold  = $data['pro_status_bold'];
				$pro_status_color = $data['pro_status_color'];

			// 상태
				$status_str = '<span style="';
				if ($pro_status_bold == 'Y') $status_str .= 'font-weight:900;';
				if ($pro_status_color != '') $status_str .= 'color:' . $pro_status_color . ';';
				$status_str .= '">' . $pro_status_str . '</span>';

			// 총담당자구하기
				$charge_arr = explode(',', $data['charge_idx']);
				$total_charge_str = '';
				$chk_mem_idx = 'N';
				foreach ($charge_arr as $charge_k => $charge_v)
				{
					if ($charge_v != '')
					{
						if ($charge_v == $project_data['apply_idx'])
						{
							$pm_img = '<img src="' . $local_dir . '/bizstory/images/icon/ico_user_pm.gif" alt="책임자" /> ';
						}
						else $pm_img = '';

						$mem_view = staff_layer_form($charge_v, $pm_img, $set_part_work_yn, $set_color_list2, 'proclassstaff', $data['proc_idx'], '');

						if ($charge_v == $code_mem)
						{
							$chk_mem_idx = 'Y';
						}

						$total_charge_str .= ', ' . $mem_view . '';
					}
				}
				$total_charge_str = substr($total_charge_str, 2, strlen($total_charge_str));

			// 수정, 삭제 권한 - 등록자, 책임자 가능함
				if ($work_class_yn == 'Y')
				{
					if ($chk_mem_idx == 'Y') // 업무등록 - 담당자도 가능
					{
						$btn_work = "work_insert('" . $data['pro_idx'] . "', '" . $data['proc_idx'] . "');";
					}
					else
					{
						$btn_work = "check_auth_popup('');";
					}
					if ($data['mem_idx'] == $code_mem || $code_level <= '11' || $project_data['apply_idx'] == $code_mem)
					{
						$btn_work   = "work_insert('" . $data['pro_idx'] . "', '" . $data['proc_idx'] . "');";
						$btn_modify = "class_modify_form('open', '" . $data['proc_idx'] . "')";
						$btn_delete = "class_delete('" . $data['proc_idx'] . "')";
					}
				}
				else
				{
					$btn_work   = "check_auth_popup('');";
					$btn_modify = "check_auth_popup('modify');";
					$btn_delete = "check_auth_popup('delete');";
				}

			// 업무
				$work_where = " and wi.pro_idx = '" . $data['pro_idx'] . "' and wi.proc_idx = '" . $data['proc_idx'] . "'";
				$work_list = work_info_data('list', $work_where, '', '', '');

				if ($pro_status == 'PS90')
				{
					$end_str = date_replace($data['end_date'], 'Y-m-d');
				}
				else
				{
					if ($work_list['total_num'] == 0)
					{
						$end_str = 0 . '%';
					}
					else
					{
						$pwork_where = " and wi.pro_idx = '" . $data['pro_idx'] . "' and wi.proc_idx = '" . $data['proc_idx'] . "'
							and (wi.work_status = 'WS90' or wi.work_status = 'WS99' or wi.work_status = 'WS60')";
						$pwork_page = work_info_data('page', $pwork_where);

						$end_str = $pwork_page['total_num'] / $work_list['total_num'] * 100 . '%';
					}
				}
?>
				<tr>
					<td><?=$status_str;?></td>
					<td>
						<div class="left pro_subject"><strong><?=$data['subject'];?></strong></div>
						<div class="left pro_charge"><?=$total_charge_str;?></div>
				<?
					$work_num = 1;
					if ($work_list['total_num'] > 0)
					{
						echo '
						<div class="left">
							<table class="sub_table">';
						foreach ($work_list as $work_k => $work_data)
						{
							if (is_array($work_data))
							{
								$list_data = work_list_data($work_data, $work_data['wi_idx']);

								echo '
								<tr>
									<td><span class="pro_st">&nbsp;</span><img src="' . $local_dir . '/bizstory/images/icon/num_' . $work_num . '.png" alt="' . $work_num . '" /></td>
									<td class="left">' . $list_data['deadline_date_str'] . '</td>
									<td class="left">' . $list_data['project_status'] . '</td>
									<td class="left">' . $list_data['charge_str'] . '</td>
									<td class="left">:
										<a href="javascript:void(0);" onclick="location.href=\'' . $local_dir . '/index.php?fmode=work&smode=work&wi_idx=' . $work_data['wi_idx'] . '\'" class="aproclass"><span class="aproclass">', $list_data['subject'], '</span></a>
										' . $list_data['file_str'] . '
										' . $list_data['report_str'] . '
										' . $list_data['comment_str'] . '
									</td>
								</tr>
								';
								unset($list_data);
								$work_num++;
							}
						}
						echo '
							</table>
						</div>';
					}
					unset($work_data);
					unset($work_list);
				?>
					</td>
					<td><span class="num"><?=date_replace($data['deadline_date'], 'Y-m-d');?></span></td>
					<td><span class="num"><?=$end_str;?></span></td>
					<td>
						<a href="javascript:void(0);" onclick="<?=$btn_work;?>" class="btn_con_green"><span>등록</span></a><br />
						<a href="javascript:void(0);" onclick="<?=$btn_modify;?>" class="btn_con_blue"><span>수정</span></a><br />
						<a href="javascript:void(0);" onclick="<?=$btn_delete;?>" class="btn_con_red"><span>삭제</span></a><br />
					</td>
				</tr>
				<div id="class_modify_<?=$data['proc_idx'];?>" title="작업수정"></div>
<?
			}
		}
	}
?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 팝업등록폼 열기
	function work_insert(idx1, idx2)
	{
		$('#classlist_pro_idx').val(idx1);
		$('#classlist_proc_idx').val(idx2);

		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/project/project_view_work_form.php',
			data: $('#classlistform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 작업 수정
	function class_modify_form(form_type, proc_idx)
	{
		class_insert_form('close');
		class_list_data('');
		$("#classlist_proc_idx").val(proc_idx);

		if (form_type == 'close')
		{
			$("#class_modify_" + proc_idx).slideUp("slow");
			$("#class_modify_" + proc_idx).html("");
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: class_form,
				data: $('#classlistform').serialize(),
				success: function(msg) {
					$("#class_modify_" + proc_idx).slideUp("slow");
					$("#class_modify_" + proc_idx).slideDown("slow");
					$("#class_modify_" + proc_idx).html(msg);
				}
			});
		}
	}

//------------------------------------ 작업 삭제
	function class_delete(idx)
	{
		if (confirm("선택하신 작업을 삭제하시겠습니까?"))
		{
			$('#classlist_sub_type').val('delete');
			$('#classlist_proc_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: class_ok,
				data: $('#classlistform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						class_modify_form('close');
						class_list_data();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>