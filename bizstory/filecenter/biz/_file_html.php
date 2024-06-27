<?
/*
	생성 : 2013.05.23
	수정 : 2013.05.23
	위치 : 파일업로드 html5
*/
	$max_size1 = $file_max_size / 1024 / 1024;

	$file_comp = $_SESSION[$sess_str . '_comp_idx'];
	$file_part = $_SESSION[$sess_str . '_part_idx'];
	$file_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$sess_id = $file_comp . '_' . $file_part . '_' . $file_mem . '_' . session_id();

	echo $sess_id, '<br />';
?>
<div class="file_html_view">
	<div style="border:1px #ccc solid; margin:5px 0 0 0; padding:0;">
		<iframe id="loadUploader" width="100%" height="300" scrolling="no" style="margin:0; padding:0;"></iframe>
	</div>
</div>

<form id="fileisForm" name="fileisForm" method="post">
	<input type="hidden" id="isForm_sess_id"    name="sess_id"    value="<?=$sess_id;?>" />
	<input type="hidden" id="isForm_comp_idx"   name="comp_idx"   value="<?=$file_comp;?>" />
	<input type="hidden" id="isForm_part_idx"   name="part_idx"   value="<?=$file_part;?>" />
	<input type="hidden" id="isForm_mem_idx"    name="mem_idx"    value="<?=$file_mem;?>" />
	<input type="hidden" id="isForm_table_name" name="table_name" value="<?=$table_name;?>" />
	<input type="hidden" id="isForm_table_idx"  name="table_idx"  value="<?=$table_idx;?>" />
	<input type="hidden" id="isForm_max_size"   name="max_size"   value="<?=$max_size1;?>" />
</form>
<script type="text/javascript">
//<![CDATA[
	//var string_chk = $('#fileisForm').serialize();
	var string_chk = $('#postform').serialize();
	alert("string_chk -> " + string_chk);

	var comp_idx = $('#isForm_comp_idx').val();
	var part_idx = $('#isForm_part_idx').val();
	var up_idx   = $('#isForm_up_idx').val();
	var mem_idx  = $('#isForm_mem_idx').val();
	var max_size = $('#isForm_max_size').val();

	var move_string = "comp_idx=" + comp_idx + "&part_idx=" + part_idx + "&up_idx=" + up_idx + "&mem_idx=" + mem_idx + "&max_size=" + max_size;
	$("#loadUploader").attr("src", "<?=$set_filecneter_url;?>/biz/file_html.php?" + move_string);
//]]>
</script>
<?
	function file_view_html($comp_idx, $part_idx, $mem_idx, $table_name, $table_idx, $max_size)
	{
		$sess_id  = $comp_idx . '_' . $part_idx . '_' . $mem_idx . '_' . $table_name . '_' . $table_idx . '_' . session_id();
		$max_size = $max_size / 1024 / 1024;

		$str['html_view'] = '
			<div class="file_html_view">
				<div style="border:1px #ccc solid; margin:5px 0 0 0; padding:0;">
					<iframe id="loadUploader" width="100%" height="300" scrolling="no" style="margin:0; padding:0;"></iframe>
				</div>
			</div>';

		$str['html_form'] = '
			<form id="fileisForm" name="fileisForm" method="post">
				<input type="hidden" id="isForm_sess_id"    name="sess_id"    value="' . $sess_id . '" />
				<input type="hidden" id="isForm_comp_idx"   name="comp_idx"   value="' . $comp_idx . '" />
				<input type="hidden" id="isForm_part_idx"   name="part_idx"   value="' . $part_idx . '" />
				<input type="hidden" id="isForm_mem_idx"    name="mem_idx"    value="' . $mem_idx . '" />
				<input type="hidden" id="isForm_table_name" name="table_name" value="' . $table_name . '" />
				<input type="hidden" id="isForm_table_idx"  name="table_idx"  value="' . $table_idx . '" />
				<input type="hidden" id="isForm_max_size"   name="max_size"   value="' . $max_size . '" />
			</form>';

		$str['html_script'] = '
			<script type="text/javascript">
			//<![CDATA[
				var string_chk = $("#fileisForm").serialize();
				alert("string_chk -> " + string_chk);

				$("#loadUploader").attr("src", "<?=$set_filecneter_url;?>/biz/file_html.php?" + string_chk);
			//]]>
			</script>';

		return $str;
	}
?>