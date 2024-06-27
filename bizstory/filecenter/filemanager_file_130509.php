<?
/*
	생성 : 2013.02.07
	수정 : 2013.04.03
	위치 : 파일센터 > 파일관리 - 파일업로드 html5
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
	if ($dir_auth['dir_write_auth'] == 'Y') // 등록권한
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
		$max_size1 = $file_max_size / 1024 / 1024;
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>V-Drive</strong> Upload Component
		<img src="/bizstory/images/filecenter/icon_close.png" onclick="popup_file_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li>'파일 추가' 버튼을 클릭해서 파일을 추가하고 난뒤 '파일 전송'을 클릭하세요.</li>
			<li>한번에 올릴 수 있는 파일 갯수는 <strong><?=$file_max_cnt1;?> 개</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 하나파일 용량은 <strong><?=$file_max_file1;?> byte</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 전체 용량은 <strong><?=$file_max_size1;?> byte</strong> 입니다.</li>
		</ul>
	</div>
	<div class="ajax_frame">

		<div class="upload_l">
			<p>현위치 <span><?=$path_data['navi_path'];?></span></p>
			<div class="upload_l_btn">
				<a href="javascript:void(0);" onclick="open_dir_change('<?=$up_idx;?>', '<?=$up_level;?>', 'open')" class="btn_con_blue"><span>위치변경</span></a>
			</div>
		</div>
		<div id="dir_list_change" title="변경할 폴더목록"></div>

		<form id="isForm" name="isForm" method="post">
			<input type="hidden" id="isForm_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />
			<input type="hidden" id="isForm_part_idx" name="part_idx" value="<?=$code_part;?>" />
			<input type="hidden" id="isForm_up_idx"   name="up_idx"   value="<?=$up_idx;?>" />
			<input type="hidden" id="isForm_mem_idx"  name="mem_idx"  value="<?=$code_mem;?>" />
			<input type="hidden" id="isForm_max_size" name="max_size" value="<?=$max_size1;?>" />
		</form>

		<div style="border:1px #ccc solid; margin:5px 0 0 0; padding:0;">
			<iframe id="loadUploader" width="100%" height="300" scrolling="no" style="margin:0; padding:0;"></iframe>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var comp_idx = $('#isForm_comp_idx').val();
	var part_idx = $('#isForm_part_idx').val();
	var up_idx   = $('#isForm_up_idx').val();
	var mem_idx  = $('#isForm_mem_idx').val();
	var max_size = $('#isForm_max_size').val();
	var move_string = "comp_idx=" + comp_idx + "&part_idx=" + part_idx + "&up_idx=" + up_idx + "&mem_idx=" + mem_idx + "&max_size=" + max_size;
	$("#loadUploader").attr("src", "http://<?=$filecneter_url;?>/filemanage/xupload/filecenter_html.php?" + move_string);

//------------------------------------ 파일위치변경
	function open_dir_change(up_idx, up_level, up_type)
	{
		$('#list_up_idx').val(up_idx);
		$('#list_up_level').val(up_level);
		var btn_dis = 'block';

		if (up_type == 'open')
		{
			$('#list_old_up_idx').val(up_idx);
			$('#list_old_up_level').val(up_level);
		}

		if (up_type == 'Y')
		{
			$('#isForm_up_idx').val(up_idx);
		}
		else
		{
			btn_dis = 'none';
		}

		$("#loading").fadeIn('slow');
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filemanager_file_change.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				$("#dir_list_change").html(msg);
			},
			complete: function(){
				$("#loading").fadeOut("slow");
			}
		});

		var comp_idx = $('#isForm_comp_idx').val();
		var part_idx = $('#isForm_part_idx').val();
		var up_idx   = $('#isForm_up_idx').val();
		var mem_idx  = $('#isForm_mem_idx').val();
		var max_size = $('#isForm_max_size').val();
		var move_string = "comp_idx=" + comp_idx + "&part_idx=" + part_idx + "&up_idx=" + up_idx + "&mem_idx=" + mem_idx + "&max_size=" + max_size + "&btn_dis=" + btn_dis;
		$("#loadUploader").attr("src", "http://<?=$filecneter_url;?>/filemanage/xupload/filecenter_html.php?" + move_string);
	}
//]]>
</script>
<?
	}
?>