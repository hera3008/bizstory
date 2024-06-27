<?
/*
	생성 : 2012.12.27
	수정 : 2013.07.10
	위치 : 업무폴더 > 프로젝트관리 - 보기 - 업무 - 등록/수정폼
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];

	$_SESSION['filecenter_table_idx'] = date('YmdHis') . '_' . $_SESSION[$sess_str . "_mem_idx"];

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
// 변수
	$f_default = 'fmode=' . $send_fmode . '&amp;smode=' . $send_smode;
	$f_search  = $f_default . '&amp;swhere=' . $send_swhere . '&amp;stext=' . $send_stext;
	$f_page    = $f_search . '&amp;sorder1=' . $send_sorder1 . '&amp;sorder2=' . $send_sorder2 . '&amp;page_size=' . $send_page_size;
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
	$form_page = $form_search . '
		<input type="hidden" name="sorder1" value="' . $send_sorder1 . '" />
		<input type="hidden" name="sorder2" value="' . $send_sorder2 . '" />
	';
	$form_all = $form_page . '
		<input type="hidden" name="page_size" value="' . $send_page_size . '" />
		<input type="hidden" name="page_num"  value="' . $page_num . '" />
		<input type="hidden" name="field_str" value="' . $field_str . '" />
	';

	$form_chk = 'Y';
	if ($form_chk == 'Y')
	{
?>
<div class="new_report">

	<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>" onsubmit="return check_form()">
		<input type="hidden" name="file_upload_num"  id="file_upload_num"     value="<?=$file_upload_num;?>" />
		<input type="hidden" name="param[pro_idx]"   id="workpost_pro_idx"    value="<?=$pro_idx;?>" />
		<input type="hidden" name="param[proc_idx]"  id="workpost_proc_idx"   value="<?=$proc_idx;?>" />
		<input type="hidden" name="param[open_yn]"   id="workpost_open_yn"    value="<?=$data_open_yn;?>" />

		<fieldset>
			<legend class="blind">업무등록 작성</legend>
			<table class="tinytable write" summary="해당 프로젝트 작업의 업무를 등록합니다.">
			<caption>업무등록</caption>
			<colgroup>
				<col width="100px" />
				<col />
			</colgroup>
			<tbody>
				<?
					include $local_path . "/bizstory/project/project_view_work_form_common.php";
				?>
				<tr>
					<th><label for="file_fname">파일</label></th>
					<td>
						<div class="filewrap">
						<div class="left"><a href="javascript:void(0);" onclick="popup_file()" class="btn_big_violet"><span>파일업로드</span></a></div>
						<div class="filewrap">
							<ul id="file_fname_add_view">
							</ul>
						</div>
					</td>
				</tr>
			</tbody>
			</table>

			<div class="section">
				<div class="fr">
					<span class="btn_big_green"><input type="submit" value="등록" /></span>
					<span class="btn_big_gray"><input type="button" value="취소" onclick="work_insert_close()" /></span>

					<input type="hidden" name="sub_type" value="post" />
					<input type="hidden" name="wi_idx"     id="project_wi_idx" value="" />
					<input type="hidden" name="pro_idx"    id="project_info_idx" value="<?=$pro_idx;?>" />
					<input type="hidden" name="idx_common" id="project_idx_common" />
					<input type="hidden" name="table_name" id="filecenter_table_name" value="project_work" />
					<input type="hidden" name="table_idx"  id="filecenter_table_idx"  value="<?=$_SESSION['filecenter_table_idx'];?>" />
				</div>
			</div>

		</fieldset>
		<?=$form_all;?>
	</form>
</div>
<?
	include $local_path . "/bizstory/project/project_view_work_form_js.php";
?>
<script type="text/javascript">
//<![CDATA[
//------------------------------------ 파일업로드 폼
	function popup_file()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_active.php',
			data: $('#postform').serialize(),
			success: function(msg) {
				$('html, body').animate({scrollTop:0}, 500);
				var maskHeight = $(document).height() + 1000;
				var maskWidth  = $(window).width();
				$("#backgroundPopup").css({"opacity": "0.7",'width':'100%','height':'100%'}).fadeIn("slow");
				$("#data_form2").slideDown("slow");
				$('.popupform').css('top',  "80px");
				$('.popupform').css('left', maskWidth/2-($('.popupform').width()/2));
				$("#data_form2").html(msg);
			}
		});
	}
//]]>
</script>

<?
	}
?>
