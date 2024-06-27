<?
/*
	생성 : 2013.09.08
	수정 : 2013.09.08
	위치 : 파일업로드 html
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

	$max_size1 = $file_max_size / 1024 / 1024;
	
	$file_html = "";
	if ($set_file_class == 'OUT') {
		$file_html = "/biz/file_html.php";
	} else {
		$file_html = "/biz/filemanage_html.php";
	}
	
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<div class="ajax_write">
	<div class="upload_title">
		Upload Component
		<img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_close.png" onclick="popupform_close2();" alt="닫기" />
	</div>

	<div class="info_text">
		<ul>
			<li>파일을 추가하고 난뒤 '파일전송'을 클릭하세요.</li>
			<li>업로드 완료된 후 '파일완료'를 클릭해주세요.</li>
			<li>한번에 올릴 수 있는 파일 갯수는 <strong><?=$file_max_cnt1;?> 개</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 하나파일 용량은 <strong><?=$file_max_file1;?> byte</strong> 입니다.</li>
			<li>한번에 올릴 수 있는 전체 용량은 <strong><?=$file_max_size1;?> byte</strong> 입니다.</li>
		</ul>
	</div>

	<div class="ajax_frame">

		<form id="isForm" name="isForm" method="post">
			<input type="hidden" id="isForm_comp_idx"      name="comp_idx"      value="<?=$code_comp;?>" />
			<input type="hidden" id="isForm_part_idx"      name="part_idx"      value="<?=$code_part;?>" />
			<input type="hidden" id="isForm_mem_idx"       name="mem_idx"       value="<?=$code_mem;?>" />
			<input type="hidden" id="isForm_max_size"      name="max_size"      value="<?=$max_size1;?>" />
			<input type="hidden" id="isForm_table_name"    name="table_name"    value="<?=$table_name;?>" />
			<input type="hidden" id="isForm_table_idx"     name="table_idx"     value="<?=$table_idx;?>" />
			<input type="hidden" id="isForm_idx_common"    name="idx_common"    value="<?=$idx_common;?>" />
		</form>

		<div style="border:1px #ccc solid; margin:5px 0 0 0; padding:0;">
			<iframe id="loadUploader" width="100%" height="300" scrolling="no" style="margin:0; padding:0;"></iframe>
		</div>
		
		<div class="upload_bottom">
			<div class="fr" id="btn_active_send">
				<span class="btn_big_green"><input type="button" value="파일완료" onclick="filecenter_complete($('#isForm_idx_common').val()); popupform_close2();" /></span>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
	var comp_idx      = $('#isForm_comp_idx').val();
	var part_idx      = $('#isForm_part_idx').val();
	var mem_idx       = $('#isForm_mem_idx').val();
	var max_size      = $('#isForm_max_size').val();
	var table_name    = $('#isForm_table_name').val();
	var table_idx     = $('#isForm_table_idx').val();
	var idx_common    = $('#isForm_idx_common').val();
	var move_string = "comp_idx=" + comp_idx + "&part_idx=" + part_idx + "&mem_idx=" + mem_idx + "&max_size=" + max_size + "&table_name=" + table_name + "&table_idx=" + table_idx + "&idx_common=" + idx_common;
	$("#loadUploader").attr("src", "<?=$set_filecneter_url;?><?=$file_html?>?" + move_string);
//]]>
</script>