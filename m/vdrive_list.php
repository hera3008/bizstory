<?
	include "../common/set_info.php";
	include "./process/mobile_setting.php";
	include "./process/member_chk.php";
	include  "./header.php";


	$send_fmode = "filecenter";
	$send_smode = "filemanager";
	if ($pro_end == '')
	{
		$pro_end = 'N';
		$send_pro_end = 'N';
		$recv_pro_end = 'N';
	}
	
	$mem_idx = $code_mem;
	if ($up_level == '') $up_level = 2;	
	
	$max_size1 = $file_max_size / 1024 / 1024;
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

	if ($up_idx == '') {
		$result = filecenter_open_check($up_idx, $code_comp, $code_part);
		$chk_up = $result['chk_up'];
		$project_fi_idx = $result['project_fi_idx'];
		$vdrive_fi_idx = $result['vdrive_fi_idx'];
		$up_idx = $vdrive_fi_idx;
	}
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$path_data     = filecenter_folder_path($up_idx); // 현위치
	$dir_auth_page = filecenter_auth_folder($up_idx); // 권한확인 - 현위치
	$link_file = $local_dir . "/bizstory/filecenter/filemanager_file.php";        // 파일업로드

	$first_name = $path_data['path_up_name'][1];

	$form_chk = 'N';
	if ($dir_auth_page['dir_view_auth'] == 'Y') // 목록권한
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
?>
<style type="text/css" src="../"></style>
<script type="text/javascript" src="/common/upload/jquery.uploadify.v2.1.4.min.js"></script>
<script type="text/javascript" src="/bizstory/js/script_file.js"></script>
<div id="page">
	<div id="header">
	<a class="back" href="javascript:history.go(-1)">BACK</a>
<?
	include "body_header.php";
?>
	</div>
	<div id="content">
		<article>
			<h2>
				<em class="mr_70">파일관리</em>
				<span class="btn_vdrive small pop1" data-bpopup='{"transition":"slideDown","speed":850,"easing":"easeOutBack"}' onclick="showUpload()">파일올리기</span>
			</h2>
			<form id="searchform" method="GET" action="./vdrive_list.php">
				<fieldset>
				<input type="hidden" name="fmode" value="<?=$send_fmode?>" />
				<input type="hidden" name="smode" value="<?=$send_smode?>" />
				<input type="hidden" name="swhere"    value="<?=$send_swhere?>" />
				<input type="hidden" name="stext"     value="<?=$send_stext?>" />
				<input type="hidden" name="swtype"    value="<?=$send_swtype?>" />
				<input type="hidden" name="shwstatus" value="<?=$send_shwstatus?>" />
				<input type="hidden" name="smember"   value="<?=$send_smember?>" />
				<input type="hidden" name="up_idx" id="list_up_idx" value="<?=$up_idx?>" />
				<input type="hidden" name="up_level" id="list_up_level" value="<?=$list_up_level?>" />
				<legend>컨텐츠 검색</legend>
					<div class="search_bar"> 
						<div class="search_area"> 
							<div class="inpwp"><input type="search" title="검색어 입력" id="inpSearch" autocomplete="off" autocorrect="off" name="keyword" value="검색할 단어 입력" class="" maxlength="40" onblur="if (this.value == '') {this.value = this.title;}" onfocus="if (this.value == this.title) {this.value = '';}" /></div>
							<i class="spr_tm mag"></i> 
							<button type="button" class="del" id="btnDelete"><i class="spr_tm">검색어 삭제</i></button> 
						</div> 
						<button type="button" class="go" id="btnSearch"><i class="spr_tm">검색하기</i></button>
					</div> 
				</fieldset> 
			</form>
			<div class="message_bar">
				<!-- ul>
					<li><a href="javascript:" onclick="regFile()"><span class="btn_g">등록</span></a></li>
				</ul -->
				<span class="btn_vup">상위</span>

				<select class="ngb_select" id="VdriveList">
					<option value="0">이름순</option>
					<option value="1">종류순</option>
					<option value="2">중요 표시순</option>
					<option value="3">업로드 최신순</option>
					<option value="3">수정한 날짜 최신순</option>
				</select>
			</div>
		</article>
		<div id="wrapper" class="work work_section">
			<div id="scroller">

