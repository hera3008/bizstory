<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.16
	위치 : 파일센터 > 파일관리
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$set_file_class    = $comp_set_data['file_class'];
	$set_filecenter_yn = $comp_set_data['filecenter_yn'];

	
	if ($pro_end == '')
	{
		$pro_end      = 'N';
		$send_pro_end = 'N';
		$recv_pro_end = 'N';
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext . '&amp;pro_end=' . $send_pro_end . '&amp;menu1=' . $send_menu1 . '&amp;menu2=' . $send_menu2 . '&amp;start_date=' . $send_start_date . '$amp;end_date=' . $send_end_date;
	$f_page    = $f_search;
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
        <input type="hidden" name="menu1"    value="' . $send_menu1 . '" />
        <input type="hidden" name="menu2"    value="' . $send_menu2 . '" .>
		<input type="hidden" name="start_date" value="' . $send_start_date . '" />
		<input type="hidden" name="end_date" value="' . $send_end_date . '"/>
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	if ($set_file_class == 'OUT' && $set_filecenter_yn == '1') // 외부이면서 파일센터를 사용할 경우
	{
		$page_view_yn = 'Y';
		if ($set_filecneter_url == '') $page_view_yn = 'N';
	}
	else
	{
		$page_view_yn = 'N';
	}

	if ($page_view_yn == 'N')
	{
		echo '
<div class="tablewrapper">
	<div id="tableheader">';
		include $local_path . "/bizstory/comp_set/part_menu_inc.php";
		echo '
	</div>
	<div class="info_text">
		<ul>
			<li>관리자에게 문의를 하세요.</li>
			<li>파일센터를 이용하실 수 없습니다.</li>';
		if ($set_filecneter_url == '')
		{
			echo '
			<li>외부서버 주소를 입력하지 않았습니다.</li>';
		}
		echo '
		</ul>
	</div>
</div>
		';
	}
	else
	{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 링크, 버튼
		$link_list         = $local_dir . "/bizstory/filecenter/filemanager_list.php";         // 목록
		$link_search       = $local_dir . "/bizstory/filecenter/filemanager_search.php";       // 검색
		$link_left         = $local_dir . "/bizstory/filecenter/filemanager_left.php";         // 왼쪽메뉴
		$link_folder       = $local_dir . "/bizstory/filecenter/filemanager_folder.php";       // 폴더생성, 수정
		$link_folder_auto  = $local_dir . "/bizstory/filecenter/filemanager_folder_auto.php";  // 폴더자동생성
		$link_file_modify  = $local_dir . "/bizstory/filecenter/filemanager_file_modify.php";  // 파일명수정
		$link_file_history = $local_dir . "/bizstory/filecenter/filemanager_file_history.php"; // 파일이력
		$link_file_copy    = $local_dir . "/bizstory/filecenter/filemanager_file_copy.php";    // 파일복사, 이동
		$link_empowerment  = $local_dir . "/bizstory/filecenter/filemanager_empowerment.php";    // 파일복사, 이동
		$link_ok           = $local_dir . "/bizstory/filecenter/filemanager_ok.php";           // 저장
		
		$link_file = $local_dir . "/bizstory/filecenter/filemanager_upload.php";        // 대용량 파일업로드
		/*
		if (strlen(stristr($mybrowser_val_val, 'IE')) > 0)
		{
			$link_file = $local_dir . "/bizstory/filecenter/filemanager_file_active.php"; // 파일업로드-active
		}
		else
		{
			$link_file = $local_dir . "/bizstory/filecenter/filemanager_file.php";        // 파일업로드
		}
		*/
		//echo $link_file;
?>
<style type="text/css">
	#downloaderContent { display:none; }
	#downloaderInstaller { display:block; }
	.installerModal {
		background-color: #f0f0f0;
		z-index: 1;
		position: fixed;
		top: 0px; left: 0px;
		width: 100%; height: 100%;
		padding: 200px 0;
		_position: absolute;
		_left: expression((0 + (ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft)) + 'px');
		_top: expression((270 + (ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px');
		display: none;
		text-align: center;
		opacity: 0.9;
		filter:alpha(opacity=90);
	}
	object { outline:none; }
</style>
<div id="vcenter-loading2">로딩중입니다...</div>
<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/fsidebar.css" media="all" />
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<input type="hidden" id="search_pro_end" name="pro_end" value="<?=$send_pro_end;?>" />
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
                <span class="left" id="up_menu_list"></span>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="folder_name"<?=selected($swhere, 'folder_name');?>>폴더명</option>
					<option value="file_name"<?=selected($swhere, 'file_name');?>>파일명</option>
					<!--<option value="reg_date"<?=selected($swhere, 'reg_date');?>>올린날짜</option>-->
					<option value="reg_id"<?=selected($swhere, 'reg_id');?>>올린사람</option>
					<!--<option value="mod_date"<?=selected($swhere, 'mod_date');?>>수정한날짜</option>
					<option value="mod_id"<?=selected($swhere, 'mod_id');?>>수정한사람</option>-->
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				올린날짜
                <input type="text" name="param[start_date]" id="post_start_date" class="type_text datepicker" title="시작일을 입력하세요." size="10" value="<?=date_replace($start_date, 'Y-m-d');?>" />
                ~
                <input type="text" name="param[end_date]" id="post_end_date" class="type_text datepicker" title="종료일을 입력하세요." size="10" value="<?=date_replace($end_date, 'Y-m-d');?>" />
				<a href="javascript:void(0);" class="btn_sml" onclick="search_end()"><span>완료프로젝트보기</span></a>
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>진행프로젝트보기</span></a>				
			</div>
		</form>
	</div>

	<div class="info_text">
		<ul>
			<li>Project 폴더는 프로젝트에 관련된 파일을 관리합니다. 프로젝트가 완료된 폴더는 나오지 않습니다.</li>
			<li>V-Drive 폴더는 사용자용입니다.</li>
			<li>[수정]은 폴더, 파일명을 수정합니다. [삭제]는 폴더, 파일을 삭제합니다. 폴더삭제는 선택한 폴더안에 폴더나 파일이 있으면 삭제가 되지 않습니다.</li>
			<li>동일한 위치에서 동일한 파일명으로 파일을 올릴 경우 같은 파일로 인식하여 파일을 덮어씌웁니다.</li>
			<li><img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_history.png" width="16px" height="16px" alt="이력" /> : 클릭시 파일이력을 볼 수 있습니다.</li>
			<li><img src="<?=$local_dir;?>/bizstory/images/filecenter/icon_preview.png" width="16px" height="16px" alt="미리보기" /> : 클릭시 파일 미리보기를 볼 수 있습니다.</li>
		</ul>
	</div>
	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<input type="hidden" id="list_code_mem"   name="code_mem"   value="<?=$code_mem;?>" />
		<?=$form_page;?>

		<input type="hidden" id="list_up_idx"   name="up_idx"   value="" />
		<input type="hidden" id="list_up_level" name="up_level" value="" />

		<input type="hidden" id="list_old_up_idx"   name="old_up_idx"   value="" />
		<input type="hidden" id="list_old_up_level" name="old_up_level" value="" />

		<input type="hidden" id="list_fmode" name="list_fmode" value="<?=$send_fmode;?>" />
		<input type="hidden" id="list_smode" name="list_smode" value="<?=$send_smode;?>" />

		<table style="height: 849px;" id="data_table">
		<tbody>
			<tr>
				<td id="sidebar">
					<div id="data_left"></div>
				</td>
				<td id="container">
					<div id="data_list"></div>
				</td>
			</tr>
		</tbody>
		</table>
		<br /><br />
	</form>
</div>
<script type="text/javascript">
//<![CDATA[
	var link_list         = '<?=$link_list;?>';
	var link_search       = '<?=$link_search;?>';
	var link_left         = '<?=$link_left;?>';
	var link_folder       = '<?=$link_folder;?>';
	var link_folder_auto  = '<?=$link_folder_auto;?>';
	var link_file         = '<?=$link_file;?>';
	var link_file_modify  = '<?=$link_file_modify;?>';
	var link_file_history = '<?=$link_file_history;?>';
	var link_file_copy    = '<?=$link_file_copy;?>';
	var link_empowerment  = '<?=$link_empowerment;?>';
	var link_ok           = '<?=$link_ok;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

        document.listform.menu1.value = $('#chk_menu1').val();
        document.listform.menu2.value = $('#chk_menu2').val();
        document.listform.start_date.value = $('#post_start_date').val();
        document.listform.end_date.value = $('#post_end_date').val();
		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: link_search,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('#data_list').html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});

		return false;
	}

//------------------------------------ 완료 프로젝트포함
	function search_end()
	{
		document.listform.pro_end.value = 'Y';
		check_search();
	}

//------------------------------------ 폴더목록
	function list_left_data()
	{
		$.ajax({
			type: "post", dataType: 'html', url: link_left,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('#data_left').html(msg);
			}
		});
	}

//------------------------------------ 선택 폴더내용
	function file_list_view(up_idx, up_level)
	{
	    file_list_show(up_idx, up_level);

		list_left_data();
	}
	
    function file_list_show(up_idx, up_level)
    {
        $('#list_up_idx').val(up_idx);
        $('#list_up_level').val(up_level);
        
        list_data();
    }
        
    function file_list_move(up_idx, up_level)
    {
        try {
            
        //$("#" + up_level + "_" + up_idx).click();
        
        file_list_show(up_idx, up_level);
        
        } catch(e) {
            //alert(e.message);            
        }
    }

//------------------------------------------------------------------------ 폴더관련
//------------------------------------ 폴더 폼
	function popup_folder(up_idx, up_level, idx)
	{
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", async: false, dataType: 'html', url: link_folder,
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top', "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 파일명수정
	function file_modify(idx)
	{
		$('#list_idx').val(idx);
		$.ajax({
			type: "post", async: false, dataType: 'html', url: link_file_modify,
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 폴더 폼 - 자동생성
	function popup_folder_auto(up_idx, up_level)
	{
		$.ajax({
			type: "post", async: false, dataType: 'html', url: link_folder_auto,
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 폴더삭제
	function folder_delete(idx)
	{
		if (confirm("선택하신 폴더를 삭제하시겠습니까?"))
		{
            $("#backgroundPopup").css({"z-index":10, "opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");        
            $("#vcenter-loading2").css({"z-index": 100000}).fadeIn('slow');

			$.ajax({
				type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/folder_ok.php', jsonp : 'callback',
				data: { 'sub_type' : 'folder_delete', 'fi_idx' : idx, 'mem_idx' : '<?=$code_mem;?>' },
				success: function(msg) {
                
                    $("#vcenter-loading2").fadeOut('slow');
                    $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
                    
					if (msg.success_chk == "Y")
					{
						popupform_close();
						list_left_data();
						list_data();
					}
					else
					{
						check_auth_popup(msg.error_string);
					}

				}
			});
		}
	}

//------------------------------------------------------------------------ 파일관련
//------------------------------------ 파일업로드 폼
	function popup_file(up_idx, up_level)
	{
	//	alert(up_idx, up_level);
		$('#list_up_idx').val(up_idx);
		$('#list_up_level').val(up_level);

		$.ajax({
			type: "post", dataType: 'html', url: link_file,
			data: $('#listform').serialize(),
			success  : function(msg) {
			//	alert(link_file);
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 파일선택삭제
	function select_delete(up_idx, up_level)
	{
		var chk_num = chk_checkbox_num('fiidx');
		if (chk_num == 0)
		{
			check_auth_popup('삭제할 파일을 선택하세요.');
		}
		else
		{
			if (confirm("선택하신 파일을 삭제하시겠습니까?"))
			{
				$('#list_sub_type').val('file_delete_select');
				$('#list_up_idx').val(up_idx);
				$('#list_up_level').val(up_level);

				$("#loading").fadeIn('slow');
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$.ajax({
					type: 'get', async: false, dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/file_ok.php', jsonp : 'callback',
					data: $('#listform').serialize(),
					success: function(msg) {
						if (msg.success_chk == "Y")
						{
							popupform_close();
							list_data();
						}
						else
						{
							$("#loading").fadeOut('slow');
							$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
							check_auth_popup(msg.error_string);
						}
					}
				});
			}
		}
	}
	
//------------------------------------ 선택파일 복사
	function file_copy_popup(up_idx, up_level)
	{
		var chk_num = chk_checkbox_num('fiidx');
		if (chk_num == 0)
		{
			check_auth_popup('복사할 파일을 선택하세요.');
		}
		else
		{
			$('#list_sub_type').val('file_copy');
			$('#list_up_idx').val(up_idx);
			$('#list_up_level').val(up_level);

			$.ajax({
				type: "post", async: false, dataType: 'html', url: link_file_copy,
				data: $('#listform').serialize(),
				success  : function(msg) {
					$('html, body').animate({scrollTop:0}, 500);
					var maskHeight = $(document).height() + 1000;
					var maskWidth  = $(window).width();
					$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
					$("#data_form").slideDown("slow");
					$('.popupform').css('top',  "80px");
					$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
					$("#data_form").html(msg);
				}
			});
		}
	}

//------------------------------------ 선택파일 이동
	function file_move_popup(up_idx, up_level)
	{
		var chk_num = chk_checkbox_num('fiidx');
		if (chk_num == 0)
		{
			check_auth_popup('이동할 파일을 선택하세요.');
		}
		else
		{
			$('#list_sub_type').val('file_move');
			$('#list_up_idx').val(up_idx);
			$('#list_up_level').val(up_level);

			$.ajax({
				type: "post", async: false, dataType: 'html', url: link_file_copy,
				data: $('#listform').serialize(),
				success  : function(msg) {
					$('html, body').animate({scrollTop:0}, 500);
					var maskHeight = $(document).height() + 1000;
					var maskWidth  = $(window).width();
					$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
					$("#data_form").slideDown("slow");
					$('.popupform').css('top',  "80px");
					$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
					$("#data_form").html(msg);
				}
			});
		}
	}

//------------------------------------ 이미지 미리보기
	function filecenter_preview_images(fi_idx)
	{
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: 'get', dataType: 'jsonp', url: '<?=$set_filecneter_url;?>/preview_image.php', jsonp : 'callback',
			data: { 'fi_idx' : fi_idx },
			success: function(msg) {
				if (msg.success_chk == "Y")
				{
					$('#popup_file_preview').html(msg.html);
					$('#images_preview a').lightBox();
					if (msg.file_num > 0)
					{
						$('#img_image_1').click();
					}
					else
					{
						alert('이미지파일이 없습니다.');
					}
				}
				else
				{
					check_auth_popup(msg.error_string);
				}
			},
			complete: function(){
				$("#backgroundPopup").fadeOut("slow");
			},
			error : function(xhr, status, error)
			{
				var error_msg = xhr + "<br />" + status + "<br />" + error + "<br />";
				alert(error_msg);
				return false;
			}
		});
	}
 
//------------------------------------ 이력
	function file_history(idx)
	{
		$('#list_idx').val(idx);

		$.ajax({
			type: "post", dataType: 'html', url: link_file_history,
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form").html(msg);
			}
		});
	}
	
//------------------------------------ 이력
    function empowerment_folder(idx)
    {
        $.ajax({
            type: "post", dataType: 'html', url: link_empowerment,
            data: $('#listform').serialize(),
            success  : function(msg) {
                $('html, body').animate({scrollTop:0}, 500);
                var maskHeight = $(document).height() + 1000;
                var maskWidth  = $(window).width();
                $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
                $("#data_form").slideDown("slow");
                $('.popupform').css('top',  "80px");
                $('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
                $("#data_form").html(msg);
                $('#fi_idx').val(idx);
            }
        });
    }

//------------------------------------ 파일위치변경 닫기
	function close_dir_change()
	{
		$('#list_up_idx').val($('#list_old_up_idx').val());
		$('#list_up_level').val($('#list_old_up_level').val());
		$("#dir_list_change").html('');
	}

	function popup_file_close()
	{
		popupform_close();
		if ($('#list_old_up_idx').val() != '') $('#list_up_idx').val($('#list_old_up_idx').val());
		if ($('#list_old_up_level').val() != '') $('#list_up_level').val($('#list_old_up_level').val());
		list_data();
	}
<?
	if (strlen(stristr($mybrowser_val_val, 'IE')) > 0) {
?>
//------------------------------------ 파일위치변경
	function open_dir_change(up_idx, up_level, up_type)
	{
		$('#list_up_idx').val(up_idx);
		$('#list_up_level').val(up_level);
		$("#btn_active_send").css({"display": "block"});

		if (up_type == 'open')
		{
			$('#list_old_up_idx').val(up_idx);
			$('#list_old_up_level').val(up_level);
		}

		if (up_type == 'Y' || up_type == 'open')
		{ }
		else
		{
			$("#btn_active_send").css({"display": "none"});
		}

		$("#loading").fadeIn('slow');
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/filemanager_file_change.php',
			data: $('#listform').serialize(),
			success: function(msg) {
				$("#dir_list_change").html(msg);
			},
			complete: function(){
				$("#loading").fadeOut("slow");
			}
		});
	}
<?
	}
?>
    
    $(function() {
        $(".datepicker").datepicker();
        up_menu_change('3', '');
    });
    

//------------------------------------ 메뉴단계변경
    function up_menu_change(menu_depth, idx)
    {
        $.ajax({
            type    : 'post', dataType: 'html', url: '/bizstory/comp_set/project_class_up.php',
            data : {
                "menu_depth" : menu_depth,
                "code_idx" : idx
            },
            success : function(msg) {
                $('#up_menu_list').html(msg);
            }
        });
    }

//------------------------------------ 선택된 메뉴 하위메뉴
    function down_menu_change(menu_depth, sel_depth, idx)
    {
        $.ajax({
            type    : 'post', dataType: 'html', url: '/bizstory/comp_set/project_class_up.php',
            data     : {
                "menu_depth" : menu_depth,
                "sel_depth" : sel_depth,
                "code_idx" : idx
            },
            success : function(msg) {
                $('#up_menu_list').html(msg);
                if ($('#menu2_code').val() != '') {
                    $("#chk_menu2").val( $('#menu2_code').val() );
                }
            }
        });
    }
	list_left_data();
	list_data();
//]]>
</script>
<!--// 다운로드 관련 //-->
<div id="button_download" class="none">
	<div id="installerModal" class="installerModal">
		<div id="downloaderInstaller"></div>
	</div>
	<div id="downloaderContent"></div>
</div>

<!-- <script type="text/javascript" src="<?=$local_dir;?>/bizstory/filecenter/downloader/swfobject.js"></script> -->
<script type="text/javascript">
//<![CDATA[
/*
	var domain       = "http://www.bizstory.co.kr/bizstory/filecenter/downloader";
	var serverScript = "http://www.bizstory.co.kr/bizstory/filecenter/filemanager_file_download.php";
	var local_down   = "<?=$local_dir;?>/bizstory/filecenter/downloader/";
	var queryData    = []; // download.php로 넘어갈 파일 목록
*/
// ---------------------------------------------------------------------------
// 다운로드 버튼을 출력합니다.
// domain과 다운로드 스크립트 URL을 설정해 주십시오.
/*
	var swfVersionStr = "10.2.0";
	var xiSwfUrlStr   = local_down + "playerProductInstall.swf";
	var appID  = "oloader";
	var params = {};
		params.quality           = "high";
		params.allowscriptaccess = "sameDomain";
		params.allowfullscreen   = "true";
		params.wmode             = 'transparent';
	var attributes = {};
		attributes.id    = appID;
		attributes.name  = appID;
		attributes.align = "middle";
	var flashvars = {};
		flashvars.domain       = domain;
		flashvars.serverScript = serverScript;
	swfobject.embedSWF(
		local_down + "oloader.swf",
		"downloaderContent", "81", "28",
		swfVersionStr, xiSwfUrlStr, flashvars, params, attributes);
	swfobject.createCSS("#downloaderContent", "");
*/
// ---------------------------------------------------------------------------
/*
	function addFiles()
	{
		queryData = new Array();
		queryData.push('<?=$code_comp;?>');
		queryData.push('<?=$code_mem;?>');

		var fi_idx = document.getElementsByName('chk_fi_idx[]');
		var i = 0;

		while(fi_idx[i])
		{
			if (fi_idx[i].type == 'checkbox' && fi_idx[i].disabled == false && fi_idx[i].checked == true)
			{
				queryData.push(fi_idx[i].value);
			}
			i++;
		}
		addDownloadFile();
	}
*/
// ---------------------------------------------------------------------------
/*
	function addDownloadFile()
	{
		var oDownloader = document.getElementById(appID);
		if (oDownloader)
		{
			for (var i = 0; i < queryData.length; i++)
			{
				oDownloader.setDownloadFile(queryData[i]);
			}
		}
	}
*/
// ---------------------------------------------------------------------------
// 설치 프로그램이 닫히거나, 설치가 완료되면 호출됩니다.
	function InstallWindowClose(result)
	{
		var bgWrapper = document.getElementById("installerModal");
		if (bgWrapper) bgWrapper.style.display = "none";

		var appButton = document.getElementById(appID);
		if (appButton) appButton.style.display = "block";
	}

// ---------------------------------------------------------------------------
// 다운로더가 설치되어 있지 않을 경우, Adobe AIR와 다운로더를 설치하기 위해
// 자동으로 실행됩니다.
	function AppVersionCheck(rdata)
	{
		if (rdata != null)
		{
			var installerID   = "AppInstaller";
			var installerAttr = {};
				installerAttr.id    = installerID;
				installerAttr.name  = installerID;
				installerAttr.align = "middle";
			flashvars.queryData = rdata;
			swfobject.embedSWF(
				local_down + "AppInstaller.swf",
				"downloaderInstaller", "280", "180",
				swfVersionStr, xiSwfUrlStr, flashvars, params, installerAttr);
			swfobject.createCSS("#downloaderInstaller", "display:block;");

		// 다운로드 플래쉬 버튼 개체를 보이지 않도록 합니다.
			var appButton = document.getElementById(appID);
			if (appButton) appButton.style.display = "none";

		// 설치 프로그램 배경 레이어(id:installerModal)를 화면에 보이도록 합니다.
			var bgWrapper = document.getElementById("installerModal");
			if (bgWrapper) bgWrapper.style.display = "block";
		}
	}
	
function auth_change(obj, sub_action) {
    var value = $(obj).attr('val');
    if (value == 'Y') {
        value = 'N';
    } else {
        value = 'Y';
    }
    
    switch (sub_action) {
        case 'dir_view':
            set_value('dir_view', value);
            if (value == 'N') {
                set_value('dir_read', value);
                set_value('dir_write', value);
                set_value('dir_delete', value);
            }
        break;
        
        case 'dir_read':
            set_value('dir_read', value);
            if (value == 'Y') {
                set_value('dir_view', value);
            } else {
                set_value('dir_write', value);
                set_value('dir_delete', value);
            }
        break;
        
        case 'dir_write':
            set_value('dir_write', value);
            if (value == 'Y') {
                set_value('dir_view', value);
                set_value('dir_read', value);
            }
        break;
        
        case 'dir_delete':
            set_value('dir_delete', value);
            
            if (value == 'Y') {
                set_value('dir_view', value);
                set_value('dir_read', value);
            }
            
        break;
    }
}

function set_value(id, val) {
    var value = (val == 'Y' ? '1' : '0');
    
    $('#' + id).val(value);
    
    $('#btn_' + id).attr('src', '/bizstory/images/icon/' + val + '.gif').attr('val', val).attr('alt', val);
}
//]]>
</script>

<?
	}
?>
