<?
/*
	생성 : 2013.04.22
	수정 : 2013.05.03
	위치 : 파일업로드
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$set_file_class = $comp_set_data['file_class'];

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

	if ($fmode == 'project' && $smode == 'project')
	{
		if ($set_file_class == 'OUT') // 외부서버사용시
		{
			$action_url = 'http://' . $filecneter_url . '/filemanage/biz/project_file_ok.php';
		}
		else
		{
			$action_url = $local_dir . '/bizstory/project/project_ok.php';
		}
	}
?>

<div class="align_r pt01 mb01">
	<span class="btn_big"><input type="button" value="파일 추가" onclick="XFile.AddFile()" /></span>
	<a href="javascript:void(0);" onclick="open_dir_change('<?=$up_idx;?>', '<?=$up_level;?>', 'open');" class="btn_big_blue"><span>위치변경</span></a>
</div>

<div style="border:1px #ccc solid;">
	<div style="width:100%;">
		<object id="XFile" width="100%" height="200" classid="clsid:16ADD49A-D148-401E-877C-1B2D1DDEBC7E"
				codebase="<?=$local_dir;?>/bizstory/filecenter/xupload/xfile.ocx">
			<param name="ServerURL"    value="<?=$action_url;?>" />
			<param name="Filter"       value="모든 파일(*.*)|*.*|" />
			<param name="MaxFileCount" value="<?=$file_max_cnt;?>" />
			<param name="MaxFileSize"  value="<?=$file_max_file;?>" />
			<param name="MaxTotalSize" value="<?=$file_max_size;?>" />
			<param name="HtmlForm"     value="postform" />
		</object>

		<form id="isForm" name="isForm" method="post">
			<input type="hidden" name="pro_idx"  id="isForm_pro_idx"  value="" />
			<input type="hidden" name="chk_comp" id="isForm_chk_comp" value="" />
			<input type="hidden" name="chk_part" id="isForm_chk_part" value="" />
			<input type="hidden" name="chk_mem"  id="isForm_chk_mem"  value="" />
		</form>
	</div>
</div>

<div class="upload_bottom">
	<div class="fl">
		<span class="btn_big_red"><input type="button" value="파일 삭제" onclick="XFile.Remove()" /></span>
		<span class="btn_big_red"><input type="button" value="전체 삭제" onclick="XFile.RemoveAll()" /></span>
	</div>
	<!--//
	<div class="fr" id="btn_active_send">
		<span class="btn_big_green"><input type="button" value="파일 전송" onclick="XFile.Upload()" /></span>
	</div>
	//-->
</div>
<div id="dir_list_change" title="변경할 폴더목록"></div>

<script type="text/javascript" for="XFile" event="UploadFile(fileName)">
/*
	if (fileName != '')
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_preview.php',
			data: { },
			success: function(msg) {
				$("#preview_file_result").html(msg);
			}
		});
	}
*/
</script>