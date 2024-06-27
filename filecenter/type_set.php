<?
/*
	생성 : 2013.01.29
	수정 : 2013.04.01
	위치 : 파일센터 > 타입설정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/filecenter/type_set_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/filecenter/type_set_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/filecenter/type_set_ok.php";   // 저장
	$link_up   = $local_dir . "/bizstory/filecenter/type_set_up.php";   // 상위
	$link_auth = $local_dir . "/bizstory/filecenter/type_set_auth.php"; // 메뉴권한
	$link_entrust = $local_dir . "/bizstory/filecenter/type_set_entrust.php"; // 위임
?>
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
</div>

<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/fsidebar.css" media="all" />
<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list?>';
	var link_form = '<?=$link_form?>';
	var link_ok   = '<?=$link_ok?>';
	var link_up   = '<?=$link_up?>';
	var link_entrust = '<?=$link_entrust?>';

//------------------------------------ 등록
	function check_form()
	{
		var action_num = 0;
		var chk_total = '', chk_value = '', chk_title = '';

		chk_value = $('#post_part_idx').val();
		chk_title = $('#post_part_idx').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_menu_depth').val();
		chk_title = $('#post_menu_depth').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		chk_value = $('#post_code_name').val();
		chk_title = $('#post_code_name').attr('title');
		if (chk_value == '')
		{
			chk_total = chk_total + chk_title + '<br />';
			action_num++;
		}

		if (action_num == 0)
		{
			$("#loading").fadeIn('slow');
			$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
			$.ajax({
				type: 'post', dataType: 'json', url: link_ok,
				data: $('#postform').serialize(),
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
				},
				complete: function(){
					$("#loading").fadeOut('slow');
					$("#backgroundPopup").fadeOut("slow");
				}
			});
		}
		else check_auth_popup(chk_total);

		return false;
	}

//------------------------------------ 단계변경
	function up_change(menu_depth, idx)
	{
		if (menu_depth == "") menu_depth = $('#post_menu_depth').val();
		$.ajax({
			type: 'post', dataType: 'html', url: link_up,
			data: {"menu_depth" : menu_depth, "code_idx" : idx, "code_part" : $('#post_part_idx').val()},
			success: function(msg) {
				$('#up_list').html(msg);
			}
		});
	}

//------------------------------------ 선택된 하위메뉴
	function down_change(menu_depth, sel_depth, idx)
	{
		if (menu_depth == "") menu_depth = $('#post_menu_depth').val();
		$.ajax({
			type: 'post', dataType: 'html', url: link_up,
			data: {"menu_depth" : menu_depth, "sel_depth" : sel_depth, "code_idx" : idx, "code_part" : $('#post_part_idx').val()},
			success: function(msg) {
				$('#up_list').html(msg);
			}
		});
	}
	

//------------------------------------ 권한설정
    function check_type_auth(obj, sub_action, idx, url)
    {
        var post_value = $(obj).attr('val');
        
        $('#other_sub_type').val('auth_dir');
        $('#other_sub_action').val(sub_action);
        $('#other_idx').val(idx);
        $('#other_post_value').val(post_value);

        $("#loading").fadeIn('slow');
        $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
        $.ajax({
            type: "post", dataType: 'json', url: link_ok,
            data: $('#otherform').serialize(),
            success: function(msg) {
                if (msg.success_chk == "Y")
                {
                    //folder_auth(msg.idx, msg.up_idx, idx);
                    var src_array = obj.src.split('/');
                    var act_value = '';
                    var act_img = '';

                    if (post_value == '1') {
                        post_value = '0';
                        act_value = 'N';
                        src_array[src_array.length - 1] = act_value + '.gif';
                    } else {
                        post_value = '1';
                        act_value = 'Y';
                        src_array[src_array.length - 1] = act_value + '.gif';
                    }
                    
                    act_img = src_array.join('/');
                    obj.src = act_img;
                    
                    // delete인 경우 view, read, write 권한이 주어지므로 해당 이미지 및 val 변경
                    // write인 경우 view, read 권한이 주어지므로 해당 이미지 및 val 변경
                    // read 경우 view 권한이 주어지므로 해당 이미지 및 val 변경

                    if (post_value == '1') {
                        if (sub_action == 'dir_delete') {
                            var btn_write = $(obj).parent().prev().children();
                            btn_write.attr('src', act_img);
                            btn_write.attr('val', post_value);
                            btn_write.attr('alt', act_value);
                            var btn_read = $(obj).parent().prev().prev().children();
                            btn_read.attr('src', act_img);
                            btn_read.attr('val', post_value);
                            btn_read.attr('alt', act_value);
                            var btn_view = $(obj).parent().prev().prev().prev().children();
                            btn_view.attr('src', act_img);
                            btn_view.attr('val', post_value);
                            btn_view.attr('alt', act_value);
                        } else if (sub_action == 'dir_write') {
                            var btn_read = $(obj).parent().prev().children();
                            btn_read.attr('src', act_img);
                            btn_read.attr('val', post_value);
                            btn_read.attr('alt', act_value);
                            var btn_view = $(obj).parent().prev().prev().children();
                            btn_view.attr('src', act_img);
                            btn_view.attr('val', post_value);
                            btn_view.attr('alt', act_value);
                        } else if (sub_action == 'dir_read') {
                            var btn_view = $(obj).parent().prev().children();
                            btn_view.attr('src', act_img);
                            btn_view.attr('val', post_value);
                            btn_view.attr('alt', act_value);
                        }
                    } else {
                        if (sub_action == 'dir_view') {
                            var btn_read = $(obj).parent().next().children();
                            btn_read.attr('src', act_img);
                            btn_read.attr('val', post_value);
                            btn_read.attr('alt', act_value);
                            var btn_write = $(obj).parent().next().next().children();
                            btn_write.attr('src', act_img);
                            btn_write.attr('val', post_value);
                            btn_write.attr('alt', act_value);
                            var btn_delete = $(obj).parent().next().next().next().children();
                            btn_delete.attr('src', act_img);
                            btn_delete.attr('val', post_value);
                            btn_delete.attr('alt', act_value);
                        } else if (sub_action == 'dir_read') {
                            var btn_write = $(obj).parent().next().children();
                            btn_write.attr('src', act_img);
                            btn_write.attr('val', post_value);
                            btn_write.attr('alt', act_value);
                            var btn_delete = $(obj).parent().next().next().children();
                            btn_delete.attr('src', act_img);
                            btn_delete.attr('val', post_value);
                            btn_delete.attr('alt', act_value);
                        } else if (sub_action == 'dir_write') {
                            var btn_delete = $(obj).parent().next().children();
                            btn_delete.attr('src', act_img);
                            btn_delete.attr('val', post_value);
                            btn_delete.attr('alt', act_value);
                        }
                    }
                    
                    $(obj).attr('src', act_img);
                    $(obj).attr('val', post_value);
                    $(obj).attr('alt', act_value);
                }
                else
                {
                    $("#loading").fadeOut('slow');
                    $("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
                    check_auth_popup(msg.error_string);
                }
            },
            complete: function(){
                $("#loading").fadeOut('slow');
            }
        });
    }


	list_data();
//]]>
</script>