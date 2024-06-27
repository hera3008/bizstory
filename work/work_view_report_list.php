<?
/*
	생성 : 2012.04.25
	수정 : 2012.09.27
	위치 : 업무폴더 > 나의업무 > 업무 - 보기 - 업무보고서목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$where = " and wr.wi_idx = '" . $wi_idx . "'";
	$list = work_report_data('list', $where, '', $m_page_num, $m_page_size);

	$work_where = " and wi.wi_idx = '" . $wi_idx . "'";
	$work_data = work_info_data('view', $work_where);

// 업무보고서
	if ($work_data['work_status'] != 'WS01' && $work_data['work_status'] != 'WS60' && $work_data['work_status'] != 'WS90' && $work_data['work_status'] != 'WS99' && $work_data['work_status'] != 'WS20') // 대기, 취소, 완료, 종료, 승인대기
	{
		$work_report_yn  = 'Y';
	}
	else
	{
		$work_report_yn  = 'N';
	}

	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$sub_where = " and mem.mem_idx = '" . $data['mem_idx'] . "'";
			$sub_data = member_info_data('view', $sub_where);

		// 업무보고 읽음으로 표시 - 자기것은 제외
			work_report_comment_check($wi_idx, $data['wr_idx'], '');

			$mem_img = member_img_view($data['mem_idx'], $comp_member_dir); // 등록자 이미지
?>
<div class="report" id="report_list_<?=$data['wr_idx'];?>">
	<div class="report_info">
		<span class="mem"><?=$mem_img['img_26'];?></span>
		<span class="user">
			<span class="relative">
				<a href="javascript:void(0)" onclick="staff_layer_open('<?=$data['mem_idx'];?>');" class="name_ui"><?=$data['writer'];?></a>
			</span>
				<div id="objreport_<?=$data['mem_idx'];?>_<?=$wi_idx;?>_<?=$data['wr_idx'];?>" class="none"></div>
		</span>
		<span class="date">
<?
			$chk_date = date_replace($data['reg_date'], 'Y-m-d');
			if ($chk_date == date('Y-m-d'))
			{
				echo '<strong>', $data['reg_date'] , '</strong>';
			}
			else
			{
				echo $data['reg_date'];
			}
?>
		</span>
<?
	if ($work_report_yn == 'Y')
	{
		if ($data['mem_idx'] == $code_mem || $_SESSION[$sess_str . '_ubstory_level'] <= '11')
		{
?>
		<a class="btn_i_update" href="javascript:void(0)" onclick="report_modify_form('open', '<?=$data['wr_idx'];?>')"></a>
		<a class="btn_i_delete" href="javascript:void(0)" onclick="report_delete('<?=$data['wr_idx'];?>')"></a>
<?
		}
	}
?>
	</div>

	<div id="report_modify_<?=$data['wr_idx'];?>" title="업무보고수정"></div>

	<div class="report_wrap" id="report_view_<?=$data['wr_idx'];?>">
		<div class="report_data">
			<div class="user_edit">
				<?=$data['remark'];?>
			</div>
			<div class="file">
<?
	$file_where = " and wrf.wr_idx = '" . $data['wr_idx'] . "'";
	$file_list = work_report_file_data('list', $file_where, '', '', '');

	if ($file_list['total_num'] > 0) {
?>
				<ul>
<?
		foreach ($file_list as $file_k => $file_data)
		{
			if (is_array($file_data))
			{
				$fsize = $file_data['img_size'];
				$fsize = byte_replace($fsize);
				$btn_str = preview_file($comp_work_dir, $file_data['wrf_idx'], 'work_report');
?>
					<li>
						<?=$btn_str;?>
						<a href="<?=$local_diir;?>/bizstory/work/work_view_report_download.php?wrf_idx=<?=$file_data['wrf_idx'];?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_data['img_fname'];?> (<?=$fsize;?>)</a>
					</li>
<?
			}
		}
?>
				</ul>
<?
		$btn_img = preview_images($data['wr_idx'], 'work_report');
		if ($btn_img != '')
		{
			echo '
				<div>' . $btn_img . '</div>
			';
		}
	}
?>
			</div>
		</div>
	</div>

</div>
<?
		}
	}
?>
<input type="hidden" id="report_new_total_page" value="<?=$list['total_page'];?>" />
<hr />

<div class="tablefooter_m">
	<?=page_view_comment($m_page_size, $m_page_num, $list['total_page'], 'report');?>
</div>
<div class ="clear"></div>
<hr />
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 업무보고서 수정
	function report_modify_form(form_type, wr_idx)
	{
		report_insert_form('close');
		$("#reportlist_wr_idx").val(wr_idx);
		if (form_type == 'close')
		{
			$("#report_modify_" + wr_idx).slideUp("slow");
			$("#report_modify_" + wr_idx).html("");
		}
		else
		{
			$.ajax({
				type: "post", dataType: 'html', url: report_form,
				data: $('#reportlistform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					$("#report_modify_" + wr_idx).slideUp("slow");
					$("#report_modify_" + wr_idx).slideDown("slow");
					$("#report_modify_" + wr_idx).html(msg);
				}
			});
		}
	}

//------------------------------------ 업무보고서 삭제
	function report_delete(idx)
	{
		if (confirm("선택하신 보고서를 삭제하시겠습니까?"))
		{
			$('#reportlist_sub_type').val('delete');
			$('#reportlist_wr_idx').val(idx);

			$.ajax({
				type: "post", dataType: 'json', url: report_ok,
				data: $('#reportlistform').serialize(),
				beforeSubmit: function(){
					$("#loading").fadeIn('slow').fadeOut('slow');
				},
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						$('#report_total_value').html(msg.total_num);
						report_modify_form('close')
						report_list_data();
						list_data();
					}
					else check_auth_popup(msg.error_string);
				}
			});
		}
	}
//]]>
</script>