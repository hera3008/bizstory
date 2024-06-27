<?
/*
	생성 : 2013.02.07
	수정 : 2013.05.21
	위치 : 파일센터 > 파일관리 - 파일업로드 ie
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
?>
<div class="align_r pt01 mb01">
	<span class="btn_big"><input type="button" value="파일추가" onclick="CHXFile.AddFile()" /></span>
</div>

<div style="border:1px #ccc solid; z-index:1;">
	<div style="width:100%; z-index:1;">
		<object id="CHXFile" wmode="transparent" width="100%" height="200" classid="clsid:16ADD49A-D148-401E-877C-1B2D1DDEBC7E" codebase="<?=$local_dir;?>/chxfile/chxfile.cab#version=1,0,0,3">
			<param name="ServerURL"    value="<?=$set_upload_url;?>/cgi-bin/chxfile/chxfile.cgi" />
			<param name="MaxFileCount" value="<?=$file_max_cnt;?>" />
			<param name="MaxFileSize"  value="<?=$file_max_file;?>" />
			<param name="MaxTotalSize" value="<?=$file_max_size;?>" />
			<param name="Filter"       value="모든 파일(*.*)|*.*|" />
			<param name="wmode"        value="transparent" />
		</object>
	</div>
</div>

<div class="upload_bottom">
	<div class="fl">
		<span class="btn_big_red"><input type="button" value="파일삭제" onclick="CHXFile.Remove()" /></span>
		<span class="btn_big_red"><input type="button" value="전체삭제" onclick="CHXFile.RemoveAll()" /></span>
	</div>
</div>
