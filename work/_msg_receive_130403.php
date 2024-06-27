<?
/*
	수정 : 2012.04.10
	위치 : 업무폴더 > 나의 업무 > 쪽지 > 받은쪽지
*/
	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;page_size=' . $send_page_size;
	$f_all     = $f_page . '&amp;page_num=' . $page_num;
	$f_script  = str_replace('&amp;', '&', $f_all);
	$field_str = str_replace('&amp;', '|', $f_all);

	$form_default  = '
		<input type="hidden" name="fmode" value="' . $send_fmode . '" />
		<input type="hidden" name="smode" value="' . $send_smode . '" />
	';
	$form_search = $form_default . '
		<input type="hidden" name="swhere" value="' . $send_swhere . '" />
		<input type="hidden" name="stext"  value="' . $send_stext . '" />
	';
	$form_page = $form_search . '';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 링크, 버튼
	if ($code_mem == 8)
	{
	$link_list  = $local_dir . "/bizstory/test/message_list.php"; // 목록
	$link_list1 = $local_dir . "/bizstory/work/msg_receive_list_list.php"; // 목록
	}
	else
	{
	$link_list = $local_dir . "/bizstory/work/msg_receive_list.php"; // 목록
	}
	$link_list = $local_dir . "/bizstory/work/msg_receive_list.php"; // 목록
	$link_form = $local_dir . "/bizstory/work/msg_form.php";         // 등록
	$link_view = $local_dir . "/bizstory/work/msg_receive_view.php"; // 보기
	$link_ok   = $local_dir . "/bizstory/work/msg_ok.php";           // 저장

	$search_column  = '칼럼 선택';
	$search_keyword = '검색할 단어 입력';
?>
<div class="tablewrapper">
	<div id="tableheader">
		<form id="searchform" name="searchform" method="post" action="<?=$this_page;?>" onsubmit="return check_search();">
			<?=$form_default;?>
			<div class="search">
				<p>검&nbsp;&nbsp;&nbsp;색</p>
				<select id="search_swhere" name="swhere" title="<?=$search_column;?>">
					<option value=""><?=$search_column;?></option>
					<option value="ms.remark"<?=selected($swhere, 'ms.remark');?>>내용</option>
					<option value="ms.mem_name"<?=selected($swhere, 'ms.mem_name');?>>보낸이</option>
				</select>
				<input type="text" id="search_stext" name="stext" class="type_text" value="<?=$search_keyword;?>" title="<?=$search_keyword;?>" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" />
				<a href="javascript:void(0);" class="btn_sml" onclick="check_search()"><span>검색</span></a>
			</div>
		</form>
	</div>

	<div id="data_view" title="상세보기 / 등록, 수정폼"></div>

	<form id="listform" name="listform" method="post" action="<?=$this_page;?>">
		<input type="hidden" id="list_sub_type"   name="sub_type"   value="" />
		<input type="hidden" id="list_sub_action" name="sub_action" value="" />
		<input type="hidden" id="list_idx"        name="idx"        value="" />
		<input type="hidden" id="list_post_value" name="post_value" value="" />
		<input type="hidden" id="list_code_part"  name="code_part"  value="<?=$code_part;?>" />
		<?=$form_page;?>

		<div id="data_list"></div>
	</form>
<?
	if ($sub_type == '')
	{ }
	else if ($sub_type == 'postform' || $sub_type == 'modifyform')
	{
		echo '<div id="work_form_view">';
		include $local_path . "/bizstory/work/msg_form.php";
		echo '</div>';
	}
?>
</div>

<script type="text/javascript">
//<![CDATA[
	var link_list  = '<?=$link_list;?>';
	var link_list1 = '<?=$link_list1;?>';
	var link_form  = '<?=$link_form;?>';
	var link_view  = '<?=$link_view;?>';
	var link_ok    = '<?=$link_ok;?>';

//------------------------------------ 검색
	function check_search()
	{
		var stext       = $('#search_stext').val();
		var stext_title = $('#search_stext').attr('title');
		if (stext == stext_title) stext = '';

		document.listform.swhere.value = $('#search_swhere').val();
		document.listform.stext.value  = stext;

		view_close();
		list_data();
		return false;
	}

//------------------------------------ 삭제하기
	function check_receive_delete(idx)
	{
		if (confirm("선택하신 쪽지를 삭제하시겠습니까?"))
		{
			view_close();
			check_code_data('delete_receive', '', idx, '');
		}
	}

//------------------------------------ 쪽지보관
	function message_store(idx)
	{
		if (confirm("선택하신 쪽지를 보관하시겠습니까?"))
		{
			view_close();
			check_code_data('receive_store', '', idx, '');
		}
	}

<?
	if ($sub_type == '')
	{
		echo 'list_data();';
	}
?>

//------------------------------------ 목록
	function msg_list_data()
	{
		$("#loading").fadeIn('slow');
		$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
		$.ajax({
			type: "post", dataType: 'html', url: link_list1,
			data: $('#listform').serialize(),
			success: function(msg) {
				$('#msg_data_list').html(msg);
			},
			complete: function(){
				$("#loading").fadeOut('slow');
				$("#backgroundPopup").fadeOut("slow");
			}
		});
	}

<?
	if ($code_mem == 8)
	{
?>
		msg_list_data();
<?
	}
?>
//]]>
</script>