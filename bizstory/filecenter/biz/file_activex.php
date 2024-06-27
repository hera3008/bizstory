<?
/*
	생성 : 2013.02.07
	수정 : 2013.05.31
	위치 : 파일업로드 ie
*/
	//$set_file_class    = 'OUT';
	//$set_filecenter_yn = '0';
	//$set_file_class    = 'IN';

	if ($set_file_class == 'IN')
	{
		$file_active_url = 'http://' . $site_url . $local_dir . '/cgi-bin/chxfile/chxfile.cgi';
	}
	else
	{
		$file_active_url = $set_upload_url . '/cgi-bin/chxfile/chxfile.cgi';
	}
?>
<div class="align_r pt01 mb01">
	<span class="btn_big"><input type="button" value="파일추가" onclick="CHXFile.AddFile()" /></span>
</div>

<div style="border:1px #ccc solid; z-index:1;">
	<div style="width:100%; z-index:1;">
		<object id="CHXFile" wmode="transparent" width="100%" height="200" classid="clsid:16ADD49A-D148-401E-877C-1B2D1DDEBC7E" codebase="<?=$local_dir;?>/chxfile/chxfile.cab#version=1,0,0,5">
			<param name="ServerURL"    value="<?=$file_active_url;?>" />
			<param name="MaxFileCount" value="<?=$file_max_cnt;?>" />
			<param name="MaxFileSize"  value="<?=$file_max_file;?>" />
			<param name="MaxTotalSize" value="<?=$file_max_size;?>" />
			<param name="AllowFolder"  value="FALSE">
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