<?
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if ($form_chk == 'Y')
	{
		if ($first_name == 'V-Drive') {
			$list_query['query_string'] = "
				select
					fi.*
					, fa.dir_view, fa.dir_read, fa.dir_write
				from
					filecenter_info fi
					left join filecenter_auth fa on fa.del_yn = 'N' and fa.comp_idx = fi.comp_idx and fa.mem_idx = '" . $code_mem . "' and fa.fi_idx = fi.fi_idx
				where
					fi.del_yn = 'N'
					and fi.comp_idx = '" . $code_comp . "' and fi.part_idx = '" . $code_part . "'
					and fi.dir_depth = '" . $up_level . "' and concat(',', fi.up_fi_idx, ',') like '%," . $up_idx . ",%'
					and (if (fi.dir_file = 'file', 1, fa.dir_view) = '1'
						or if (fi.dir_file = 'file', 1, fa.dir_read) = '1'
						or if (fi.dir_file = 'file', 1, fa.dir_write) = '1')
				order by
					fi.dir_file desc, fi.file_path asc, fi.file_name asc
			";
			//echo $list_query;
			$list_query['page_num']  = $page_num;
			$list_query['page_size'] = $page_size;
			$list = query_list($list_query);
			
			//print_r($list);
?>
				<ul class="list">
<?
	$i = 1;
	$num = $list["total_num"];
	foreach($list as $k => $data)
	{
		if (is_array($data))
		{
			$fi_idx     = $data['fi_idx'];
			$next_depth = $data['dir_depth'] + 1;
			$modify_auth = 'N';
			$delete_auth = 'N';
			
			if ($dir_auth['btn_modify'] != '') {
				$modify_auth = 'Y';
			}
			if ($dir_auth['btn_delete'] != '') {
				$delete_auth = 'Y';
			}
			
			$dir_auth = filecenter_auth($fi_idx, $data, $dir_auth_page['dir_view_auth'], $dir_auth_page['dir_read_auth'], $dir_auth_page['dir_write_auth']); // 권한

			if ($data['dir_file'] == 'folder') // 폴더일 경우
			{
				$icon_img   = '<img src="./images/ico_folder.png" alt="folder" /> ';
				$file_size  = '';
				$file_type  = 'folder';
				$file_url   = '<a href="javascript:" onclick="file_list_view(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">';
				
				$file_name  = $data['file_name'];

				if ($data['dir_depth'] == 1)
				{
					if ($data['file_name'] == 'Project') {
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_p.png" alt="Porject" title="Porject" /> ';
					} else {
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_v.png" alt="V-Center" title="V-Center" /> ';
					}
				}
				else
				{
					if ($data['set_type'] == 'fix' && $data['file_name'] == 'Member')
					{
						$icon_img = '<img src="' . $local_dir . './images/icon_m.png" alt="Member" title="Member" /> ';
					}
					else if ($data['set_type'] == 'fix' && $data['file_name'] == 'Work')
					{
						$icon_img = '<img src="' . $local_dir . '/bizstory/images/filecenter/icon_w.png" alt="Work" title="Work" /> ';
					}
				}

				$chk_level = $up_level - 1;
				$chk_type  = $path_data['path_set_type'][$chk_level];
				$chk_name  = $path_data['path_up_name'][$chk_level];
				if (($chk_name == 'Member' && $chk_type == 'fix') || ($chk_name == 'Work' && $chk_type == 'fix'))
				{
					$mem_where = " and mem.mem_idx = '" . $data["file_sname"] . "'";
					$mem_data = member_info_data('view', $mem_where);
					if ($mem_data['total_num'] > 0)
					{
						$mem_name = '[<span style="color:' . $set_color_list2[$mem_data['part_sort']] . '">' . $mem_data['part_name'] . '</span> (' . $mem_data['group_name'] . ')] : ' . $data['file_name'] . '</span>';

						//$file_url = 'file_list_view(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')';
						$file_url   = '<a href="javascript:" onclick="file_list_view(\'' . $data['fi_idx'] . '\', \'' . $next_depth . '\')">';
					}
				}
			}
			else
			{
				//$checkbox_html = '<input type="checkbox" id="fiidx_' . $i . '" name="chk_fi_idx[]" value="' . $data['fi_idx'] . '" title="선택" onclick="addFiles()" />';
				$icon_img   = mobile_file_ext_img($data['file_ext']) . ' ';
				$file_size  = byte_replace($data['file_size']);
				$file_type  = $data["file_ext"];
				$file_name = $data['file_name'];

				if ($dir_auth_page['dir_read_auth'] == 'Y' || $dir_auth_page['dir_write_auth'] == 'Y')
				{
					$file_url = '<a href="' . $set_filecneter_url . '/file_download.php?idx=' . $data['fi_idx'] . '&amp;idx2=' . $code_mem . '" title="' . $data['file_name'] . ' 다운로드">';
					
					//$file_url = '<a href="javascript:" onclick="showFileDialog(' . $data['fi_idx'] . ',\' . $modify_a'',\'' . $file_name . '\')" class="md-trigger" data-modal="filemodal">';
					//$file_url = 'showFileDialog(' . $data['fi_idx'] . ')';
				}
				
			}

			
			//$charge_str = staff_layer_form($data['reg_id'], '', 'N', $set_color_list2, 'fileliststtaff', $data['fi_idx'], '');
?>
		<li>
			<?=$file_url?>
				<?=$icon_img;?>
				<strong class="title"><?=$file_name?></strong>
				<!--<button class="btn_area" style="float:right" class="md-trigger" data-modal="filemodal">설정</button>-->
				<span class="date"><?=date('Y-m-d H:mi', $data["reg_date"]);?> <?=$file_size?></span>
			</a>
		</li>
<?
			$i++;
			$num--;
		}
	}
	if ($list["total_num"] == 0)
	{
?>
		<li>
			등록된 데이타가 없습니다.
		</li>
<?
	}
?>
				</ul>
<?
		}
	}
?>
			</div>
		</div>
	</div>
