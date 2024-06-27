<?
/*
	생성 : 2013.02.04
	수정 : 2013.04.01
	위치 : 파일센터 > 권한설정
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 정렬
	if ($sorder1 == '') $sorder1 = 'mem.mem_name';
	if ($sorder2 == '') $sorder2 = 'asc';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_search  = $f_search . '&amp;shgroup=' . $send_shgroup;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" id="list_fmode" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" id="list_smode" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere"  value="' . $send_swhere . '" />
		<input type="hidden" name="stext"   value="' . $send_stext . '" />
		<input type="hidden" name="shgroup" value="' . $send_shgroup . '" />
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	$link_list = $local_dir . "/bizstory/filecenter/staff_auth_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/filecenter/staff_auth_form.php"; // 등록
	$link_ok   = $local_dir . "/bizstory/filecenter/staff_auth_ok.php";   // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>

<div class="info_text">
	<ul>
		<li>보기 : 폴더, 파일 보이고 기능은 "미리보기"만 가능</li>
		<li>읽기 : 다운로드 가능, 미리보기, 파일링크, 이력보기 가능</li>
		<li>쓰기 : 생성, 수정, 삭제를 하나로 통합</li>
	</ul>
</div>
<hr />

<link type="text/css" rel="stylesheet" href="<?=$local_dir;?>/bizstory/css/fsidebar.css" media="all" />
<div class="tablewrapper">
	<div id="tableheader">
		<? include $local_path . "/bizstory/comp_set/part_menu_inc.php"; ?>

		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
				<select id="search_shgroup" name="shgroup" title="전체직원그룹">
					<option value="">전체직원그룹</option>
				</select>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value="mem.mem_name"<?=selected($swhere, 'mem.mem_name');?>>직원명</option>
					<option value="mem.mem_id"<?=selected($swhere, 'mem.mem_id');?>>아이디</option>
					<option value="mem.mem_email"<?=selected($swhere, 'mem.mem_email');?>>이메일</option>
					<option value="mem.tel_num"<?=selected($swhere, 'mem.tel_num');?>>연락처</option>
					<option value="mem.address"<?=selected($swhere, 'mem.address');?>>주소</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
				<?=$btn_down;?>
				<?=$btn_print;?>
				<?=$btn_print_sel;?>
			</div>
		</form>
	</div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>

		<input type="hidden" id="list_up_idx"  name="up_idx"  value="" />
		<input type="hidden" id="list_mem_idx" name="mem_idx" value="" />

		<div id="data_list"></div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list = '<?=$link_list;?>';
	var link_form = '<?=$link_form;?>';
	var link_ok   = '<?=$link_ok;?>';

//------------------------------------ 폴더권한
	function folder_auth(idx, up_idx, fi_idx)
	{
		$('#list_mem_idx').val(idx);
		$('#list_up_idx').val(up_idx);
		$('#list_idx').val(fi_idx);

		$.ajax({
			type: "post", dataType: 'html', url: link_form,
			data: $('#listform').serialize(),
			success  : function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$('.popupform').show();
				$("#data_form").html(msg);
			}
		});
	}

//------------------------------------ 권한설정
	function check_mem_auth(obj, sub_action, idx)
	{
	    var post_value = $(obj).attr('val');
	    
		$('#list_sub_type').val('auth_menu')
		$('#list_sub_action').val(sub_action);
		$('#list_idx').val(idx);
		$('#list_post_value').val(post_value);

		$('#list_up_idx').val($('#post_up_idx').val());
		$('#list_mem_idx').val($('#post_mem_idx').val());

		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'json', url: link_ok,
			data: $('#listform').serialize(),
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

	function folder_auth_open(str)
	{
		alert(sttr);
		$(str).css({"display": "block"});
	}

	part_information('<?=$code_part;?>', 'staff_group', 'search_shgroup', '<?=$shgroup;?>', 'select');
	list_data();
//]]>
</script>