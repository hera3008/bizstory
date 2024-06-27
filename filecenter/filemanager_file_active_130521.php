<?
/*
	생성 : 2013.02.07
	수정 : 2013.05.08
	위치 : 파일센터 > 파일관리 - 파일업로드 ie
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
?>
<script type="text/javascript" for="CHXFile" event="UploadFile(fileName)">
/*
	alert(fileName);
	if (fileName != '')
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filecenter_preview.php',
			data: { },
			success: function(msg) {
				$("#preview_file_result").html(msg);
			}
		});
	}
*/
</script>
<!--파일 전송이 완료되면 UploadFinish 이벤트가 자동으로 발생합니다.-->
<script type="text/javascript" for="CHXFile" event="UploadFinish(chk_idx)">
// 변수를 통해서 데이타 다시 저장하기, 파일 옮기기
	if (chk_idx != '')
	{
		$.ajax({
			type: "post", dataType: 'json', url: '<?=$set_filecneter_url;?>/xupload/filecenter_activex_ok.php',
			data: { 'comp_idx' : '<?=$code_comp;?>', 'part_idx' : '<?=$code_part;?>', 'mem_idx' : '<?=$code_mem;?>', 'up_idx' : '<?=$up_idx;?>', 'chk_idx' : chk_idx },
			success: function(msg) {
				alert('완료되었습니다.');
			}
		});
	}
</script>

<div class="ajax_write">
	<div class="upload_title">
		<strong>V-Drive</strong> Upload Component
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popup_file_close();" alt="닫기" />
	</div>
	<div class="info_text">
		<ul>
			<li>파일을 추가하고 난뒤 '파일전송'을 클릭하세요.</li>
			<li>위치변경시 업로드권한이 없으면 '파일전송'버튼이 보이지 않습니다.</li>
			<li>한번에 올릴 수 있는 파일 갯수는 <strong><?=$file_max_cnt1;?> 개</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 하나파일 용량은 <strong><?=$file_max_file1;?> byte</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 전체 용량은 <strong><?=$file_max_size1;?> byte</strong> 입니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">

		<div class="upload_l">
			<p>현위치 <span><?=$path_data['navi_path'];?></span></p>
			<div class="upload_l_btn">
				<a href="javascript:void(0);" onclick="open_dir_change('<?=$up_idx;?>', '<?=$up_level;?>', 'open');" class="btn_con_blue"><span>위치변경</span></a>
			</div>
		</div>
		<div id="dir_list_change" title="변경할 폴더목록"></div>

		<div class="align_r pt01 mb01">
			<span class="btn_big"><input type="button" value="파일추가" onclick="CHXFile.AddFile()" /></span>
		</div>

		<div style="border:1px #ccc solid;">
			<div style="width:100%;">
				<object id="CHXFile" width="100%" height="200" classid="clsid:16ADD49A-D148-401E-877C-1B2D1DDEBC7E" codebase="<?=$local_dir;?>/chxfile/chxfile.ocx">
					<param name="ServerURL"    value="<?=$set_upload_url;?>/cgi-bin/chxfile/chxfile.cgi" />
					<param name="Filter"       value="모든 파일(*.*)|*.*|" />
					<param name="MaxFileCount" value="<?=$file_max_cnt;?>" />
					<param name="MaxFileSize"  value="<?=$file_max_file;?>" />
					<param name="MaxTotalSize" value="<?=$file_max_size;?>" />
				</object>
			</div>
		</div>

		<div class="upload_bottom">
			<div class="fl">
				<span class="btn_big_red"><input type="button" value="파일삭제" onclick="CHXFile.Remove()" /></span>
				<span class="btn_big_red"><input type="button" value="전체삭제" onclick="CHXFile.RemoveAll()" /></span>
			</div>
			<div class="fr" id="btn_active_send">
				<span class="btn_big_green"><input type="button" value="파일전송" onclick="CHXFile.Upload()" /></span>
			</div>
		</div>
	</div>
</div>
<?
	}
?>