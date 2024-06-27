<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.26
	위치 : 파일센터 > 파일관리 - 목록
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";
	
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	if ($pro_end == '')
	{
		$pro_end = 'N';
		$send_pro_end = 'N';
		$recv_pro_end = 'N';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;pro_end=' . $send_pro_end;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="pro_end" value="' . $send_pro_end . '" />
	';
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$result = filecenter_open_check($up_idx, $code_comp, $code_part);
	$chk_up = $result['chk_up'];
	$project_fi_idx = $result['project_fi_idx'];
	$vdrive_fi_idx = $result['vdrive_fi_idx'];
?>

<script src="<?=$local_dir;?>/common/js/fileTree/jquery_.js" type="text/javascript"></script>
<script src="<?=$local_dir;?>/common/js/fileTree/jquery.easing.js" type="text/javascript"></script>
<script src="<?=$local_dir;?>/common/js/fileTree/jqueryFileTree.js" type="text/javascript"></script>
<link href="<?=$local_dir;?>/common/js/fileTree/jqueryFileTree.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
    var E = $.noConflict();
    E(document).ready( function() {
		//$.ajax({ type: 'GET', url: '<?=$local_dir?>/common/js/fileTree/jquery.js', dataType: 'script'});        
        E('#project_list').fileTree({ script: '<?=$local_dir?>/bizstory/filecenter/ajax/filecenter_project_folder_list.php' , params : '<?=$code_comp?>||<?=$code_mem?>||2||<?=$project_fi_idx?>||<?=$pro_end?>||fsubmenu', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Un momento...' }, function(file) {           
        });
        
        E('#vdrive_list').fileTree({ script: '<?=$local_dir?>/bizstory/filecenter/ajax/filecenter_vdrive_folder_list.php' , params : '<?=$code_comp?>||<?=$code_part?>||<?=$code_mem?>||2||<?=$vdrive_fi_idx?>||fsubmenu', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Un momento...' }, function(file) { 
        });
        
    });
    
</script>
<!--
<link rel="stylesheet" href="<?=$local_dir;?>/common/js/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
<script type="text/javascript" src="<?=$local_dir;?>/common/js/zTree/js/jquery.ztree.core-3.5.js"></script>
<script type="text/javascript">
    var setting = {
        async: {
            enable: true,
            url:"<?=$local_dir?>/bizstory/filecenter/ajax/project_folder_list.php",
            autoParam:["id", "name=n", "level=lv"],
            otherParam:{"otherParam":'<?=$code_comp?>||<?=$code_mem?>||2||<?=$project_fi_idx?>||<?=$pro_end?>||fsubmenu'}
        }
    };
    
    var setting2 = {
        async: {
            enable: true,
            url:"<?=$local_dir?>/bizstory/filecenter/ajax/vdrive_folder_list.php",
            autoParam:["id", "name=n", "level=lv"],
            otherParam:{"otherParam":'<?=$code_comp?>||<?=$code_part?>||<?=$code_mem?>||2||<?=$vdrive_fi_idx?>||fsubmenu'}
        }
    };

    $(document).ready(function(){
        $.fn.zTree.init($("#project_list"), setting);
        
        $.fn.zTree.init($("#vdrive_list"), setting2);
    });
</script>
-->
<div id="fsidebar">
<?
	/*
	$file_part_where = " and fi.comp_idx = '" . $code_comp . "' and fi.dir_depth = '1' and fi.file_name = 'Project'";
	$file_part_order = "fi.part_idx asc";
	$file_part_data = filecenter_info_data('view', $file_part_where, $file_part_order);
	$project_fi_idx = $file_part_data['fi_idx'];

	$file_part_where = " and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "' and fi.dir_depth = '1' and fi.file_name = 'V-Drive'";
	$file_part_data = filecenter_info_data('view', $file_part_where);
	$vdrive_fi_idx = $file_part_data['fi_idx'];
	*/
?>
	<ul id="fsub_navi">
		<li id="fleft_1">
			<a href="javascript:void(0);" onclick="file_list_show('<?=$project_fi_idx;?>', '2')"><img src="/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> Project</a>
			<ul id="project_list"></ul>
<?
		//$project_folder = filecenter_folder_left_project($code_comp, $code_mem, 2, $project_fi_idx, $pro_end, 'fsubmenu');
		//echo $project_folder;
?>
		</li>
		<li id="fleft_2">
			<a href="javascript:void(0);" onclick="file_list_view('<?=$vdrive_fi_idx;?>', '2')"><img src="/bizstory/images/filecenter/icon_v.png" alt="V-Drive" title="V-Drive" /> V-Drive</a>
			<div id="vdrive_list"></div>
<?
		//$vdrive_folder = filecenter_folder_left_vdrive($code_comp, $code_part, $code_mem, 2, $vdrive_fi_idx, 'fsubmenu');
		//echo $vdrive_folder;
?>
		</li>
	</ul>
</div>
<hr />

</script>
<?
	db_close();
?>