<?
/*
	생성 : 2013.03.18
	수정 : 2013.04.26
	위치 : 파일센터 > 파일관리 - 파일위치변경
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$set_part_yn = $comp_set_data['part_yn'];

	$path_data = filecenter_folder_path($up_idx); // 위치
	$dir_auth  = filecenter_folder_auth($up_idx); // 권한확인

// 펼침
	$result = filecenter_open_check($up_idx, $code_comp, $code_part);
	$chk_up = $result['chk_up'];
	$project_fi_idx = $result['project_fi_idx'];
	$vdrive_fi_idx = $result['vdrive_fi_idx'];
	//$chk_up = filecenter_open_check($up_idx);

	if ($dir_auth['dir_write_auth'] != 'Y') // 등록권한
	{
		$navi_subject = " -> 업로드 권한이 없습니다.";
	}
?>
<div class="upload_l2">
	<div class="change_title">
		<strong>변경할 위치 : <?=$path_data['navi_path'];?> <?=$navi_subject;?></strong>
		<div class="upload_l_btn">
			<a href="javascript:void(0);" onclick="close_dir_change();" class="btn_con_red"><span>변경취소</span></a>
		</div>
	</div>
	<div class="ajax_frame">
		<div id="fsidebar">

            <ul id="ffsub_navi">
                <li id="fleft_1">
                    <a href="javascript:void(0);" onclick="open_dir_change2('<?=$project_fi_idx;?>', '2', 'Y')"><img src="/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> Project</a>
                    <ul id="project_list"></ul>
                </li>
                <li id="fleft_2">
                    <a href="javascript:void(0);" onclick="open_dir_change2('<?=$vdrive_fi_idx;?>', '2', 'Y')"><img src="/bizstory/images/filecenter/icon_v.png" alt="V-Drive" title="V-Drive" /> V-Drive</a>
                    <ul id="vdrive_list"></ul>
                </li>
            </ul>
		</div>
	</div>
</div>
<script src="<?=$local_dir;?>/common/js/fileTree/jquery_.js" type="text/javascript"></script>
<script src="<?=$local_dir;?>/common/js/fileTree/jquery.easing.js" type="text/javascript"></script>
<script src="<?=$local_dir;?>/common/js/fileTree/jqueryFileTree.js" type="text/javascript"></script>
<link href="<?=$local_dir;?>/common/js/fileTree/jqueryFileTree.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
//<![CDATA[
    var now_ff_menu_id = 'ffsubmenu_<?=$chk_up;?>';
    var dir_write_auth = '<?=$dir_auth['dir_write_auth']?>';
    var E = $.noConflict();
    var fsidebar = document.getElementById("ffsub_navi");
    
    E(document).ready( function() {
        //$.ajax({ type: 'GET', url: '<?=$local_dir?>/common/js/fileTree/jquery.js', dataType: 'script'});        
        E('#project_list').fileTree({ script: '<?=$local_dir?>/bizstory/filecenter/ajax/filecenter_project_folder_list.php' , params : '<?=$code_comp?>||<?=$code_mem?>||2||<?=$project_fi_idx?>||<?=$pro_end?>||ffsubmenu', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Un momento...' }, function(file) {           
        });
        
        E('#vdrive_list').fileTree({ script: '<?=$local_dir?>/bizstory/filecenter/ajax/filecenter_vdrive_folder_list.php' , params : '<?=$code_comp?>||<?=$code_part?>||<?=$code_mem?>||2||<?=$vdrive_fi_idx?>||ffsubmenu', folderEvent: 'click', expandSpeed: 750, collapseSpeed: 750, expandEasing: 'easeOutBounce', collapseEasing: 'easeOutBounce', loadMessage: 'Un momento...' }, function(file) {
        });
        
    });
    
    if(fsidebar)
    {
        this.flistItem = function(li){
            if(li.getElementsByTagName("ul").length > 0)
            {
                var li_a = li.getElementsByTagName("a")[0];
                var ul = li.getElementsByTagName("ul")[0];
                var ul_id = $(ul).attr('id');
                var ul_id_arr = ul_id.split('_');
                var chk_ul_id = ul_id_arr[0] + '_' +  ul_id_arr[1];

                ul.style.display = "none";
                var span = document.createElement("span");
                span.className = "collapsed";
                span.onclick = function(){
                    ul.style.display = (ul.style.display == "none") ? "block" : "none";
                    this.className = (ul.style.display == "none") ? "collapsed" : "expanded";
                };

                var file_menu_arr = now_ff_menu_id.split('_');
                var left_str = 'ffsubmenu';
                for (var left_num = 1; left_num < 10; left_num++)
                {
                    if (file_menu_arr[left_num] != undefined && file_menu_arr[left_num] != '')
                    {
                        left_str = left_str + '_' + file_menu_arr[left_num];
                        $("#" + left_str).css({"display": "block"});
                        if (ul_id == left_str)
                        {
                            span.className = "expanded";
                        }
                    }
                }

                li_a.appendChild(span);
            };
        };
        var items = fsidebar.getElementsByTagName("li");
       
        for(var i = 0; i < items.length; i++)
        {            
            flistItem(items[i]);
        }
    }
    
//]]>
</script>
<?
	db_close();
?>
