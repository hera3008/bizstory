<?
/*
	생성 : 2013.03.21
	수정 : 2013.04.26
	위치 : 파일센터 > 파일관리 - 파일 복사, 이동
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

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

	$path_data = filecenter_folder_path($up_idx); // 현위치
	$dir_auth  = filecenter_folder_auth($up_idx); // 권한확인

	$form_chk = 'N';
	if ($dir_auth['dir_write_auth'] == 'Y') // 복사, 이동권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
				$("#backgroundPopup").fadeOut("slow");
			//]]>
			</script>
		';
	}

// 해당폴더에 대한 권한을 가지고 처리한다.
	if ($form_chk == 'Y')
	{
		$chk_fi_idx = $_POST['chk_fi_idx'];
		if ($sub_type == 'file_copy') $file_title  = "복사";
		else $file_title  = "이동";
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>파일 <?=$file_title;?></strong>
		<img src="/bizstory/images/filecenter/icon_close.png" onclick="popup_file_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li><?=$file_title;?>할 위치에 같은 이름의 파일이 있을 경우 <?=$file_title;?>할 수 없습니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">
		<div class="upload_l">
			<p>현위치 <span><?=$path_data['navi_path'];?></span></p>
			<div class="upload_l_btn">
				<a href="javascript:void(0);" onclick="open_dir_change('<?=$up_idx;?>', '<?=$up_level;?>', 'open')" class="btn_con_green"><span>위치변경</span></a>
			</div>
		</div>
		<div id="dir_list_change" title="변경할 폴더목록"></div>

		<form id="copyform" name="copyform" method="post" class="writeform" action="<?=$this_page;?>">
			<?=$form_all;?>
			<input type="hidden" id="copy_code_part" name="code_part" value="<?=$code_part;?>" />
			<input type="hidden" id="copy_code_mem"  name="code_mem"  value="<?=$code_mem;?>" />
			<input type="hidden" id="copy_up_idx"    name="up_idx"    value="<?=$up_idx;?>" />
			<input type="hidden" id="copy_sub_type"  name="sub_type"  value="<?=$sub_type;?>" />

			<div id="file_copy_move_list">

				<fieldset>
					<legend class="blind">파일 <?=$file_title;?> 폼</legend>

					<table class="tinytable write" summary="<?=$file_title;?>할 파일목록입니다.">
					<caption><?=$file_title;?>할 파일목록</caption>
					<colgroup>
						<col width="30px" />
						<col width="120px" />
						<col />
					</colgroup>
					<tbody>
<?
	$chk_num = 1;
	foreach ($chk_fi_idx as $k => $v)
	{
		$where = " and fi.fi_idx = '" . $v . "'";
		$data = filecenter_info_data('view', $where);

		$file_size = byte_replace($data['file_size']);
		$copy_file_path = $data['file_path'];
?>
						<tr>
							<td><?=$chk_num;?></td>
							<td>
								<div class="left">
									<?=$data['file_name'];?>(<?=$file_size;?>)
									<input type="hidden" name="chk_fi_idx[]" id="copyfiidx_<?=$k;?>" value="<?=$v;?>" />
								</div>
							</td>
						</tr>
<?
		$chk_num++;
	}
?>
					</tbody>
					</table>

					<div class="section">
						<div class="fr">
							<span class="btn_big_green"><input type="button" value="파일<?=$file_title;?>" onclick="check_copy_move_file()" /></span>
							<span class="btn_big_green"><input type="button" value="<?=$file_title;?>취소" onclick="popup_file_close()" /></span>
						</div>
					</div>
				</fieldset>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 복사, 이동 중복확인
	function check_copy_move_file()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		$("#loading").fadeIn('slow');
		$.ajax({
			type: 'post', dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filemanager_file_copy_check.php',
			data: $('#copyform').serialize(),
			success: function(msg) {
				$("#file_copy_move_list").html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
			}
		});
	}

//------------------------------------ 복사, 이동
	function check_copy_move()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		var chk_num = chk_checkbox_num('copyfiidx');
		if (chk_num == 0)
		{
			check_auth_popup('파일을 선택하세요.');
		}
		else
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'jsonp', url: 'http://<?=$filecneter_url;?>/filemanage/file_ok.php', jsonp : 'callback',
				data: $('#copyform').serialize(),
				success: function(msg) {
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_data();
					}
					else
					{
						$("#loading").fadeOut('slow');
						$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
						check_auth_popup(msg.error_string);
					}
				}
			});
		}
	}

//------------------------------------ 파일위치변경
	function open_dir_change(up_idx, up_level, up_type)
	{
		$('#list_up_idx').val(up_idx);
		$('#list_up_level').val(up_level);
		$('#copy_up_idx').val(up_idx);

		if (up_type == 'open')
		{
			$('#list_old_up_idx').val(up_idx);
			$('#list_old_up_level').val(up_level);
		}

		$("#loading").fadeIn('slow');
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filemanager_file_change.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				$("#dir_list_change").html(msg);
				check_copy_move_file();
			},
			complete: function(){
				$("#loading").fadeOut("slow");
			}
		});
	}

	open_dir_change('<?=$up_idx;?>', '<?=$up_level;?>', 'open');
//]]>
</script>
<?
	}
?>