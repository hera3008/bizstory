<?
/*
	생성 : 2013.02.07
	수정 : 2013.05.09
	위치 : 파일센터 > 파일관리 - 파일업로드 html5
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
$form_chk = 'Y';
// 해당폴더에 대한 권한을 가지고 처리한다.
	if ($form_chk == 'Y')
	{
		$max_size1 = $file_max_size / 1024 / 1024;
        ///////////////////////////////////////
?>
<div class="ajax_write">
	<div class="upload_title">
		<strong>V-Drive</strong> Upload Component
		<img src="/bizstory/images/filecenter/icon_close.png" onclick="popup_file_close2();" alt="닫기" />
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
		
		<form id="isForm" name="isForm" method="post">
			<input type="hidden" id="isForm_comp_idx"   name="comp_idx"   value="<?=$code_comp?>" />
			<input type="hidden" id="isForm_part_idx"   name="part_idx"   value="<?=$code_part?>" />
			<input type="hidden" id="isForm_mem_idx"    name="mem_idx"    value="<?=$code_mem?>" />
			<input type="hidden" id="isForm_max_size"   name="max_size"   value="<?=$max_size1?>" />
			<input type="hidden" id="isForm_table_name" name="table_name" value="<?=$table_name;?>" />
			<input type="hidden" id="isForm_table_idx"  name="table_idx"  value="<?=$table_idx;?>" />
			<input type="hidden" id="isForm_idx_common" name="idx_common" value="<?=getGUID()?>" />
		</form>

		<div style="border:1px #ccc solid; margin:5px 0 0 0; padding:0;">
			<iframe id="loadUploader" width="100%" height="300" scrolling="no" style="margin:0; padding:0;"></iframe>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[

	function setLoadUploader(btn_dis) {
		var comp_idx 	= $('#isForm_comp_idx').val();
		var part_idx 	= $('#isForm_part_idx').val();
		var mem_idx  	= $('#isForm_mem_idx').val();
		var max_size 	= $('#isForm_max_size').val();
		var table_name  = $('#isForm_table_name').val();
		var table_idx   = $('#isForm_table_idx').val();
		var idx_common 	= $('#isForm_idx_common').val();
		
		var move_string = "comp_idx=" + comp_idx + "&part_idx=" + part_idx + "&mem_idx=" + mem_idx + "&max_size=" + max_size + "&table_name=" + table_name + "&table_idx=" + table_idx + "&idx_common=" + idx_common;
		
		if (btn_dis != null) {
			move_string += "&btn_dis=" + btn_dis;
		}
		
		// $("#loadUploader").attr("src", "<?=$set_filecneter_url;?>/xupload/filecenter_html.php?" + move_string);
		$("#loadUploader").attr("src", "<?=$set_filecneter_url;?>/chxupload/filecenter.php?" + move_string);		
	}

	
	$(function() {
		
		setLoadUploader();
		
		$(".ajax_frame").delegate("#cont_contents", 'change', function() {
			
			setLoadUploader();
		});
	});

//]]>
</script>
<?
	}
    
    function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }else{
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = (md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = (substr($charid, 0, 8).substr($charid, 8, 4).substr($charid,12, 4).substr($charid,16, 4).substr($charid,20,12));
            return $uuid;
        }
    }
    
?>