<!-- 팝업 내용 -->
<div class="md-modal md-effect" id="filemodal">
	<div class="md-content">
		<h3></h3>
		<div>
			<ul style="list-style:none;">
				<li id="file_download" style="display:inline; border:solid 1px #efefef;">내려받기</li>
				<li style="display:inline; border:solid 1px #efefef;">이름변경</li>
				<li style="display:inline; border:solid 1px #efefef;">삭제</li>
			</ul>
		</div>
	</div>
</div>


<div class="md-overlay"></div>
<script type="text/javascript">

 	function file_list_view(up_idx, up_level)
	{
		$('#list_up_idx').val(up_idx);
		$('#list_up_level').val(up_level);
		$("#searchform").submit();
	}

	function showFileDialog(fi_idx, file_name) {
		$("#filemodal .md-content h3").html( file_name );
		//var file_link = '<a href="<?=$set_filecneter_url?>/file_download.php?idx=' + fi_idx + '&amp;idx2=<?=$code_mem?>" title="' + file_name + ' 다운로드">내려받기</a>';
		$("#filemodal #file_download").html( file_link );
		//$("#filemodal .md-content div").prepend(json.mem_img.img_53);
		//$("#filemodal .md-content div ul").html( html );
	}
	
	function popup_file_close()
	{
		popupform_close();
		if ($('#list_old_up_idx').val() != '') $('#list_up_idx').val($('#list_old_up_idx').val());
		if ($('#list_old_up_level').val() != '') $('#list_up_level').val($('#list_old_up_level').val());
		list_data();
	}
	
	function showUpload() {
		setLoadUploader();
		
		$(".ajax_frame").delegate("#cont_contents", 'change', function() {
			
			setLoadUploader();
		});
	}
	
	function setLoadUploader(btn_dis) {
		/*
		var comp_idx = $('#isForm_comp_idx').val();
		var part_idx = $('#isForm_part_idx').val();
		var up_idx   = $('#isForm_up_idx').val();
		var mem_idx  = $('#isForm_mem_idx').val();
		var max_size = $('#isForm_max_size').val();
		*/
		var comp_idx = "<?=$code_comp;?>";
		var part_idx = "<?=$code_part;?>";
		var up_idx   = "<?=$up_idx;?>";
		var mem_idx  = "<?=$code_mem;?>";
		var max_size = "<?=$max_size1;?>";
		var contents = "";//encodeURIComponent($('#cont_contents').val());
		
		var move_string = "comp_idx=" + comp_idx + "&part_idx=" + part_idx + "&up_idx=" + up_idx + "&mem_idx=" + mem_idx + "&max_size=" + max_size + "&contents=" + contents;
		
		if (btn_dis != null) {
			move_string += "&btn_dis=" + btn_dis;
		}
		
		var html = [];
		
		html.push('<div class="md-content" style="padding-bottom:10px;">');
		html.push('<h3 class="v_title">V-Drive Upload Component</h3>');
		html.push('<div class="vcenter_section">');
		html.push('	<div class="vcenter_area ">');
		html.push('	<div class="ajax_write">');
		html.push('		<div class="ajax_frame">');
		html.push('			<div class="upload_l">');
		html.push('				<p>현위치 <span><?=str_replace("'", "\'", $path_data['navi_path']);?></span></p>');
		html.push('			</div>');
		html.push('			<form id="isForm" name="isForm" method="post">');
		html.push('				<input type="hidden" id="isForm_comp_idx" name="comp_idx" value="<?=$code_comp;?>" />');
		html.push('				<input type="hidden" id="isForm_part_idx" name="part_idx" value="<?=$code_part;?>" />');
		html.push('				<input type="hidden" id="isForm_up_idx"   name="up_idx"   value="<?=$up_idx;?>" />');
		html.push('				<input type="hidden" id="isForm_mem_idx"  name="mem_idx"  value="<?=$code_mem;?>" />');
		html.push('				<input type="hidden" id="isForm_max_size" name="max_size" value="<?=$max_size1;?>" />');
		html.push('			</form>');
		html.push('			<div style="border:1px #ccc solid; margin:5px 0 0 0; padding:0; overflow:hidden;">');
		html.push('				<iframe id="loadUploader" src="<?=$set_filecneter_url;?>/xupload/filecenter_mobile_html.php?' + move_string + '" class="vcenter_iframe" width="90%" height="300" scrolling="no" style="margin:0; padding:0;"></iframe>');
		html.push('			</div>');
		html.push('		</div>');
		html.push('	</div>');
		html.push('	<div class="float_r"><a href="javascript:" onclick="reloadList()" class="btn01_2">완료</a></div>');
		html.push('	</div>');
		html.push('	<button class="md-close" onclick="reloadList()"><img src="./images/btn_close.png" alt="닫기" /></button>');
		html.push('</div>');
		html.push('</div>');
		
		$("#popup").html(html.join('\n'));
		//$("#loadUploader").attr("src", "<?=$set_filecneter_url;?>/xupload/filecenter_mobile_html.php?" + move_string);		
	}
	
	function reloadList() {
		location.reload();
	}

</script>
<?
	include "./footer.php";
?>