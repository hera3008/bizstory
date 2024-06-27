<?
/*
	생성 : 2013.09.08
	수정 : 2013.09.08
	위치 : 업무관리 > 프로젝트관리 - 등록, 수정 - html5형식
*/
	require_once "../common/setting.php";
	require_once "../common/no_direct.php";
	$page_chk = 'html';
	require_once "../common/member_chk.php";

	$code_comp = $_SESSION[$sess_str . '_comp_idx'];
	$code_part = search_company_part($code_part);
	$code_mem  = $_SESSION[$sess_str . '_mem_idx'];
	$pro_idx   = $idx;

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

	$form_chk = 'N';
	if (($auth_menu['int'] == 'Y' && $pro_idx == '') || ($auth_menu['mod'] == 'Y' && $pro_idx != '')) // 등록, 수정권한
	{
		$form_chk = 'Y';
	}
	else
	{
		echo '
			<script type="text/javascript">
			//<![CDATA[
				check_auth_popup("");
			//]]>
			</script>
		';
	}

	if ($form_chk == 'Y')
	{
	// 파일
		if ($pro_idx > 0)
		{
			$where = " and pro.pro_idx = '" .  $pro_idx . "'";
			$data = project_info_data('view', $where);
			$data = project_list_data($data, $pro_idx);

			$file_where = " and prof.pro_idx = '" . $pro_idx . "'";
			$file_list = project_file_data('list', $file_where, '', '', '');
		}
		if ($data['open_yn'] == "") $data['open_yn'] = 'Y';

		$project_idx_common = 'projecthtml5_' . $pro_idx . date('YmdHis');
?>
<div class="ajax_write">
	<div class="ajax_frame">

		<form id="postform" name="postform" method="post" class="writeform" action="<?=$this_page;?>">
            <input type="hidden" name="menu1_code" id="menu1_code" value="<?=$data['menu1_code']?>" />
            <input type="hidden" name="menu2_code" id="menu2_code" value="<?=$data['menu2_code']?>" />
            
			<fieldset>
				<legend class="blind">프로젝트정보 폼</legend>

				<table class="tinytable write" summary="프로젝트정보를 등록/수정합니다.">
					<caption>프로젝트정보</caption>
					<colgroup>
						<col width="120px" />
						<col />
					</colgroup>
					<tbody>
					<?
						include $local_path . "/bizstory/project/project_form_common.php";
					?>
						<tr>
							<th><label for="file_fname">파일</label></th>
							<td colspan="3">
								<div class="left"><a href="javascript:void(0);" onclick="popup_file()" class="btn_big_green"><span>파일업로드</span></a></div>
								<div class="filewrap">
									<ul id="file_fname_add_view">
									</ul>
									<ul id="file_fname_view">
					<?
						if (is_array($file_list))
						{
							foreach ($file_list as $file_k => $file_data)
							{
								if (is_array($file_data))
								{
									$img_path = $file_data['img_path'];
									$in_out   = $file_data['in_out'];
									$fsize    = $file_data['img_size'];
									$fsize    = byte_replace($fsize);

									$btn_str = preview_files($file_data['prof_idx'], 'project');

									if ($in_out == 'CENTER')
									{
										$down_url = $set_filecneter_url . '/biz/project_download.php?prof_idx=' . $file_data['prof_idx'];
									}
									else if ($in_out == 'OUT')
									{
										$down_url = $set_filecneter_url . '/biz/project_download.php?prof_idx=' . $file_data['prof_idx'];
									}
									else
									{
										$down_url = $local_dir . '/bizstory/project/project_download.php?prof_idx=' . $file_data['prof_idx'];
									}

									if ($img_path != '')
									{
										$file_url = substr($img_path, 1, strlen($img_path)) . '/<strong>' . $file_data['img_fname'] . '</strong>';
									}
									else
									{
										$file_url = '<strong>' . $file_data['img_fname'] . '</strong>';
									}
						?>
										<li id="file_fname_<?=$file_k;?>_liview" class="org_file left">
											<a href="<?=$down_url;?>" title="<?=$file_data['img_fname'];?> 다운로드" class="fileicon"><?=$file_url;?> (<?=$fsize;?>)</a>
											<a href="javascript:void(0);" class="btn_con_red" onclick="file_multi_form_delete('<?=$file_data['prof_idx'];?>', '<?=$file_k;?>')"><span>삭제</span></a>
										</li>
						<?
								}
							}
						}
					?>
									</ul>
								</div>
							</td>
						</tr>
					</tbody>
				</table>

				<div class="section">
					<div class="fr">
				<?
					if ($pro_idx == '') {
				?>
						<span class="btn_big_green"><input type="button" value="등록" onclick="check_form();" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" id="project_sub_type" value="post" />
				<?
					} else {
				?>
						<span class="btn_big_blue"><input type="button" value="수정" onclick="check_form();" /></span>
						<span class="btn_big_gray"><input type="button" value="취소" onclick="close_data_form()" /></span>

						<input type="hidden" name="sub_type" id="project_sub_type" value="modify" />
				<?
					}
				?>
						<input type="hidden" name="pro_idx"    id="project_info_idx"      value="<?=$pro_idx;?>" />
						<input type="hidden" name="idx_common" id="project_idx_common"    value="<?=$project_idx_common;?>" />
						<input type="hidden" name="table_name" id="filecenter_table_name" value="project" />
						<input type="hidden" name="table_idx"  id="filecenter_table_idx"  value="<?=$_SESSION['filecenter_table_idx'];?>" />
					</div>
				</div>

			</fieldset>
			<?=$form_all;?>
		</form>
	</div>
</div>
<?
		include $local_path . "/bizstory/project/project_form_js.php";
	}

    
?>

<script type="text/javascript">
//<![CDATA[
//------------------------------------ 파일업로드 폼
	function popup_file()
	{
		$.ajax({
			type: "post", dataType: 'html', url: '<?=$local_dir;?>/bizstory/filecenter/biz/file_html.php',
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

    $(function() {
       if ($('#menu1_code').val() != '') {
           setTimeout(function() {
            down_menu_change(3, 1, $('#menu1_code').val(), true);
           }, 1000);
       }
    });

//]]>
</script>