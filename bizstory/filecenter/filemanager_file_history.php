<?
/*
	생성 : 2013.03.08
	생성 : 2013.05.02
	위치 : 파일센터 > 파일관리 - 목록 - 파일이력
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>이력보기</strong>
		<img src="/bizstory/images/filecenter/icon_close.png" onclick="popupform_close();" alt="닫기" />
	</div>
	<div class="ajax_frame">
<?
// 파일정보
	$chk_where = " and fi.fi_idx = '" . $idx . "'";
	$chk_data = filecenter_info_data('view', $chk_where);

	if ($chk_data['total_num'] == 0)
	{
		echo '선택한 파일이 삭제가 되어 이력을 볼 수 없습니다.';
	}
	else
	{
		$up_fi_idx = $chk_data['up_fi_idx'];
		$up_fi_arr = explode(',', $up_fi_idx);
		$up_fi_num = count($up_fi_arr) - 1;
		$up_idx    = $up_fi_arr[$up_fi_num];
		$dir_depth = $chk_data['dir_depth'];
		
		$next_depth = $dir_depth + 1;

		$dir_auth = filecenter_folder_auth($up_idx, $idx); // 권한 - 현 위치 폴더에 대한 권한 - up_idx

		if ($dir_auth['dir_read_auth'] == 'Y' || $dir_auth['dir_write_auth'] == 'Y') // 읽기, 쓰기일 경우 가능함.
		{
			$where   = " and fh.fi_idx = '" . $idx . "'";
			$list = filecenter_history_data('list', $where, '', '', '');
?>
		<form id="hislistform" name="hislistform" method="post" action="<?=$this_page;?>">
			<input type="hidden" id="hislist_fi_idx" name="fi_idx" value="<?=$idx;?>" />
			<?=$form_page;?>
			<div id="history_list_data"></div>
		</form>

		<table class="tinytable">
			<colgroup>
				<col width="30px" />
				<col />
				<col width="80px" />
				<col width="80px" />
				<col width="80px" />
				<col width="110px" />
			</colgroup>
			<thead>
				<tr>
					<th class="nosort"><input type="checkbox" name="fhidx" onclick="check_all('fhidx', this);" /></th>
					<th class="nosort"><h3>파일명</h3></th>
					<th class="nosort"><h3>올린사람</h3></th>
					<th class="nosort"><h3>올린날짜</h3></th>
					<th class="nosort"><h3>파일관리</h3></th>
					<th class="nosort"><h3>이력관리</h3></th>
				</tr>
			</thead>
			<tbody>
		<?
			$i = 0;
			if ($list["total_num"] == 0) {
		?>
				<tr>
					<td colspan="6">등록된 이력데이타가 없습니다.</td>
				</tr>
		<?
			}
			else
			{
				$i = 1;
				$num = $list["total_num"];
				foreach($list as $k => $data)
				{
					if (is_array($data))
					{
						$reg_type = substr($data['reg_type'], 0, 8);
						if ($data['reg_type'] != 'update' && $reg_type != 'download' && $data['reg_type'] != 'delete')
						{
							$checkbox_html = '<input type="checkbox" id="fhidx_' . $i . '" name="chk_fh_idx[]" value="' . $data['fh_idx'] . '" title="선택" />';
							$icon_img      = file_ext_img($data['file_ext']) . ' ';

							$charge_str = staff_layer_form($data['reg_id'], '', 'N', $set_color_list2, 'fileliststtaff', $data['fi_idx'], '');
		?>
				<tr>
					<td><?=$checkbox_html;?></td>
					<td>
						<div class="left">
							<?=$icon_img;?><a href="<?=$set_filecneter_url;?>/file_download.php?fh_type=history&amp;idx=<?=$data['fh_idx'];?>" title="<?=$data['new_subject'];?> 다운로드"><?=$data['new_subject'];?></a>
						</div>
						<div class="left history_st">
					<?
						if ($data['contents'] != '') echo $data['contents'];

					?>
						</div>
					</td>
					<td><?=$charge_str;?></td>
					<td><span class="eng"><?=date('Y-m-d', $data["reg_date"]);?></span></td>
					<td>
					<?											
						if ($i > 1) {
					?>
						<a href="javascript:void(0);" onclick="history_file_delete('<?=$data['fh_idx']?>')" class="btn_con_red"><span>삭제</span></a>
					<?
						}
					?>
					</td>
					<td>
				<?
					if ($data['contents'] != '')
					{
						echo '<a href="javascript:void(0);" onclick="history_contents(\'' . $data['fh_idx'] . '\')" class="btn_con_blue"><span>수정</span></a>&nbsp;';
						echo '<a href="javascript:void(0);" onclick="history_contents_delete(\'' . $data['fh_idx'] . '\')" class="btn_con_red"><span>삭제</span></a>';
					}
					else
					{
						echo '<a href="javascript:void(0);" onclick="history_contents(\'' . $data['fh_idx'] . '\')" class="btn_con_green"><span>등록</span></a>';
					}
				?>
					</td>
				</tr>
		<?
							$num--;
							$i++;
						}
					}
				}
			}
		?>
			</tbody>
		</table>
<?
		}
		else
		{
			echo '권한이 없습니다. 이력보기가 가능하지 않습니다.';
		}
	}
?>
	</div>
</div>

<form id="historyform" name="historyform" method="post" action="<?=$this_page;?>">
	<input type="hidden" id="history_sub_type" name="sub_type" value="" />
	<input type="hidden" id="history_fi_idx"   name="fi_idx"   value="<?=$idx;?>" />
	<input type="hidden" id="history_fh_idx"   name="fh_idx"   value="" />
	<input type="hidden" id="code_mem"         name="code_mem" value="<?=$_SESSION[$sess_str . '_mem_idx']?>"/>
</form>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 비고 수정, 등록
	function history_contents(idx)
	{
		$('#history_fh_idx').val(idx);
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filemanager_file_history_form.php',
			data: $('#historyform').serialize(),
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

//------------------------------------ 이력관리 삭제
	function history_contents_delete(idx)
	{
		if (confirm("선택하신 데이타의 이력관리를 삭제하시겠습니까?"))
		{
			$('#history_fh_idx').val(idx);
			$('#history_sub_type').val('history_delete');
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#historyform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_history('<?=$idx;?>');
					}
					else
					{
						check_auth_popup(msg.error_string);
					}
				}
			});
		}
	}

//------------------------------------ 파일선택삭제
	function history_file_delete(idx)
	{
		if (confirm("선택하신 파일을 삭제하시겠습니까?"))
		{
			$('#history_fh_idx').val(idx);
			$('#history_sub_type').val('filecenter_delete_select');

			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/file_ok.php', jsonp : 'callback',
				data: $('#historyform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						file_history('<?=$idx;?>');
					}
					else
					{
						check_auth_popup(msg.error_string);
					}
				},
				complete: function(){
					$("#loading").fadeOut("slow");
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}

	}

		
//------------------------------------ 이력목록
	function history_list_data()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filemanager_file_history_list.php',
			data: $('#hislistform').serialize(),
			success: function(msg) {
				$('#history_list_data').html(msg);
			}
		});
	}

//------------------------------------ 페이지이동 - 댓글
	function page_move_history(str)
	{
		var total_page = $('#history_new_total_page').val();
		var page_num   = $('#history_page_page_num').val();

		if (str == 'first')
		{
			$('#history_page_page_num').val(1);
		}
		else if (str == 'last')
		{
			$('#history_page_page_num').val(total_page);
		}
		else if (str == 'prev')
		{
			page_num = parseInt(page_num) - 1;
			if (page_num < 1) page_num = 1;
			$('#history_page_page_num').val(page_num);
		}
		else if (str == 'next')
		{
			page_num = parseInt(page_num) + 1;
			if (page_num > total_page) page_num = total_page;
			$('#history_page_page_num').val(page_num);
		}
		else if (str == 'all')
		{
			$('#history_page_page_num').val(1);
			$('#history_page_page_size').append('<option value="1000">1000</option>');
			$('#history_page_page_size').val(1000);
		}
		else
		{
			$('#history_page_page_num').val(str);
		}
		history_list_data()
	}

	history_list_data();
//]]>
</script>

