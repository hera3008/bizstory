<?
/*
	생성 : 2013.06.20
	수정 : 2013.06.20
	위치 : 파일업로드 ie
*/
	require_once "../../common/setting.php";
	require_once "../../common/no_direct.php";
	require_once "../../common/member_chk.php";

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
?>

<div class="ajax_write">
	<div class="upload_title">
		Upload Component
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close2();" alt="닫기" />
	</div>

	<div class="info_text">
		<ul>
			<li>파일을 추가하고 난뒤 '파일전송'을 클릭하세요.</li>
			<li>전송이 완료되고 난뒤 '완료되었습니다.' 문구가 나올때 까지 기다리세요.</li>
			<li>한번에 올릴 수 있는 파일 갯수는 <strong><?=$file_max_cnt1;?> 개</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 하나파일 용량은 <strong><?=$file_max_file1;?> byte</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 전체 용량은 <strong><?=$file_max_size1;?> byte</strong> 입니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">

		<div class="align_r pt01 mb01">
			<span class="btn_big"><input type="button" value="파일추가" onclick="CHXFile.AddFile()" /></span>
		</div>

		<div style="border:1px #ccc solid;">
			<div style="width:100%; z-index:1">
				<object id="CHXFile" width="100%" height="200" classid="clsid:16ADD49A-D148-401E-877C-1B2D1DDEBC7E" codebase="<?=$local_dir;?>/chxfile/chxfile.cab#version=1,0,0,5">
					<param name="ServerURL"    value="<?=$set_upload_url;?>/cgi-bin/chxfile/chxfile.cgi" />
					<param name="MaxFileCount" value="<?=$file_max_cnt;?>" />
					<param name="MaxFileSize"  value="<?=$file_max_file;?>" />
					<param name="MaxTotalSize" value="<?=$file_max_size;?>" />
					<param name="Filter"       value="모든 파일(*.*)|*.*|" />
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