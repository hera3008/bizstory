<?php
	$sess_id    = $_REQUEST['sess_id'];
	$comp_idx   = $_REQUEST['comp_idx'];
	$part_idx   = $_REQUEST['part_idx'];
	$mem_idx    = $_REQUEST['mem_idx'];
	$max_size   = $_REQUEST['max_size'];
	$table_name = $_REQUEST['table_name'];
	$table_idx  = $_REQUEST['table_idx'];
	$idx_common = $_REQUEST['idx_common'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>XFile</title>
<link rel="stylesheet" type="text/css" href="../xupload/css/main.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="../xupload/js/xupload.js"></script>
<script type="text/javascript" src="../xupload/js/xupload.html5.js"></script>
<script type="text/javascript" src="../xupload/js/xupload.html4.js"></script>
<script type="text/javascript" src="../xupload/js/jquery.xupload.queue.js"></script>
<script type="text/javascript">
//<![CDATA[
	$(document).ready(function() {
		$("#uploader").xuploadQueue({
			runtimes : 'html5,html4',
			url : 'file_html_ok.php',
			multipart_params : {
				'sess_id'    : '<?php echo $sess_id;?>',
				'comp_idx'   : '<?php echo $comp_idx;?>',
				'part_idx'   : '<?php echo $part_idx;?>',
				'mem_idx'    : '<?php echo $mem_idx;?>',
				'table_name' : '<?php echo $table_name;?>',
				'table_idx'  : '<?php echo $table_idx;?>',
				'idx_common' : '<?php echo $idx_common;?>',
				'file_class' : '<?php echo $file_class;?>'
			},
			max_file_size : '<?php echo $max_size;?>mb',
			unique_names : true,
			multiple_queues : true,
			filters : [
				{title : "All files", extensions : "*"}
			]
		});
	});
//]]>
</script>
</head>

<body>
	<div id="uploader"></div>
	<div id="preview_file_result"></div>
	<form name="uploadform" id="uploadform">
		<input type="hidden" id="html_sess_id"    value="<?php echo $sess_id;?>" />
		<input type="hidden" id="html_comp_idx"   value="<?php echo $comp_idx;?>" />
		<input type="hidden" id="html_part_idx"   value="<?php echo $part_idx;?>" />
		<input type="hidden" id="html_mem_idx"    value="<?php echo $mem_idx;?>" />
		<input type="hidden" id="html_table_name" value="<?php echo $table_name;?>" />
		<input type="hidden" id="html_table_idx"  value="<?php echo $table_idx;?>" />
		<input type="hidden" id="html_idx_common" value="<?php echo $idx_common;?>" />
	</form>
</body>
</html>
